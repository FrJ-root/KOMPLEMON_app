<x-filament-panels::page>
    {{ $this->form }}
    
    @php
        $reportData = $this->getOrdersData();
        $orders = $reportData['orders'];
        $totalSales = $reportData['total_sales'];
        $averageOrder = $reportData['average_order'];
        $orderCount = $reportData['order_count'];
        $statusCounts = $reportData['status_counts'];
    @endphp
    
    @if($orderCount > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <x-filament::section>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total des ventes</div>
                <div class="text-3xl font-bold">{{ number_format($totalSales, 2) }} €</div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre de commandes</div>
                <div class="text-3xl font-bold">{{ $orderCount }}</div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Valeur moyenne</div>
                <div class="text-3xl font-bold">{{ number_format($averageOrder, 2) }} €</div>
            </x-filament::section>
            
            <x-filament::section>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Statuts</div>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($statusCounts as $status => $count)
                        <div class="px-2 py-1 text-xs font-medium rounded-full
                            @if($status == 'annulé') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                            @elseif($status == 'en attente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                            @elseif($status == 'livré') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @elseif($status == 'confirmé') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                            @elseif($status == 'expédié') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                            @endif">
                            {{ $status }}: {{ $count }}
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        </div>
        
        <x-filament::section heading="Liste des commandes" class="mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Client</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Statut</th>
                            <th scope="col" class="px-6 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">#{{ $order->id }}</td>
                                <td class="px-6 py-4">{{ $order->client->nom ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $order->date_commande->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->statut == 'annulé') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @elseif($order->statut == 'en attente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($order->statut == 'livré') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($order->statut == 'confirmé') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @elseif($order->statut == 'expédié') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                        @endif">
                                        {{ $order->statut }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ number_format($order->total, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @elseif(!empty($this->data))
        <x-filament::section class="mt-6">
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Aucune commande trouvée</h3>
                <p class="mt-1 text-gray-500 dark:text-gray-400">Aucune commande n'a été trouvée pour la période sélectionnée.</p>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
