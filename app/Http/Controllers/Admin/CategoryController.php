<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,gestionnaire_produits');
    }
    
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoryData = collect($validated)->except(['image'])->toArray();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $categoryData['image_url'] = $imagePath;
        }

        Category::create($categoryData);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $categoryData = collect($validated)->except(['image'])->toArray();

        if ($request->hasFile('image')) {
            if ($category->getRawOriginal('image_url') && Storage::disk('public')->exists($category->getRawOriginal('image_url'))) {
                Storage::disk('public')->delete($category->getRawOriginal('image_url'));
            }
            
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

    public function destroy(Category $category)
    {
        $productsCount = Product::where('categorie_id', $category->id)->count();
        
        if ($productsCount > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }
        
        if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
            Storage::disk('public')->delete($category->image_url);
        }
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
