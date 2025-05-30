@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Gestion des Coupons</h1>
        <div class="page-actions">
            <a href="{{ route('coupons.create') }}" class="btn btn-create animated-btn">
                <i class="icon-plus"></i> Nouveau Coupon
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="icon-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button class="alert-close">&times;</button>
    </div>
    @endif

    <div class="filter-bar">
        <div class="filter-group">
            <div class="search-box">
                <i class="icon-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un coupon...">
            </div>
        </div>
        <div class="filter-group">
            <select id="statusFilter" class="select-filter">
                <option value="all">Tous les statuts</option>
                <option value="active">Actifs</option>
                <option value="inactive">Inactifs</option>
                <option value="expired">Expirés</option>
            </select>
        </div>
    </div>

    <div class="coupon-grid">
        @forelse($coupons as $coupon)
        <div class="coupon-card" data-status="{{ $coupon->is_active ? 'active' : 'inactive' }}">
            <div class="coupon-banner {{ $coupon->is_active ? 'active' : 'inactive' }}">
                <span class="coupon-status">{{ $coupon->is_active ? 'Actif' : 'Inactif' }}</span>
                @if($coupon->expires_at && strtotime($coupon->expires_at) < time())
                <span class="coupon-expired">Expiré</span>
                @endif
            </div>
            <div class="coupon-content">
                <div class="coupon-code">{{ $coupon->code }}</div>
                <div class="coupon-value">
                    @if($coupon->discount_amount)
                    <span class="value">{{ $coupon->discount_amount }}€</span> de réduction
                    @elseif($coupon->discount_percent)
                    <span class="value">{{ $coupon->discount_percent }}%</span> de réduction
                    @endif
                </div>
                <div class="coupon-description">
                    {{ $coupon->description ?: 'Aucune description' }}
                </div>
                @if($coupon->expires_at)
                <div class="coupon-expiry">
                    <i class="icon-calendar"></i> Expire le: {{ date('d/m/Y', strtotime($coupon->expires_at)) }}
                </div>
                @else
                <div class="coupon-expiry">
                    <i class="icon-infinity"></i> Sans date d'expiration
                </div>
                @endif
            </div>
            <div class="coupon-actions">
                <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn-action edit" title="Modifier">
                    <i class="icon-edit"></i>
                </a>
                <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-action delete" title="Supprimer" onclick="confirmDelete(this)">
                        <i class="icon-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        @if(count($coupons) === 0)
        <div class="empty-state">
            <div class="empty-icon">🎟️</div>
            <h3>Aucun coupon trouvé</h3>
            <p>Créez votre premier coupon pour offrir des réductions à vos clients</p>
        </div>
        @endif
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $coupons->links() }}
    </div>
</div>

<style>
    /* General styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* Page header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #10b981;
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .btn-create:hover {
        background-color: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .alert i {
        margin-right: 0.75rem;
        font-size: 1.25rem;
    }

    .alert-close {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: currentColor;
        opacity: 0.7;
    }

    /* Filter bar */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background-color: #f8fafc;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .select-filter {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: white;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.5rem;
    }

    /* Coupon grid */
    .coupon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .coupon-card {
        position: relative;
        background-color: white;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .coupon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    .coupon-banner {
        padding: 0.75rem 1.25rem;
        color: white;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .coupon-banner.active {
        background-color: #10b981;
    }

    .coupon-banner.inactive {
        background-color: #6b7280;
    }

    .coupon-expired {
        background-color: #ef4444;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .coupon-content {
        padding: 1.5rem;
        flex-grow: 1;
    }

    .coupon-code {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
        letter-spacing: 0.05em;
    }

    .coupon-value {
        font-size: 1.125rem;
        color: #334155;
        margin-bottom: 1rem;
    }

    .coupon-value .value {
        color: #10b981;
        font-weight: 600;
    }

    .coupon-description {
        color: #64748b;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .coupon-expiry {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.75rem;
    }

    .coupon-actions {
        display: flex;
        border-top: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }

    .btn-action {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        border: none;
        background: none;
        cursor: pointer;
        color: #64748b;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .btn-action.edit:hover {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    .btn-action.delete:hover {
        background-color: #fee2e2;
        color: #b91c1c;
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

    /* Animated button styles */
    .animated-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #10b981;
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
        animation: pulse 2s infinite;
    }
    
    .animated-btn:hover {
        background-color: #059669;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        animation: none;
    }
    
    .animated-btn::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }
    
    .animated-btn:hover::after {
        transform: translateX(100%);
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .filter-bar {
            flex-direction: column;
        }
    }

    /* Font Awesome Icons - Include if needed */
    .icon-plus:before { content: "+"; }
    .icon-edit:before { content: "✎"; }
    .icon-trash:before { content: "🗑"; }
    .icon-calendar:before { content: "📅"; }
    .icon-infinity:before { content: "∞"; }
    .icon-search:before { content: "🔍"; }
    .icon-check-circle:before { content: "✓"; }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', filterCoupons);
    document.getElementById('statusFilter').addEventListener('change', filterCoupons);

    function filterCoupons() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        document.querySelectorAll('.coupon-card').forEach(card => {
            const code = card.querySelector('.coupon-code').textContent.toLowerCase();
            const description = card.querySelector('.coupon-description').textContent.toLowerCase();
            const cardStatus = card.getAttribute('data-status');
            const isExpired = card.querySelector('.coupon-expired') !== null;
            
            const matchesSearch = code.includes(searchTerm) || description.includes(searchTerm);
            let matchesStatus = true;
            
            if (statusFilter === 'active') {
                matchesStatus = cardStatus === 'active' && !isExpired;
            } else if (statusFilter === 'inactive') {
                matchesStatus = cardStatus === 'inactive';
            } else if (statusFilter === 'expired') {
                matchesStatus = isExpired;
            }
            
            card.style.display = matchesSearch && matchesStatus ? 'flex' : 'none';
        });
    }

    // Confirm delete
    function confirmDelete(button) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce coupon ?')) {
            button.closest('form').submit();
        }
    }

    // Close alerts
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', () => {
            button.closest('.alert').remove();
        });
    });
</script>
@endsection
