@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Gestion des Catégories</h1>
            <a href="{{ route('categories.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nouvelle Catégorie</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-purple-500/10">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Rechercher une catégorie..." 
                       class="w-full bg-gray-900 text-white pl-10 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
            </div>
            <div class="flex items-center gap-2">
                <select id="sortFilter" class="bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    <option value="recent">Plus récentes</option>
                    <option value="oldest">Plus anciennes</option>
                    <option value="name_asc">Nom (A-Z)</option>
                    <option value="name_desc">Nom (Z-A)</option>
                    <option value="products_desc">Plus de produits</option>
                    <option value="products_asc">Moins de produits</option>
                </select>
                <button id="resetFilters" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Réinitialiser</span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @foreach($categories as $category)
        <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-lg hover:border-purple-500/30 transition-all category-card" 
             data-name="{{ $category->nom }}"
             data-products="{{ $category->products->count() }}"
             data-date="{{ $category->created_at->timestamp }}">
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
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-gray-900 to-transparent p-4">
                    <h2 class="text-white font-semibold text-xl truncate">{{ $category->nom }}</h2>
                </div>
            </div>
            <div class="p-4">
                <div class="mb-3">
                    <p class="text-gray-400 line-clamp-2 h-10">{{ $category->description ?: 'Aucune description' }}</p>
                </div>
                <div class="flex items-center justify-between text-sm">
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
                <div class="mt-4 pt-4 border-t border-gray-700 flex justify-between">
                    <a href="{{ route('categories.edit', $category) }}" class="text-blue-400 hover:text-blue-300 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Modifier</span>
                    </a>
                    
                    @if($category->products->count() === 0)
                    <button type="button" onclick="confirmDelete({{ $category->id }}, '{{ $category->nom }}')" class="text-red-400 hover:text-red-300 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Supprimer</span>
                    </button>
                    <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    @else
                    <span class="text-gray-500 flex items-center gap-1" title="Cette catégorie contient des produits et ne peut pas être supprimée">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Verrouillée</span>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        
        <a href="{{ route('categories.create') }}" class="bg-gray-800 rounded-lg border-2 border-dashed border-gray-700 hover:border-purple-500 flex flex-col items-center justify-center p-6 transition-all h-full">
            <svg class="w-16 h-16 text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-gray-400 text-lg">Ajouter une catégorie</span>
        </a>
    </div>
    
    @if($categories->isEmpty())
    <div class="bg-gray-800 rounded-lg p-8 border border-gray-700 text-center">
        <svg class="w-16 h-16 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="text-white text-lg font-medium mb-2">Aucune catégorie trouvée</h3>
        <p class="text-gray-400 mb-6">Commencez par créer une catégorie pour organiser vos produits</p>
        <a href="{{ route('categories.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md inline-flex items-center gap-2 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Créer une catégorie</span>
        </a>
    </div>
    @endif
    
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 border border-red-500/20">
        <div class="text-center mb-4">
            <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <h3 class="text-lg font-medium text-white" id="deleteModalTitle">Supprimer la catégorie</h3>
            <p class="text-gray-400 mt-2" id="deleteModalText">Êtes-vous sûr de vouloir supprimer cette catégorie? Cette action est irréversible.</p>
        </div>
        <div class="flex gap-3 justify-center">
            <button id="cancelDelete" class="bg-gray-700 text-gray-300 hover:bg-gray-600 px-4 py-2 rounded-md transition-colors">
                Annuler
            </button>
            <button id="confirmDelete" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md transition-colors flex items-center gap-2">
                <span>Supprimer</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const sortFilter = document.getElementById('sortFilter');
        const resetFilters = document.getElementById('resetFilters');
        const categoryCards = document.querySelectorAll('.category-card');
        
        function filterCategories() {
            const searchTerm = searchInput.value.toLowerCase();
            const sortOption = sortFilter.value;
            
            categoryCards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                const isVisible = name.includes(searchTerm);
                card.style.display = isVisible ? 'block' : 'none';
            });
            
            const visibleCards = Array.from(categoryCards).filter(card => card.style.display !== 'none');
            sortCategories(visibleCards, sortOption);
        }
        
        function sortCategories(cards, sortOption) {
            const container = cards[0].parentNode;
            
            cards.sort((a, b) => {
                switch (sortOption) {
                    case 'recent':
                        return parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date'));
                    case 'oldest':
                        return parseInt(a.getAttribute('data-date')) - parseInt(b.getAttribute('data-date'));
                    case 'name_asc':
                        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                    case 'name_desc':
                        return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                    case 'products_desc':
                        return parseInt(b.getAttribute('data-products')) - parseInt(a.getAttribute('data-products'));
                    case 'products_asc':
                        return parseInt(a.getAttribute('data-products')) - parseInt(b.getAttribute('data-products'));
                    default:
                        return 0;
                }
            });
            
            cards.forEach(card => {
                container.appendChild(card);
            });
        }
        
        function resetAllFilters() {
            searchInput.value = '';
            sortFilter.value = 'recent';
            
            categoryCards.forEach(card => {
                card.style.display = 'block';
            });
            
            sortCategories(Array.from(categoryCards), 'recent');
        }
        
        searchInput.addEventListener('input', filterCategories);
        sortFilter.addEventListener('change', filterCategories);
        resetFilters.addEventListener('click', resetAllFilters);
    });
    
    let currentCategoryId = null;
    
    function confirmDelete(categoryId, categoryName) {
        currentCategoryId = categoryId;
        
        document.getElementById('deleteModalTitle').textContent = 'Supprimer la catégorie: ' + categoryName;
        
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        currentCategoryId = null;
    });
    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (currentCategoryId) {
            this.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Suppression...</span>
            `;
            this.disabled = true;
            
            document.getElementById('delete-form-' + currentCategoryId).submit();
        }
    });
    
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            this.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentCategoryId = null;
        }
    });
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentCategoryId = null;
        }
    });
</script>
@endpush

<style>
    .category-card {
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .hex-pattern {
        background: linear-gradient(120deg, #000 0%, transparent 50%),
            linear-gradient(240deg, #000 0%, transparent 50%),
            linear-gradient(360deg, #000 0%, transparent 50%);
        background-size: 10px 10px;
    }
</style>
@endsection
