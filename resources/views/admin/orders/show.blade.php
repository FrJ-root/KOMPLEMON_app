@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Détails de la Commande</h1>
            <div class="flex gap-2">
                <a href="{{ route('orders.edit', $order) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Modifier la Commande</span>
                </a>
                <a href="{{ route('orders.export.single', $order) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Exporter cette commande</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-semibold text-white mb-4">Informations sur la Commande</h2>
                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                    <div class="flex justify-between text-gray-400 text-sm mb-2">
                        <div>ID de la Commande</div>
                        <div>#{{ $order->id }}</div>
                    </div>
                    <div class="flex justify-between text-gray-400 text-sm mb-2">
                        <div>Date de la Commande</div>
                        <div>{{ $order->date_commande->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="flex justify-between text-gray-400 text-sm mb-2">
                        <div>Statut</div>
                        <div>
                            <span class="px-2 py-1 text-xs font-medium rounded
                                @if($order->statut === 'livré') bg-green-500/20 text-green-400
                                @elseif($order->statut === 'en attente') bg-yellow-500/20 text-yellow-400
                                @elseif($order->statut === 'annulé') bg-red-500/20 text-red-400
                                @elseif($order->statut === 'confirmé') bg-blue-500/20 text-blue-400
                                @elseif($order->statut === 'expédié') bg-purple-500/20 text-purple-400
                                @endif">
                                {{ $order->statut }}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between text-gray-400 text-sm">
                        <div>Total</div>
                        <div>{{ number_format($order->total, 2) }} €</div>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold text-white mb-4">Informations sur le Client</h2>
                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                    <div class="flex justify-between text-gray-400 text-sm mb-2">
                        <div>Nom</div>
                        <div>{{ $order->client->nom ?? 'N/A' }}</div>
                    </div>
                    <div class="flex justify-between text-gray-400 text-sm mb-2">
                        <div>Email</div>
                        <div>{{ $order->client->email ?? 'N/A' }}</div>
                    </div>
                    <div class="flex justify-between text-gray-400 text-sm mb-2">
                        <div>Téléphone</div>
                        <div>{{ $order->client->telephone ?? 'N/A' }}</div>
                    </div>
                    <div class="flex justify-between text-gray-400 text-sm">
                        <div>Adresse</div>
                        <div>{{ $order->client->adresse ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-white mb-4">Détails des Produits</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Produit
                            </th>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Quantité
                            </th>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Prix Unitaire
                            </th>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $item->product->nom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ number_format($item->unit_price, 2) }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ number_format($item->total_price, 2) }} €
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection