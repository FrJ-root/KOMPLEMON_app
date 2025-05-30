@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Gestion des Utilisateurs</h1>
        <div class="page-actions">
            <a href="{{ route('users.create') }}" class="btn btn-create animated-btn">
                <i class="icon-plus"></i> Nouvel Utilisateur
            </a>
        </div>
    </div>

    <div class="filter-bar">
        <div class="filter-group" style="flex:1;">
            <div class="search-box">
                <i class="icon-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un utilisateur...">
                <button type="button" class="clear-search" id="clearSearch">
                    <i class="icon-times"></i>
                </button>
            </div>
        </div>
        <div class="filter-group" style="flex:1;">
            <select id="roleOrSearchFilter" class="select-filter">
                <option value="all">Tous les rôles</option>
                <option value="gestionnaire_produits">Gestionnaire de produits</option>
                <option value="gestionnaire_commandes">Gestionnaire de commandes</option>
                <option value="editeur_contenu">Éditeur de contenu</option>
            </select>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table users-table sortable">
                <thead>
                    <tr>
                        <th class="sortable-header" data-sort="name">
                            Nom
                            <span class="sort-icon">
                                <i class="icon-chevron-up"></i>
                                <i class="icon-chevron-down"></i>
                            </span>
                        </th>
                        <th class="sortable-header" data-sort="email">
                            Email
                            <span class="sort-icon">
                                <i class="icon-chevron-up"></i>
                                <i class="icon-chevron-down"></i>
                            </span>
                        </th>
                        <th class="sortable-header" data-sort="role">
                            Rôle
                            <span class="sort-icon">
                                <i class="icon-chevron-up"></i>
                                <i class="icon-chevron-down"></i>
                            </span>
                        </th>
                        <th class="sortable-header" data-sort="date">
                            Date d'inscription
                            <span class="sort-icon">
                                <i class="icon-chevron-up"></i>
                                <i class="icon-chevron-down"></i>
                            </span>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users->where('role', '!=', 'administrateur') as $user)
                    <tr data-role="{{ $user->role }}">
                        <td>
                            <div class="user-info-cell">
                                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                <div class="user-name">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $user->role }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="btn-action block-permissions" 
                                        title="Bloquer des permissions" 
                                        data-user-id="{{ $user->id }}" 
                                        data-user-name="{{ $user->name }}"
                                        data-user-role="{{ $user->role }}"
                                        onclick="openPermissionsModal(this)">
                                    <i class="icon-lock"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Permissions Modal -->
