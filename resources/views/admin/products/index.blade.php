@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Gestion des Produits</h1>
        <div class="page-actions">
            <a href="{{ route('products.create') }}" class="btn-create animated-btn">
                <i class="icon-plus"></i> Nouveau Produit
            </a>
        </div>
    </div>

    <div class="products-filter-bar">
        <div class="search-section">
            <div class="search-box">
                <i class="icon-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un produit...">
                <button type="button" class="clear-search" id="clearSearch">
                    <i class="icon-times"></i>
                </button>
            </div>
        </div>
        <div class="filters-section">
            <select id="categoryFilter" class="select-filter">
                <option value="all">Toutes les catégories</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            
            <select id="statusFilter" class="select-filter">
                <option value="all">Tous les statuts</option>
                <option value="in_stock">En stock</option>
                <option value="low_stock">Stock faible</option>
                <option value="out_of_stock">Rupture de stock</option>
            </select>
            
            <button type="button" class="btn-filter" id="advancedFilters">
                <i class="icon-filter"></i> Filtres avancés
            </button>
        </div>
    </div>

    <div class="advanced-filters" style="display: none;">
        <div class="filter-card">
            <div class="filter-card-header">
                <h3>Filtres avancés</h3>
                <button type="button" class="btn-close" id="closeAdvancedFilters">
                    <i class="icon-times"></i>
                </button>
            </div>
            <div class="filter-card-body">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Prix</label>
                        <div class="range-inputs">
                            <input type="number" id="minPrice" placeholder="Min" class="form-control">
                            <span class="range-separator">-</span>
                            <input type="number" id="maxPrice" placeholder="Max" class="form-control">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label>Date d'ajout</label>
                        <select id="dateFilter" class="form-control">
                            <option value="all">Toutes les dates</option>
                            <option value="today">Aujourd'hui</option>
                            <option value="this_week">Cette semaine</option>
                            <option value="this_month">Ce mois</option>
                            <option value="this_year">Cette année</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Tri par</label>
                        <select id="sortBy" class="form-control">
                            <option value="newest">Plus récents</option>
                            <option value="oldest">Plus anciens</option>
                            <option value="price_asc">Prix croissant</option>
                            <option value="price_desc">Prix décroissant</option>
                            <option value="name_asc">Nom (A-Z)</option>
                            <option value="name_desc">Nom (Z-A)</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="button" class="btn-secondary" id="resetFilters">Réinitialiser</button>
                    <button type="button" class="btn-primary" id="applyFilters">Appliquer les filtres</button>
                </div>
            </div>
        </div>
    </div>

    <div class="products-grid">
        @forelse($products as $product)
        <div class="product-card" data-category="{{ $product->category_id }}" data-status="{{ $product->getStockStatus() }}">
            <div class="product-card-header">
                <div class="product-image">
                    @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                    @else
                    <div class="no-image">
                        <i class="icon-image"></i>
                    </div>
                    @endif
                    
                    <div class="product-status {{ $product->getStockStatus() }}">
                        @if($product->getStockStatus() === 'in_stock')
                            En stock
                        @elseif($product->getStockStatus() === 'low_stock')
                            Stock faible
                        @else
                            Rupture
                        @endif
                    </div>
                </div>
                
                @if($product->is_featured)
                <div class="product-featured">
                    <i class="icon-star"></i> Produit vedette
                </div>
                @endif
                
                @if($product->discount_percentage > 0)
                <div class="product-discount">
                    -{{ $product->discount_percentage }}%
                </div>
                @endif
            </div>
            
            <div class="product-card-body">
                <h3 class="product-name">{{ $product->name }}</h3>
                
                <div class="product-category">
                    <span class="category-badge">{{ $product->category->name }}</span>
                </div>
                
                <div class="product-prices">
                    @if($product->discount_percentage > 0)
                    <span class="original-price">{{ number_format($product->original_price, 2) }}€</span>
                    @endif
                    <span class="current-price">{{ number_format($product->price, 2) }}€</span>
                </div>
                
                <div class="product-stock">
                    <div class="stock-label">Stock:</div>
                    <div class="stock-bar">
                        <div class="stock-progress" style="width: {{ ($product->stock_threshold > 0) ? min(100, ($product->stock_quantity / $product->stock_threshold) * 100) : 0 }}%"></div>
                    </div>
                    <div class="stock-quantity">{{ $product->stock_quantity }}</div>
                </div>
            </div>
            
            <div class="product-card-footer">
                <div class="product-actions">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn-action edit" title="Modifier">
                        <i class="icon-edit"></i>
                    </a>
                    
                    <button type="button" class="btn-action view" title="Aperçu" onclick="previewProduct({{ $product->id }})">
                        <i class="icon-eye"></i>
                    </button>
                    
                    <button type="button" class="btn-action duplicate" title="Dupliquer" onclick="duplicateProduct({{ $product->id }})">
                        <i class="icon-copy"></i>
                    </button>
                    
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-action delete" title="Supprimer" onclick="confirmDelete(this)">
                            <i class="icon-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">🛍️</div>
            <h3>Aucun produit trouvé</h3>
            <p>Commencez par ajouter votre premier produit</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Ajouter un produit</a>
        </div>
        @endforelse
    </div>
    
    <div class="pagination-wrapper">
        {{ $products->links() }}
    </div>
