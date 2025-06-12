<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_produits');
    }
    
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with('category', 'media')->paginate(10);
        $categories = Category::all(); // Fetch all categories for the filter dropdown
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }
    
    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'ingredients' => 'nullable|string',
            'valeurs_nutritionnelles' => 'nullable|string',
            'statut' => 'required|in:publié,brouillon',
            'variations' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Create the product
            $product = Product::create($validated);
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductMedia::create([
                        'produit_id' => $product->id,
                        'url' => $path,
                        'type' => 'image',
                    ]);
                }
            }
            
            // Handle variations
            if ($request->has('variations') && is_array($request->variations)) {
                foreach ($request->variations as $variation) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'size' => $variation['size'] ?? null,
                        'flavor' => $variation['flavor'] ?? null,
                        'quantity' => $variation['quantity'] ?? null,
                        'price' => $variation['price'] ?? $product->prix,
                        'stock_quantity' => $variation['stock_quantity'] ?? $product->stock,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Produit créé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création du produit: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('category', 'media');
        $variations = ProductVariation::where('product_id', $product->id)->get();
        
        return view('admin.products.show', compact('product', 'variations'));
    }
    
    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Eager load only what's needed
        $product->load(['category']);
        
        // Get categories without loading all their relationships
        $categories = Category::select('id', 'nom')->get();
        
        // Simplified media loading
        $productMedia = null;
        try {
            // Only attempt to load media if table exists and only load the most recent ones
            if (Schema::hasTable('media')) {
                $productMedia = $product->media()->latest()->take(5)->get();
            }
        } catch (\Exception $e) {
            // Log the error but don't break the page
            \Log::error('Error loading media: ' . $e->getMessage());
        }
        
        return view('admin.products.edit', compact('product', 'categories', 'productMedia'));
    }
    
    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'ingredients' => 'nullable|string',
            'valeurs_nutritionnelles' => 'nullable|string',
            'statut' => 'required|in:publié,brouillon',
            'variations' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_media' => 'nullable|array',
            'delete_variations' => 'nullable|array',
        ]);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Update the product
            $product->update($validated);
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductMedia::create([
                        'produit_id' => $product->id,
                        'url' => $path,
                        'type' => 'image',
                    ]);
                }
            }
            
            // Handle media deletions
            if ($request->has('delete_media') && is_array($request->delete_media)) {
                $mediaToDelete = ProductMedia::whereIn('id', $request->delete_media)
                    ->where('produit_id', $product->id)
                    ->get();
                
                foreach ($mediaToDelete as $media) {
                    Storage::disk('public')->delete($media->url);
                    $media->delete();
                }
            }
            
            // Handle variations updates and additions
            if ($request->has('variations') && is_array($request->variations)) {
                foreach ($request->variations as $variationData) {
                    if (isset($variationData['id'])) {
                        // Update existing variation
                        $variation = ProductVariation::where('id', $variationData['id'])
                            ->where('product_id', $product->id)
                            ->first();
                            
                        if ($variation) {
                            $variation->update([
                                'size' => $variationData['size'] ?? null,
                                'flavor' => $variationData['flavor'] ?? null,
                                'quantity' => $variationData['quantity'] ?? null,
                                'price' => isset($variationData['price']) ? (float)$variationData['price'] : $product->prix,
                                'stock_quantity' => isset($variationData['stock_quantity']) ? (int)$variationData['stock_quantity'] : $product->stock,
                            ]);
                        }
                    } else {
                        // Add new variation
                        ProductVariation::create([
                            'product_id' => $product->id,
                            'size' => $variationData['size'] ?? null,
                            'flavor' => $variationData['flavor'] ?? null,
                            'quantity' => $variationData['quantity'] ?? null,
                            'price' => $variationData['price'] ?? $product->prix,
                            'stock_quantity' => $variationData['stock_quantity'] ?? $product->stock,
                        ]);
                    }
                }
            }
            
            // Handle variation deletions
            if ($request->has('delete_variations') && is_array($request->delete_variations)) {
                ProductVariation::whereIn('id', $request->delete_variations)
                    ->where('product_id', $product->id)
                    ->delete();
            }
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Produit mis à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour du produit: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Delete product media
            $media = ProductMedia::where('produit_id', $product->id)->get();
            
            foreach ($media as $item) {
                Storage::disk('public')->delete($item->url);
                $item->delete();
            }
            
            // Delete product variations
            ProductVariation::where('product_id', $product->id)->delete();
            
            // Delete the product
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Produit supprimé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression du produit: ' . $e->getMessage());
        }
    }
    
    /**
     * Update product stock
     */
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);
        
        $product->update(['stock' => $validated['stock']]);
        
        return redirect()->back()->with('success', 'Stock mis à jour avec succès.');
    }
}
