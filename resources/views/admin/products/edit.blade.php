@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header section with backgrop filter effect -->
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6 backdrop-blur-sm">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <span class="text-purple-400">#{{ $product->id }}</span> 
                    <span>{{ $product->nom }}</span>
                </h1>
                <p class="text-gray-400 text-sm">Dernière mise à jour: {{ $product->updated_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('products.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-md flex items-center gap-2 transition-all border border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Retour</span>
                </a>
                <button type="submit" form="product-form" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all shadow-lg shadow-purple-700/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Enregistrer</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main content with preview and form -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Product preview panel (left) -->
        <div class="lg:col-span-4 xl:col-span-3 space-y-6">
            <!-- Product Preview Card -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-xl shadow-black/20 product-preview transform transition-all duration-300 hover:scale-[1.02]">
                <div class="relative aspect-square bg-gray-900 flex items-center justify-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->nom }}" class="object-cover w-full h-full">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-800 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    @if($product->prix_promo)
                        <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md shadow-lg">
                            -{{ round((1 - $product->prix_promo / $product->prix) * 100) }}%
                        </div>
                    @endif
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white font-medium truncate">{{ $product->nom }}</div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if($product->prix_promo)
                                    <span class="text-white font-bold">{{ number_format($product->prix_promo, 2) }} €</span>
                                    <span class="text-gray-400 line-through text-sm ml-2">{{ number_format($product->prix, 2) }} €</span>
                                @else
                                    <span class="text-white font-bold">{{ number_format($product->prix, 2) }} €</span>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->stock > 10 ? 'bg-green-500/20 text-green-400' : ($product->stock > 0 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                {{ $product->stock > 0 ? $product->stock . ' en stock' : 'Épuisé' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->statut === 'publié' ? 'bg-green-500/20 text-green-400' : 'bg-gray-600/40 text-gray-400' }}">
                            {{ ucfirst($product->statut) }}
                        </span>
                        
                        @if($product->featured)
                            <span class="px-2 py-1 text-xs rounded-full bg-purple-500/20 text-purple-400">
                                Mis en avant
                            </span>
                        @endif
                        
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">
                            {{ $product->category->nom ?? 'Sans catégorie' }}
                        </span>
                    </div>
                    
                    <p class="text-gray-400 text-sm line-clamp-3 mb-3">{{ $product->description }}</p>
                    
                    <div class="pt-3 border-t border-gray-700 flex justify-between text-xs text-gray-500">
                        <div>ID: #{{ $product->id }}</div>
                        <div>Vues: {{ $product->vues ?? 0 }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Status Card -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-4 shadow-xl shadow-black/20">
                <h3 class="text-white font-medium mb-3">Statut du produit</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Visibilité</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->statut === 'publié' ? 'bg-green-500/20 text-green-400' : 'bg-gray-600/40 text-gray-400' }}">
                            {{ ucfirst($product->statut) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Stock</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->stock > 10 ? 'bg-green-500/20 text-green-400' : ($product->stock > 0 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                            {{ $product->stock }} unités
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Prix</span>
                        <span class="text-white">{{ number_format($product->prix, 2) }} €</span>
                    </div>
                    
                    @if($product->prix_promo)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Prix promo</span>
                        <span class="text-green-400">{{ number_format($product->prix_promo, 2) }} €</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Catégorie</span>
                        <span class="text-blue-400">{{ $product->category->nom ?? 'Non définie' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit form (right) -->
        <div class="lg:col-span-8 xl:col-span-9">
            <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Tabbed Navigation -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-xl shadow-black/20">
                    <div class="flex border-b border-gray-700">
                        <button type="button" class="tab-button active flex-1 py-3 px-4 focus:outline-none transition-all" data-tab="general">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Général
                            </span>
                        </button>
                        <button type="button" class="tab-button flex-1 py-3 px-4 focus:outline-none transition-all" data-tab="details">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Détails
                            </span>
                        </button>
                        <button type="button" class="tab-button flex-1 py-3 px-4 focus:outline-none transition-all" data-tab="media">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Médias
                            </span>
                        </button>
                    </div>
                    
                    <!-- General Tab -->
                    <div id="general-tab" class="tab-content p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label for="nom" class="block text-gray-300">Nom du produit <span class="text-purple-500">*</span></label>
                                <input type="text" id="nom" name="nom" 
                                       class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors
                                       @error('nom') border-red-500 @enderror" 
                                       value="{{ old('nom', $product->nom) }}" required>
                                @error('nom')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="categorie_id" class="block text-gray-300">Catégorie</label>
                                <select id="categorie_id" name="categorie_id" 
                                       class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors
                                       @error('categorie_id') border-red-500 @enderror">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('categorie_id', $product->categorie_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categorie_id')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6 space-y-2">
                            <label for="description" class="block text-gray-300">Description</label>
                            <textarea id="description" name="description" rows="4"
                                    class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors
                                    @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="space-y-2">
                                <label for="prix" class="block text-gray-300">Prix <span class="text-purple-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500">€</span>
                                    </div>
                                    <input type="number" id="prix" name="prix" step="0.01" min="0"
                                          class="w-full bg-gray-900/50 text-white pl-8 px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors
                                          @error('prix') border-red-500 @enderror" 
                                          value="{{ old('prix', $product->prix) }}" required>
                                </div>
                                @error('prix')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="prix_promo" class="block text-gray-300">Prix promotionnel</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500">€</span>
                                    </div>
                                    <input type="number" id="prix_promo" name="prix_promo" step="0.01" min="0"
                                          class="w-full bg-gray-900/50 text-white pl-8 px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors
                                          @error('prix_promo') border-red-500 @enderror" 
                                          value="{{ old('prix_promo', $product->prix_promo) }}">
                                </div>
                                @error('prix_promo')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="stock" class="block text-gray-300">Stock</label>
                                <input type="number" id="stock" name="stock" min="0"
                                      class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors
                                      @error('stock') border-red-500 @enderror" 
                                      value="{{ old('stock', $product->stock) }}">
                                @error('stock')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-6">
                            <div class="flex items-center h-5">
                                <input id="featured" name="featured" type="checkbox" value="1"
                                      {{ old('featured', $product->featured) ? 'checked' : '' }}
                                      class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800 transition-colors">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="featured" class="text-gray-300 font-medium">Produit mis en avant</label>
                                <p class="text-gray-500">Ce produit sera affiché dans les sections "Produits populaires" du site</p>
                            </div>
                        </div>
                        
                        <div class="mb-6 space-y-2">
                            <label for="statut" class="block text-gray-300">Statut</label>
                            <div class="relative">
                                <select id="statut" name="statut" 
                                      class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors appearance-none">
                                    <option value="brouillon" {{ old('statut', $product->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="publié" {{ old('statut', $product->statut) == 'publié' ? 'selected' : '' }}>Publié</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details Tab -->
                    <div id="details-tab" class="tab-content p-6 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label for="ingredients" class="block text-gray-300">Ingrédients</label>
                                <textarea id="ingredients" name="ingredients" rows="8"
                                      class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors">{{ old('ingredients', is_string($product->ingredients) ? $product->ingredients : json_encode($product->ingredients, JSON_PRETTY_PRINT)) }}</textarea>
                                <p class="text-gray-500 text-xs">Format JSON accepté pour les données structurées</p>
                            </div>
                            
                            <div class="space-y-2">
                                <label for="valeurs_nutritionnelles" class="block text-gray-300">Valeurs nutritionnelles</label>
                                <textarea id="valeurs_nutritionnelles" name="valeurs_nutritionnelles" rows="8"
                                      class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors">{{ old('valeurs_nutritionnelles', is_string($product->valeurs_nutritionnelles) ? $product->valeurs_nutritionnelles : json_encode($product->valeurs_nutritionnelles, JSON_PRETTY_PRINT)) }}</textarea>
                                <p class="text-gray-500 text-xs">Format JSON accepté pour les données structurées</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label for="seuil_alerte_stock" class="block text-gray-300">Seuil d'alerte de stock</label>
                                <input type="number" id="seuil_alerte_stock" name="seuil_alerte_stock" min="0"
                                      class="w-full bg-gray-900/50 text-white px-4 py-3 rounded-lg border border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500/20 focus:outline-none transition-colors"
                                      value="{{ old('seuil_alerte_stock', $product->seuil_alerte_stock ?? 5) }}">
                                <p class="text-gray-500 text-xs">Vous serez alerté lorsque le stock passe sous ce seuil</p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-gray-300">Suivi du stock</label>
                                <div class="flex items-center h-full pt-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="suivi_stock" name="suivi_stock" value="1" 
                                              {{ old('suivi_stock', $product->suivi_stock ?? true) ? 'checked' : '' }}
                                              class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        <span class="ml-3 text-gray-300">Activer le suivi du stock</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Media Tab -->
                    <div id="media-tab" class="tab-content p-6 hidden">
                        <div class="mb-6">
                            <label class="block text-gray-300 mb-3">Image principale</label>
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="w-full md:w-1/3">
                                    @if($product->image)
                                        <div class="bg-gray-900/50 border border-gray-700 rounded-lg overflow-hidden p-2 flex items-center justify-center h-40">
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->nom }}" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <div class="mt-2 text-center">
                                            <button type="button" class="text-red-400 hover:text-red-300 text-sm" id="remove-image">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Supprimer
                                            </button>
                                        </div>
                                    @else
                                        <div class="bg-gray-900/50 border border-gray-700 rounded-lg overflow-hidden p-4 flex flex-col items-center justify-center h-40 text-gray-500">
                                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Aucune image</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1">
                                    <label for="image" class="block w-full cursor-pointer group">
                                        <div class="bg-gray-900/50 border-2 border-gray-700 border-dashed rounded-lg p-6 flex flex-col items-center justify-center h-40 group-hover:border-purple-500 transition-colors">
                                            <svg class="w-10 h-10 text-gray-500 mb-2 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <span class="text-gray-400 group-hover:text-white transition-colors">Cliquez pour télécharger une image</span>
                                            <span class="text-gray-500 text-sm mt-2">PNG, JPG ou WebP. Max 5MB.</span>
                                        </div>
                                        <input type="file" id="image" name="image" class="hidden" accept="image/*">
                                    </label>
                                    
                                    <div id="upload-progress" class="mt-3 hidden">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-gray-400">Téléchargement</span>
                                            <span class="text-sm text-purple-400" id="progress-percentage">0%</span>
                                        </div>
                                        <div class="w-full bg-gray-700 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full" id="progress-bar" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-300 mb-3">Galerie d'images</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($product->productMedia()->where('type', 'image')->take(8)->get() as $media)
                                <div class="bg-gray-900/50 border border-gray-700 rounded-lg overflow-hidden aspect-square relative group">
                                    <img src="{{ $media->url }}" alt="Image produit" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <button type="button" class="text-red-400 hover:text-red-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                
                                <label for="gallery_images" class="bg-gray-900/50 border-2 border-gray-700 border-dashed rounded-lg flex flex-col items-center justify-center aspect-square cursor-pointer hover:border-purple-500 transition-colors">
                                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="text-gray-500 text-sm mt-2">Ajouter</span>
                                    <input type="file" id="gallery_images" name="gallery_images[]" class="hidden" accept="image/*" multiple>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Tab styling */
    .tab-button {
        color: #9ca3af;
        position: relative;
    }
    
    .tab-button:hover {
        color: #f3f4f6;
    }
    
    .tab-button.active {
        color: #fff;
        background-color: rgba(139, 92, 246, 0.1);
    }
    
    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #8b5cf6;
    }
    
    /* Form animations */
    input, textarea, select {
        transition: all 0.3s ease;
    }
    
    input:focus, textarea:focus, select:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.1), 0 2px 4px -1px rgba(139, 92, 246, 0.06);
    }
    
    /* Product preview card hover effects */
    .product-preview:hover {
        border-color: rgba(139, 92, 246, 0.5);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Tab content animation */
    .tab-content {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all tabs
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.add('hidden'));
                
                // Add active class to current tab
                button.classList.add('active');
                
                // Show corresponding content
                const tabId = button.getAttribute('data-tab');
                document.getElementById(`${tabId}-tab`).classList.remove('hidden');
            });
        });
        
        // Image preview functionality
        const imageInput = document.getElementById('image');
        const removeImageBtn = document.getElementById('remove-image');
        
        if (imageInput) {
            imageInput.addEventListener('change', function(event) {
                if (event.target.files.length > 0) {
                    const file = event.target.files[0];
                    const reader = new FileReader();
                    
                    // Show upload progress (simulated)
                    const progressBar = document.getElementById('progress-bar');
                    const progressPercentage = document.getElementById('progress-percentage');
                    const progressContainer = document.getElementById('upload-progress');
                    
                    progressContainer.classList.remove('hidden');
                    
                    let progress = 0;
                    const interval = setInterval(() => {
                        progress += 5;
                        progressBar.style.width = `${progress}%`;
                        progressPercentage.textContent = `${progress}%`;
                        
                        if (progress >= 100) {
                            clearInterval(interval);
                            setTimeout(() => {
                                progressContainer.classList.add('hidden');
                            }, 500);
                        }
                    }, 50);
                    
                    reader.onload = function(e) {
                        const previewContainer = imageInput.closest('.flex-col, .flex').querySelector('.w-full.md\\:w-1\\/3');
                        
                        previewContainer.innerHTML = `
                            <div class="bg-gray-900/50 border border-gray-700 rounded-lg overflow-hidden p-2 flex items-center justify-center h-40">
                                <img src="${e.target.result}" alt="Preview" class="max-h-full max-w-full object-contain">
                            </div>
                            <div class="mt-2 text-center">
                                <button type="button" class="text-red-400 hover:text-red-300 text-sm" id="remove-image">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        `;
                        
                        // Re-attach event listener to new button
                        document.getElementById('remove-image').addEventListener('click', handleRemoveImage);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Handle remove image functionality
        function handleRemoveImage() {
            const previewContainer = this.closest('.w-full.md\\:w-1\\/3');
            
            previewContainer.innerHTML = `
                <div class="bg-gray-900/50 border border-gray-700 rounded-lg overflow-hidden p-4 flex flex-col items-center justify-center h-40 text-gray-500">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Aucune image</span>
                </div>
            `;
            
            // Create a hidden input to signal image removal
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_image';
            hiddenInput.value = '1';
            document.getElementById('product-form').appendChild(hiddenInput);
            
            // Reset file input
            document.getElementById('image').value = '';
        }
        
        // Attach remove image event handler
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', handleRemoveImage);
        }
        
        // Gallery images handling
        const galleryInput = document.getElementById('gallery_images');
        if (galleryInput) {
            galleryInput.addEventListener('change', function(event) {
                if (event.target.files.length > 0) {
                    // Handle multiple file preview logic here
                    console.log(`${event.target.files.length} files selected`);
                    
                    // Show a notification
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-purple-600 text-white py-2 px-4 rounded-md shadow-lg z-50 animate-fade-in';
                    notification.textContent = `${event.target.files.length} images prêtes à être uploadées`;
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.classList.add('opacity-0');
                        setTimeout(() => notification.remove(), 300);
                    }, 3000);
                }
            });
        }
    });
</script>
@endpush
@endsection
