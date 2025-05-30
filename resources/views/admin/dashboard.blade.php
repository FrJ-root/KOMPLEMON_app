<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOMPLEMON Admin Dashboard</title>
    <style>
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
        
        .sidebar {
            width: 260px;
            background-color: var(--dark);
            color: white;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
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
        
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
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
            font-size: 1.5rem;
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
        
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .stat-title {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-products { color: var(--primary); }
        .stat-users { color: var(--secondary); }
        .stat-orders { color: var(--warning); }
        .stat-revenue { color: var(--success); }
        
        .stat-description {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .card {
            background-color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            text-align: left;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: #4b5563;
        }
        
        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #b91c1c; }
        
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        .view-all:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
                padding: 1rem 0;
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
            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
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
                    <a href="/admin/dashboard" class="nav-link active">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                
                <!-- All links are visible regardless of role -->
                <li class="nav-item">
                    <a href="/admin/products" class="nav-link"
                       data-requires-role="gestionnaire_produits"
                       onclick="{{ auth()->user()->role !== 'gestionnaire_produits' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">🛍️</span>
                        <span class="nav-text">Produits</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/categories" class="nav-link"
                       data-requires-role="gestionnaire_produits"
                       onclick="{{ auth()->user()->role !== 'gestionnaire_produits' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">🏷️</span>
                        <span class="nav-text">Catégories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/media" class="nav-link"
                       data-requires-role="gestionnaire_produits"
                       onclick="{{ auth()->user()->role !== 'gestionnaire_produits' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">🖼️</span>
                        <span class="nav-text">Médiathèque</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/orders" class="nav-link"
                       data-requires-role="gestionnaire_commandes"
                       onclick="{{ auth()->user()->role !== 'gestionnaire_commandes' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">📋</span>
                        <span class="nav-text">Commandes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/customers" class="nav-link"
                       data-requires-role="gestionnaire_commandes"
                       onclick="{{ auth()->user()->role !== 'gestionnaire_commandes' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">👥</span>
                        <span class="nav-text">Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/export" class="nav-link"
                       data-requires-role="gestionnaire_commandes"
                       onclick="{{ auth()->user()->role !== 'gestionnaire_commandes' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">📤</span>
                        <span class="nav-text">Exporter</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/articles" class="nav-link"
                       data-requires-role="editeur_contenu"
                       onclick="{{ auth()->user()->role !== 'editeur_contenu' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">📝</span>
                        <span class="nav-text">Articles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/testimonials" class="nav-link"
                       data-requires-role="editeur_contenu"
                       onclick="{{ auth()->user()->role !== 'editeur_contenu' && auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">💬</span>
                        <span class="nav-text">Témoignages</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/coupons" class="nav-link"
                       data-requires-role="administrateur"
                       onclick="{{ auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">🎟️</span>
                        <span class="nav-text">Coupons</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link"
                       data-requires-role="administrateur"
                       onclick="{{ auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">👤</span>
                        <span class="nav-text">Utilisateurs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/settings" class="nav-link"
                       data-requires-role="administrateur"
                       onclick="{{ auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">⚙️</span>
                        <span class="nav-text">Paramètres</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/statistics" class="nav-link"
                       data-requires-role="administrateur"
                       onclick="{{ auth()->user()->role !== 'administrateur' ? 'checkPermission(event, this)' : '' }}">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">Statistiques</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="welcome">Bienvenue, {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</div>
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
            
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Tableau de Bord</h2>
                    </div>
                    
                    <div class="card-body">
                        <!-- Role-based dashboard content -->
                        @if(auth()->user()->role === 'administrateur')
                            @include('admin.partials.admin_dashboard')
                        @elseif(auth()->user()->role === 'gestionnaire_produits')
                            @include('admin.partials.product_manager_dashboard')
                        @elseif(auth()->user()->role === 'gestionnaire_commandes')
                            @include('admin.partials.order_manager_dashboard')
                        @elseif(auth()->user()->role === 'editeur_contenu')
                            @include('admin.partials.content_editor_dashboard')
                        @endif
                        
                        <!-- Common quick links based on role -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Accès rapide</h3>
                            </div>
                            <div class="card-body">
                                <div class="quick-actions">
                                    @if(auth()->user()->role === 'administrateur')
                                        <a href="/admin/coupons/create" class="quick-action">
                                            <span class="quick-action-icon">🎟️</span>
                                            <span>Créer un coupon</span>
                                        </a>
                                        <a href="/admin/users/create" class="quick-action">
                                            <span class="quick-action-icon">👤</span>
                                            <span>Ajouter un utilisateur</span>
                                        </a>
                                        <a href="/admin/settings" class="quick-action">
                                            <span class="quick-action-icon">⚙️</span>
                                            <span>Paramètres du site</span>
                                        </a>
                                        <a href="/admin/statistics" class="quick-action">
                                            <span class="quick-action-icon">📊</span>
                                            <span>Voir les statistiques</span>
                                        </a>
                                    @endif
                                    
                                    @if(auth()->user()->role === 'administrateur' || auth()->user()->role === 'gestionnaire_produits')
                                        <a href="/admin/products/create" class="quick-action">
                                            <span class="quick-action-icon">🛍️</span>
                                            <span>Ajouter un produit</span>
                                        </a>
                                        <a href="/admin/categories/create" class="quick-action">
                                            <span class="quick-action-icon">🏷️</span>
                                            <span>Ajouter une catégorie</span>
                                        </a>
                                    @endif
                                    
                                    @if(auth()->user()->role === 'administrateur' || auth()->user()->role === 'gestionnaire_commandes')
                                        <a href="/admin/orders" class="quick-action">
                                            <span class="quick-action-icon">📋</span>
                                            <span>Gérer les commandes</span>
                                        </a>
                                        <a href="/admin/export" class="quick-action">
                                            <span class="quick-action-icon">📤</span>
                                            <span>Exporter les commandes</span>
                                        </a>
                                    @endif
                                    
                                    @if(auth()->user()->role === 'administrateur' || auth()->user()->role === 'editeur_contenu')
                                        <a href="/admin/articles/create" class="quick-action">
                                            <span class="quick-action-icon">📝</span>
                                            <span>Nouvel article</span>
                                        </a>
                                        <a href="/admin/testimonials" class="quick-action">
                                            <span class="quick-action-icon">💬</span>
                                            <span>Gérer les témoignages</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
