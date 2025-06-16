<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_produits');
    }
    
    public function index()
    {
        $products = Product::with('category', 'media')->paginate(10);
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorie_id' => 'required|exists:categories,id',
            'valeurs_nutritionnelles' => 'nullable|string',
            'statut' => 'required|in:publié,brouillon',
            'prix_promo' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'ingredients' => 'nullable|string',
            'variations' => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        
        try {
            $product = Product::create($validated);
            
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
    
    public function show(Product $product)
    {
        $product->load('category', 'media');
        $variations = ProductVariation::where('product_id', $product->id)->get();
        
        return view('admin.products.show', compact('product', 'variations'));
    }
    
    public function edit(Product $product)
    {
        $product->load(['category']);
        
        $categories = Category::select('id', 'nom')->get();
        
        $productMedia = null;
        try {
            if (Schema::hasTable('media')) {
                $productMedia = $product->media()->latest()->take(5)->get();
            }
        } catch (\Exception $e) {
            \Log::error('Error loading media: ' . $e->getMessage());
        }
        
        return view('admin.products.edit', compact('product', 'categories', 'productMedia'));
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorie_id' => 'required|exists:categories,id',
            'valeurs_nutritionnelles' => 'nullable|string',
            'statut' => 'required|in:publié,brouillon',
            'prix_promo' => 'nullable|numeric|min:0',
            'delete_variations' => 'nullable|array',
            'stock' => 'required|integer|min:0',
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'ingredients' => 'nullable|string',
            'delete_media' => 'nullable|array',
            'variations' => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        
        try {
            $product->update($validated);
            
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
            
            if ($request->has('delete_media') && is_array($request->delete_media)) {
                $mediaToDelete = ProductMedia::whereIn('id', $request->delete_media)
                    ->where('produit_id', $product->id)
                    ->get();
                
                foreach ($mediaToDelete as $media) {
                    Storage::disk('public')->delete($media->url);
                    $media->delete();
                }
            }
            
            if ($request->has('variations') && is_array($request->variations)) {
                foreach ($request->variations as $variationData) {
                    if (isset($variationData['id'])) {
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
    
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        
        try {
            $media = ProductMedia::where('produit_id', $product->id)->get();
            
            foreach ($media as $item) {
                Storage::disk('public')->delete($item->url);
                $item->delete();
            }
            
            ProductVariation::where('product_id', $product->id)->delete();
            
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Produit supprimé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression du produit: ' . $e->getMessage());
        }
    }
    
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);
        
        $product->update(['stock' => $validated['stock']]);
        
        return redirect()->back()->with('success', 'Stock mis à jour avec succès.');
    }
}