<div id="permissionsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Gérer les permissions: <span id="modalUserName"></span></h2>
            <span class="close" onclick="closePermissionsModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="rolePermissionsInfo" class="mb-4"></div>
            
            <form id="blockPermissionsForm" action="{{ route('users.block-permissions') }}" method="POST">
                @csrf
                <input type="hidden" id="userId" name="user_id">
                
                <div id="gestionnaire_produits_permissions" class="permission-group" style="display: none;">
                    <h3 class="permission-group-title">Permissions du gestionnaire de produits</h3>
                    <div class="permission-options">
                        <div class="permission-option">
                            <input type="checkbox" id="block_manage_products" name="blocked_permissions[]" value="manage_products">
                            <label for="block_manage_products">Gérer les produits</label>
                        </div>
                        <div class="permission-option">
                            <input type="checkbox" id="block_manage_categories" name="blocked_permissions[]" value="manage_categories">
                            <label for="block_manage_categories">Gérer les catégories</label>
                        </div>
                        <div class="permission-option">
                            <input type="checkbox" id="block_manage_media" name="blocked_permissions[]" value="manage_media">
                            <label for="block_manage_media">Gérer les médias</label>
                        </div>
                    </div>
                </div>
                
                <div id="gestionnaire_commandes_permissions" class="permission-group" style="display: none;">
                    <h3 class="permission-group-title">Permissions du gestionnaire de commandes</h3>
                    <div class="permission-options">
                        <div class="permission-option">
                            <input type="checkbox" id="block_manage_orders" name="blocked_permissions[]" value="manage_orders">
                            <label for="block_manage_orders">Gérer les commandes</label>
                        </div>
                        <div class="permission-option">
                            <input type="checkbox" id="block_export_orders" name="blocked_permissions[]" value="export_orders">
                            <label for="block_export_orders">Exporter les commandes</label>
                        </div>
                        <div class="permission-option">
                            <input type="checkbox" id="block_manage_customers" name="blocked_permissions[]" value="manage_customers">
                            <label for="block_manage_customers">Gérer les clients</label>
                        </div>
                    </div>
                </div>
                
                <div id="editeur_contenu_permissions" class="permission-group" style="display: none;">
                    <h3 class="permission-group-title">Permissions de l'éditeur de contenu</h3>
                    <div class="permission-options">
                        <div class="permission-option">
                            <input type="checkbox" id="block_manage_articles" name="blocked_permissions[]" value="manage_articles">
                            <label for="block_manage_articles">Gérer les articles</label>
                        </div>
                        <div class="permission-option">
                            <input type="checkbox" id="block_manage_testimonials" name="blocked_permissions[]" value="manage_testimonials">
                            <label for="block_manage_testimonials">Gérer les témoignages</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-4">
                    <label for="block_reason" class="form-label">Raison du blocage (optionnel)</label>
                    <textarea id="block_reason" name="block_reason" class="form-control" rows="3" placeholder="Indiquez pourquoi vous bloquez ces permissions..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closePermissionsModal()">Annuler</button>
            <button type="button" class="btn btn-primary" onclick="saveBlockedPermissions()">Enregistrer</button>
        </div>
    </div>
</div>

<style>
    /* Table styles */
    .table-responsive {
        overflow-x: auto;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .users-table th {
        background-color: #f8fafc;
        color: #1e293b;
        font-weight: 600;
        text-align: left;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .users-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    
    .users-table tr:hover {
        background-color: #f8fafc;
    }
    
    /* User info cell */
    .user-info-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        background-color: #3b82f6;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .user-name {
        font-weight: 500;
    }
    
    /* Role badges */
    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .role-administrateur {
        background-color: #fecaca;
        color: #b91c1c;
    }
    
    .role-gestionnaire_produits {
        background-color: #bae6fd;
        color: #0369a1;
    }
    
    .role-gestionnaire_commandes {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .role-editeur_contenu {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 0.375rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        background: none;
    }
    
    .btn-action.edit {
        color: #0369a1;
    }
    
    .btn-action.edit:hover {
        background-color: #bae6fd;
    }
    
    .btn-action.delete {
        color: #b91c1c;
    }
    
    .btn-action.delete:hover {
        background-color: #fecaca;
    }
    
    /* Block permission button */
    .btn-action.block-permissions {
        color: white;
        background-color: #6366f1;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
        position: relative;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    
    .btn-action.block-permissions:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        z-index: 0;
    }
    
    .btn-action.block-permissions i {
        position: relative;
        z-index: 1;
        font-size: 1rem;
    }
    
    .btn-action.block-permissions:hover {
        transform: translateY(-3px) scale(1.05);
        background-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .btn-action.block-permissions:after {
        content: "Gérer permissions";
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #1e293b;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        pointer-events: none;
    }
    
    .btn-action.block-permissions:hover:after {
        opacity: 1;
        visibility: visible;
        top: -35px;
    }
    
    .btn-action.block-permissions:active {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.2);
    }
    
    /* Icons */
    .icon-lock {
        display: inline-block;
        width: 16px;
        height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 1C8.676 1 6 3.676 6 7v2H4v14h16V9h-2V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v2H8V7c0-2.276 1.724-4 4-4zm-6 8h12v10H6V11z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
    }
    
    /* Make page header flex */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .page-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    /* Enhanced filter bar styles */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .filter-group {
        flex: 1;
        min-width: 200px;
    }
    
    /* Enhanced search box */
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .search-box i.icon-search {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1rem;
        pointer-events: none;
    }
    
    .search-box input {
        width: 100%;
        padding: 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
        background-color: #f8fafc;
    }
    
    .search-box input:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: white;
    }
    
    .clear-search {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        display: none;
        font-size: 0.875rem;
    }
    
    .clear-search:hover {
        color: #64748b;
    }
    
    /* Enhanced select filter */
    .select-filter {
        width: 100%;
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: #f8fafc;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.5rem;
        transition: all 0.2s;
    }
    
    .select-filter:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: white;
    }
    
    /* Sortable table headers */
    .sortable-header {
        cursor: pointer;
        position: relative;
        user-select: none;
        white-space: nowrap;
        transition: background-color 0.2s;
    }
    
    .sortable-header:hover {
        background-color: #f1f5f9;
    }
    
    .sort-icon {
        display: inline-flex;
        flex-direction: column;
        margin-left: 0.5rem;
        font-size: 0.625rem;
        color: #94a3b8;
        position: relative;
        top: 1px;
        height: 10px;
        width: 10px;
        opacity: 0.5;
    }
    
    .sortable-header.sort-asc .icon-chevron-up,
    .sortable-header.sort-desc .icon-chevron-down {
        color: #3b82f6;
        opacity: 1;
    }
    
    .sortable-header.sort-active .sort-icon {
        opacity: 1;
    }
    
    /* Icons */
    .icon-chevron-up:before { content: "▲"; }
    .icon-chevron-down:before { content: "▼"; }
    .icon-times:before { content: "×"; }
