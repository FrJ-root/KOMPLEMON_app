@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Ajouter un Produit</h1>
            <a href="{{ route('products.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information Column -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gray-900/50 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Informations générales</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="nom" class="block text-gray-300 mb-2">Nom du produit <span class="text-purple-500">*</span></label>
                                <input type="text" id="nom" name="nom" 
                                       class="w-full bg-gray-800 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('nom') border-red-500 @enderror" 
                                       value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="description" class="block text-gray-300 mb-2">Description <span class="text-purple-500">*</span></label>
                                <textarea id="description" name="description" 
                                          class="w-full bg-gray-800 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('description') border-red-500 @enderror" 
                                          rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="categorie_id" class="block text-gray-300 mb-2">Catégorie <span class="text-purple-500">*</span></label>
                                    <select id="categorie_id" name="categorie_id" 
                                            class="w-full bg-gray-800 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('categorie_id') border-red-500 @enderror" 
                                            required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('categorie_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categorie_id')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="statut" class="block text-gray-300 mb-2">Statut <span class="text-purple-500">*</span></label>
                                    <select id="statut" name="statut" 
                                            class="w-full bg-gray-800 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('statut') border-red-500 @enderror" 
                                            required>
                                        <option value="brouillon" {{ old('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                        <option value="publié" {{ old('statut') == 'publié' ? 'selected' : '' }}>Publié</option>
                                    </select>
                                    @error('statut')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-900/50 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Images du produit</h2>
                        
                        <div class="mb-4">
                            <label for="images" class="block text-gray-300 mb-2">Images (max 5)</label>
                            <div class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center hover:border-purple-500 transition-colors">
                                <input type="file" id="images" name="images[]" multiple accept="image/*" 
                                       class="hidden" onchange="showPreview(this)">
                                
                                <label for="images" class="cursor-pointer block">
                                    <div id="preview-container" class="flex flex-wrap gap-4 justify-center mb-4" style="display: none;"></div>
                                    
                                    <div id="upload-prompt" class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span class="text-gray-400">Cliquez pour télécharger des images</span>
                                        <span class="text-gray-500 text-sm mt-1">Formats acceptés: JPG, PNG, GIF</span>
                                    </div>
                                </label>
                            </div>
                            @error('images')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="bg-gray-900/50 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Variations de produit</h2>
                        
                        <div id="variations-container" class="space-y-4">
                            <div class="variation-item bg-gray-800 p-4 rounded-lg border border-gray-700">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-300 mb-2">Taille/Format</label>
                                        <input type="text" name="variations[0][size]" placeholder="ex: 100g, 250ml"
                                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-300 mb-2">Saveur</label>
                                        <input type="text" name="variations[0][flavor]" placeholder="ex: Fraise, Vanille"
                                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-300 mb-2">Quantité en stock</label>
                                        <input type="number" name="variations[0][stock_quantity]" min="0" placeholder="Quantité"
                                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <button type="button" id="add-variation" class="inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>Ajouter une variation</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-gray-900/50 rounded-lg p-6">
                        <h2 class="flex items-center text-lg font-semibold text-white mb-4">
                            <span>Ingrédients et nutrition</span>
                            <span class="ml-2 text-xs text-gray-400 font-normal">(optionnel)</span>
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="ingredients" class="block text-gray-300 mb-2">Ingrédients</label>
                                <textarea id="ingredients" name="ingredients" 
                                          class="w-full bg-gray-800 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" 
                                          rows="5">{{ old('ingredients') }}</textarea>
                            </div>
                            
                            <div>
                                <label for="valeurs_nutritionnelles" class="block text-gray-300 mb-2">Valeurs nutritionnelles</label>
                                <textarea id="valeurs_nutritionnelles" name="valeurs_nutritionnelles" 
                                          class="w-full bg-gray-800 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" 
                                          rows="5">{{ old('valeurs_nutritionnelles') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Column -->
                <div class="space-y-6">
                    <div class="bg-gray-900/50 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">Prix et stock</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="prix" class="block text-gray-300 mb-2">Prix (€) <span class="text-purple-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">€</span>
                                    <input type="number" id="prix" name="prix" min="0" step="0.01" 
                                           class="w-full bg-gray-800 text-white pl-8 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('prix') border-red-500 @enderror" 
                                           value="{{ old('prix') }}" required>
                                </div>
                                @error('prix')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="prix_promo" class="block text-gray-300 mb-2">Prix promotionnel (€)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">€</span>
                                    <input type="number" id="prix_promo" name="prix_promo" min="0" step="0.01" 
                                           class="w-full bg-gray-800 text-white pl-8 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('prix_promo') border-red-500 @enderror" 
                                           value="{{ old('prix_promo') }}">
                                </div>
                                @error('prix_promo')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <div class="text-gray-500 text-xs mt-1">Laissez vide si le produit n'est pas en promotion</div>
                            </div>
                            
                            <div>
                                <label for="stock" class="block text-gray-300 mb-2">Stock global <span class="text-purple-500">*</span></label>
                                <input type="number" id="stock" name="stock" min="0" 
                                       class="w-full bg-gray-800 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none @error('stock') border-red-500 @enderror" 
                                       value="{{ old('stock', 0) }}" required>
                                @error('stock')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="flex items-center gap-3 pt-3">
                                <div class="flex-1">
                                    <label for="suivi_stock" class="block text-gray-300 mb-2">Suivi du stock</label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="suivi_stock" name="suivi_stock" value="1" 
                                              {{ old('suivi_stock', 1) ? 'checked' : '' }}
                                              class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 pt-3">
                                <div class="flex-1">
                                    <label for="featured" class="block text-gray-300 mb-2">Mettre en avant</label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="featured" name="featured" value="1" 
                                              {{ old('featured') ? 'checked' : '' }}
                                              class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-900/20 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-lg flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium">Conseil</p>
                            <p class="text-sm">Les produits avec le statut "Brouillon" ne seront pas visibles par les clients sur le site web.</p>
                        </div>
                    </div>
                    
                    <div class="sticky top-6">
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md flex items-center justify-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Créer le produit</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    function showPreview(input) {
        const previewContainer = document.getElementById('preview-container');
        const uploadPrompt = document.getElementById('upload-prompt');
        
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            previewContainer.style.display = 'flex';
            
            Array.from(input.files).forEach((file, index) => {
                if (index < 5) { // Limit to 5 images
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'relative w-24 h-24 overflow-hidden rounded-lg bg-gray-800';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover';
                        previewItem.appendChild(img);
                        
                        previewContainer.appendChild(previewItem);
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
            
            uploadPrompt.innerHTML = `
                <span class="text-gray-400 mt-2">Cliquez pour modifier la sélection</span>
                <span class="text-gray-500 text-sm">${input.files.length} fichier(s) sélectionné(s)</span>
            `;
        } else {
            previewContainer.style.display = 'none';
            uploadPrompt.innerHTML = `
                <svg class="w-12 h-12 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span class="text-gray-400">Cliquez pour télécharger des images</span>
                <span class="text-gray-500 text-sm mt-1">Formats acceptés: JPG, PNG, GIF</span>
            `;
        }
    }
    
    // Product variations functionality
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('variations-container');
        const addButton = document.getElementById('add-variation');
        let variationCount = 1;
        
        addButton.addEventListener('click', function() {
            const newVariation = document.createElement('div');
            newVariation.className = 'variation-item bg-gray-800 p-4 rounded-lg border border-gray-700 relative';
            newVariation.innerHTML = `
                <button type="button" class="absolute top-2 right-2 text-gray-500 hover:text-red-400" onclick="this.closest('.variation-item').remove()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-300 mb-2">Taille/Format</label>
                        <input type="text" name="variations[${variationCount}][size]" placeholder="ex: 100g, 250ml"
                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2">Saveur</label>
                        <input type="text" name="variations[${variationCount}][flavor]" placeholder="ex: Fraise, Vanille"
                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2">Quantité en stock</label>
                        <input type="number" name="variations[${variationCount}][stock_quantity]" min="0" placeholder="Quantité"
                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    </div>
                </div>
            `;
            
            container.appendChild(newVariation);
            variationCount++;
        });
    });
</script>
@endpush
@endsection
