<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $request->validate([
            'nom_client' => 'required|string|max:255',
            'contenu' => 'required|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
            'media_type' => 'nullable|string|in:image,video,youtube,none',
            'youtube_url' => 'nullable|string|url',
            'statut' => 'required|string|in:approuvé,en attente',
        ]);

        $testimonial = new Testimonial();
        $testimonial->nom_client = $request->nom_client;
        $testimonial->contenu = $request->contenu;
        $testimonial->statut = $request->statut;
        $testimonial->media_type = $request->media_type ?? 'none';

        // Handle media upload
        if ($request->media_type === 'youtube' && $request->youtube_url) {
            $testimonial->media_url = $request->youtube_url;
        } elseif ($request->hasFile('media')) {
            $media = $request->file('media');
            $filename = Str::slug($request->nom_client) . '-' . time() . '.' . $media->getClientOriginalExtension();
            $path = $media->storeAs('public/testimonials', $filename);
            $testimonial->media_url = str_replace('public/', 'storage/', $path);
        }

        $testimonial->save();

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
        $request->validate([
            'nom_client' => 'required|string|max:255',
            'contenu' => 'required|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
            'media_type' => 'nullable|string|in:image,video,youtube,none',
            'youtube_url' => 'nullable|string|url',
            'statut' => 'required|string|in:approuvé,en attente',
        ]);

        $testimonial->nom_client = $request->nom_client;
        $testimonial->contenu = $request->contenu;
        $testimonial->statut = $request->statut;
        
        // Handle media update
        if ($request->media_type !== $testimonial->media_type || 
            ($request->media_type === 'youtube' && $request->youtube_url !== $testimonial->media_url)) {
            
            // Remove old media if it exists and it's not a YouTube URL
            if ($testimonial->media_url && !Str::contains($testimonial->media_url, ['youtube.com', 'youtu.be'])) {
                $oldPath = str_replace('storage/', 'public/', $testimonial->media_url);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }
            
            $testimonial->media_type = $request->media_type ?? 'none';
            
            if ($request->media_type === 'youtube' && $request->youtube_url) {
                $testimonial->media_url = $request->youtube_url;
            } elseif ($request->hasFile('media')) {
                $media = $request->file('media');
                $filename = Str::slug($request->nom_client) . '-' . time() . '.' . $media->getClientOriginalExtension();
                $path = $media->storeAs('public/testimonials', $filename);
                $testimonial->media_url = str_replace('public/', 'storage/', $path);
            } elseif ($request->media_type === 'none' || ($request->media_type !== 'youtube' && !$request->hasFile('media'))) {
                $testimonial->media_url = null;
            }
        }

        $testimonial->save();

        return redirect()->route('testimonials.index')
            ->with('success', 'Témoignage mis à jour avec succès.');
    }

    /**
     * Change the approval status of a testimonial.
     */
    public function toggleApproval(Testimonial $testimonial)
    {
        $testimonial->statut = $testimonial->statut === 'approuvé' ? 'en attente' : 'approuvé';
        $testimonial->save();
        
        return redirect()->back()
            ->with('success', 'Statut du témoignage mis à jour avec succès.');
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        // Delete media file if exists and not a YouTube URL
        if ($testimonial->media_url && !Str::contains($testimonial->media_url, ['youtube.com', 'youtu.be'])) {
            $path = str_replace('storage/', 'public/', $testimonial->media_url);
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }
        
        $testimonial->delete();
        
        return redirect()->route('testimonials.index')
            ->with('success', 'Témoignage supprimé avec succès.');
    }
}
