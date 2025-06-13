@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Modifier la Commande #{{ $order->id }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('orders.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Order Details Panel -->
            <div class="col-span-1 bg-gray-800 rounded-lg p-6 border border-purple-500/10">
                <h2 class="text-lg font-semibold text-white mb-4">Détails de la commande</h2>
                
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="statut" class="block text-gray-300 mb-2">Statut</label>
                        <select id="statut" name="statut" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                            <option value="en attente" {{ $order->statut === 'en attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmé" {{ $order->statut === 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                            <option value="expédié" {{ $order->statut === 'expédié' ? 'selected' : '' }}>Expédié</option>
                            <option value="livré" {{ $order->statut === 'livré' ? 'selected' : '' }}>Livré</option>
                            <option value="annulé" {{ $order->statut === 'annulé' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="client_id" class="block text-gray-300 mb-2">Client</label>
                        <select id="client_id" name="client_id" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $order->client_id === $client->id ? 'selected' : '' }}>{{ $client->nom }} ({{ $client->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-300 mb-2">Date de commande</label>
                        <div class="w-full bg-gray-900 text-gray-400 px-4 py-2 rounded-md border border-gray-700">
                            {{ $order->date_commande->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-300 mb-2">Total</label>
                        <div class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 font-medium">
                            <span class="text-green-400">{{ number_format($order->total, 2) }} €</span>
                            <span class="text-xs text-gray-500 ml-2">(Calculé automatiquement)</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Client Details Panel -->
            <div class="col-span-1 bg-gray-800 rounded-lg p-6 border border-purple-500/10">
                <h2 class="text-lg font-semibold text-white mb-4">Informations client</h2>
                
                <div id="client-details" class="space-y-3">
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Nom</label>
                        <div class="text-white">{{ $order->client->nom ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Email</label>
                        <div class="text-white">{{ $order->client->email ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Téléphone</label>
                        <div class="text-white">{{ $order->client->telephone ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Adresse</label>
                        <div class="text-white">{{ $order->client->adresse ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Order History Panel -->
            <div class="col-span-1 bg-gray-800 rounded-lg p-6 border border-purple-500/10">
                <h2 class="text-lg font-semibold text-white mb-4">Historique</h2>
                
                <div class="space-y-3 max-h-60 overflow-y-auto text-sm">
                    @if(!empty($order->historique))
                        @foreach(array_filter(explode("\n", $order->historique)) as $entry)
                            <div class="bg-gray-900/50 p-2 rounded border-l-2 border-purple-500">
                                {{ $entry }}
                            </div>
                        @endforeach
                    @else
                        <div class="text-gray-400 italic">Aucun historique disponible</div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Order Items Panel -->
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Articles commandés</h2>
                <button type="button" id="add-item-btn" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-md flex items-center gap-1 text-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Ajouter un article</span>
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700 mb-4">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 bg-gray-800 text-xs font-medium text-gray-400 uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="order-items" class="divide-y divide-gray-700">
                        @foreach($order->items as $index => $item)
                        <tr class="order-item" data-index="{{ $index }}">
                            <td class="px-6 py-4">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <select name="items[{{ $index }}][produit_id]" class="w-full bg-gray-900 text-white px-3 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none product-select" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->prix }}" {{ $item->produit_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="items[{{ $index }}][prix_unitaire]" value="{{ $item->prix_unitaire }}" step="0.01" min="0" class="w-full bg-gray-900 text-white px-3 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none price-input" required>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="items[{{ $index }}][quantite]" value="{{ $item->quantite }}" min="1" class="w-full bg-gray-900 text-white px-3 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none quantity-input" required>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white font-medium item-total">{{ number_format($item->prix_unitaire * $item->quantite, 2) }} €</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" class="text-red-400 hover:text-red-300 remove-item">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-end">
                <div class="w-64">
                    <div class="flex justify-between border-t border-gray-700 pt-2 pb-1">
                        <span class="text-gray-400">Sous-total:</span>
                        <span class="text-white" id="order-subtotal">{{ number_format($order->total, 2) }} €</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-700 pt-2 pb-1">
                        <span class="text-gray-400 font-medium">Total:</span>
                        <span class="text-green-400 font-medium" id="order-total">{{ number_format($order->total, 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end gap-4">
            <a href="{{ route('orders.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-all">
                Annuler
            </a>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Enregistrer les modifications</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables
        let itemIndex = {{ count($order->items) }};
        const orderItems = document.getElementById('order-items');
        const addItemBtn = document.getElementById('add-item-btn');
        const clientSelect = document.getElementById('client_id');
        const clientDetails = document.getElementById('client-details');
        
        // Update client details when client changes
        clientSelect.addEventListener('change', function() {
            const clientId = this.value;
            // In a real app, this would be an AJAX call to get client details
            // For demo purposes, we'll just show a loading state
            clientDetails.innerHTML = '<div class="text-gray-400">Chargement des informations...</div>';
            
            // Simulate API call
            setTimeout(() => {
                // This would be populated with actual client data from the API response
                clientDetails.innerHTML = `
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Nom</label>
                        <div class="text-white">Client #${clientId}</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Email</label>
                        <div class="text-white">client${clientId}@example.com</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Téléphone</label>
                        <div class="text-white">+33 6 12 34 56 78</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="block text-gray-400 text-sm">Adresse</label>
                        <div class="text-white">123 Rue Example, 75000 Paris</div>
                    </div>
                `;
            }, 500);
        });
        
        // Add new item
        addItemBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.className = 'order-item';
            newRow.dataset.index = itemIndex;
            
            newRow.innerHTML = `
                <td class="px-6 py-4">
                    <select name="items[${itemIndex}][produit_id]" class="w-full bg-gray-900 text-white px-3 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none product-select" required>
                        <option value="">Sélectionner un produit</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->prix }}">{{ $product->nom }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-6 py-4">
                    <input type="number" name="items[${itemIndex}][prix_unitaire]" value="0" step="0.01" min="0" class="w-full bg-gray-900 text-white px-3 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none price-input" required>
                </td>
                <td class="px-6 py-4">
                    <input type="number" name="items[${itemIndex}][quantite]" value="1" min="1" class="w-full bg-gray-900 text-white px-3 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none quantity-input" required>
                </td>
                <td class="px-6 py-4">
                    <div class="text-white font-medium item-total">0.00 €</div>
                </td>
                <td class="px-6 py-4 text-center">
                    <button type="button" class="text-red-400 hover:text-red-300 remove-item">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            `;
            
            orderItems.appendChild(newRow);
            itemIndex++;
            
            // Initialize the new row's event listeners
            initItemRow(newRow);
            updateTotals();
        });
        
        // Initialize existing items
        document.querySelectorAll('.order-item').forEach(item => {
            initItemRow(item);
        });
        
        // Initialize item row
        function initItemRow(row) {
            const productSelect = row.querySelector('.product-select');
            const priceInput = row.querySelector('.price-input');
            const quantityInput = row.querySelector('.quantity-input');
            const removeBtn = row.querySelector('.remove-item');
            
            // Set price when product changes
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                priceInput.value = price;
                updateItemTotal(row);
                updateTotals();
            });
            
            // Update totals when price or quantity changes
            priceInput.addEventListener('input', function() {
                updateItemTotal(row);
                updateTotals();
            });
            
            quantityInput.addEventListener('input', function() {
                updateItemTotal(row);
                updateTotals();
            });
            
            // Remove item
            removeBtn.addEventListener('click', function() {
                if (orderItems.querySelectorAll('.order-item').length > 1) {
                    row.remove();
                    updateTotals();
                } else {
                    alert('La commande doit contenir au moins un article.');
                }
            });
        }
        
        // Update item total
        function updateItemTotal(row) {
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
            const total = price * quantity;
            row.querySelector('.item-total').textContent = total.toFixed(2) + ' €';
        }
        
        // Update order totals
        function updateTotals() {
            let subtotal = 0;
            
            document.querySelectorAll('.order-item').forEach(item => {
                const price = parseFloat(item.querySelector('.price-input').value) || 0;
                const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
                subtotal += price * quantity;
            });
            
            document.getElementById('order-subtotal').textContent = subtotal.toFixed(2) + ' €';
            document.getElementById('order-total').textContent = subtotal.toFixed(2) + ' €';
        }
    });
</script>
@endpush
@endsection
