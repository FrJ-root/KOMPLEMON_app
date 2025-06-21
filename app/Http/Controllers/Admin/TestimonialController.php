<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur,editeur_contenu');
    }
    
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
            'media_type' => 'nullable|string|in:image,video,youtube,none',
            'statut' => 'required|string|in:approuvé,en attente',
            'nom_client' => 'required|string|max:255',
            'youtube_url' => 'nullable|string|url',
            'contenu' => 'required|string',
        ]);

        $testimonial = new Testimonial();
        $testimonial->nom_client = $request->nom_client;
        $testimonial->contenu = $request->contenu;
        $testimonial->statut = $request->statut;
        $testimonial->media_type = $request->media_type ?? 'none';

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

    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
            'media_type' => 'nullable|string|in:image,video,youtube,none',
            'statut' => 'required|string|in:approuvé,en attente',
            'nom_client' => 'required|string|max:255',
            'youtube_url' => 'nullable|string|url',
            'contenu' => 'required|string',
        ]);

        $testimonial->nom_client = $request->nom_client;
        $testimonial->contenu = $request->contenu;
        $testimonial->statut = $request->statut;
        
        if ($request->media_type !== $testimonial->media_type || 
            ($request->media_type === 'youtube' && $request->youtube_url !== $testimonial->media_url)) {
            
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

    public function toggleApproval(Testimonial $testimonial)
    {
        $testimonial->statut = $testimonial->statut === 'approuvé' ? 'en attente' : 'approuvé';
        $testimonial->save();
        
        return redirect()->back()
            ->with('success', 'Statut du témoignage mis à jour avec succès.');
    }

    public function destroy(Testimonial $testimonial)
    {
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