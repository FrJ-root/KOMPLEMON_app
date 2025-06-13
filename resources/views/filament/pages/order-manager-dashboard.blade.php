<x-filament-panels::page>
    @php
        $stats = $this->getOrderStats();
        $recentData = $this->getRecentOrders();
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Orders Card -->
        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Commandes Totales</div>
                    <div class="text-3xl font-bold">{{ $stats['total_orders'] }}</div>
                </div>
                <div class="bg-primary-500/10 p-3 rounded-full">
                    <x-heroicon-o-shopping-bag class="w-6 h-6 text-primary-500" />
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ $stats['pending_orders'] }} en attente
            </div>
        </x-filament::section>
        
        <!-- Today's Orders Card -->
        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Commandes du jour</div>
                    <div class="text-3xl font-bold">{{ $stats['today_orders'] }}</div>
                </div>
                <div class="bg-warning-500/10 p-3 rounded-full">
                    <x-heroicon-o-clock class="w-6 h-6 text-warning-500" />
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ $stats['today_pending'] }} à traiter
            </div>
        </x-filament::section>
        
        <!-- Customers Card -->
        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Clients</div>
                    <div class="text-3xl font-bold">{{ $stats['customers_count'] }}</div>
                </div>
                <div class="bg-info-500/10 p-3 rounded-full">
                    <x-heroicon-o-users class="w-6 h-6 text-info-500" />
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                @if($stats['customer_percent_change'] > 0)
                    <span class="text-success-500">↑ {{ $stats['customer_percent_change'] }}%</span> ce mois
                @elseif($stats['customer_percent_change'] < 0)
                    <span class="text-danger-500">↓ {{ abs($stats['customer_percent_change']) }}%</span> ce mois
                @else
                    Stable ce mois
                @endif
            </div>
        </x-filament::section>
        
        <!-- Monthly Revenue Card -->
        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">CA mensuel</div>
                    <div class="text-3xl font-bold">{{ number_format($stats['monthly_revenue'], 2) }} €</div>
                </div>
                <div class="bg-success-500/10 p-3 rounded-full">
                    <x-heroicon-o-currency-euro class="w-6 h-6 text-success-500" />
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                @if($stats['revenue_percent_change'] > 0)
                    <span class="text-success-500">↑ {{ $stats['revenue_percent_change'] }}%</span> vs mois dernier
                @elseif($stats['revenue_percent_change'] < 0)
                    <span class="text-danger-500">↓ {{ abs($stats['revenue_percent_change']) }}%</span> vs mois dernier
                @else
                    Stable vs mois dernier
                @endif
            </div>
        </x-filament::section>
    </div>
    
    <!-- Recent Orders Section -->
    <x-filament::section class="mt-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold tracking-tight">Commandes récentes</h2>
            <div>
                <a href="{{ \App\Filament\Resources\OrderResource::getUrl('index') }}" class="text-primary-500 hover:text-primary-600 text-sm font-medium flex items-center gap-1">
                    Voir tout
                    <x-heroicon-s-arrow-small-right class="w-4 h-4" />
                </a>
            </div>
        </div>
        
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/20">
                    <tr>
                        <th scope="col" class="px-4 py-3 rounded-l-lg">ID</th>
                        <th scope="col" class="px-4 py-3">Client</th>
                        <th scope="col" class="px-4 py-3">Date</th>
                        <th scope="col" class="px-4 py-3">Statut</th>
                        <th scope="col" class="px-4 py-3 rounded-r-lg text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentData['orders'] as $order)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 font-medium">#{{ $order->id }}</td>
                            <td class="px-4 py-3">{{ $order->client->nom ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $order->date_commande->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    'bg-warning-500/10 text-warning-700 dark:text-warning-500' => $order->statut === 'en attente',
                                    'bg-success-500/10 text-success-700 dark:text-success-500' => $order->statut === 'livré',
                                    'bg-danger-500/10 text-danger-700 dark:text-danger-500' => $order->statut === 'annulé',
                                    'bg-primary-500/10 text-primary-700 dark:text-primary-500' => $order->statut === 'confirmé',
                                    'bg-info-500/10 text-info-700 dark:text-info-500' => $order->statut === 'expédié',
                                ])>
                                    {{ $order->statut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format($order->total, 2) }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Aucune commande récente trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
    
    <!-- Quick Actions Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <x-filament::section>
            <h3 class="text-lg font-medium">Commandes en attente</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $stats['pending_orders'] }} commandes nécessitent votre attention
            </p>
            <div class="mt-4">
                <a href="{{ \App\Filament\Resources\OrderResource::getUrl('index') }}?tableFilters[statut][value]=en+attente" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                    Gérer les commandes en attente
                </a>
            </div>
        </x-filament::section>
        
        <x-filament::section>
            <h3 class="text-lg font-medium">Créer une commande</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ajoutez une nouvelle commande manuellement
            </p>
            <div class="mt-4">
                <a href="{{ \App\Filament\Resources\OrderResource::getUrl('create') }}" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-success-600 hover:bg-success-500 focus:bg-success-700 focus:ring-offset-success-700">
                    <x-heroicon-m-plus class="w-5 h-5" />
                    Nouvelle commande
                </a>
            </div>
        </x-filament::section>
        
        <x-filament::section>
            <h3 class="text-lg font-medium">Exporter les données</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Téléchargez les commandes au format Excel
            </p>
            <div class="mt-4">
                <a href="{{ route('admin.orders.export') }}" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 dark:text-white bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-primary-600 dark:focus:ring-primary-600">
                    <x-heroicon-m-arrow-down-tray class="w-5 h-5" />
                    Exporter
                </a>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
