@extends('admin.layouts.app')

@section('content')
<div class="statistics-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Tableau de Bord Statistique</h1>
        <div class="date-range-picker">
            <button class="date-range-button active" data-range="7">7 jours</button>
            <button class="date-range-button" data-range="30">30 jours</button>
            <button class="date-range-button" data-range="90">90 jours</button>
            <button class="date-range-button" data-range="365">365 jours</button>
            <button class="date-range-button custom" data-range="custom">
                <i class="icon-calendar"></i> Personnalisé
            </button>
        </div>
    </div>
    
    <!-- KPI Cards -->
    <div class="kpi-cards">
        <div class="kpi-card">
            <div class="kpi-icon sales-icon">
                <i class="icon-shopping-cart"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Ventes Totales</div>
                <div class="kpi-value">{{ $totalSales }}</div>
                <div class="kpi-trend positive">
                    <i class="icon-trending-up"></i> +{{ rand(2, 15) }}% vs mois précédent
                </div>
            </div>
        </div>
        
        <div class="kpi-card">
            <div class="kpi-icon revenue-icon">
                <i class="icon-currency-euro"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Chiffre d'Affaires</div>
                <div class="kpi-value">{{ number_format($totalRevenue, 2) }} €</div>
                <div class="kpi-trend positive">
                    <i class="icon-trending-up"></i> +{{ rand(2, 15) }}% vs mois précédent
                </div>
            </div>
        </div>
        
        <div class="kpi-card">
            <div class="kpi-icon orders-icon">
                <i class="icon-package"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Commandes</div>
                <div class="kpi-value">{{ $totalOrders }}</div>
                @if(isset($ordersByStatus) && $ordersByStatus->count() > 0)
                    @php
                        $pendingCount = $ordersByStatus->where('status', 'en attente')->first()->count ?? 0;
                    @endphp
                    <div class="kpi-detail">{{ $pendingCount }} en attente</div>
                @endif
            </div>
        </div>
        
        <div class="kpi-card">
            <div class="kpi-icon customers-icon">
                <i class="icon-users"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-label">Clients</div>
                <div class="kpi-value">{{ $totalUsers }}</div>
                <div class="kpi-trend positive">
                    <i class="icon-trending-up"></i> +{{ rand(2, 15) }}% vs mois précédent
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Charts Section -->
    <div class="chart-sections">
        <div class="chart-card large">
            <div class="chart-header">
                <h2 class="chart-title">Évolution des Ventes</h2>
                <div class="chart-actions">
                    <button class="chart-action-btn active" data-chart="revenue">Revenus</button>
                    <button class="chart-action-btn" data-chart="orders">Commandes</button>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="salesTrendChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Split Section -->
    <div class="dashboard-section split">
        <div class="section-column">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2 class="card-title">Meilleures Ventes</h2>
                    <button class="card-action-btn">
                        <i class="icon-dots-vertical"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="top-products-list">
                        @forelse($bestSellingProducts as $index => $product)
                        <div class="top-product-item">
                            <div class="product-rank">{{ $index + 1 }}</div>
                            <div class="product-image">
                                @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->nom }}">
                                @else
                                <div class="no-image">
                                    <i class="icon-image"></i>
                                </div>
                                @endif
                            </div>
                            <div class="product-details">
                                <div class="product-name">{{ $product->nom }}</div>
                                <div class="product-stats">
                                    <span class="stat-label">Vendus:</span>
                                    <span class="stat-value">{{ $product->quantity_sold }}</span>
                                    <span class="stat-label">CA:</span>
                                    <span class="stat-value">{{ number_format($product->total_sales, 2) }} €</span>
                                </div>
                            </div>
                            <div class="product-chart">
                                <canvas class="mini-chart" id="productChart{{ $index }}" width="80" height="30"></canvas>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <div class="empty-icon">📊</div>
                            <p>Aucune donnée disponible pour les meilleures ventes</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#" class="view-all-link">Voir tous les produits <i class="icon-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="section-column">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2 class="card-title">Statistiques par produit</h2>
                    <div class="card-filter">
                        <select class="filter-select" id="productStatsFilter">
                            <option value="sales">Ventes</option>
                            <option value="views">Vues</option>
                            <option value="conversion">Taux de conversion</option>
                        </select>
                    </div>
                </div>
                <div class="card-body scroll-container">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Ventes</th>
                                <th>Vues</th>
                                <th>Conversion</th>
                                <th>CA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productStatistics as $product)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-thumbnail">
                                        @else
                                        <div class="no-thumbnail">
                                            <i class="icon-image"></i>
                                        </div>
                                        @endif
                                        <span class="product-name">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $product->quantity_sold ?? 0 }}</td>
                                <td>{{ $product->views ?? 0 }}</td>
                                <td>
                                    @if(($product->views ?? 0) > 0)
                                        {{ round(($product->quantity_sold / $product->views) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td>{{ number_format($product->revenue, 2) }} €</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="empty-data">Aucune donnée disponible</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="#" class="view-all-link">Voir toutes les statistiques <i class="icon-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Tracking Section -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2 class="section-title">Tableau de suivi des commandes</h2>
        </div>
        
        <div class="tab-navigation">
            <button class="tab-button active" data-tab="recent">Commandes récentes</button>
            <button class="tab-button" data-tab="pending">Commandes en attente ({{ $pendingOrders->count() }})</button>
        </div>
        
        <div class="tab-content">
            <div class="tab-panel active" id="recent-panel">
                <div class="orders-table-container">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'Client inconnu' }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $order->statut }}">
                                        {{ ucfirst($order->statut) }}
                                    </span>
                                </td>
                                <td>{{ number_format($order->total, 2) }} €</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn-action view" title="Voir la commande">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <button class="btn-action ship" title="Expédier">
                                            <i class="icon-truck"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="empty-data">Aucune commande récente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="tab-panel" id="pending-panel">
                <div class="orders-table-container">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Délai d'attente</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'Client inconnu' }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $waitingDays = \Carbon\Carbon::now()->diffInDays($order->created_at);
                                    @endphp
                                    <span class="waiting-time {{ $waitingDays > 3 ? 'critical' : ($waitingDays > 1 ? 'warning' : '') }}">
                                        {{ $waitingDays }} jour(s)
                                    </span>
                                </td>
                                <td>{{ number_format($order->total, 2) }} €</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn-action view" title="Voir la commande">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <button class="btn-action ship" title="Expédier">
                                            <i class="icon-truck"></i>
                                        </button>
                                        <button class="btn-action cancel" title="Annuler">
                                            <i class="icon-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="empty-data">Aucune commande en attente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Dashboard Layout */
    .statistics-dashboard {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        padding: 1.5rem;
        max-width: 1600px;
        margin: 0 auto;
        font-family: 'Courier New', monospace;
    }
    
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #00b894;
        margin: 0;
        font-family: 'Courier New', monospace;
    }
    
    /* Date Range Picker */
    .date-range-picker {
        display: flex;
        gap: 0.5rem;
    }
    
    .date-range-button {
        background-color: #2c3e50;
        border: 1px solid #34495e;
        color: #b2bec3;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Courier New', monospace;
    }
    
    .date-range-button:hover {
        background-color: #34495e;
        color: #dfe6e9;
    }
    
    .date-range-button.active {
        background-color: #00b894;
        color: #1e272e;
        border-color: #00b894;
    }
    
    /* KPI Cards */
    .kpi-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    
    .kpi-card {
        background-color: #2c3e50;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: all 0.3s;
        border: 1px solid #34495e;
    }
    
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        border-color: #00b894;
    }
    
    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #1e272e;
        flex-shrink: 0;
    }
    
    .kpi-icon.sales-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    
    .kpi-icon.revenue-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .kpi-icon.orders-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .kpi-icon.customers-icon {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    }
    
    .kpi-content {
        flex: 1;
    }
    
    .kpi-label {
        font-size: 0.875rem;
        color: #b2bec3;
        margin-bottom: 0.25rem;
        font-family: 'Courier New', monospace;
    }
    
    .kpi-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #dfe6e9;
        margin-bottom: 0.25rem;
        font-family: 'Courier New', monospace;
    }
    
    .kpi-trend {
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .kpi-trend.positive {
        color: #10b981;
    }
    
    .kpi-trend.negative {
        color: #ef4444;
    }
    
    /* Chart Sections */
    .chart-card {
        background-color: #2c3e50;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #34495e;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #34495e;
        background-color: rgba(0, 0, 0, 0.2);
    }
    
    .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #00b894;
        margin: 0;
        font-family: 'Courier New', monospace;
    }
    
    .chart-action-btn {
        background-color: #2d3436;
        border: 1px solid #34495e;
        color: #b2bec3;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Courier New', monospace;
    }
    
    .chart-action-btn:hover {
        background-color: #34495e;
        color: #dfe6e9;
    }
    
    .chart-action-btn.active {
        background-color: #00b894;
        color: #1e272e;
        border-color: #00b894;
    }
    
    /* Dashboard Sections */
    .dashboard-section {
        margin-bottom: 1.5rem;
    }
    
    .dashboard-section.split {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 500px), 1fr));
        gap: 1.5rem;
    }
    
    .section-column {
        display: flex;
        flex-direction: column;
    }
    
    .section-header {
        margin-bottom: 1rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    /* Dashboard Cards */
    .dashboard-card {
        background-color: #2c3e50;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid #34495e;
    }
    
    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #00b894;
        margin: 0;
        font-family: 'Courier New', monospace;
    }
    
    /* Tab Navigation */
    .tab-button {
        padding: 0.75rem 1.25rem;
        background: none;
        border: none;
        font-size: 0.875rem;
        color: #b2bec3;
        cursor: pointer;
        position: relative;
        font-family: 'Courier New', monospace;
    }
    
    .tab-button.active {
        color: #00b894;
        font-weight: 600;
    }
    
    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #00b894;
    }
    
    /* Tables */
    .stats-table th, .orders-table th {
        text-align: left;
        padding: 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #00b894;
        border-bottom: 1px solid #34495e;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background-color: rgba(0, 0, 0, 0.2);
        font-family: 'Courier New', monospace;
    }
    
    .stats-table td, .orders-table td {
        padding: 0.75rem;
        font-size: 0.875rem;
        color: #dfe6e9;
        border-bottom: 1px solid #34495e;
        font-family: 'Courier New', monospace;
    }
    
    .stats-table tr:hover td, .orders-table tr:hover td {
        background-color: rgba(52, 73, 94, 0.5);
    }
    
    /* Action Buttons */
    .btn-action {
        width: 28px;
        height: 28px;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #34495e;
        background: none;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .btn-action.view {
        color: #3b82f6;
    }
    
    .btn-action.view:hover {
        background-color: rgba(59, 130, 246, 0.2);
    }
    
    .btn-action.ship {
        color: #10b981;
    }
    
    .btn-action.ship:hover {
        background-color: rgba(16, 185, 129, 0.2);
    }
    
    .btn-action.cancel {
        color: #ef4444;
    }
    
    .btn-action.cancel:hover {
        background-color: rgba(239, 68, 68, 0.2);
    }
    
    /* Icons */
    .icon-shopping-cart:before { content: "🛒"; }
    .icon-currency-euro:before { content: "€"; }
    .icon-package:before { content: "📦"; }
    .icon-users:before { content: "👥"; }
    .icon-trending-up:before { content: "📈"; }
    .icon-calendar:before { content: "📅"; }
    .icon-arrow-right:before { content: "→"; }
    .icon-image:before { content: "🖼️"; }
    .icon-eye:before { content: "👁️"; }
    .icon-truck:before { content: "🚚"; }
    .icon-x:before { content: "❌"; }
    .icon-dots-vertical:before { content: "⋮"; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date range picker
        const rangeButtons = document.querySelectorAll('.date-range-button');
        rangeButtons.forEach(button => {
            button.addEventListener('click', function() {
                rangeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                // In a real app, this would fetch data for the selected range
            });
        });
        
        // Tab navigation
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Update active tab button
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Update active tab panel
                document.querySelectorAll('.tab-panel').forEach(panel => {
                    panel.classList.remove('active');
                });
                document.getElementById(targetTab + '-panel').classList.add('active');
            });
        });
        
        // Chart Actions
        const chartActionBtns = document.querySelectorAll('.chart-action-btn');
        chartActionBtns.forEach(button => {
            button.addEventListener('click', function() {
                chartActionBtns.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateSalesChart(this.getAttribute('data-chart'));
            });
        });
        
        // Initialize charts
        initSalesChart();
        initMiniCharts();
    });
    
    function initSalesChart() {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        
        // Sample data - in a real app, this would come from the backend
        const labels = @json($dailyRevenue->pluck('date'));
        const revenueData = @json($dailyRevenue->pluck('revenue'));
        
        // Generate random order data for demonstration
        const orderData = labels.map(() => Math.floor(Math.random() * 10) + 1);
        
        window.salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenus (€)',
                    data: revenueData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label.includes('Revenus')) {
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' €';
                                } else {
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 0,
                            callback: function(value, index, values) {
                                // Only show some labels to avoid crowding
                                if (values.length > 10) {
                                    return index % 3 === 0 ? this.getLabelForValue(value) : '';
                                }
                                return this.getLabelForValue(value);
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2]
                        },
                        ticks: {
                            callback: function(value) {
                                return value + ' €';
                            }
                        }
                    }
                }
            }
        });
    }
    
    function updateSalesChart(dataType) {
        // Sample data - in a real app, this would come from the backend
        const labels = @json($dailyRevenue->pluck('date'));
        
        if (dataType === 'revenue') {
            const revenueData = @json($dailyRevenue->pluck('revenue'));
            
            window.salesChart.data.datasets[0] = {
                label: 'Revenus (€)',
                data: revenueData,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 1,
                pointRadius: 3,
                pointHoverRadius: 5
            };
            
            window.salesChart.options.scales.y.ticks.callback = function(value) {
                return value + ' €';
            };
        } else {
            // Generate random order data for demonstration
            const orderData = labels.map(() => Math.floor(Math.random() * 10) + 1);
            
            window.salesChart.data.datasets[0] = {
                label: 'Commandes',
                data: orderData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 1,
                pointRadius: 3,
                pointHoverRadius: 5
            };
            
            window.salesChart.options.scales.y.ticks.callback = function(value) {
                return value;
            };
        }
        
        window.salesChart.update();
    }
    
    function initMiniCharts() {
        // Create mini trend charts for top products
        document.querySelectorAll('.mini-chart').forEach((canvas, index) => {
            // Generate random data for each product
            const data = Array.from({length: 7}, () => Math.floor(Math.random() * 10) + 1);
            
            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['', '', '', '', '', '', ''],
                    datasets: [{
                        data: data,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false,
                            min: 0
                        }
                    }
                }
            });
        });
    }
</script>
@endsection
