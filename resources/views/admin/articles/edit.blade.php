@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Modifier l'Article</h1>
            <a href="{{ route('articles.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 shadow-lg">
        <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="form-group group">
                    <label for="titre" class="block text-gray-300 mb-2 group-hover:text-purple-400 transition-colors">
                        Titre <span class="text-purple-500">*</span>
                    </label>
                    <input type="text" id="titre" name="titre" 
                           class="w-full bg-gray-900 text-white px-4 py-3 rounded-md border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-all
                           @error('titre') border-red-500 @enderror" 
                           value="{{ old('titre', $article->titre) }}" required>
                    @error('titre')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group group">
                    <label for="categorie" class="block text-gray-300 mb-2 group-hover:text-purple-400 transition-colors">
                        Catégorie
                    </label>
                    <input type="text" id="categorie" name="categorie" 
                           class="w-full bg-gray-900 text-white px-4 py-3 rounded-md border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-all
                           @error('categorie') border-red-500 @enderror" 
                           value="{{ old('categorie', $article->categorie) }}">
                    @error('categorie')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-8 form-group group">
                <label for="contenu" class="block text-gray-300 mb-2 group-hover:text-purple-400 transition-colors">
                    Contenu <span class="text-purple-500">*</span>
                </label>
                <textarea id="contenu" name="contenu" 
                          class="w-full bg-gray-900 text-white px-4 py-3 rounded-md border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-all
                          @error('contenu') border-red-500 @enderror" 
                          rows="12" required>{{ old('contenu', $article->contenu) }}</textarea>
                @error('contenu')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-8 form-group group">
                <label for="statut" class="block text-gray-300 mb-2 group-hover:text-purple-400 transition-colors">
                    Statut <span class="text-purple-500">*</span>
                </label>
                <div class="relative">
                    <select id="statut" name="statut" 
                           class="w-full bg-gray-900 text-white px-4 py-3 rounded-md border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none appearance-none transition-all
                           @error('statut') border-red-500 @enderror" required>
                        <option value="brouillon" {{ old('statut', $article->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="publié" {{ old('statut', $article->statut) == 'publié' ? 'selected' : '' }}>Publié</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('statut')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                <div class="text-gray-500 text-xs mt-2">
                    Les articles en brouillon ne sont visibles que par les administrateurs
                </div>
            </div>
            
            <div class="mb-8 form-group">
                <label class="block text-gray-300 mb-3 group-hover:text-purple-400 transition-colors">
                    Image de couverture
                </label>
                <div class="flex flex-col md:flex-row gap-6">
                    @if($article->image_url)
                    <div class="w-full md:w-1/3">
                        <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex items-center justify-center h-48 hover:border-purple-500/50 transition-all">
                            <img src="{{ asset($article->image_url) }}" alt="Image actuelle" class="max-h-full max-w-full rounded shadow-md">
                        </div>
                        <div class="mt-3 text-center">
                            <button type="button" class="text-red-400 hover:text-red-300 text-sm flex items-center justify-center mx-auto group" data-action="remove-image">
                                <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer l'image
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex-1">
                        <label for="image" class="block w-full cursor-pointer">
                            <div class="bg-gray-900 border border-gray-700 border-dashed rounded-lg p-6 flex flex-col items-center justify-center h-48 hover:border-purple-500 hover:bg-gray-800/50 transition-all">
                                <svg class="w-12 h-12 text-gray-500 mb-3 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="text-gray-400">Cliquez pour télécharger une image</span>
                                <span class="text-gray-500 text-sm mt-2">PNG, JPG ou WebP. Max 2MB.</span>
                            </div>
                            <input type="file" id="image" name="image" class="hidden" accept="image/*">
                        </label>
                        <div id="image-preview-name" class="mt-2 text-center text-gray-400 text-sm hidden"></div>
                    </div>
                </div>
                @error('image')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="bg-blue-900/20 border border-blue-500/30 text-blue-400 px-5 py-4 rounded-lg mb-8 flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium">Information</p>
                    <p class="text-sm mt-1">Les articles publiés seront visibles sur le blog du site. Les brouillons ne sont visibles que par les administrateurs.</p>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md flex items-center gap-2 transition-all hover:translate-y-[-2px] active:translate-y-0 shadow-lg hover:shadow-purple-600/20">
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
    .form-group:focus-within label {
        color: #a78bfa;
    }
    
    .form-group:hover label {
        color: #a78bfa;
    }
    
    @keyframes pulse-border {
        0%, 100% { border-color: rgba(139, 92, 246, 0.3); }
        50% { border-color: rgba(139, 92, 246, 0.6); }
    }
    
    .focus-within\:pulse-border:focus-within {
        animation: pulse-border 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle image removal
        const removeImageBtn = document.querySelector('[data-action="remove-image"]');
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                // Create hidden input to signal image removal
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_image';
                hiddenInput.value = '1';
                document.querySelector('form').appendChild(hiddenInput);
                
                // Update UI
                const imageContainer = this.closest('.w-full.md\\:w-1\\/3');
                imageContainer.remove();
            });
        }
        
        // Show file name when selected
        const imageInput = document.getElementById('image');
        const imagePreviewName = document.getElementById('image-preview-name');
        
        if (imageInput && imagePreviewName) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    imagePreviewName.textContent = this.files[0].name;
                    imagePreviewName.classList.remove('hidden');
                }
            });
        }
        
        // Initialize WYSIWYG editor if needed
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(document.querySelector('#contenu'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                })
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>
@endpush
@endsection
