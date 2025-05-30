@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Tableau de Bord Statistique</h2>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-title">Total des commandes</div>
                            <div class="stat-value stat-orders">{{ $totalOrders }}</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-title">Utilisateurs</div>
                            <div class="stat-value stat-users">{{ $totalUsers }}</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-title">Produits</div>
                            <div class="stat-value stat-products">{{ $totalProducts }}</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-title">Chiffre d'affaires</div>
                            <div class="stat-value stat-revenue">{{ number_format($totalRevenue, 2) }}€</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ventes mensuelles</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Rapports détaillés</h3>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="{{ route('admin.statistics.sales') }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Rapport des ventes</h5>
                                        <small>Voir détails</small>
                                    </div>
                                    <p class="mb-1">Analyse détaillée des ventes par période, catégorie et produit.</p>
                                </a>
                                
                                <a href="{{ route('admin.statistics.users') }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Rapport des utilisateurs</h5>
                                        <small>Voir détails</small>
                                    </div>
                                    <p class="mb-1">Statistiques sur les inscriptions et la répartition des utilisateurs.</p>
                                </a>
                                
                                <a href="{{ route('admin.statistics.products') }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Rapport des produits</h5>
                                        <small>Voir détails</small>
                                    </div>
                                    <p class="mb-1">Analyse des produits les plus vendus et de leur performance.</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales chart
        const salesData = @json($monthlySales);
        const labels = salesData.map(item => {
            const date = new Date(item.year, item.month - 1);
            return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
        });
        
        const values = salesData.map(item => item.total_sales);
        
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventes mensuelles (€)',
                    data: values,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Évolution des ventes (6 derniers mois)'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + ' €';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' €';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
