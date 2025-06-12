<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_produits');
    }
    
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Remove image from validated data since we'll handle it separately
        $categoryData = collect($validated)->except(['image'])->toArray();

        if ($request->hasFile('image')) {
            // Store the image in the public disk's categories directory
            $imagePath = $request->file('image')->store('categories', 'public');
            $categoryData['image_url'] = $imagePath;
        }

        Category::create($categoryData);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Remove image from validated data since we'll handle it separately
        $categoryData = collect($validated)->except(['image'])->toArray();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->getRawOriginal('image_url') && Storage::disk('public')->exists($category->getRawOriginal('image_url'))) {
                Storage::disk('public')->delete($category->getRawOriginal('image_url'));
            }
            
            // Store the new image
            $imagePath = $request->file('image')->store('categories', 'public');
            $categoryData['image_url'] = $imagePath;
        }

        if ($request->has('remove_image') && $category->getRawOriginal('image_url')) {
            Storage::disk('public')->delete($category->getRawOriginal('image_url'));
            $categoryData['image_url'] = null;
        }

        $category->update($categoryData);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if there are products in this category
        $productsCount = Product::where('categorie_id', $category->id)->count();
        
        if ($productsCount > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }
        
        // Delete category image if exists
        if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
            Storage::disk('public')->delete($category->image_url);
        }
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
