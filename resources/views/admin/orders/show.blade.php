@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Commande #{{ $order->id }}</h1>
                <p class="text-gray-400">Créée le {{ $order->date_commande->format('d/m/Y à H:i') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('orders.edit', $order) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Modifier</span>
                </a>
                
                <a href="{{ route('orders.export.single', $order) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Exporter</span>
                </a>
                
                <a href="{{ route('orders.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Order Info -->
        <div class="lg:col-span-2 bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Détails de la commande</h2>
                <div class="px-3 py-1 rounded text-sm font-medium 
                    @if($order->statut == 'livré') bg-green-500/20 text-green-400
                    @elseif($order->statut == 'en attente') bg-yellow-500/20 text-yellow-400
                    @elseif($order->statut == 'annulé') bg-red-500/20 text-red-400
                    @elseif($order->statut == 'confirmé') bg-blue-500/20 text-blue-400
                    @elseif($order->statut == 'expédié') bg-purple-500/20 text-purple-400
                    @endif">
                    {{ ucfirst($order->statut) }}
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 bg-gray-800 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 bg-gray-800 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 bg-gray-800 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-900/30">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                {{ $item->product->nom ?? 'Produit inconnu' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-right">
                                {{ number_format($item->prix_unitaire, 2) }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                {{ $item->quantite }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-right font-medium">
                                {{ number_format($item->prix_unitaire * $item->quantite, 2) }} €
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-end">
                <div class="w-full max-w-xs">
                    <div class="flex justify-between py-2 text-gray-400">
                        <span>Sous-total:</span>
                        <span class="text-white">{{ number_format($order->total, 2) }} €</span>
                    </div>
                    <div class="flex justify-between py-2 text-gray-400 border-t border-gray-700">
                        <span>TVA (20%):</span>
                        <span class="text-white">{{ number_format($order->total * 0.2, 2) }} €</span>
                    </div>
                    <div class="flex justify-between py-3 text-lg font-bold border-t border-gray-700">
                        <span class="text-white">Total:</span>
                        <span class="text-green-400">{{ number_format($order->total * 1.2, 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
            <h2 class="text-xl font-semibold text-white mb-6">Informations client</h2>
            
            <div class="bg-gray-900/30 rounded-lg p-4 mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-600/20 rounded-full flex items-center justify-center text-purple-400 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-medium">{{ $order->client->nom ?? 'Client inconnu' }}</h3>
                        <p class="text-gray-400 text-sm">Client #{{ $order->client->id ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex">
                        <span class="text-gray-400 w-24">Email:</span>
                        <span class="text-white flex-1">{{ $order->client->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-400 w-24">Téléphone:</span>
                        <span class="text-white flex-1">{{ $order->client->telephone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-400 w-24">Adresse:</span>
                        <span class="text-white flex-1">{{ $order->client->adresse ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            <h2 class="text-xl font-semibold text-white mb-4">Historique des statuts</h2>
            
            <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                @if(!empty($order->historique))
                    @foreach(array_filter(explode("\n", $order->historique)) as $entry)
                    <div class="bg-gray-900/30 p-3 rounded-lg border-l-2 
                        @if(strpos($entry, 'créée') !== false) border-blue-500
                        @elseif(strpos($entry, 'en attente') !== false) border-yellow-500
                        @elseif(strpos($entry, 'confirmé') !== false) border-blue-500
                        @elseif(strpos($entry, 'expédié') !== false) border-purple-500
                        @elseif(strpos($entry, 'livré') !== false) border-green-500
                        @elseif(strpos($entry, 'annulé') !== false) border-red-500
                        @else border-gray-500
                        @endif">
                        <div class="text-xs text-gray-400 mb-1">
                            {{ substr($entry, 0, 19) }}
                        </div>
                        <div class="text-white text-sm">
                            {{ substr($entry, 21) }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-gray-400 italic text-center py-4">
                        Aucun historique disponible
                    </div>
                @endif
            </div>
            
            <div class="mt-6">
                <form action="{{ route('orders.update', $order) }}" method="POST" class="w-full">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                    
                    @foreach($order->items as $index => $item)
                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                    <input type="hidden" name="items[{{ $index }}][produit_id]" value="{{ $item->produit_id }}">
                    <input type="hidden" name="items[{{ $index }}][quantite]" value="{{ $item->quantite }}">
                    <input type="hidden" name="items[{{ $index }}][prix_unitaire]" value="{{ $item->prix_unitaire }}">
                    @endforeach
                    
                    <div class="flex items-center space-x-3">
                        <select name="statut" class="flex-1 bg-gray-900 text-white rounded-md border border-gray-700 px-4 py-2 focus:border-purple-500 focus:outline-none appearance-none">
                            <option value="en attente" {{ $order->statut === 'en attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmé" {{ $order->statut === 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                            <option value="expédié" {{ $order->statut === 'expédié' ? 'selected' : '' }}>Expédié</option>
                            <option value="livré" {{ $order->statut === 'livré' ? 'selected' : '' }}>Livré</option>
                            <option value="annulé" {{ $order->statut === 'annulé' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Mettre à jour</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection