@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Ajouter un Produit</h1>
        <div class="page-actions">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="icon-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
        <button class="alert-close">&times;</button>
    </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
        @csrf
        
        <div class="form-layout">
            <div class="form-main">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Informations Produit</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nom">Nom du produit <span class="required">*</span></label>
                            <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description <span class="required">*</span></label>
                            <textarea id="description" name="description" class="form-control richtext @error('description') is-invalid @enderror" rows="10">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Images du Produit</h2>
                    </div>
                    <div class="card-body">
                        <div class="product-images-uploader">
                            <div class="product-images-preview" id="imagesPreview">
                                <div class="image-upload-placeholder">
                                    <i class="icon-image"></i>
                                    <span>Aucune image sélectionnée</span>
                                </div>
                            </div>
                            
                            <div class="image-upload-controls">
                                <label for="images" class="btn btn-outline">
                                    <i class="icon-upload"></i> Télécharger des images
                                </label>
                                <input type="file" id="images" name="images[]" class="image-upload-input" multiple accept="image/*">
                                <div class="form-text">Formats acceptés: JPG, PNG, GIF. Max 2MB par image.</div>
                            </div>
                        </div>
                        
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Ingrédients et Valeurs Nutritionnelles</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ingredients">Ingrédients</label>
                            <textarea id="ingredients" name="ingredients" class="form-control richtext @error('ingredients') is-invalid @enderror" rows="5">{{ old('ingredients') }}</textarea>
                            @error('ingredients')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="valeurs_nutritionnelles">Valeurs Nutritionnelles</label>
                            <textarea id="valeurs_nutritionnelles" name="valeurs_nutritionnelles" class="form-control richtext @error('valeurs_nutritionnelles') is-invalid @enderror" rows="5">{{ old('valeurs_nutritionnelles') }}</textarea>
                            @error('valeurs_nutritionnelles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Variations du Produit</h2>
                    </div>
                    <div class="card-body">
                        <div id="variationsContainer">
                            <!-- Variations will be added here -->
                            <div class="variations-empty-state">
                                <i class="icon-box"></i>
                                <p>Aucune variation ajoutée</p>
                                <p class="text-muted">Cliquez sur le bouton ci-dessous pour ajouter des variations</p>
                            </div>
                        </div>
                        
                        <div class="variation-actions">
                            <button type="button" class="btn btn-outline" id="addVariationBtn">
                                <i class="icon-plus"></i> Ajouter une variation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-sidebar">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Publication</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="statut">Statut <span class="required">*</span></label>
                            <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                <option value="publié" {{ old('statut') === 'publié' ? 'selected' : '' }}>Publié</option>
                                <option value="brouillon" {{ old('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="categorie_id">Catégorie <span class="required">*</span></label>
                            <select id="categorie_id" name="categorie_id" class="form-control @error('categorie_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('categorie_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="icon-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Prix</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="prix">Prix normal <span class="required">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" id="prix" name="prix" class="form-control @error('prix') is-invalid @enderror" value="{{ old('prix') }}" step="0.01" min="0" required>
                            </div>
                            @error('prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="prix_promo">Prix promotionnel</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" id="prix_promo" name="prix_promo" class="form-control @error('prix_promo') is-invalid @enderror" value="{{ old('prix_promo') }}" step="0.01" min="0">
                            </div>
                            <div class="form-text">Laissez vide si le produit n'est pas en promotion</div>
                            @error('prix_promo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Inventaire</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="stock">Quantité en stock <span class="required">*</span></label>
                            <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Variation Template -->
<template id="variationTemplate">
    <div class="variation-item" data-index="{index}">
        <div class="variation-header">
            <h3 class="variation-title">Variation #{index}</h3>
            <button type="button" class="btn-remove-variation" onclick="removeVariation({index})">
                <i class="icon-trash"></i>
            </button>
        </div>
        <div class="variation-grid">
            <div class="form-group">
                <label for="variations[{index}][size]">Taille</label>
                <input type="text" name="variations[{index}][size]" class="form-control" placeholder="ex: 250g, 500g, etc.">
            </div>
            <div class="form-group">
                <label for="variations[{index}][flavor]">Saveur</label>
                <input type="text" name="variations[{index}][flavor]" class="form-control" placeholder="ex: Vanille, Chocolat, etc.">
            </div>
            <div class="form-group">
                <label for="variations[{index}][quantity]">Quantité</label>
                <input type="text" name="variations[{index}][quantity]" class="form-control" placeholder="ex: 30 gélules, 60 comprimés, etc.">
            </div>
            <div class="form-group">
                <label for="variations[{index}][price]">Prix spécifique</label>
                <input type="number" name="variations[{index}][price]" class="form-control" step="0.01" min="0" placeholder="Laisser vide pour utiliser le prix par défaut">
            </div>
            <div class="form-group">
                <label for="variations[{index}][stock_quantity]">Stock spécifique</label>
                <input type="number" name="variations[{index}][stock_quantity]" class="form-control" min="0" placeholder="Laisser vide pour utiliser le stock par défaut">
            </div>
        </div>
    </div>
</template>

<style>
    /* Form layout */
    .form-layout {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .form-main {
        flex: 2;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .form-sidebar {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    /* Cards */
    .card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
    
    .card-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Form controls */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #334155;
    }
    
    .form-control {
        display: block;
        width: 100%;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #1e293b;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus {
        border-color: #3b82f6;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
    
    .form-control.richtext {
        height: auto;
    }
    
    .form-text {
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .is-invalid {
        border-color: #ef4444;
    }
    
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: #ef4444;
    }
    
    .is-invalid + .invalid-feedback,
    .invalid-feedback.d-block {
        display: block;
    }
    
    .required {
        color: #ef4444;
    }
    
    /* Input group */
    .input-group {
        display: flex;
    }
    
    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        color: #1e293b;
        text-align: center;
        white-space: nowrap;
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem 0 0 0.375rem;
        border-right: 0;
    }
    
    .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        position: relative;
        flex: 1 1 auto;
        width: 1%;
        margin-bottom: 0;
    }
    
    /* Images uploader */
    .product-images-uploader {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .product-images-preview {
        min-height: 150px;
        border: 2px dashed #cbd5e1;
        border-radius: 0.5rem;
        padding: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .image-upload-placeholder {
        width: 100%;
        height: 100%;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
    
    .image-upload-placeholder i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .preview-image-container {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 0.375rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .remove-image {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        background-color: rgba(239, 68, 68, 0.8);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .image-upload-controls {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .image-upload-input {
        display: none;
    }
    
    /* Variations */
    .variations-empty-state {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }
    
    .variations-empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .variations-empty-state p {
        margin: 0.25rem 0;
    }
    
    .text-muted {
        color: #64748b;
        font-size: 0.875rem;
    }
    
    .variation-item {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }
    
    .variation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .variation-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #334155;
        margin: 0;
    }
    
    .btn-remove-variation {
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        font-size: 1rem;
    }
    
    .variation-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .variation-actions {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }
    
    /* Buttons */
    .btn {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }
    
    .btn-primary {
        color: white;
        background-color: #3b82f6;
        border: 1px solid #3b82f6;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    
    .btn-secondary {
        color: #1e293b;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    
    .btn-secondary:hover {
        background-color: #e2e8f0;
        border-color: #cbd5e1;
    }
    
    .btn-outline {
        color: #3b82f6;
        background-color: transparent;
        border: 1px solid #3b82f6;
    }
    
    .btn-outline:hover {
        color: white;
        background-color: #3b82f6;
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .form-layout {
            flex-direction: column;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image upload preview
        const imagesInput = document.getElementById('images');
        const imagesPreview = document.getElementById('imagesPreview');
        const maxFiles = 5;
        let uploadedFiles = [];
        
        imagesInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            
            if (files.length > maxFiles) {
                alert(`Vous ne pouvez télécharger que ${maxFiles} images maximum.`);
                this.value = '';
                return;
            }
            
            uploadedFiles = files;
            updateImagePreview();
        });
        
        function updateImagePreview() {
            imagesPreview.innerHTML = '';
            
            if (uploadedFiles.length === 0) {
                imagesPreview.innerHTML = `
                    <div class="image-upload-placeholder">
                        <i class="icon-image"></i>
                        <span>Aucune image sélectionnée</span>
                    </div>
                `;
                return;
            }
            
            uploadedFiles.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const container = document.createElement('div');
                    container.className = 'preview-image-container';
                    
                    const img = document.createElement('img');
                    img.className = 'preview-image';
                    img.src = e.target.result;
                    
                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image';
                    removeBtn.innerHTML = '×';
                    removeBtn.addEventListener('click', function() {
                        uploadedFiles.splice(index, 1);
                        updateImagePreview();
                        
                        // Reset the file input to reflect the changes
                        if (uploadedFiles.length === 0) {
                            imagesInput.value = '';
                        } else {
                            // This is a workaround, we can't directly modify FileList
                            // In a real app, you might need to use FormData instead
                        }
                    });
                    
                    container.appendChild(img);
                    container.appendChild(removeBtn);
                    imagesPreview.appendChild(container);
                };
                
                reader.readAsDataURL(file);
            });
        }
        
        // Variations management
        const variationsContainer = document.getElementById('variationsContainer');
        const addVariationBtn = document.getElementById('addVariationBtn');
        const variationTemplate = document.getElementById('variationTemplate').innerHTML;
        let variationCount = 0;
        
        addVariationBtn.addEventListener('click', function() {
            addVariation();
        });
        
        function addVariation() {
            // Remove empty state if it's the first variation
            if (variationCount === 0) {
                variationsContainer.innerHTML = '';
            }
            
            // Add variation
            const newVariation = variationTemplate.replace(/{index}/g, variationCount);
            variationsContainer.insertAdjacentHTML('beforeend', newVariation);
            
            variationCount++;
        }
        
        // Global function to remove variations
        window.removeVariation = function(index) {
            const variationToRemove = document.querySelector(`.variation-item[data-index="${index}"]`);
            if (variationToRemove) {
                variationToRemove.remove();
                
                // If no variations left, show empty state
                if (variationsContainer.children.length === 0) {
                    variationsContainer.innerHTML = `
                        <div class="variations-empty-state">
                            <i class="icon-box"></i>
                            <p>Aucune variation ajoutée</p>
                            <p class="text-muted">Cliquez sur le bouton ci-dessous pour ajouter des variations</p>
                        </div>
                    `;
                    variationCount = 0;
                }
            }
        };
        
        // Initialize rich text editors if available
        if (typeof tinyMCE !== 'undefined') {
            tinymce.init({
                selector: '.richtext',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            });
        }
    });
</script>
@endsection
