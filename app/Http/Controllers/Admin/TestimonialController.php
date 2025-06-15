<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,editeur_contenu');
    }
    
    /**
     * Display a listing of the testimonials.
     */
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_client' => 'required|string|max:255',
            'contenu' => 'required|string',
            'media_url' => 'nullable|string|max:255',
            'statut' => 'required|in:approuvé,en attente',
        ]);
        
        Testimonial::create($validated);
        
        return redirect()->route('testimonials.index')
            ->with('success', 'Témoignage créé avec succès.');
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'nom_client' => 'required|string|max:255',
            'contenu' => 'required|string',
            'media_url' => 'nullable|string|max:255',
            'statut' => 'required|in:approuvé,en attente',
        ]);
        
        $testimonial->update($validated);
        
        return redirect()->route('testimonials.index')
            ->with('success', 'Témoignage mis à jour avec succès.');
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        
        return redirect()->route('testimonials.index')
            ->with('success', 'Témoignage supprimé avec succès.');
    }
}
