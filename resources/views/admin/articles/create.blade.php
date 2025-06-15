@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Créer un Nouvel Article</h1>
            <a href="{{ route('articles.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title Field -->
                    <div class="form-group">
                        <label for="titre" class="block text-gray-300 mb-2">Titre <span class="text-purple-500">*</span></label>
                        <input type="text" id="titre" name="titre" 
                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('titre') border-red-500 @enderror" 
                               value="{{ old('titre') }}" required>
                        @error('titre')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Content Field -->
                    <div class="form-group">
                        <label for="contenu" class="block text-gray-300 mb-2">Contenu <span class="text-purple-500">*</span></label>
                        <textarea id="contenu" name="contenu" rows="12"
                                  class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('contenu') border-red-500 @enderror" 
                                  required>{{ old('contenu') }}</textarea>
                        @error('contenu')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Category Dropdown -->
                    <div class="form-group">
                        <label for="categorie" class="block text-gray-300 mb-2">Catégorie <span class="text-purple-500">*</span></label>
                        <div class="relative">
                            <select id="categorie" name="categorie" 
                                   class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none appearance-none @error('categorie') border-red-500 @enderror" 
                                   required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Nutrition" {{ old('categorie') == 'Nutrition' ? 'selected' : '' }}>Nutrition</option>
                                <option value="Fitness" {{ old('categorie') == 'Fitness' ? 'selected' : '' }}>Fitness</option>
                                <option value="Bien-être" {{ old('categorie') == 'Bien-être' ? 'selected' : '' }}>Bien-être</option>
                                <option value="Compléments alimentaires" {{ old('categorie') == 'Compléments alimentaires' ? 'selected' : '' }}>Compléments alimentaires</option>
                                <option value="Recettes" {{ old('categorie') == 'Recettes' ? 'selected' : '' }}>Recettes</option>
                                <option value="Conseils" {{ old('categorie') == 'Conseils' ? 'selected' : '' }}>Conseils</option>
                                <option value="Actualités" {{ old('categorie') == 'Actualités' ? 'selected' : '' }}>Actualités</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @error('categorie')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Status Field -->
                    <div class="form-group">
                        <label for="statut" class="block text-gray-300 mb-2">Statut <span class="text-purple-500">*</span></label>
                        <div class="relative">
                            <select id="statut" name="statut" 
                                   class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none appearance-none @error('statut') border-red-500 @enderror" 
                                   required>
                                <option value="brouillon" {{ old('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="publié" {{ old('statut') == 'publié' ? 'selected' : '' }}>Publié</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @error('statut')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Featured Image -->
                    <div class="form-group">
                        <label for="image" class="block text-gray-300 mb-2">Image à la une</label>
                        <label for="image" class="block w-full cursor-pointer">
                            <div class="bg-gray-900 border border-gray-700 border-dashed rounded-lg p-6 flex flex-col items-center justify-center hover:border-purple-500 transition-colors">
                                <svg class="w-10 h-10 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="text-gray-400">Cliquez pour ajouter une image</span>
                                <span class="text-gray-500 text-sm mt-2">PNG, JPG. Max 2MB.</span>
                            </div>
                            <input type="file" id="image" name="image" class="hidden" accept="image/*">
                        </label>
                        <div id="image-preview" class="mt-3 hidden">
                            <div class="bg-gray-900 border border-gray-700 rounded-lg p-2">
                                <img src="" alt="Aperçu" class="w-full h-40 object-cover rounded">
                                <button type="button" id="remove-image" class="mt-2 text-red-400 hover:text-red-300 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                        @error('image')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Publication Info -->
                    <div class="bg-gray-900/50 rounded-lg p-4 mt-4">
                        <div class="text-gray-400 text-sm mb-3">
                            <span class="block font-medium text-white mb-1">Informations de publication</span>
                            L'article sera créé et sauvegardé avec le statut sélectionné.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span>Publier l'article</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = imagePreview.querySelector('img');
        const removeImageBtn = document.getElementById('remove-image');
        
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
        
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.classList.add('hidden');
        });
        
        // Rich text editor initialization (if you have a WYSIWYG editor)
        // This is a placeholder - you'd need to add your specific editor initialization code
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(document.querySelector('#contenu'))
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>
@endpush
@endsection
