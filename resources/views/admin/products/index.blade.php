@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Gestion des Produits</h1>
            <a href="{{ route('products.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nouveau Produit</span>
            </a>
        </div>
    </div>

    <!-- Filter and Search Bar -->
    <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-purple-500/10">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Rechercher un produit..." 
                       class="w-full bg-gray-900 text-white pl-10 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
            </div>
            <div class="flex flex-wrap gap-2">
                <select id="categoryFilter" class="bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                    @endforeach
                </select>
                <select id="statusFilter" class="bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    <option value="">Tous statuts</option>
                    <option value="publié">Publié</option>
                    <option value="brouillon">Brouillon</option>
                </select>
                <select id="stockFilter" class="bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    <option value="">Tous stocks</option>
                    <option value="stock">En stock</option>
                    <option value="lowstock">Stock faible</option>
                    <option value="outofstock">Épuisé</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Produit
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Catégorie
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Prix
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Stock
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($products as $product)
                    <tr class="product-row hover:bg-gray-700/50" 
                        data-name="{{ $product->nom }}" 
                        data-category="{{ $product->categorie_id }}" 
                        data-status="{{ $product->statut }}"
                        data-stock="{{ $product->stock <= 0 ? 'outofstock' : ($product->stock <= 5 ? 'lowstock' : 'stock') }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($product->image)
                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ asset($product->image) }}" alt="{{ $product->nom }}">
                                    @else
                                        <div class="h-10 w-10 rounded-md bg-gray-700 flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $product->nom }}</div>
                                    <div class="text-sm text-gray-400">ID: {{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-300">{{ $product->category->nom ?? 'Non catégorisé' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ number_format($product->prix, 2) }} €</div>
                            @if($product->prix_promo)
                                <div class="text-sm text-purple-400">{{ number_format($product->prix_promo, 2) }} €</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $product->stock <= 0 ? 'bg-red-900/30 text-red-400' : 
                                   ($product->stock <= 5 ? 'bg-yellow-900/30 text-yellow-400' : 'bg-green-900/30 text-green-400') }}">
                                {{ $product->stock }} unités
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $product->statut === 'publié' ? 'bg-green-900/30 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                                {{ ucfirst($product->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="text-blue-400 hover:text-blue-300" title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');
        const stockFilter = document.getElementById('stockFilter');
        const productRows = document.querySelectorAll('.product-row');
        
        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            const categoryValue = categoryFilter.value;
            const statusValue = statusFilter.value;
            const stockValue = stockFilter.value;
            
            productRows.forEach(row => {
                const name = row.getAttribute('data-name').toLowerCase();
                const category = row.getAttribute('data-category');
                const status = row.getAttribute('data-status');
                const stock = row.getAttribute('data-stock');
                
                // Check if row matches all filters
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = categoryValue === '' || category === categoryValue;
                const matchesStatus = statusValue === '' || status === statusValue;
                const matchesStock = stockValue === '' || stock === stockValue;
                
                // Show/hide the row based on filter results
                if (matchesSearch && matchesCategory && matchesStatus && matchesStock) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Add event listeners to filters
        searchInput.addEventListener('input', filterProducts);
        categoryFilter.addEventListener('change', filterProducts);
        statusFilter.addEventListener('change', filterProducts);
        stockFilter.addEventListener('change', filterProducts);
    });
</script>
@endpush
@endsection