</div>

<!-- Product Preview Modal -->
<div id="productPreviewModal" class="modal">
    <div class="modal-content product-preview-modal">
        <div class="modal-header">
            <h2 class="modal-title">Aperçu du produit</h2>
            <span class="close" onclick="closeProductPreview()">&times;</span>
        </div>
        <div class="modal-body" id="productPreviewContent">
            <!-- Content will be loaded here -->
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Chargement...</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Product filter bar */
    .products-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .search-section {
        flex: 1;
        min-width: 250px;
    }
    
    .filters-section {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .select-filter {
        min-width: 180px;
    }
    
    .btn-filter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        color: #64748b;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-filter:hover {
        background-color: #f1f5f9;
        color: #334155;
    }
    
    /* Advanced filters */
    .advanced-filters {
        margin-bottom: 1.5rem;
    }
    
    .filter-card {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .filter-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .filter-card-header h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .btn-close {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1.25rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 9999px;
        transition: all 0.2s;
    }
    
    .btn-close:hover {
        background-color: #f1f5f9;
        color: #64748b;
    }
    
    .filter-card-body {
        padding: 1.5rem;
    }
    
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-group {
        flex: 1;
        min-width: 200px;
    }
    
    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #334155;
        font-size: 0.875rem;
    }
    
    .range-inputs {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .range-separator {
        color: #94a3b8;
    }
    
    .filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }
    
    /* Products grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    /* Product card */
    .product-card {
        background-color: white;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s;
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
    
    .product-card-header {
        position: relative;
    }
    
    .product-image {
        height: 180px;
        background-color: #f8fafc;
        position: relative;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 2rem;
    }
    
    .product-status {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 1;
    }
    
    .product-status.in_stock {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .product-status.low_stock {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .product-status.out_of_stock {
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    .product-featured {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: #e0f2fe;
        color: #0369a1;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .product-discount {
        position: absolute;
        bottom: 0.75rem;
        right: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        background-color: #ef4444;
        color: white;
        z-index: 1;
    }
    
    .product-card-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .product-name {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .product-category {
        margin-top: auto;
    }
    
    .category-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        background-color: #f1f5f9;
        color: #64748b;
    }
    
    .product-prices {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .original-price {
        font-size: 0.875rem;
        color: #94a3b8;
        text-decoration: line-through;
    }
    
    .current-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .product-stock {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.5rem;
    }
    
    .stock-label {
        flex-shrink: 0;
    }
    
    .stock-bar {
        flex: 1;
        height: 6px;
        background-color: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
    }
    
    .stock-progress {
        height: 100%;
        background-color: #10b981;
        border-radius: 9999px;
    }
    
    .stock-quantity {
        flex-shrink: 0;
    }
    
    .product-card-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
    
    .product-actions {
        display: flex;
        justify-content: space-between;
    }
    
    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 9999px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-action.edit {
        background-color: #bae6fd;
        color: #0369a1;
    }
    
    .btn-action.edit:hover {
        background-color: #7dd3fc;
    }
    
    .btn-action.view {
        background-color: #e0e7ff;
        color: #4f46e5;
    }
    
    .btn-action.view:hover {
        background-color: #c7d2fe;
    }
    
    .btn-action.duplicate {
        background-color: #d1fae5;
        color: #059669;
    }
    
    .btn-action.duplicate:hover {
        background-color: #a7f3d0;
    }
    
    .btn-action.delete {
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    .btn-action.delete:hover {
        background-color: #fecaca;
    }
    
    /* Product preview modal */
    .product-preview-modal {
        max-width: 800px;
        width: 90%;
    }
    
    .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e2e8f0;
        border-top: 3px solid #10b981;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Empty state */
    .empty-state {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
        text-align: center;
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #64748b;
        margin-bottom: 1.5rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
    }
    
    /* Icons */
    .icon-search:before { content: "🔍"; }
    .icon-times:before { content: "×"; }
    .icon-filter:before { content: "🔍"; }
    .icon-edit:before { content: "✏️"; }
    .icon-eye:before { content: "👁️"; }
    .icon-copy:before { content: "📋"; }
    .icon-trash:before { content: "🗑️"; }
    .icon-star:before { content: "⭐"; }
    .icon-image:before { content: "🖼️"; }
    .icon-plus:before { content: "+"; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Advanced filters toggle
        const advancedFiltersBtn = document.getElementById('advancedFilters');
        const closeAdvancedFiltersBtn = document.getElementById('closeAdvancedFilters');
        const advancedFiltersPanel = document.querySelector('.advanced-filters');
        
        advancedFiltersBtn.addEventListener('click', function() {
            advancedFiltersPanel.style.display = 'block';
        });
        
        closeAdvancedFiltersBtn.addEventListener('click', function() {
            advancedFiltersPanel.style.display = 'none';
        });
        
        // Filter functionality
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');
        const clearSearchBtn = document.getElementById('clearSearch');
        const productCards = document.querySelectorAll('.product-card');
        
        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            const category = categoryFilter.value;
            const status = statusFilter.value;
            
            productCards.forEach(card => {
                const productName = card.querySelector('.product-name').textContent.toLowerCase();
                const productCategory = card.getAttribute('data-category');
                const productStatus = card.getAttribute('data-status');
                
                const matchesSearch = productName.includes(searchTerm);
                const matchesCategory = category === 'all' || productCategory === category;
                const matchesStatus = status === 'all' || productStatus === status;
                
                card.style.display = matchesSearch && matchesCategory && matchesStatus ? 'flex' : 'none';
            });
            
            // Show/hide clear search button
            clearSearchBtn.style.display = searchInput.value ? 'block' : 'none';
        }
        
        searchInput.addEventListener('input', filterProducts);
        categoryFilter.addEventListener('change', filterProducts);
        statusFilter.addEventListener('change', filterProducts);
        
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            filterProducts();
        });
        
        // Reset filters
        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('minPrice').value = '';
            document.getElementById('maxPrice').value = '';
            document.getElementById('dateFilter').value = 'all';
            document.getElementById('sortBy').value = 'newest';
        });
        
        // Initialize
        clearSearchBtn.style.display = 'none';
    });
    
    // Product preview
    function previewProduct(productId) {
        const modal = document.getElementById('productPreviewModal');
        const contentContainer = document.getElementById('productPreviewContent');
        
        // Show modal with loading spinner
        modal.style.display = 'block';
        contentContainer.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Chargement...</p>
            </div>
        `;
        
        // In a real implementation, you would fetch product details from the server
        // For now, we'll simulate a network request
        setTimeout(() => {
            // Example product details - in a real app, this would come from your API
            contentContainer.innerHTML = `
                <div class="product-preview">
                    <div class="preview-gallery">
                        <img src="https://via.placeholder.com/600x400" alt="Product Image" class="preview-main-image">
                        <div class="preview-thumbnails">
                            <img src="https://via.placeholder.com/100x100" alt="Thumbnail 1" class="preview-thumbnail active">
                            <img src="https://via.placeholder.com/100x100" alt="Thumbnail 2" class="preview-thumbnail">
                            <img src="https://via.placeholder.com/100x100" alt="Thumbnail 3" class="preview-thumbnail">
                        </div>
                    </div>
                    <div class="preview-details">
                        <h2>Exemple de Produit #${productId}</h2>
                        <div class="preview-meta">
                            <span class="preview-sku">SKU: PRD-${productId}</span>
                            <span class="preview-category">Catégorie: Exemple</span>
                        </div>
                        <div class="preview-price">29.99€</div>
                        <div class="preview-description">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.</p>
                        </div>
                        <div class="preview-specs">
                            <h4>Caractéristiques</h4>
                            <ul>
                                <li>Caractéristique 1: Valeur 1</li>
                                <li>Caractéristique 2: Valeur 2</li>
                                <li>Caractéristique 3: Valeur 3</li>
                            </ul>
                        </div>
                        <div class="preview-stock">
                            <h4>Stock</h4>
                            <div class="stock-info">
                                <div class="stock-amount">42 unités en stock</div>
                                <div class="stock-bar-container">
                                    <div class="stock-bar">
                                        <div class="stock-progress" style="width: 70%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <style>
                    .product-preview {
                        display: flex;
                        gap: 2rem;
                    }
                    
                    .preview-gallery {
                        flex: 1;
                    }
                    
                    .preview-main-image {
                        width: 100%;
                        height: 300px;
                        object-fit: cover;
                        border-radius: 0.5rem;
                        margin-bottom: 1rem;
                    }
                    
                    .preview-thumbnails {
                        display: flex;
                        gap: 0.5rem;
                    }
                    
                    .preview-thumbnail {
                        width: 60px;
                        height: 60px;
                        border-radius: 0.25rem;
                        cursor: pointer;
                        object-fit: cover;
                        border: 2px solid transparent;
                    }
                    
                    .preview-thumbnail.active {
                        border-color: #10b981;
                    }
                    
                    .preview-details {
                        flex: 1;
                    }
                    
                    .preview-details h2 {
                        margin-top: 0;
                        margin-bottom: 1rem;
                        color: #1e293b;
                    }
                    
                    .preview-meta {
                        display: flex;
                        gap: 1rem;
                        color: #64748b;
                        font-size: 0.875rem;
                        margin-bottom: 1rem;
                    }
                    
                    .preview-price {
                        font-size: 1.5rem;
                        font-weight: 700;
                        color: #10b981;
                        margin-bottom: 1rem;
                    }
                    
                    .preview-description {
                        margin-bottom: 1.5rem;
                        color: #334155;
                    }
                    
                    .preview-specs h4, .preview-stock h4 {
                        margin-top: 0;
                        margin-bottom: 0.5rem;
                        color: #1e293b;
                    }
                    
                    .preview-specs ul {
                        padding-left: 1.5rem;
                        margin-bottom: 1.5rem;
                    }
                    
                    .preview-specs li {
                        margin-bottom: 0.25rem;
                        color: #334155;
                    }
                    
                    .stock-info {
                        display: flex;
                        flex-direction: column;
                        gap: 0.5rem;
                    }
                    
                    .stock-amount {
                        color: #334155;
                    }
                    
                    .stock-bar-container {
                        margin-top: 0.25rem;
                    }
                    
                    @media (max-width: 768px) {
                        .product-preview {
                            flex-direction: column;
                        }
                    }
                </style>
            `;
            
            // Add event listeners to thumbnails
            document.querySelectorAll('.preview-thumbnail').forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Remove active class from all thumbnails
                    document.querySelectorAll('.preview-thumbnail').forEach(t => t.classList.remove('active'));
                    // Add active class to clicked thumbnail
                    this.classList.add('active');
                    // Update main image (in a real app you'd use the actual image src)
                    document.querySelector('.preview-main-image').src = this.src.replace('100x100', '600x400');
                });
            });
        }, 1000);
    }
    
    function closeProductPreview() {
        document.getElementById('productPreviewModal').style.display = 'none';
    }
    
    function duplicateProduct(productId) {
        // In a real implementation, this would make an AJAX request to duplicate the product
        alert(`Produit #${productId} dupliqué avec succès!`);
    }
    
    function confirmDelete(button) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
            button.closest('form').submit();
        }
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('productPreviewModal');
        if (event.target === modal) {
            closeProductPreview();
        }
    }
</script>
@endsection