</style>

<script>
    // Search and filter functionality
    document.getElementById('searchInput').addEventListener('input', filterUsers);
    document.getElementById('roleOrSearchFilter').addEventListener('change', filterUsers);

    function filterUsers() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const roleFilter = document.getElementById('roleOrSearchFilter').value;
        
        document.querySelectorAll('.users-table tbody tr').forEach(row => {
            if (row.querySelector('td.text-center')) return; // Skip "no users found" row
            
            const name = row.querySelector('.user-name').textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const role = row.getAttribute('data-role');
            
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesRole = roleFilter === 'all' || role === roleFilter;
            
            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }

    // Confirm delete
    function confirmDelete(button) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
            button.closest('form').submit();
        }
    }

    // Close alerts
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', () => {
            button.closest('.alert').remove();
        });
    });
    
    // Permission modal functions
    function openPermissionsModal(button) {
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        const userRole = button.getAttribute('data-user-role');
        
        document.getElementById('userId').value = userId;
        document.getElementById('modalUserName').textContent = userName;
        
        // Reset form
        document.getElementById('blockPermissionsForm').reset();
        
        // Hide all permission groups first
        document.querySelectorAll('.permission-group').forEach(group => {
            group.style.display = 'none';
        });
        
        // Show appropriate permission group based on role
        if (userRole === 'gestionnaire_produits') {
            document.getElementById('gestionnaire_produits_permissions').style.display = 'block';
            document.getElementById('rolePermissionsInfo').innerHTML = `
                <div class="alert alert-info">
                    <i class="icon-info-circle"></i>
                    <div>
                        <strong>Permissions du gestionnaire de produits</strong>
                        <p>Par défaut, ce rôle peut gérer les produits, les catégories et les médias. Sélectionnez les permissions que vous souhaitez bloquer.</p>
                    </div>
                </div>
            `;
        } else if (userRole === 'gestionnaire_commandes') {
            document.getElementById('gestionnaire_commandes_permissions').style.display = 'block';
            document.getElementById('rolePermissionsInfo').innerHTML = `
                <div class="alert alert-info">
                    <i class="icon-info-circle"></i>
                    <div>
                        <strong>Permissions du gestionnaire de commandes</strong>
                        <p>Par défaut, ce rôle peut gérer les commandes, les exporter et gérer les clients. Sélectionnez les permissions que vous souhaitez bloquer.</p>
                    </div>
                </div>
            `;
        } else if (userRole === 'editeur_contenu') {
            document.getElementById('editeur_contenu_permissions').style.display = 'block';
            document.getElementById('rolePermissionsInfo').innerHTML = `
                <div class="alert alert-info">
                    <i class="icon-info-circle"></i>
                    <div>
                        <strong>Permissions de l'éditeur de contenu</strong>
                        <p>Par défaut, ce rôle peut gérer les articles et les témoignages. Sélectionnez les permissions que vous souhaitez bloquer.</p>
                    </div>
                </div>
            `;
        }
        
        // Show the modal
        document.getElementById('permissionsModal').style.display = 'block';
    }
    
    function closePermissionsModal() {
        document.getElementById('permissionsModal').style.display = 'none';
    }
    
    function saveBlockedPermissions() {
        // In a real implementation, this would submit the form with AJAX
        // For now, we'll just submit the form normally
        document.getElementById('blockPermissionsForm').submit();
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('permissionsModal');
        if (event.target === modal) {
            closePermissionsModal();
        }
    }
    
    // Enhanced filtering and sorting
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearButton = document.getElementById('clearSearch');
        const roleOrSearchFilter = document.getElementById('roleOrSearchFilter');
        const tableRows = Array.from(document.querySelectorAll('.users-table tbody tr'));
        const sortHeaders = document.querySelectorAll('.sortable-header');
        
        let currentSort = { column: null, direction: 'asc' };
        
        // Unified filtering function
        function filterTableRows() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedRole = roleOrSearchFilter.value;

            tableRows.forEach(row => {
                if (row.querySelector('td.text-center')) return; // Skip "no users found" row
                
                const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                const email = row.cells[1]?.textContent.toLowerCase() || '';
                const role = row.getAttribute('data-role') || '';
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = selectedRole === 'all' || role === selectedRole;
                
                row.style.display = matchesSearch && matchesRole ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', function() {
            filterTableRows();
            clearButton.style.display = searchInput.value.length > 0 ? 'block' : 'none';
        });

        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            filterTableRows();
            clearButton.style.display = 'none';
        });

        roleOrSearchFilter.addEventListener('change', filterTableRows);

        // Sorting functionality
        sortHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const column = this.getAttribute('data-sort');
                const direction = currentSort.column === column && currentSort.direction === 'asc' ? 'desc' : 'asc';
                
                // Update active sort header styles
                sortHeaders.forEach(h => h.classList.remove('sort-active', 'sort-asc', 'sort-desc'));
                this.classList.add('sort-active', direction === 'asc' ? 'sort-asc' : 'sort-desc');
                
                // Update current sort state
                currentSort = { column, direction };
                
                // Sort the table rows
                sortTable(column, direction);
            });
        });
        
        function sortTable(column, direction) {
            const tbody = document.querySelector('.users-table tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not([colspan])'));
            
            const sortedRows = rows.sort((a, b) => {
                let aValue, bValue;
                
                if (column === 'name') {
                    aValue = a.querySelector('.user-name')?.textContent || '';
                    bValue = b.querySelector('.user-name')?.textContent || '';
                } else if (column === 'email') {
                    aValue = a.cells[1]?.textContent || '';
                    bValue = b.cells[1]?.textContent || '';
                } else if (column === 'role') {
                    aValue = a.cells[2]?.textContent.trim() || '';
                    bValue = b.cells[2]?.textContent.trim() || '';
                } else if (column === 'date') {
                    aValue = a.cells[3]?.textContent || '';
                    bValue = b.cells[3]?.textContent || '';
                    
                    // Convert DD/MM/YYYY to sortable format
                    aValue = aValue.split('/').reverse().join('');
                    bValue = bValue.split('/').reverse().join('');
                }
                
                // Direction modifier
                const modifier = direction === 'asc' ? 1 : -1;
                
                // Case-insensitive comparison
                return modifier * aValue.localeCompare(bValue, undefined, { sensitivity: 'base' });
            });
            
            // Reorder the DOM
            sortedRows.forEach(row => {
                tbody.appendChild(row);
            });
        }
        
        // Initialize the clear button state
        clearButton.style.display = searchInput.value.length > 0 ? 'block' : 'none';
    });
</script>
@endsection
