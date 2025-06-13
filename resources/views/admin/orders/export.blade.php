@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Exporter les Commandes</h1>
            <a href="{{ route('orders.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Options d'exportation</h2>
        
        <form action="{{ route('admin.orders.export') }}" method="GET">
            <!-- Hidden inputs for selected orders -->
            @foreach($selectedOrders as $order)
                <input type="hidden" name="order_ids[]" value="{{ $order->id }}">
            @endforeach
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="export_format" class="block text-gray-300 mb-2">Format d'exportation</label>
                    <select id="export_format" name="format" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                        <option value="xlsx">Excel (XLSX)</option>
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                
                <div>
                    <label for="filename" class="block text-gray-300 mb-2">Nom du fichier</label>
                    <input type="text" id="filename" name="filename" value="commandes_{{ date('Y-m-d') }}" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                </div>
            </div>
            
            <div class="bg-gray-900/30 rounded-lg p-4 mb-6">
                <h3 class="text-white font-medium mb-3">Colonnes à inclure</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="col_id" name="columns[]" value="id" checked 
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_id" class="ml-2 text-gray-300">ID Commande</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_date" name="columns[]" value="date" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_date" class="ml-2 text-gray-300">Date</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_client" name="columns[]" value="client" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_client" class="ml-2 text-gray-300">Client</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_email" name="columns[]" value="email" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_email" class="ml-2 text-gray-300">Email</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_tel" name="columns[]" value="telephone" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_tel" class="ml-2 text-gray-300">Téléphone</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_address" name="columns[]" value="adresse" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_address" class="ml-2 text-gray-300">Adresse</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_status" name="columns[]" value="statut" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_status" class="ml-2 text-gray-300">Statut</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_total" name="columns[]" value="total" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_total" class="ml-2 text-gray-300">Total</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="col_items" name="columns[]" value="items" checked
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="col_items" class="ml-2 text-gray-300">Articles</label>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-900/20 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-lg mb-6 flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium">Vous êtes sur le point d'exporter {{ count($selectedOrders) }} commande(s)</p>
                    <p class="text-sm">L'exportation générera un fichier contenant les données des commandes sélectionnées.</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <h3 class="text-white font-medium">Commandes sélectionnées</h3>
                <div class="overflow-x-auto bg-gray-900/30 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($selectedOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->date_commande->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $order->client->nom ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        @if($order->statut === 'livré') bg-green-500/20 text-green-400
                                        @elseif($order->statut === 'en attente') bg-yellow-500/20 text-yellow-400
                                        @elseif($order->statut === 'annulé') bg-red-500/20 text-red-400
                                        @elseif($order->statut === 'confirmé') bg-blue-500/20 text-blue-400
                                        @elseif($order->statut === 'expédié') bg-purple-500/20 text-purple-400
                                        @endif">
                                        {{ $order->statut }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ number_format($order->total, 2) }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Exporter maintenant</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
        
        // Handle "All statuses" checkbox logic
        const statusAllCheckbox = document.getElementById('status_all');
        const statusCheckboxes = document.querySelectorAll('input[name="status[]"]:not(#status_all)');
        
        statusAllCheckbox.addEventListener('change', function() {
            if (this.checked) {
                statusCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.disabled = true;
                });
            } else {
                statusCheckboxes.forEach(checkbox => {
                    checkbox.disabled = false;
                });
            }
        });
        
        statusCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    statusAllCheckbox.checked = false;
                }
            });
        });
    });
</script>
@endpush
@endsection