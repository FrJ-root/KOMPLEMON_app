<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_produits');
    }
    
    public function index(Request $request)
    {
        $query = ProductMedia::with('product');
        
        if ($request->has('product_id') && $request->product_id) {
            $query->where('produit_id', $request->product_id);
        }
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
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
    
    public function create()
    {
        $products = Product::orderBy('nom')->get();
        return view('admin.media.create', compact('products'));
    }
    
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
            
            $storagePath = $type === 'image' ? 'products/images/' : 'products/videos/';
            
            if ($type === 'image') {
                $this->processAndStoreImage($file, $storagePath, $fileName, $shouldOptimize);
            } else {
                $file->storeAs('public/' . $storagePath, $fileName);
            }
            
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
    
    public function destroy(ProductMedia $medium)
    {
        if (Storage::disk('public')->exists($medium->url)) {
            Storage::disk('public')->delete($medium->url);
            
            if ($medium->type === 'image') {
                $this->deleteThumbnails($medium->url);
            }
        }
        
        $medium->delete();
        
        return redirect()->route('media.index')
            ->with('success', 'Le média a été supprimé avec succès.');
    }
    
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
                
                if ($media->type === 'image') {
                    $this->deleteThumbnails($media->url);
                }
            }
        }
        
        ProductMedia::whereIn('id', $validated['media_ids'])->delete();
        
        return redirect()->route('media.index')
            ->with('success', count($validated['media_ids']) . ' médias ont été supprimés avec succès.');
    }
    
    public function setAsMainImage(Request $request, ProductMedia $medium)
    {
        $request->validate([
            'product_id' => 'required|exists:produits,id'
        ]);
        
        $product = Product::find($request->product_id);
        
        if ($medium->type !== 'image') {
            return redirect()->back()->with('error', 'Seules les images peuvent être définies comme image principale.');
        }
        
        $product->update(['image' => $medium->url]);
        
        return redirect()->back()->with('success', 'Image principale mise à jour avec succès.');
    }
    
    private function processAndStoreImage($file, $directory = 'uploads')
    {
        $path = public_path("storage/{$directory}");
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        $img = Image::make($file);
        
        if ($img->width() > 1200) {
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        $img->save("{$path}/{$filename}", 80);
        
        return "{$directory}/{$filename}";
    }
    
    private function calculateOptimalQuality($filesize)
    {
        $filesize = $filesize / 1024;
        
        if ($filesize > 5000) {
            return 60;
        } elseif ($filesize > 2000) {
            return 70;
        } elseif ($filesize > 1000) {
            return 75;
        } elseif ($filesize > 500) {
            return 80;
        } else {
            return 85;
        }
    }
    
    private function deleteThumbnails($imagePath)
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['basename'];
        $mediumPath = str_replace($directory, $directory . '/medium', $imagePath);
        if (Storage::disk('public')->exists($mediumPath)) {
            Storage::disk('public')->delete($mediumPath);
        }
        
        $thumbnailPath = str_replace($directory, $directory . '/thumbnails', $imagePath);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
    }
    
    private function determineFileType($file)
    {
        $mimeType = $file->getMimeType();
        
        if (strpos($mimeType, 'video/') === 0) {
            return 'video';
        }
        
        return 'image';
    }
}