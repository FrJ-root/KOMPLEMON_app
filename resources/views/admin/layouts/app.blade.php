<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KOMPLEMON Admin</title>
    <style>
        /* Base styles */
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #4f46e5;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
            --info: #3b82f6;
            --light: #f3f4f6;
            --dark: #1f2937;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            line-height: 1.5;
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: 260px;
            background-color: var(--dark);
            color: white;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-list {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: var(--primary);
        }
        
        .nav-icon {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
        }
        
        /* Main content styles */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .welcome {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-name {
            font-weight: 500;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        /* Modal/Popup styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        .modal-title {
            margin-top: 0;
            color: #ef4444;
        }
        
        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }
        
        /* Media queries */
        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar-header {
                padding: 0 0.5rem;
                margin-bottom: 1rem;
            }
            
            .logo span {
                display: none;
            }
            
            .nav-link {
                padding: 0.75rem;
                justify-content: center;
            }
            
            .nav-icon {
                margin-right: 0;
                font-size: 1.25rem;
            }
            
            .nav-text {
                display: none;
            }
            
            .main-content {
                margin-left: 80px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .sidebar.active {
                width: 260px;
                padding: 1.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="/admin/dashboard" class="logo">
                    <span>📦</span>
                    <span>KOMPLEMON</span>
                </a>
            </div>
            
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                
                <!-- Product Management Links - visible to all but with permission checks -->
                <li class="nav-item">
                    <a href="/admin/products" 
                       class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}"
                       data-requires-role="gestionnaire_produits"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">🛍️</span>
                        <span class="nav-text">Produits</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/categories" 
                       class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}"
                       data-requires-role="gestionnaire_produits" 
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">🏷️</span>
                        <span class="nav-text">Catégories</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/media" 
                       class="nav-link {{ request()->is('admin/media*') ? 'active' : '' }}"
                       data-requires-role="gestionnaire_produits"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">🖼️</span>
                        <span class="nav-text">Médiathèque</span>
                    </a>
                </li>
                
                <!-- Order Management Links - visible to all but with permission checks -->
                <li class="nav-item">
                    <a href="/admin/orders" 
                       class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}"
                       data-requires-role="gestionnaire_commandes"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">📋</span>
                        <span class="nav-text">Commandes</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/customers" 
                       class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}"
                       data-requires-role="gestionnaire_commandes"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">👥</span>
                        <span class="nav-text">Clients</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/export" 
                       class="nav-link {{ request()->is('admin/export*') ? 'active' : '' }}"
                       data-requires-role="gestionnaire_commandes"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">📤</span>
                        <span class="nav-text">Exporter</span>
                    </a>
                </li>
                
                <!-- Content Management Links - visible to all but with permission checks -->
                <li class="nav-item">
                    <a href="/admin/articles" 
                       class="nav-link {{ request()->is('admin/articles*') ? 'active' : '' }}"
                       data-requires-role="editeur_contenu"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">📝</span>
                        <span class="nav-text">Articles</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/testimonials" 
                       class="nav-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}"
                       data-requires-role="editeur_contenu"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">💬</span>
                        <span class="nav-text">Témoignages</span>
                    </a>
                </li>
                
                <!-- Administrator-only Links - visible to all but with permission checks -->
                <li class="nav-item">
                    <a href="/admin/coupons" 
                       class="nav-link {{ request()->is('admin/coupons*') ? 'active' : '' }}"
                       data-requires-role="administrateur"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">🎟️</span>
                        <span class="nav-text">Coupons</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/users" 
                       class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                       data-requires-role="administrateur"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">👤</span>
                        <span class="nav-text">Utilisateurs</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/settings" 
                       class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}"
                       data-requires-role="administrateur"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">⚙️</span>
                        <span class="nav-text">Paramètres</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/statistics" 
                       class="nav-link {{ request()->is('admin/statistics*') ? 'active' : '' }}"
                       data-requires-role="administrateur"
                       onclick="return checkPermission(event, this)">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">Statistiques</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="welcome">Bienvenue, {{ auth()->user()->name }} <span class="badge badge-primary">{{ ucfirst(auth()->user()->role) }}</span></div>
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->email }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Déconnexion</button>
                    </form>
                </div>
            </div>
            
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Permission Denied Modal -->
    <div id="permissionDeniedModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 class="modal-title">Accès refusé</h2>
            <p id="permissionMessage">Vous n'avez pas les permissions nécessaires pour accéder à cette section.</p>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal()">OK</button>
            </div>
        </div>
    </div>
    
    <script>
        // Current user role from PHP to JavaScript
        const userRole = "{{ auth()->user()->role }}";
        
        function checkPermission(event, element) {
            const requiredRole = element.getAttribute('data-requires-role');
            
            // Allow access if user has the required role
            if (userRole === requiredRole || userRole === 'administrateur') {
                return true;
            }
            
            // Otherwise prevent navigation and show modal
            event.preventDefault();
            
            // Get the nav text to display in the modal
            const navText = element.querySelector('.nav-text').textContent;
            
            // Set custom message based on the section
            let roleDisplay;
            switch(requiredRole) {
                case 'gestionnaire_produits':
                    roleDisplay = 'le gestionnaire de produits';
                    break;
                case 'gestionnaire_commandes':
                    roleDisplay = 'le gestionnaire de commandes';
                    break;
                case 'editeur_contenu':
                    roleDisplay = 'l\'éditeur de contenu';
                    break;
                case 'administrateur':
                    roleDisplay = 'l\'administrateur';
                    break;
                default:
                    roleDisplay = requiredRole;
            }
            
            document.getElementById('permissionMessage').textContent = 
                `Seul ${roleDisplay} peut accéder à la section "${navText}".`;
            
            // Show the modal
            document.getElementById('permissionDeniedModal').style.display = 'block';
            return false;
        }
        
        function closeModal() {
            document.getElementById('permissionDeniedModal').style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('permissionDeniedModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>