@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Gestion des Coupons</h1>
            <a href="{{ route('coupons.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nouveau Coupon</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-900/30 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="text-green-400 hover:text-green-300" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-purple-500/10">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Rechercher un coupon..." 
                       class="w-full bg-gray-900 text-white pl-10 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
            </div>
            <div class="flex-initial">
                <select id="statusFilter" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                    <option value="all">Tous les statuts</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                    <option value="expired">Expirés</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Coupons Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
        @forelse($coupons as $coupon)
            <div class="coupon-card relative bg-gradient-to-b from-gray-800 to-gray-900 rounded-lg overflow-hidden border border-purple-500/10 hover:border-purple-500/30 transition-all shadow-lg group">
                <!-- Card Header with Expiration Badge -->
                <div class="px-5 pt-5 pb-3 border-b border-gray-700">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-bold text-white font-mono tracking-wider">{{ $coupon->code }}</h3>
                        
                        @if($coupon->expires_at && $coupon->expires_at->isPast())
                            <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-400 font-medium">Expiré</span>
                        @elseif(!$coupon->is_active)
                            <span class="px-2 py-1 text-xs rounded bg-gray-500/20 text-gray-400 font-medium">Inactif</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400 font-medium">Actif</span>
                        @endif
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-5">
                    <!-- Discount Value -->
                    <div class="flex flex-col mb-5">
                        <span class="text-gray-400 text-xs mb-1">Réduction</span>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-purple-400">
                                @if($coupon->discount_percent)
                                    {{ $coupon->discount_percent }}%
                                @else
                                    {{ number_format($coupon->discount_amount, 2) }}€
                                @endif
                            </span>
                            
                            @if($coupon->discount_percent)
                                <span class="ml-2 text-xs bg-purple-500/20 text-purple-400 rounded px-2 py-1">Pourcentage</span>
                            @else
                                <span class="ml-2 text-xs bg-blue-500/20 text-blue-400 rounded px-2 py-1">Montant fixe</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Expiration Date & Description -->
                    <div class="mb-4">
                        @if($coupon->expires_at)
                            <div class="flex items-center text-sm text-gray-400 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Expire le: {{ $coupon->expires_at->format('d/m/Y') }}</span>
                            </div>
                        @else
                            <div class="flex items-center text-sm text-gray-400 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Pas de date d'expiration</span>
                            </div>
                        @endif
                        
                        @if($coupon->description)
                            <p class="text-sm text-gray-300 line-clamp-2">{{ $coupon->description }}</p>
                        @else
                            <p class="text-sm text-gray-500 italic">Aucune description</p>
                        @endif
                    </div>
                </div>
                
                <!-- Card Footer with Action Buttons -->
                <div class="px-5 py-3 bg-gray-900 border-t border-gray-700 flex justify-between items-center">
                    <span class="text-xs text-gray-400">Créé le {{ $coupon->created_at->format('d/m/Y') }}</span>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('coupons.edit', $coupon) }}" class="text-blue-400 hover:text-blue-300 p-1" title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        
                        <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce coupon?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 p-1" title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        
                        <button type="button" class="text-cyan-400 hover:text-cyan-300 p-1 copy-code" data-code="{{ $coupon->code }}" title="Copier le code">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Glowing effect on hover -->
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600/0 via-purple-600/0 to-cyan-600/0 opacity-0 group-hover:opacity-20 transition-opacity pointer-events-none"></div>
            </div>
        @empty
            <div class="col-span-full bg-gray-800 rounded-lg p-8 text-center border border-gray-700">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-400 mb-2">Aucun coupon trouvé</h3>
                <p class="text-gray-500 mb-6">Commencez par créer votre premier coupon de réduction</p>
                <a href="{{ route('coupons.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Créer un coupon
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
</div>

<style>
    /* Card hover effect */
    .coupon-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .coupon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px -10px rgba(139, 92, 246, 0.3);
    }
    
    /* Make sure TailwindCSS applies the line-clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Hex pattern background */
    .hex-pattern {
        background: linear-gradient(120deg, #000 0%, transparent 50%),
            linear-gradient(240deg, #000 0%, transparent 50%),
            linear-gradient(360deg, #000 0%, transparent 50%);
        background-size: 10px 10px;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const couponCards = document.querySelectorAll('.coupon-card');
        
        function filterCoupons() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            
            couponCards.forEach(card => {
                const code = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                const hasActiveClass = card.querySelector('.bg-green-500\\/20') !== null;
                const hasInactiveClass = card.querySelector('.bg-gray-500\\/20') !== null;
                const hasExpiredClass = card.querySelector('.bg-red-500\\/20') !== null;
                
                let matchesStatus = statusValue === 'all' ||
                    (statusValue === 'active' && hasActiveClass) ||
                    (statusValue === 'inactive' && hasInactiveClass) ||
                    (statusValue === 'expired' && hasExpiredClass);
                
                let matchesSearch = code.includes(searchTerm) || description.includes(searchTerm);
                
                if (matchesStatus && matchesSearch) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Check if any cards are visible
            const visibleCards = document.querySelectorAll('.coupon-card:not(.hidden)');
            const noResultsElement = document.querySelector('.col-span-full');
            
            if (visibleCards.length === 0 && couponCards.length > 0) {
                // If no results, show the no results message
                if (!noResultsElement) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'col-span-full bg-gray-800 rounded-lg p-8 text-center border border-gray-700';
                    noResultsDiv.innerHTML = `
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="text-xl font-medium text-gray-400 mb-2">Aucun résultat trouvé</h3>
                        <p class="text-gray-500">Modifiez vos critères de recherche</p>
                    `;
                    document.querySelector('.grid').appendChild(noResultsDiv);
                }
            } else if (noResultsElement && visibleCards.length > 0) {
                // If results are found, hide the no results message
                noResultsElement.remove();
            }
        }
        
        if (searchInput && statusFilter) {
            searchInput.addEventListener('input', filterCoupons);
            statusFilter.addEventListener('change', filterCoupons);
        }
        
        // Copy coupon code functionality
        const copyButtons = document.querySelectorAll('.copy-code');
        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    // Change button appearance temporarily to indicate success
                    const originalHTML = this.innerHTML;
                    this.innerHTML = `
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    `;
                    this.classList.remove('text-cyan-400', 'hover:text-cyan-300');
                    this.classList.add('text-green-400');
                    
                    // Show tooltip
                    const tooltip = document.createElement('div');
                    tooltip.className = 'absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black text-white text-xs rounded whitespace-nowrap';
                    tooltip.textContent = 'Code copié!';
                    this.style.position = 'relative';
                    this.appendChild(tooltip);
                    
                    // Reset after animation
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.add('text-cyan-400', 'hover:text-cyan-300');
                        this.classList.remove('text-green-400');
                        if (tooltip) tooltip.remove();
                    }, 1500);
                });
            });
        }
    });
</script>
@endpush
@endsection
