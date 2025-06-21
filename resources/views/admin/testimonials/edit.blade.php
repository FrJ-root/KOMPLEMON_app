@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto hide-scrollbar">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Modifier le Témoignage</h1>
            <a href="{{ route('testimonials.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <form action="{{ route('testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nom_client" class="block text-gray-300 mb-2">Nom du client <span class="text-purple-500">*</span></label>
                    <input type="text" id="nom_client" name="nom_client" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('nom_client') border-red-500 @enderror" 
                           value="{{ old('nom_client', $testimonial->nom_client) }}" required>
                    @error('nom_client')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="statut" class="block text-gray-300 mb-2">Statut <span class="text-purple-500">*</span></label>
                    <select id="statut" name="statut" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('statut') border-red-500 @enderror" required>
                        <option value="en attente" {{ old('statut', $testimonial->statut) == 'en attente' ? 'selected' : '' }}>En attente</option>
                        <option value="approuvé" {{ old('statut', $testimonial->statut) == 'approuvé' ? 'selected' : '' }}>Approuvé</option>
                    </select>
                    @error('statut')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label for="contenu" class="block text-gray-300 mb-2">Témoignage <span class="text-purple-500">*</span></label>
                <textarea id="contenu" name="contenu" 
                          class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                          @error('contenu') border-red-500 @enderror" 
                          rows="5" required>{{ old('contenu', $testimonial->contenu) }}</textarea>
                @error('contenu')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-300 mb-2">Type de média</label>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="flex items-center">
                        <input type="radio" id="media_none" name="media_type" value="none" class="w-4 h-4 text-purple-600 bg-gray-700 border-gray-600"
                               {{ !$testimonial->media_url || old('media_type') == 'none' ? 'checked' : '' }}>
                        <label for="media_none" class="ml-2 text-gray-300">Aucun</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="media_image" name="media_type" value="image" class="w-4 h-4 text-purple-600 bg-gray-700 border-gray-600"
                               {{ $testimonial->media_type == 'image' || old('media_type') == 'image' ? 'checked' : '' }}>
                        <label for="media_image" class="ml-2 text-gray-300">Image</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="media_video" name="media_type" value="video" class="w-4 h-4 text-purple-600 bg-gray-700 border-gray-600"
                               {{ $testimonial->media_type == 'video' || old('media_type') == 'video' ? 'checked' : '' }}>
                        <label for="media_video" class="ml-2 text-gray-300">Vidéo</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="media_youtube" name="media_type" value="youtube" class="w-4 h-4 text-purple-600 bg-gray-700 border-gray-600"
                               {{ $testimonial->media_type == 'youtube' || old('media_type') == 'youtube' ? 'checked' : '' }}>
                        <label for="media_youtube" class="ml-2 text-gray-300">YouTube</label>
                    </div>
                </div>
            </div>
            
            <!-- Current Media Preview -->
            @if($testimonial->media_url)
            <div class="mb-6 bg-gray-900 p-4 rounded-lg border border-gray-700">
                <h3 class="text-white text-lg mb-3">Média actuel</h3>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/3">
                        @if($testimonial->media_type == 'image')
                            <img src="{{ asset($testimonial->media_url) }}" alt="Témoignage média" class="max-w-full h-auto rounded">
                        @elseif($testimonial->media_type == 'video')
                            <video controls class="max-w-full h-auto rounded">
                                <source src="{{ asset($testimonial->media_url) }}" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        @elseif($testimonial->media_type == 'youtube')
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe 
                                    src="{{ str_replace('watch?v=', 'embed/', $testimonial->media_url) }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="w-full h-full rounded"
                                ></iframe>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">
                                Type: <span class="text-purple-400">{{ ucfirst($testimonial->media_type) }}</span>
                            </p>
                            @if($testimonial->media_type == 'youtube')
                                <p class="text-gray-400 text-sm truncate mt-2">
                                    URL: <a href="{{ $testimonial->media_url }}" target="_blank" class="text-blue-400 hover:underline">{{ $testimonial->media_url }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <div id="media_upload" class="mb-6 {{ in_array($testimonial->media_type, ['image', 'video']) || old('media_type') == 'image' || old('media_type') == 'video' ? '' : 'hidden' }}">
                <label for="media" class="block text-gray-300 mb-2">Nouveau fichier média</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-md hover:border-purple-500/50 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-400">
                            <label for="media" class="relative cursor-pointer bg-gray-800 rounded-md font-medium text-purple-400 hover:text-purple-300 focus-within:outline-none">
                                <span>Télécharger un fichier</span>
                                <input id="media" name="media" type="file" class="sr-only" accept="image/*,video/*">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, GIF, MP4, MOV jusqu'à 10MB
                        </p>
                    </div>
                </div>
                <div id="file_name" class="mt-2 text-sm text-gray-400 hidden"></div>
                @error('media')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div id="youtube_input" class="mb-6 {{ $testimonial->media_type == 'youtube' || old('media_type') == 'youtube' ? '' : 'hidden' }}">
                <label for="youtube_url" class="block text-gray-300 mb-2">URL YouTube</label>
                <input type="url" id="youtube_url" name="youtube_url" 
                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                       @error('youtube_url') border-red-500 @enderror" 
                       value="{{ old('youtube_url', $testimonial->media_type == 'youtube' ? $testimonial->media_url : '') }}" 
                       placeholder="https://www.youtube.com/watch?v=...">
                @error('youtube_url')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="bg-blue-900/20 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-lg mb-6 flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium">Information</p>
                    <p class="text-sm">Les témoignages approuvés seront visibles sur le site. Les témoignages en attente ne sont visibles que dans l'administration.</p>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Mettre à jour</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
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
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mediaTypeRadios = document.querySelectorAll('input[name="media_type"]');
        const mediaUploadDiv = document.getElementById('media_upload');
        const youtubeInputDiv = document.getElementById('youtube_input');
        const mediaInput = document.getElementById('media');
        const fileNameDiv = document.getElementById('file_name');
        
        // Handle media type selection
        mediaTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'image' || this.value === 'video') {
                    mediaUploadDiv.classList.remove('hidden');
                    youtubeInputDiv.classList.add('hidden');
                } else if (this.value === 'youtube') {
                    mediaUploadDiv.classList.add('hidden');
                    youtubeInputDiv.classList.remove('hidden');
                } else {
                    mediaUploadDiv.classList.add('hidden');
                    youtubeInputDiv.classList.add('hidden');
                }
            });
        });
        
        // Show file name when selected
        mediaInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileNameDiv.textContent = this.files[0].name;
                fileNameDiv.classList.remove('hidden');
            } else {
                fileNameDiv.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection
