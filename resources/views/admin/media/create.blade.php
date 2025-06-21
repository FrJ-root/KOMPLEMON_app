@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Ajouter des Médias</h1>
            <a href="{{ route('media.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" id="media-upload-form">
            @csrf
            
            <div class="mb-6">
                <label for="produit_id" class="block text-gray-300 mb-2">Produit <span class="text-purple-500">*</span></label>
                <select id="produit_id" name="produit_id" 
                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                       @error('produit_id') border-red-500 @enderror" required>
                    <option value="">Sélectionner un produit</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('produit_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->nom }}
                        </option>
                    @endforeach
                </select>
                @error('produit_id')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="type" class="block text-gray-300 mb-2">Type de média <span class="text-purple-500">*</span></label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="image" {{ old('type', 'image') === 'image' ? 'checked' : '' }}
                               class="form-radio text-purple-600 border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 bg-gray-900">
                        <span class="ml-2 text-gray-300">Images</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="video" {{ old('type') === 'video' ? 'checked' : '' }}
                               class="form-radio text-purple-600 border-gray-700 focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 bg-gray-900">
                        <span class="ml-2 text-gray-300">Vidéos</span>
                    </label>
                </div>
                @error('type')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label for="files" class="block text-gray-300">Fichiers <span class="text-purple-500">*</span></label>
                    <div class="flex items-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="optimize" value="1" checked
                                   class="form-checkbox text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                            <span class="ml-2 text-gray-300 text-sm">Optimisation automatique</span>
                        </label>
                        <div class="ml-2 group relative">
                            <svg class="w-5 h-5 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="absolute right-0 bottom-full mb-2 w-64 bg-gray-900 p-3 rounded-lg border border-gray-700 text-xs text-gray-300 shadow-lg hidden group-hover:block z-10">
                                L'optimisation réduit la taille des fichiers pour un chargement plus rapide sur le web. Désactivez uniquement si vous avez déjà optimisé vos images.
                            </div>
                        </div>
                    </div>
                </div>
                <div id="dropzone" class="border-2 border-dashed border-gray-700 rounded-lg p-8 text-center cursor-pointer hover:border-purple-500 transition-colors">
                    <div id="dropzone-content">
                        <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-gray-400 mb-2">Glissez et déposez vos fichiers ici, ou cliquez pour sélectionner</p>
                        <p class="text-gray-500 text-sm" id="file-types-info">PNG, JPG, GIF, WEBP acceptés. Max 20MB par fichier.</p>
                    </div>
                    <input type="file" id="files" name="files[]" class="hidden" multiple accept="image/*,video/*">
                </div>
                <div id="upload-progress" class="mt-3 hidden">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-400" id="processing-status">Préparation des fichiers...</span>
                        <span class="text-sm text-purple-400" id="progress-percentage">0%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" id="progress-bar" style="width: 0%"></div>
                    </div>
                </div>
                <div id="file-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4"></div>
                @error('files')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                @error('files.*')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="bg-blue-900/20 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-lg mb-6 flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium">Information</p>
                    <p class="text-sm">L'optimisation automatique réduit la taille des images tout en maintenant une bonne qualité. Des versions miniatures seront également générées pour les carrousels et les listes. Les formats supportés sont JPEG, PNG, GIF et WebP pour les images et MP4, MOV et AVI pour les vidéos.</p>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" id="submit-button" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>Télécharger</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('files');
        const filePreview = document.getElementById('file-preview');
        const fileTypesInfo = document.getElementById('file-types-info');
        const typeRadios = document.querySelectorAll('input[name="type"]');
        const uploadProgress = document.getElementById('upload-progress');
        const progressBar = document.getElementById('progress-bar');
        const progressPercentage = document.getElementById('progress-percentage');
        const processingStatus = document.getElementById('processing-status');
        const submitButton = document.getElementById('submit-button');
        const uploadForm = document.getElementById('media-upload-form');
        
        let selectedFiles = [];
        
        // Update allowed file types based on selected media type
        function updateAllowedFileTypes() {
            const selectedType = document.querySelector('input[name="type"]:checked').value;
            
            if (selectedType === 'image') {
                fileInput.accept = "image/*";
                fileTypesInfo.textContent = "PNG, JPG, GIF, WEBP acceptés. Max 20MB par fichier.";
            } else if (selectedType === 'video') {
                fileInput.accept = "video/*";
                fileTypesInfo.textContent = "MP4, MOV, AVI acceptés. Max 20MB par fichier.";
            }
            
            // Clear file previews when changing type
            filePreview.innerHTML = '';
            selectedFiles = [];
        }
        
        // Initialize allowed file types
        updateAllowedFileTypes();
        
        // Listen for changes on the type radio buttons
        typeRadios.forEach(radio => {
            radio.addEventListener('change', updateAllowedFileTypes);
        });
        
        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            handleFileSelection(e.target.files);
        });
        
        // Handle drag and drop
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('border-purple-500', 'bg-purple-500/5');
        });
        
        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropzone.classList.remove('border-purple-500', 'bg-purple-500/5');
        });
        
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('border-purple-500', 'bg-purple-500/5');
            
            if (e.dataTransfer.files.length) {
                handleFileSelection(e.dataTransfer.files);
            }
        });
        
        // Trigger file input when clicking on dropzone
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Handle form submission with progress
        uploadForm.addEventListener('submit', function(e) {
            if (selectedFiles.length === 0) {
                alert('Veuillez sélectionner au moins un fichier.');
                e.preventDefault();
                return;
            }
            
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Traitement en cours...</span>
            `;
            
            // Show progress bar
            uploadProgress.classList.remove('hidden');
            
            // Simulate upload progress (in real app, use AJAX for actual progress)
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 5;
                if (progress > 100) progress = 100;
                
                progressBar.style.width = `${progress}%`;
                progressPercentage.textContent = `${Math.round(progress)}%`;
                
                if (progress >= 100) {
                    clearInterval(interval);
                    processingStatus.textContent = "Optimisation des images...";
                }
            }, 200);
        });
        
        // Process selected files
        function handleFileSelection(files) {
            const selectedType = document.querySelector('input[name="type"]:checked').value;
            
            // Filter files by type
            const filteredFiles = Array.from(files).filter(file => {
                if (selectedType === 'image') {
                    return file.type.startsWith('image/');
                } else if (selectedType === 'video') {
                    return file.type.startsWith('video/');
                }
                return false;
            });
            
            if (filteredFiles.length === 0) {
                alert(`Veuillez sélectionner des fichiers de type ${selectedType}.`);
                return;
            }
            
            // Add to selected files array
            selectedFiles = [...selectedFiles, ...filteredFiles];
            
            // Update UI
            updateFilePreview(filteredFiles);
            
            // Transfer to the file input for form submission
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
        }
        
        // Update file preview
        function updateFilePreview(newFiles) {
            for (let i = 0; i < newFiles.length; i++) {
                const file = newFiles[i];
                const fileType = file.type.split('/')[0];
                const previewItem = document.createElement('div');
                previewItem.className = 'relative bg-gray-900 rounded-lg overflow-hidden border border-gray-700 group';
                
                if (fileType === 'image') {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewItem.innerHTML = `
                            <div class="aspect-square">
                                <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-2">
                                <div class="text-xs text-gray-400 truncate">${file.name}</div>
                                <div class="text-xs text-gray-500">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity remove-file" data-index="${selectedFiles.indexOf(file)}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;
                        
                        // Add remove button event listener
                        previewItem.querySelector('.remove-file').addEventListener('click', function() {
                            removeFile(parseInt(this.getAttribute('data-index')));
                        });
                    };
                    
                    reader.readAsDataURL(file);
                } else if (fileType === 'video') {
                    previewItem.innerHTML = `
                        <div class="aspect-square bg-black flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="p-2">
                            <div class="text-xs text-gray-400 truncate">${file.name}</div>
                            <div class="text-xs text-gray-500">${formatFileSize(file.size)}</div>
                        </div>
                        <button type="button" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity remove-file" data-index="${selectedFiles.indexOf(file)}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    `;
                    
                    // Add remove button event listener
                    previewItem.querySelector('.remove-file').addEventListener('click', function() {
                        removeFile(parseInt(this.getAttribute('data-index')));
                    });
                }
                
                filePreview.appendChild(previewItem);
            }
        }
        
        // Remove file from selection
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            
            // Update file input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            
            // Update preview
            filePreview.innerHTML = '';
            updateFilePreview(selectedFiles);
        }
        
        // Format file size to human-readable
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    });
</script>
@endpush
@endsection
