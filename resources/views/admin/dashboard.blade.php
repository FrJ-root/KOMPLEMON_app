<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KOMPLEMON - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .hex-pattern {
            background: linear-gradient(120deg, #000 0%, transparent 50%),
                linear-gradient(240deg, #000 0%, transparent 50%),
                linear-gradient(360deg, #000 0%, transparent 50%);
            background-size: 10px 10px;
        }
        .typing::after {
            content: '|';
            animation: blink 1s step-end infinite;
        }
        @keyframes blink {
            from, to { opacity: 1 }
            50% { opacity: 0 }
        }
        .status-pulse {
            animation: statusPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes statusPulse {
            0%, 100% { background-color: rgba(52, 211, 153, 0.2); }
            50% { background-color: rgba(52, 211, 153, 0.4); }
        }
        body {
            font-family: 'Courier Prime', monospace;
            background-color: #111827;
            color: #e5e7eb;
        }
        #logoutPopup {
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
        }
        .logout-popup-inner {
            background-color: rgba(0, 0, 0, 0.85);
            color: #00FF00;
            border: 2px solid #00FF00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        }
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
</head>
<body class="bg-gray-900">
    <div id="dashboard-content">
        <!-- Admin Portal Header -->
        <div class="admin-portal bg-gradient-to-r from-gray-900 via-black to-gray-900 relative">
            <div class="hex-pattern absolute inset-0 opacity-5"></div>
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center bg-black/50 rounded-lg px-4 py-2 border border-purple-500/20">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="ml-2 text-gray-300 font-medium">Admin Portal</span>
                        </div>
                        <div class="hidden md:flex items-center space-x-2">
                            <div class="h-2 w-2 rounded-full status-pulse"></div>
                            <span class="text-green-400 text-sm">System Status: Operational</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-black/30 rounded-lg px-4 py-2 text-sm">
                            <span class="text-gray-400">Session ID:</span>
                            <span class="text-purple-400 ml-2 font-mono">{{ substr(md5(session()->getId()), 0, 6) }}</span>
                        </div>
                        <div class="flex items-center bg-black/30 rounded-lg px-4 py-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-green-400 text-sm font-medium typing">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex">
            <!-- Sidebar -->
            <div class="w-64 bg-black min-h-screen p-6">
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-purple-600 rounded flex items-center justify-center text-white">
                        <code class="text-sm">&lt;/&gt;</code>
                    </div>
                    <span class="text-xl font-bold text-purple-600">KOMPLEMON</span>
                </div>

                <nav class="space-y-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-gray-200 hover:text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Produits</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Catégories</span>
                    </a>

                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Commandes</span>
                    </a>

                    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Clients</span>
                    </a>

                    <a href="{{ route('admin.statistics.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Statistiques</span>
                    </a>

                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Utilisateurs</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Paramètres</span>
                    </a>
                </nav>

                <a href="#" onclick="toggleLogoutPopup()" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 mt-8">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Déconnexion</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-8">
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
                        <div class="h-80">
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
                                                @if($order->statut == 'terminé') bg-green-500/20 text-green-400
                                                @elseif($order->statut == 'en attente') bg-yellow-500/20 text-yellow-400
                                                @elseif($order->statut == 'annulé') bg-red-500/20 text-red-400
                                                @else bg-blue-500/20 text-blue-400
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
        </div>

        <!-- Logout Popup -->
        <div id="logoutPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
            <div class="logout-popup-inner w-80 p-6 rounded-lg text-center">
                <h2 class="text-xl font-bold mb-4">Confirmation de déconnexion</h2>
                <p class="mb-4">Êtes-vous sûr de vouloir vous déconnecter?</p>
                <p class="text-gray-500 mb-6">Votre session sera terminée.</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="confirmLogout()" class="px-4 py-2 rounded hover:bg-green-600 transition-colors">Confirmer</button>
                    <button onclick="toggleLogoutPopup()" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logout Popup
        function toggleLogoutPopup() {
            const popup = document.getElementById('logoutPopup');
            popup.classList.toggle('hidden');
        }
        
        function confirmLogout() {
            window.location.href = '{{ route("logout") }}';
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sales chart
            const ctx = document.getElementById('salesChart').getContext('2d');
            
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
            
            const salesChart = new Chart(ctx, config);
            
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
        });
    </script>
</body>
</html>
