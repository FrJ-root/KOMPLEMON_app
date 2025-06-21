@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto hide-scrollbar">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Détails du Témoignage</h1>
            <a href="{{ route('testimonials.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold text-white mb-1">{{ $testimonial->nom_client }}</h2>
                <div class="flex items-center space-x-4 text-sm">
                    <span class="text-gray-400">{{ $testimonial->created_at->format('d/m/Y H:i') }}</span>
                    <span class="px-2 py-1 text-xs font-medium rounded {{ $testimonial->statut === 'approuvé' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                        {{ $testimonial->statut === 'approuvé' ? 'Approuvé' : 'En attente' }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('testimonials.edit', $testimonial) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Modifier</span>
                </a>
                <form action="{{ route('testimonials.toggleApproval', $testimonial) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 {{ $testimonial->statut === 'approuvé' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-md flex items-center gap-2 transition-all">
                        @if($testimonial->statut === 'approuvé')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Désapprouver</span>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Approuver</span>
                        @endif
                    </button>
                </form>
            </div>
        </div>
        
        <div class="mb-8">
            <h3 class="text-gray-400 text-sm uppercase tracking-wider mb-3">Contenu du témoignage</h3>
            <div class="bg-gray-900 rounded-lg p-5 border border-gray-700">
                <p class="text-gray-200 whitespace-pre-line">{{ $testimonial->contenu }}</p>
            </div>
        </div>
        
        @if($testimonial->media_url)
        <div>
            <h3 class="text-gray-400 text-sm uppercase tracking-wider mb-3">Média</h3>
            <div class="bg-gray-900 rounded-lg p-5 border border-gray-700">
                <div class="flex flex-col md:flex-row items-start gap-6">
                    <div class="w-full md:w-1/2">
                        @if($testimonial->media_type === 'image')
                            <img src="{{ asset($testimonial->media_url) }}" alt="Témoignage média" class="max-w-full h-auto rounded">
                        @elseif($testimonial->media_type === 'video')
                            <video controls class="max-w-full h-auto rounded">
                                <source src="{{ asset($testimonial->media_url) }}" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        @elseif($testimonial->media_type === 'youtube')
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe 
                                    src="{{ str_replace('watch?v=', 'embed/', $testimonial->media_url) }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="w-full h-80 rounded"
                                ></iframe>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <span class="text-gray-400 w-24">Type:</span>
                                <span class="text-white">{{ ucfirst($testimonial->media_type) }}</span>
                            </div>
                            
                            @if($testimonial->media_type === 'youtube')
                            <div class="flex items-start">
                                <span class="text-gray-400 w-24">URL:</span>
                                <a href="{{ $testimonial->media_url }}" target="_blank" class="text-blue-400 hover:underline break-all">
                                    {{ $testimonial->media_url }}
                                </a>
                            </div>
                            @endif
                            
                            <div class="flex items-center">
                                <span class="text-gray-400 w-24">Ajouté le:</span>
                                <span class="text-white">{{ $testimonial->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <h3 class="text-gray-400 text-sm uppercase tracking-wider mb-3">Actions</h3>
        <div class="flex space-x-4">
            <a href="{{ route('testimonials.edit', $testimonial) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Modifier</span>
            </a>
            
            <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Supprimer</span>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%;
    }
    
    .aspect-h-9 {
        position: relative;
    }
    
    .aspect-w-16 iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 0.375rem;
    }
</style>
@endsection
