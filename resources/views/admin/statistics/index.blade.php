@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Statistiques</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <select id="date-range" class="bg-gray-800 text-white border border-gray-700 rounded-md px-4 py-2 focus:outline-none focus:border-purple-500 appearance-none pr-8">
                        <option value="7">7 derniers jours</option>
                        <option value="30" selected>30 derniers jours</option>
                        <option value="90">3 derniers mois</option>
                        <option value="365">12 derniers mois</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button id="refresh-stats" class="bg-purple-600 hover:bg-purple-700 text-white rounded-md px-4 py-2 flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Actualiser</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 hover:border-purple-500/30 transition-all">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Total Produits</div>
                <div class="bg-purple-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-bold text-white">{{ \App\Models\Product::count() }}</span>
                <span class="text-green-400 text-sm ml-2">+12%</span>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 hover:border-purple-500/30 transition-all">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Commandes</div>
                <div class="bg-purple-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-bold text-white">{{ \App\Models\Order::count() }}</span>
                <span class="text-green-400 text-sm ml-2">+5%</span>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 hover:border-purple-500/30 transition-all">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Catégories</div>
                <div class="bg-purple-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-bold text-white">{{ \App\Models\Category::count() }}</span>
                <span class="text-green-400 text-sm ml-2">+3 nouveau</span>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 hover:border-purple-500/30 transition-all">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Clients</div>
                <div class="bg-purple-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl font-bold text-white">{{ \App\Models\Client::count() }}</span>
                <span class="text-green-400 text-sm ml-2">+8 cette semaine</span>
            </div>
        </div>
    </div>

    <!-- Sales Chart & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between mb-6">
                <div class="text-white font-semibold">Évolution des Ventes</div>
                <div class="flex gap-2">
                    <button class="chart-filter-btn active" data-period="7">7j</button>
                    <button class="chart-filter-btn" data-period="30">30j</button>
                    <button class="chart-filter-btn" data-period="90">90j</button>
                </div>
            </div>
            <div class="h-80 w-full relative" id="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between mb-6">
                <div class="text-white font-semibold">Meilleures Ventes</div>
                <div class="bg-purple-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="space-y-4">
                @foreach(\App\Models\Product::take(5)->get() as $index => $product)
                <div class="flex items-center p-3 bg-gray-900 rounded-lg hover:bg-gray-700 transition-colors">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center text-white font-semibold mr-4">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white text-sm font-medium truncate">{{ $product->nom }}</h3>
                        <p class="text-gray-400 text-xs">{{ number_format($product->prix, 2) }} €</p>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-400">{{ rand(10, 100) }} vendus</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Orders & Categories -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between mb-6">
                <div class="text-white font-semibold">Commandes Récentes</div>
                <a href="{{ route('orders.index') }}" class="text-purple-400 hover:text-purple-300 text-sm">Voir tout</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left">
                            <th class="pb-3 text-gray-400 text-xs uppercase font-semibold">ID</th>
                            <th class="pb-3 text-gray-400 text-xs uppercase font-semibold">Client</th>
                            <th class="pb-3 text-gray-400 text-xs uppercase font-semibold">Date</th>
                            <th class="pb-3 text-gray-400 text-xs uppercase font-semibold">Statut</th>
                            <th class="pb-3 text-gray-400 text-xs uppercase font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Order::latest()->take(5)->get() as $order)
                        <tr class="border-t border-gray-700">
                            <td class="py-3 text-white">#{{ $order->id }}</td>
                            <td class="py-3 text-white">{{ $order->client->nom ?? 'N/A' }}</td>
                            <td class="py-3 text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    @if($order->statut == 'livré') bg-green-500/20 text-green-400
                                    @elseif($order->statut == 'en attente') bg-yellow-500/20 text-yellow-400
                                    @elseif($order->statut == 'annulé') bg-red-500/20 text-red-400
                                    @elseif($order->statut == 'expédié') bg-blue-500/20 text-blue-400
                                    @else bg-purple-500/20 text-purple-400
                                    @endif">
                                    {{ $order->statut }}
                                </span>
                            </td>
                            <td class="py-3 text-white">{{ number_format($order->total, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between mb-6">
                <div class="text-white font-semibold">Catégories</div>
                <a href="{{ route('categories.index') }}" class="text-purple-400 hover:text-purple-300 text-sm">Gérer</a>
            </div>
            <div class="space-y-4">
                @foreach(\App\Models\Category::take(5)->get() as $category)
                <div class="p-3 bg-gray-900 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-600/20 text-purple-400 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <span class="text-white">{{ $category->nom }}</span>
                    </div>
                    <div class="text-sm text-gray-400">{{ $category->products->count() }} produits</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .chart-filter-btn {
        background-color: rgba(99, 102, 241, 0.1);
        color: #a5b4fc;
        border: 1px solid rgba(99, 102, 241, 0.2);
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        transition: all 0.2s;
    }
    
    .chart-filter-btn:hover {
        background-color: rgba(99, 102, 241, 0.2);
    }
    
    .chart-filter-btn.active {
        background-color: #6366f1;
        color: white;
        border-color: #6366f1;
    }
    
    .hex-pattern {
        background: linear-gradient(120deg, #000 0%, transparent 50%),
            linear-gradient(240deg, #000 0%, transparent 50%),
            linear-gradient(360deg, #000 0%, transparent 50%);
        background-size: 10px 10px;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Make sure Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                document.getElementById('chart-container').innerHTML = '<div class="flex items-center justify-center h-full w-full text-gray-500">Unable to load chart library</div>';
                return;
            }
            
            // Verify canvas element exists
            const canvas = document.getElementById('salesChart');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }
            
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Unable to get canvas context');
                return;
            }
            
            // Sample data - in a real app this would come from your backend
            const labels = Array.from({length: 7}, (_, i) => {
                const d = new Date();
                d.setDate(d.getDate() - i);
                return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
            }).reverse();
            
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Ventes (€)',
                    data: [12500, 19200, 15700, 23400, 18100, 24300, 28600],
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8b5cf6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    pointRadius: 4
                }]
            };
            
            const config = {
                type: 'line',
                data: data,
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
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#f1f5f9',
                            bodyColor: '#e2e8f0',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            padding: 12,
                            titleFont: {
                                family: "'Courier Prime', monospace",
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                family: "'Courier Prime', monospace",
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} €`;
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
                                color: '#64748b',
                                font: {
                                    family: "'Courier Prime', monospace"
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 2],
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: "'Courier Prime', monospace"
                                },
                                callback: function(value) {
                                    return value.toLocaleString() + ' €';
                                }
                            }
                        }
                    }
                }
            };
            
            // Create the chart with a delay to ensure DOM is fully loaded
            setTimeout(() => {
                window.salesChart = new Chart(ctx, config);
                console.log('Chart initialized successfully');
            }, 100);
            
            // Chart filter buttons
            const filterButtons = document.querySelectorAll('.chart-filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    const period = parseInt(this.getAttribute('data-period'));
                    
                    // Simulate data change based on period
                    // In a real app, you would fetch data from the server
                    let newLabels;
                    if (period === 7) {
                        newLabels = Array.from({length: 7}, (_, i) => {
                            const d = new Date();
                            d.setDate(d.getDate() - i);
                            return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
                        }).reverse();
                        
                        salesChart.data.datasets[0].data = [12500, 19200, 15700, 23400, 18100, 24300, 28600];
                    } else if (period === 30) {
                        newLabels = Array.from({length: 6}, (_, i) => {
                            const d = new Date();
                            d.setDate(d.getDate() - i * 5);
                            return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
                        }).reverse();
                        
                        salesChart.data.datasets[0].data = [56700, 72300, 64500, 89200, 78600, 92400];
                    } else if (period === 90) {
                        newLabels = Array.from({length: 6}, (_, i) => {
                            const d = new Date();
                            d.setDate(d.getDate() - i * 15);
                            return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
                        }).reverse();
                        
                        salesChart.data.datasets[0].data = [167800, 214500, 195600, 248700, 232100, 275400];
                    }
                    
                    salesChart.data.labels = newLabels;
                    salesChart.update();
                });
            });
            
            // Refresh button functionality
            document.getElementById('refresh-stats').addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = `
                    <svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Chargement...</span>
                `;
                
                // Simulate API call
                setTimeout(() => {
                    this.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Actualiser</span>
                    `;
                    this.disabled = false;
                    
                    // Update chart with new random data
                    salesChart.data.datasets[0].data = Array(7).fill().map(() => Math.floor(Math.random() * 20000) + 10000);
                    salesChart.update();
                }, 1500);
            });
        } catch (error) {
            console.error('Error initializing chart:', error);
            document.getElementById('chart-container').innerHTML = 
                '<div class="flex items-center justify-center h-full w-full text-gray-500">Error loading chart: ' + error.message + '</div>';
        }
    });
</script>
@endpush
@endsection
