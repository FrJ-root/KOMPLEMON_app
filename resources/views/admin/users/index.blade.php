@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Gestion des Utilisateurs</h1>
            <a href="{{ route('users.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nouvel Utilisateur</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-purple-500/10">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Rechercher un utilisateur..." 
                       class="w-full bg-gray-900 text-white pl-10 pr-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                <button type="button" id="clearSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1">
                <select id="roleOrSearchFilter" class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none appearance-none bg-no-repeat bg-right"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23666\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M6 9l6 6 6-6\"/></svg>'); background-position: right 0.75rem center; background-size: 1rem;">
                    <option value="all">Tous les rôles</option>
                    <option value="gestionnaire_produits">Gestionnaire de produits</option>
                    <option value="gestionnaire_commandes">Gestionnaire de commandes</option>
                    <option value="editeur_contenu">Éditeur de contenu</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider cursor-pointer sortable-header" data-sort="name">
                            Nom
                            <span class="inline-block ml-1 sort-icon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider cursor-pointer sortable-header" data-sort="email">
                            Email
                            <span class="inline-block ml-1 sort-icon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider cursor-pointer sortable-header" data-sort="role">
                            Rôle
                            <span class="inline-block ml-1 sort-icon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider cursor-pointer sortable-header" data-sort="date">
                            Date d'inscription
                            <span class="inline-block ml-1 sort-icon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($users->where('role', '!=', 'administrateur') as $user)
                    <tr data-role="{{ $user->role }}" class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 flex-shrink-0 mr-3 bg-purple-600/20 rounded-full flex items-center justify-center text-purple-400 text-sm font-medium">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="text-sm font-medium text-white user-name">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded
                                @if($user->role === 'administrateur') bg-purple-500/20 text-purple-400
                                @elseif($user->role === 'gestionnaire_produits') bg-blue-500/20 text-blue-400
                                @elseif($user->role === 'gestionnaire_commandes') bg-green-500/20 text-green-400
                                @elseif($user->role === 'editeur_contenu') bg-yellow-500/20 text-yellow-400
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('users.edit', $user) }}" class="text-blue-400 hover:text-blue-300" title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                
                                <button type="button" class="text-purple-400 hover:text-purple-300" title="Permissions" 
                                        onclick="openPermissionsModal(this)"
                                        data-user-id="{{ $user->id }}" 
                                        data-user-name="{{ $user->name }}"
                                        data-user-role="{{ $user->role }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </button>
                                
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-400">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Permissions Modal -->
<div id="permissionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/20 w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Gérer les permissions: <span id="modalUserName" class="text-purple-400"></span></h2>
            <button onclick="closePermissionsModal()" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div id="rolePermissionsInfo" class="mb-4"></div>
        
        <form id="blockPermissionsForm" action="{{ route('users.block-permissions') }}" method="POST">
            @csrf
            <input type="hidden" id="userId" name="user_id">
            
            <div id="gestionnaire_produits_permissions" class="permission-group hidden space-y-4 mb-4">
                <h3 class="text-white font-medium">Permissions du gestionnaire de produits</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="block_manage_products" name="blocked_permissions[]" value="manage_products"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_manage_products" class="ml-2 text-gray-300">Gérer les produits</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="block_manage_categories" name="blocked_permissions[]" value="manage_categories"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_manage_categories" class="ml-2 text-gray-300">Gérer les catégories</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="block_manage_media" name="blocked_permissions[]" value="manage_media"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_manage_media" class="ml-2 text-gray-300">Gérer les médias</label>
                    </div>
                </div>
            </div>
            
            <div id="gestionnaire_commandes_permissions" class="permission-group hidden space-y-4 mb-4">
                <h3 class="text-white font-medium">Permissions du gestionnaire de commandes</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="block_manage_orders" name="blocked_permissions[]" value="manage_orders"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_manage_orders" class="ml-2 text-gray-300">Gérer les commandes</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="block_export_orders" name="blocked_permissions[]" value="export_orders"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_export_orders" class="ml-2 text-gray-300">Exporter les commandes</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="block_manage_customers" name="blocked_permissions[]" value="manage_customers"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_manage_customers" class="ml-2 text-gray-300">Gérer les clients</label>
                    </div>
                </div>
            </div>
            
            <div id="editeur_contenu_permissions" class="permission-group hidden space-y-4 mb-4">
                <h3 class="text-white font-medium">Permissions de l'éditeur de contenu</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="block_manage_articles" name="blocked_permissions[]" value="manage_articles"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_manage_articles" class="ml-2 text-gray-300">Gérer les articles</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="block_manage_testimonials" name="blocked_permissions[]" value="manage_testimonials"
                               class="w-4 h-4 text-purple-600 border-gray-700 rounded focus:ring-purple-500 focus:ring-offset-gray-800">
                        <label for="block_manage_testimonials" class="ml-2 text-gray-300">Gérer les témoignages</label>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="block_reason" class="block text-gray-300 mb-2">Raison du blocage (optionnel)</label>
                <textarea id="block_reason" name="block_reason" rows="3"
                          class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                          placeholder="Indiquez pourquoi vous bloquez ces permissions..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closePermissionsModal()" class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="saveBlockedPermissions()" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Sortable headers */
    .sortable-header {
        position: relative;
        cursor: pointer;
        user-select: none;
        transition: color 0.2s;
    }
    
    .sortable-header:hover {
        color: #a5b4fc;
    }
    
    .sortable-header.sort-asc .sort-icon svg {
        transform: rotate(0deg);
        color: #a5b4fc;
    }
    
    .sortable-header.sort-desc .sort-icon svg {
        transform: rotate(180deg);
        color: #a5b4fc;
    }
