<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,editeur_contenu');
    }
    
    /**
     * Display a listing of the articles.
     */
    public function index()
    {
        $articles = BlogArticle::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'categorie' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'statut' => 'required|in:publié,brouillon',
        ]);

        BlogArticle::create($validated);
        
        return redirect()->route('articles.index')
            ->with('success', 'Article créé avec succès.');
    }

    /**
     * Display the specified article.
     */
    public function show(BlogArticle $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(BlogArticle $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, BlogArticle $article)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'categorie' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'statut' => 'required|in:publié,brouillon',
        ]);

        $article->update($validated);
        
        return redirect()->route('articles.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(BlogArticle $article)
    {
        $article->delete();
        
        return redirect()->route('articles.index')
            ->with('success', 'Article supprimé avec succès.');
    }
}
