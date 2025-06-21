@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Créer une Commande</h1>
            <a href="{{ route('orders.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-900/30 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" id="order-form" class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="client_id" class="block text-gray-300 mb-2">Client <span class="text-purple-500">*</span></label>
                <select id="client_id" name="client_id" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" required>
                    <option value="">Sélectionner un client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->nom }} ({{ $client->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="statut" class="block text-gray-300 mb-2">Statut <span class="text-purple-500">*</span></label>
                <select id="statut" name="statut" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" required>
                    <option value="en attente" {{ old('statut') == 'en attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmé" {{ old('statut') == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                    <option value="expédié" {{ old('statut') == 'expédié' ? 'selected' : '' }}>Expédié</option>
                    <option value="livré" {{ old('statut') == 'livré' ? 'selected' : '' }}>Livré</option>
                    <option value="annulé" {{ old('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
        </div>
        
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Articles de la commande</h2>
                <button type="button" id="add-item" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-md flex items-center gap-1 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Ajouter un article</span>
                </button>
            </div>
            
            <div id="items-container" class="space-y-4">
                <div class="item-row bg-gray-900/50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-5">
                            <label class="block text-gray-300 mb-2">Produit <span class="text-purple-500">*</span></label>
                            <select name="items[0][produit_id]" class="product-select w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->prix }}">
                                        {{ $product->nom }} ({{ number_format($product->prix, 2) }} €)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-300 mb-2">Quantité <span class="text-purple-500">*</span></label>
                            <input type="number" name="items[0][quantite]" class="quantity-input w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" min="1" value="1" required>
                        </div>
                        
                        <div class="md:col-span-3">
                            <label class="block text-gray-300 mb-2">Prix unitaire (€) <span class="text-purple-500">*</span></label>
                            <input type="number" name="items[0][prix_unitaire]" class="price-input w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" min="0" step="0.01" required>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-300 mb-2">Total</label>
                            <div class="item-total bg-gray-800 px-4 py-2 rounded-md border border-gray-700 text-white">0.00 €</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
                <div class="bg-gray-900/50 p-4 rounded-lg w-full md:w-1/3">
                    <div class="flex justify-between text-gray-300 mb-2">
                        <span>Total de la commande:</span>
                        <span id="order-total" class="text-white font-medium">0.00 €</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Créer la commande</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemCount = 1;
        
        // Initialize the first item
        initializeItem(0);
        
        // Add new item
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const itemHtml = `
                <div class="item-row bg-gray-900/50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-5">
                            <label class="block text-gray-300 mb-2">Produit <span class="text-purple-500">*</span></label>
                            <select name="items[${itemCount}][produit_id]" class="product-select w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->prix }}">
                                        {{ $product->nom }} ({{ number_format($product->prix, 2) }} €)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-300 mb-2">Quantité <span class="text-purple-500">*</span></label>
                            <input type="number" name="items[${itemCount}][quantite]" class="quantity-input w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" min="1" value="1" required>
                        </div>
                        
                        <div class="md:col-span-3">
                            <label class="block text-gray-300 mb-2">Prix unitaire (€) <span class="text-purple-500">*</span></label>
                            <input type="number" name="items[${itemCount}][prix_unitaire]" class="price-input w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" min="0" step="0.01" required>
                        </div>
                        
                        <div class="md:col-span-1">
                            <label class="block text-gray-300 mb-2">Total</label>
                            <div class="item-total bg-gray-800 px-4 py-2 rounded-md border border-gray-700 text-white">0.00 €</div>
                        </div>
                        
                        <div class="md:col-span-1 flex items-end">
                            <button type="button" class="remove-item bg-red-600 hover:bg-red-700 text-white p-2 rounded-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = itemHtml;
            const newItem = tempDiv.firstElementChild;
            container.appendChild(newItem);
            
            initializeItem(itemCount);
            
            // Add remove button listener
            newItem.querySelector('.remove-item').addEventListener('click', function() {
                newItem.remove();
                updateOrderTotal();
            });
            
            itemCount++;
        });
        
        // Function to initialize item event listeners
        function initializeItem(index) {
            const row = document.querySelectorAll('.item-row')[index];
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const priceInput = row.querySelector('.price-input');
            const itemTotal = row.querySelector('.item-total');
            
            // Set initial price when product is selected
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price') || 0;
                priceInput.value = price;
                updateItemTotal(quantityInput.value, priceInput.value, itemTotal);
                updateOrderTotal();
            });
            
            // Update total when quantity or price changes
            quantityInput.addEventListener('input', function() {
                updateItemTotal(this.value, priceInput.value, itemTotal);
                updateOrderTotal();
            });
            
            priceInput.addEventListener('input', function() {
                updateItemTotal(quantityInput.value, this.value, itemTotal);
                updateOrderTotal();
            });
        }
        
        // Function to update item total
        function updateItemTotal(quantity, price, totalElement) {
            const total = (parseFloat(quantity) || 0) * (parseFloat(price) || 0);
            totalElement.textContent = total.toFixed(2) + ' €';
        }
        
        // Function to update order total
        function updateOrderTotal() {
            let total = 0;
            document.querySelectorAll('.item-total').forEach(function(element) {
                total += parseFloat(element.textContent) || 0;
            });
            
            document.getElementById('order-total').textContent = total.toFixed(2) + ' €';
        }
    });
</script>
@endpush
@endsection