</style>

@push('scripts')
<script>
    // Search and filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearButton = document.getElementById('clearSearch');
        const roleFilter = document.getElementById('roleOrSearchFilter');
        const tableRows = document.querySelectorAll('tbody tr');
        const sortHeaders = document.querySelectorAll('.sortable-header');
        
        let currentSort = { column: null, direction: 'asc' };
        
        // Search and filter function
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedRole = roleFilter.value;
            
            tableRows.forEach(row => {
                if (row.querySelector('td[colspan]')) return; // Skip "no results" row
                
                const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                const email = row.cells[1]?.textContent.toLowerCase() || '';
                const role = row.getAttribute('data-role') || '';
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = selectedRole === 'all' || role === selectedRole;
                
                row.style.display = matchesSearch && matchesRole ? '' : 'none';
            });
        }
        
        // Sort table function
        function sortTable(column, direction) {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not([colspan])'));
            
            const sortedRows = rows.sort((a, b) => {
                let valueA, valueB;
                
                if (column === 'name') {
                    valueA = a.querySelector('.user-name')?.textContent || '';
                    valueB = b.querySelector('.user-name')?.textContent || '';
                } else if (column === 'email') {
                    valueA = a.cells[1]?.textContent || '';
                    valueB = b.cells[1]?.textContent || '';
                } else if (column === 'role') {
                    valueA = a.cells[2]?.textContent.trim() || '';
                    valueB = b.cells[2]?.textContent.trim() || '';
                } else if (column === 'date') {
                    // Convert DD/MM/YYYY to sortable format
                    const dateA = a.cells[3]?.textContent || '';
                    const dateB = b.cells[3]?.textContent || '';
                    valueA = dateA.split('/').reverse().join('');
                    valueB = dateB.split('/').reverse().join('');
                }
                
                return direction === 'asc' 
                    ? valueA.localeCompare(valueB, undefined, { sensitivity: 'base' })
                    : valueB.localeCompare(valueA, undefined, { sensitivity: 'base' });
            });
            
            // Remove all rows
            rows.forEach(row => row.remove());
            
            // Add sorted rows
            sortedRows.forEach(row => tbody.appendChild(row));
        }
        
        // Event listeners
        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
        
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            filterTable();
        });
        
        // Sort headers click event
        sortHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const column = this.getAttribute('data-sort');
                const direction = currentSort.column === column && currentSort.direction === 'asc' ? 'desc' : 'asc';
                
                // Reset all headers
                sortHeaders.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
                
                // Update current header
                this.classList.add(direction === 'asc' ? 'sort-asc' : 'sort-desc');
                
                // Update current sort state
                currentSort = { column, direction };
                
                // Sort the table
                sortTable(column, direction);
            });
        });
    });
    
    // Permissions modal
    function openPermissionsModal(button) {
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        const userRole = button.getAttribute('data-user-role');
        
        document.getElementById('userId').value = userId;
        document.getElementById('modalUserName').textContent = userName;
        
        // Reset form
        document.getElementById('blockPermissionsForm').reset();
        
        // Hide all permission groups
        document.querySelectorAll('.permission-group').forEach(group => {
            group.classList.add('hidden');
        });
        
        // Show appropriate role permissions
        if (userRole === 'gestionnaire_produits') {
            document.getElementById('gestionnaire_produits_permissions').classList.remove('hidden');
            document.getElementById('rolePermissionsInfo').innerHTML = `
                <div class="bg-blue-900/20 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-lg flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-medium">Permissions du gestionnaire de produits</p>
                        <p class="text-sm">Par défaut, ce rôle peut gérer les produits, les catégories et les médias. Sélectionnez les permissions que vous souhaitez bloquer.</p>
                    </div>
                </div>
            `;
        } else if (userRole === 'gestionnaire_commandes') {
            document.getElementById('gestionnaire_commandes_permissions').classList.remove('hidden');
            document.getElementById('rolePermissionsInfo').innerHTML = `
                <div class="bg-green-900/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-medium">Permissions du gestionnaire de commandes</p>
                        <p class="text-sm">Par défaut, ce rôle peut gérer les commandes, les exporter et gérer les clients. Sélectionnez les permissions que vous souhaitez bloquer.</p>
                    </div>
                </div>
            `;
        } else if (userRole === 'editeur_contenu') {
            document.getElementById('editeur_contenu_permissions').classList.remove('hidden');
            document.getElementById('rolePermissionsInfo').innerHTML = `
                <div class="bg-yellow-900/20 border border-yellow-500/30 text-yellow-400 px-4 py-3 rounded-lg flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-medium">Permissions de l'éditeur de contenu</p>
                        <p class="text-sm">Par défaut, ce rôle peut gérer les articles et les témoignages. Sélectionnez les permissions que vous souhaitez bloquer.</p>
                    </div>
                </div>
            `;
        }
        
        // Show modal
        document.getElementById('permissionsModal').classList.remove('hidden');
    }
    
    function closePermissionsModal() {
        document.getElementById('permissionsModal').classList.add('hidden');
    }
    
    function saveBlockedPermissions() {
        document.getElementById('blockPermissionsForm').submit();
    }
    
    // Close modal when clicking outside
    document.getElementById('permissionsModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closePermissionsModal();
        }
    });
</script>
@endpush
@endsection
