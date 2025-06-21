<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogArticle;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,editeur_contenu');
    }
    
    public function index()
    {
        $articles = BlogArticle::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

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

    public function show(BlogArticle $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    public function edit(BlogArticle $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

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

    public function destroy(BlogArticle $article)
    {
        $article->delete();
        
        return redirect()->route('articles.index')
            ->with('success', 'Article supprimé avec succès.');
    }
}
