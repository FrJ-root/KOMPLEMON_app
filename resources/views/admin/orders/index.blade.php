@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Gestion des Commandes</h1>
            <div class="flex gap-2">
                <a href="{{ route('orders.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nouvelle Commande</span>
                </a>
            </div>
        </div>
        <div class="mt-2 text-gray-300 text-sm">
            Affichage de {{ $orders->count() }} commandes sur {{ $totalCount }} au total
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-purple-500/10">
        <form action="{{ route('orders.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="statut" class="block text-gray-400 mb-1 text-sm">Statut</label>
                    <select id="statut" name="statut" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                        <option value="">Tous les statuts</option>
                        <option value="en attente" {{ $filter['status'] == 'en attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmé" {{ $filter['status'] == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                        <option value="expédié" {{ $filter['status'] == 'expédié' ? 'selected' : '' }}>Expédié</option>
                        <option value="livré" {{ $filter['status'] == 'livré' ? 'selected' : '' }}>Livré</option>
                        <option value="annulé" {{ $filter['status'] == 'annulé' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                
                <div>
                    <label for="date_debut" class="block text-gray-400 mb-1 text-sm">Date de début</label>
                    <input type="date" id="date_debut" name="date_debut" value="{{ $filter['date_debut'] }}" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                </div>
                
                <div>
                    <label for="date_fin" class="block text-gray-400 mb-1 text-sm">Date de fin</label>
                    <input type="date" id="date_fin" name="date_fin" value="{{ $filter['date_fin'] }}" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                </div>
                
                <div>
                    <label for="search" class="block text-gray-400 mb-1 text-sm">Recherche</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ $filter['search'] }}" 
                               placeholder="ID, nom client ou email..."
                               class="w-full bg-gray-900 text-white pl-10 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-2">
                <a href="{{ route('orders.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-4 py-2 rounded-md transition-all">
                    Réinitialiser
                </a>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition-all">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="bg-red-900/30 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
        @endif
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Commande #
                        </th>
                        <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                            #{{ $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ $order->date_commande->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300">
                            <div class="font-medium">{{ $order->client->nom ?? 'N/A' }}</div>
                            <div class="text-gray-400 text-xs">{{ $order->client->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ number_format($order->total, 2) }} €
                        </td>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('orders.show', $order) }}" class="text-gray-300 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('orders.edit', $order) }}" class="text-blue-400 hover:text-blue-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline-block delete-order-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>Aucune commande trouvée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure delete forms work properly
        const deleteForms = document.querySelectorAll('.delete-order-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const confirmed = confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');
                
                if (!confirmed) {
                    e.preventDefault();
                    return false;
                }
                
                // Disable button to prevent double submission
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    `;
                }
                
                return true;
            });
        });
    });
</script>
@endpush
@endsection