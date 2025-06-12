<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_produits');
    }
    
    /**
     * Display a listing of media.
     */
    public function index(Request $request)
    {
        $query = ProductMedia::with('product');
        
        // Filter by product if specified
        if ($request->has('product_id') && $request->product_id) {
            $query->where('produit_id', $request->product_id);
        }
        
        // Filter by type if specified
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Search by filename
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }
        
        $media = $query->latest()->paginate(20);
        $products = Product::orderBy('nom')->get();
        
        return view('admin.media.index', compact('media', 'products'));
    }
    
    /**
     * Show the form for creating a new media item.
     */
    public function create()
    {
        $products = Product::orderBy('nom')->get();
        return view('admin.media.create', compact('products'));
    }
    
    /**
     * Store newly created media items in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:20480',
            'type' => 'required|in:image,video',
            'optimize' => 'nullable|boolean',
        ]);
        
        $uploadedFiles = $request->file('files');
        $mediaItems = [];
        $shouldOptimize = $request->has('optimize') && $request->optimize;
        
        foreach ($uploadedFiles as $file) {
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $type = $this->determineFileType($file);
            
            // Determine storage path based on type
            $storagePath = $type === 'image' ? 'products/images/' : 'products/videos/';
            
            if ($type === 'image') {
                // Process and optimize image
                $this->processAndStoreImage($file, $storagePath, $fileName, $shouldOptimize);
            } else {
                // Store video directly
                $file->storeAs('public/' . $storagePath, $fileName);
            }
            
            // Create media record
            $mediaItems[] = [
                'produit_id' => $validated['produit_id'],
                'url' => $storagePath . $fileName,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        ProductMedia::insert($mediaItems);
        
        return redirect()->route('media.index')
            ->with('success', count($mediaItems) . ' fichiers média ont été ajoutés avec succès.');
    }
    
    /**
     * Remove the specified media item from storage.
     */
    public function destroy(ProductMedia $medium)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($medium->url)) {
            Storage::disk('public')->delete($medium->url);
            
            // Delete thumbnail if it's an image
            if ($medium->type === 'image') {
                $this->deleteThumbnails($medium->url);
            }
        }
        
        $medium->delete();
        
        return redirect()->route('media.index')
            ->with('success', 'Le média a été supprimé avec succès.');
    }
    
    /**
     * Bulk destroy multiple media items.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'media_ids' => 'required|array',
            'media_ids.*' => 'exists:medias_produits,id',
        ]);
        
        $mediaItems = ProductMedia::whereIn('id', $validated['media_ids'])->get();
        
        foreach ($mediaItems as $media) {
            if (Storage::disk('public')->exists($media->url)) {
                Storage::disk('public')->delete($media->url);
                
                // Delete thumbnail if it's an image
                if ($media->type === 'image') {
                    $this->deleteThumbnails($media->url);
                }
            }
        }
        
        ProductMedia::whereIn('id', $validated['media_ids'])->delete();
        
        return redirect()->route('media.index')
            ->with('success', count($validated['media_ids']) . ' médias ont été supprimés avec succès.');
    }
    
    /**
     * Update product main image from media gallery.
     */
    public function setAsMainImage(Request $request, ProductMedia $medium)
    {
        $request->validate([
            'product_id' => 'required|exists:produits,id'
        ]);
        
        $product = Product::find($request->product_id);
        
        // Only proceed if this is an image type
        if ($medium->type !== 'image') {
            return redirect()->back()->with('error', 'Seules les images peuvent être définies comme image principale.');
        }
        
        // Update the product's main image
        $product->update(['image' => $medium->url]);
        
        return redirect()->back()->with('success', 'Image principale mise à jour avec succès.');
    }
    
    /**
     * Process and store an image with optimizations.
     */
    private function processAndStoreImage($file, $storagePath, $fileName, $optimize = true)
    {
        // Create the directory if it doesn't exist
        Storage::disk('public')->makeDirectory($storagePath . 'thumbnails', 0755, true, true);
        Storage::disk('public')->makeDirectory($storagePath . 'medium', 0755, true, true);
        
        if ($optimize) {
            // Optimize for web - main image (max 1200px width)
            $img = Image::make($file)
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            
            // Adjust quality based on size
            $quality = $this->calculateOptimalQuality($img->filesize());
            
            // Encode with calculated quality
            $img->encode(null, $quality);
            
            // Save optimized image
            Storage::disk('public')->put($storagePath . $fileName, $img->stream());
            
            // Create medium size (600px)
            $medium = Image::make($file)
                ->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode(null, $quality);
            
            Storage::disk('public')->put($storagePath . 'medium/' . $fileName, $medium->stream());
            
            // Create thumbnail (200x200px)
            $thumbnail = Image::make($file)
                ->fit(200, 200)
                ->encode(null, $quality);
            
            Storage::disk('public')->put($storagePath . 'thumbnails/' . $fileName, $thumbnail->stream());
        } else {
            // Store original without optimization
            $file->storeAs('public/' . $storagePath, $fileName);
            
            // Still create basic thumbnails
            $medium = Image::make($file)
                ->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            
            Storage::disk('public')->put($storagePath . 'medium/' . $fileName, $medium->encode()->stream());
            
            $thumbnail = Image::make($file)->fit(200, 200);
            Storage::disk('public')->put($storagePath . 'thumbnails/' . $fileName, $thumbnail->encode()->stream());
        }
    }
    
    /**
     * Calculate optimal quality based on file size.
     */
    private function calculateOptimalQuality($filesize)
    {
        // Adjust quality based on image size
        // Larger images get more compression
        $filesize = $filesize / 1024; // Convert to KB
        
        if ($filesize > 5000) {
            return 60; // Large image (>5MB)
        } elseif ($filesize > 2000) {
            return 70; // Medium-large (2-5MB)
        } elseif ($filesize > 1000) {
            return 75; // Medium (1-2MB)
        } elseif ($filesize > 500) {
            return 80; // Medium-small (0.5-1MB)
        } else {
            return 85; // Small image (<0.5MB)
        }
    }
    
    /**
     * Delete all thumbnail versions of an image.
     */
    private function deleteThumbnails($imagePath)
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['basename'];
        
        // Delete medium version
        $mediumPath = str_replace($directory, $directory . '/medium', $imagePath);
        if (Storage::disk('public')->exists($mediumPath)) {
            Storage::disk('public')->delete($mediumPath);
        }
        
        // Delete thumbnail
        $thumbnailPath = str_replace($directory, $directory . '/thumbnails', $imagePath);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
    }
    
    /**
     * Determine file type based on MIME type.
     */
    private function determineFileType($file)
    {
        $mimeType = $file->getMimeType();
        
        if (strpos($mimeType, 'video/') === 0) {
            return 'video';
        }
        
        return 'image';
    }
}
