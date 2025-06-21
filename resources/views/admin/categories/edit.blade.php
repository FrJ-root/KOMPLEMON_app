@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Modifier la Catégorie</h1>
            <a href="{{ route('categories.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4 xl:col-span-3 space-y-6">
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-lg">
                <div class="aspect-video bg-gray-900 relative">
                    @if($category->image_url)
                    <img src="{{ $category->image_url }}" alt="{{ $category->nom }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h2 class="text-white font-semibold text-xl mb-2">{{ $category->nom }}</h2>
                    <p class="text-gray-400 mb-3">{{ $category->description ?: 'Aucune description' }}</p>
                    
                    <div class="flex items-center justify-between text-sm pt-3 border-t border-gray-700">
                        <span class="text-purple-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            {{ $category->products->count() }} produits
                        </span>
                        <span class="text-gray-500">
                            Créée le {{ $category->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-4">
                <h3 class="text-white font-medium mb-3">Produits dans cette catégorie</h3>
                
                @if($category->products->count() > 0)
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                    @foreach($category->products->take(5) as $product)
                    <div class="flex items-center gap-3 bg-gray-900/50 p-2 rounded-lg">
                        <div class="w-10 h-10 bg-gray-900 rounded-lg overflow-hidden flex-shrink-0">
                            @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->nom }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm truncate">{{ $product->nom }}</p>
                            <p class="text-gray-500 text-xs">{{ $product->prix }} €</p>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($category->products->count() > 5)
                    <div class="text-center pt-2">
                        <span class="text-gray-500 text-sm">+ {{ $category->products->count() - 5 }} autres produits</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="bg-gray-900/50 p-4 rounded-lg text-center">
                    <span class="text-gray-500">Aucun produit dans cette catégorie</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="lg:col-span-8 xl:col-span-9">
            <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
                <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="nom" class="block text-gray-300 mb-2">Nom de la catégorie <span class="text-purple-500">*</span></label>
                        <input type="text" id="nom" name="nom" 
                               class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                               @error('nom') border-red-500 @enderror" 
                               value="{{ old('nom', $category->nom) }}" required>
                        @error('nom')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="description" class="block text-gray-300 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                                  @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="image" class="block text-gray-300 mb-2">Image de la catégorie</label>
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-1/3">
                                @if($category->image_url)
                                    <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-2 flex items-center justify-center h-40">
                                        <img src="{{ asset($category->image_url) }}" alt="{{ $category->nom }}" class="max-h-full max-w-full object-contain">
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
                                    <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex flex-col items-center justify-center h-40 text-gray-500">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Aucune image</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <label for="image" class="block w-full cursor-pointer">
                                    <div class="bg-gray-900 border border-gray-700 border-dashed rounded-lg p-6 flex flex-col items-center justify-center h-40 hover:border-purple-500 transition-colors">
                                        <svg class="w-10 h-10 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span class="text-gray-400">Cliquez pour télécharger une image</span>
                                        <span class="text-gray-500 text-sm mt-2">PNG, JPG ou WebP. Max 2MB.</span>
                                    </div>
                                    <input type="file" id="image" name="image" class="hidden" accept="image/*">
                                </label>
                                @error('image')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        @if($category->products->count() === 0)
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Supprimer</span>
                            </button>
                        </form>
                        @endif
                        
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
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const removeImageBtn = document.getElementById('remove-image');
        
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const previewContainer = imageInput.closest('.flex-col, .flex').querySelector('.w-full.md\\:w-1\\/3');
                        
                        previewContainer.innerHTML = `
                            <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-2 flex items-center justify-center h-40">
                                <img src="${event.target.result}" alt="Preview" class="max-h-full max-w-full object-contain">
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
                        
                        document.getElementById('remove-image').addEventListener('click', handleRemoveImage);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
        
        function handleRemoveImage() {
            const previewContainer = this.closest('.w-full.md\\:w-1\\/3');
            
            previewContainer.innerHTML = `
                <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex flex-col items-center justify-center h-40 text-gray-500">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Aucune image</span>
                </div>
            `;
            
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_image';
            hiddenInput.value = '1';
            document.querySelector('form').appendChild(hiddenInput);
            
            document.getElementById('image').value = '';
        }
        
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', handleRemoveImage);
        }
    });
</script>
@endpush

<style>
    .hex-pattern {
        background: linear-gradient(120deg, #000 0%, transparent 50%),
            linear-gradient(240deg, #000 0%, transparent 50%),
            linear-gradient(360deg, #000 0%, transparent 50%);
        background-size: 10px 10px;
    }
</style>
@endsection
