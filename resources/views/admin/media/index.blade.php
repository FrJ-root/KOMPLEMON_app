@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Médiathèque</h1>
            <a href="{{ route('media.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Ajouter des médias</span>
            </a>
        </div>
    </div>

    <!-- Filter and Search Bar -->
    <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-purple-500/10">
        <form method="GET" action="{{ route('media.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" placeholder="Rechercher un média..." 
                       class="w-full bg-gray-900 text-white pl-10 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                       value="{{ request('search') }}">
            </div>
            <div class="flex flex-wrap gap-2">
                <select name="product_id" class="bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    <option value="">Tous les produits</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->nom }}
                        </option>
                    @endforeach
                </select>
                <select name="type" class="bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    <option value="">Tous les types</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéos</option>
                </select>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filtrer</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-purple-500/10 hidden" id="bulk-actions-bar">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-white mr-4">
                    <span id="selected-count">0</span> éléments sélectionnés
                </span>
                <button id="select-all-btn" class="text-purple-400 hover:text-purple-300 text-sm mr-4">
                    Tout sélectionner
                </button>
                <button id="deselect-all-btn" class="text-purple-400 hover:text-purple-300 text-sm">
                    Tout désélectionner
                </button>
            </div>
            <form id="bulk-delete-form" action="{{ route('media.bulk-destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="media_ids" id="selected-media-ids">
                <button type="button" id="bulk-delete-btn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Supprimer la sélection</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Media Gallery -->
    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-900/30 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($media->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($media as $item)
            <div class="media-item group bg-gray-900 rounded-lg overflow-hidden border border-gray-700 relative hover:border-purple-500/50 hover:shadow-lg hover:shadow-purple-900/20 transition-all" data-id="{{ $item->id }}">
                <div class="absolute top-2 left-2 z-10">
                    <input type="checkbox" class="media-checkbox form-checkbox h-5 w-5 text-purple-600 rounded border-gray-700 focus:ring-offset-gray-800 transition-colors">
                </div>
                
                <div class="media-preview aspect-square relative">
                    @if($item->type === 'image')
                        <img src="{{ asset('storage/' . str_replace('products/images/', 'products/images/medium/', $item->url)) }}" alt="Media {{ $item->id }}" class="w-full h-full object-cover">
                    @elseif($item->type === 'video')
                        <div class="bg-black w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-opacity">
                        <a href="{{ asset('storage/' . $item->url) }}" target="_blank" class="text-white bg-blue-600 hover:bg-blue-700 p-2 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        
                        @if($item->type === 'image')
                        <button type="button" class="text-white bg-green-600 hover:bg-green-700 p-2 rounded-full transition-colors set-main-image" 
                                data-media-id="{{ $item->id }}" 
                                data-product-id="{{ $item->produit_id }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </button>
                        @endif
                        
                        <form action="{{ route('media.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce média?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 p-2 rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">ID: {{ $item->id }}</span>
                        <span class="text-xs px-2 py-1 rounded {{ $item->type === 'image' ? 'bg-green-500/20 text-green-400' : 'bg-blue-500/20 text-blue-400' }}">
                            {{ ucfirst($item->type) }}
                        </span>
                    </div>
                    <div class="mt-1 text-sm text-white truncate">
                        {{ $item->product->nom ?? 'Sans produit' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $media->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-xl font-medium text-white mb-2">Aucun média trouvé</h3>
            <p class="text-gray-400 mb-6">Commencez par ajouter des images ou des vidéos à vos produits</p>
            <a href="{{ route('media.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md inline-flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Ajouter des médias</span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Set as Main Image Modal Form -->
<form id="set-main-image-form" action="" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="product_id" id="main-image-product-id">
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mediaCheckboxes = document.querySelectorAll('.media-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCount = document.getElementById('selected-count');
        const selectedMediaIds = document.getElementById('selected-media-ids');
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        const setMainImageButtons = document.querySelectorAll('.set-main-image');
        const setMainImageForm = document.getElementById('set-main-image-form');
        
        // Update bulk actions bar and selected count
        function updateBulkActionsBar() {
            const checkedBoxes = document.querySelectorAll('.media-checkbox:checked');
            const count = checkedBoxes.length;
            
            if (count > 0) {
                bulkActionsBar.classList.remove('hidden');
                selectedCount.textContent = count;
                
                // Update hidden input with selected media IDs
                const ids = Array.from(checkedBoxes).map(checkbox => {
                    return checkbox.closest('.media-item').getAttribute('data-id');
                });
                
                selectedMediaIds.value = ids.join(',');
            } else {
                bulkActionsBar.classList.add('hidden');
            }
        }
        
        // Add event listener to each checkbox
        mediaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionsBar);
        });
        
        // Select all functionality
        selectAllBtn.addEventListener('click', function() {
            mediaCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateBulkActionsBar();
        });
        
        // Deselect all functionality
        deselectAllBtn.addEventListener('click', function() {
            mediaCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulkActionsBar();
        });
        
        // Bulk delete confirmation
        bulkDeleteBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer les médias sélectionnés?')) {
                bulkDeleteForm.submit();
            }
        });
        
        // Set main image functionality
        setMainImageButtons.forEach(button => {
            button.addEventListener('click', function() {
                const mediaId = this.getAttribute('data-media-id');
                const productId = this.getAttribute('data-product-id');
                
                // Update form action and product ID
                setMainImageForm.action = `/admin/media/${mediaId}/set-as-main`;
                document.getElementById('main-image-product-id').value = productId;
                
                // Submit the form
                if (confirm('Définir cette image comme image principale du produit?')) {
                    setMainImageForm.submit();
                }
            });
        });
        
        // Preview videos on hover
        document.querySelectorAll('.media-item').forEach(item => {
            const preview = item.querySelector('.media-preview');
            
            if (preview && preview.querySelector('video')) {
                const video = preview.querySelector('video');
                
                item.addEventListener('mouseenter', function() {
                    video.play();
                });
                
                item.addEventListener('mouseleave', function() {
                    video.pause();
                    video.currentTime = 0;
                });
            }
        });
    });
</script>
@endpush
@endsection
