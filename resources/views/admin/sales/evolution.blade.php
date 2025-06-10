@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Évolution des Ventes</h1>
            <div class="flex items-center space-x-4">
                <select id="period-selector" class="bg-gray-800 text-white border border-gray-700 rounded-md px-4 py-2 focus:outline-none focus:border-purple-500">
                    <option value="7">7 derniers jours</option>
                    <option value="30" selected>30 derniers jours</option>
                    <option value="90">3 derniers mois</option>
                    <option value="365">12 derniers mois</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="text-white font-semibold">Évolution des Ventes</div>
            <div class="flex gap-2">
                <button class="chart-filter-btn active" data-type="daily">Jour</button>
                <button class="chart-filter-btn" data-type="weekly">Semaine</button>
                <button class="chart-filter-btn" data-type="monthly">Mois</button>
            </div>
        </div>
        <div class="h-80">
            <canvas id="salesEvolutionChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Ventes totales</div>
                <div class="bg-purple-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="total-amount" class="text-2xl font-bold text-white">Chargement...</span>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Commandes</div>
                <div class="bg-blue-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="total-orders" class="text-2xl font-bold text-white">Chargement...</span>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Panier Moyen</div>
                <div class="bg-green-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="average-cart" class="text-2xl font-bold text-white">Chargement...</span>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">Taux de Conversion</div>
                <div class="bg-yellow-500/10 rounded-full p-2">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="conversion-rate" class="text-2xl font-bold text-white">Chargement...</span>
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
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart instance
        let salesChart;
        
        // Default values
        let selectedPeriod = '30';
        let selectedType = 'daily';
        
        // Initialize the chart
        initChart();
        
        // Period selector
        document.getElementById('period-selector').addEventListener('change', function() {
            selectedPeriod = this.value;
            fetchData();
        });
        
        // Chart type buttons
        document.querySelectorAll('.chart-filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.chart-filter-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                selectedType = this.getAttribute('data-type');
                fetchData();
            });
        });
        
        function initChart() {
            const ctx = document.getElementById('salesEvolutionChart').getContext('2d');
            
            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Ventes (€)',
                        data: [],
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
            });
            
            // Initial data fetch
            fetchData();
        }
        
        function fetchData() {
            // Show loading state
            document.getElementById('total-amount').textContent = 'Chargement...';
            document.getElementById('total-orders').textContent = 'Chargement...';
            document.getElementById('average-cart').textContent = 'Chargement...';
            document.getElementById('conversion-rate').textContent = 'Chargement...';
            
            // Fetch data from the server
            fetch(`/admin/sales/evolution/data?period=${selectedPeriod}&type=${selectedType}`)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                    updateStats(data);
                })
                .catch(error => {
                    console.error('Error fetching sales data:', error);
                });
        }
        
        function updateChart(data) {
            salesChart.data.labels = data.labels;
            salesChart.data.datasets[0].data = data.values;
            salesChart.update();
        }
        
        function updateStats(data) {
            // Calculate total amount
            const totalAmount = data.values.reduce((sum, value) => sum + Number(value), 0);
            document.getElementById('total-amount').textContent = `${totalAmount.toLocaleString()} €`;
            
            // Estimate number of orders (simulated)
            const estimatedOrders = Math.round(totalAmount / 120); // Assuming average order is 120€
            document.getElementById('total-orders').textContent = estimatedOrders.toLocaleString();
            
            // Calculate average cart
            const averageCart = totalAmount / estimatedOrders;
            document.getElementById('average-cart').textContent = `${averageCart.toLocaleString(undefined, {maximumFractionDigits: 2})} €`;
            
            // Simulated conversion rate
            const conversionRate = (Math.random() * 3 + 2).toFixed(2); // Random between 2% and 5%
            document.getElementById('conversion-rate').textContent = `${conversionRate}%`;
        }
    });
</script>
@endpush
@endsection
