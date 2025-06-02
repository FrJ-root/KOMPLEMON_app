<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KOMPLEMON - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        .hex-pattern {
            background: linear-gradient(120deg, #000 0%, transparent 50%),
                linear-gradient(240deg, #000 0%, transparent 50%),
                linear-gradient(360deg, #000 0%, transparent 50%);
            background-size: 10px 10px;
        }
        .typing::after {
            content: '|';
            animation: blink 1s step-end infinite;
        }
        @keyframes blink {
            from, to {
                opacity: 1
            }
            50% {
                opacity: 0
            }
        }
        .status-pulse {
            animation: statusPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes statusPulse {
            0%, 100% {
                background-color: rgba(52, 211, 153, 0.2);
            }
            50% {
                background-color: rgba(52, 211, 153, 0.4);
            }
        }
        body {
            font-family: 'Courier New', monospace;
        }
        #logoutPopup {
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            font-family: 'Courier New', monospace;
        }
        .logout-popup-inner {
            background-color: rgba(0, 0, 0, 0.85);
            color: #00FF00;
            border: 2px solid #00FF00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
            width: 300px;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            position: relative;
        }
        .logout-popup-inner h2 {
            font-size: 1.5rem;
            letter-spacing: 2px;
            color: #00FF00;
            text-transform: uppercase;
            text-shadow: 0 0 5px #00FF00, 0 0 10px #00FF00;
        }
        .logout-popup-inner p {
            color: #66FF66;
            margin-bottom: 20px;
        }
        .logout-popup-inner .flex {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .logout-popup-inner button {
            background-color: transparent;
            color: #00FF00;
            border: 2px solid #00FF00;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            letter-spacing: 1px;
            border-radius: 5px;
        }
        .logout-popup-inner button:hover {
            background-color: #00FF00;
            color: #000000;
            text-shadow: 0 0 5px #000000, 0 0 10px #000000;
        }
        .logout-popup-inner button:active {
            transform: scale(0.98);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-900">
    @if(session('welcome_admin'))
    <div id="welcomePopup" class="fixed inset-0 bg-black bg-opacity-90 flex justify-center items-center z-50">
        <div class="welcome-popup-inner text-center">
            <h2 class="text-3xl font-bold text-green-400 mb-4 typing-effect">Welcome {{ Auth::user()->name }}</h2>
            <p class="text-green-300 text-lg mb-6 fade-in-effect">System Initializing...</p>
            <p class="text-gray-500 text-sm fade-in-effect-delay">Access Granted. Enjoy your session.</p>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const welcomePopup = document.getElementById('welcomePopup');        
                welcomePopup.style.display = 'flex';
                setTimeout(() => {
                    welcomePopup.classList.add('fade-out');
                    setTimeout(() => {
                        welcomePopup.remove();
                    }, 1000);
                }, 8000);
            });
        </script>
        <style>
            @keyframes typing {
                from { width: 0; }
                to { width: 100%; }
            }
            .typing-effect {
                overflow: hidden;
                white-space: nowrap;
                margin: 0 auto;
                letter-spacing: 0.15em;
                animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            .fade-in-effect {
                opacity: 0;
                animation: fadeIn 2s ease-in 3.5s forwards;
            }
            .fade-in-effect-delay {
                opacity: 0;
                animation: fadeIn 2s ease-in 5.5s forwards;
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
            .fade-out {
                animation: fadeOut 1s ease-in forwards;
            }
        </style>
    </div>
    @endif

    <div id="dashboard-content">
        <!-- Admin Portal Header -->
        <div class="admin-portal bg-gradient-to-r from-gray-900 via-black to-gray-900 relative">
            <div class="hex-pattern absolute inset-0 opacity-5"></div>
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center bg-black/50 rounded-lg px-4 py-2 border border-purple-500/20">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="ml-2 text-gray-300 font-medium">Admin Portal</span>
                        </div>
                        <div class="hidden md:flex items-center space-x-2">
                            <div class="h-2 w-2 rounded-full status-pulse"></div>
                            <span class="text-green-400 text-sm">System Status: Operational</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="bg-black/30 rounded-lg px-4 py-2 text-sm">
                            <span class="text-gray-400">Session ID:</span>
                            <span class="text-purple-400 ml-2 font-mono">{{ substr(md5(session()->getId()), 0, 6) }}</span>
                        </div>
                        <div class="flex items-center bg-black/30 rounded-lg px-4 py-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-green-400 text-sm font-medium typing">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex">
            <!-- Sidebar -->
            <div id="sidebar" class="w-64 bg-black min-h-screen p-6">
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-purple-600 rounded flex items-center justify-center text-white">
                        <code class="text-sm">&lt;/&gt;</code>
                    </div>
                    <span class="text-xl font-bold text-purple-600">KOMPLEMON</span>
                </div>

                <nav class="space-y-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/dashboard') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/products*') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Produits</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/categories*') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Catégories</span>
                    </a>

                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/orders*') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Commandes</span>
                    </a>

                    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/customers*') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Clients</span>
                    </a>

                    <a href="{{ route('admin.statistics.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/statistics*') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Statistiques</span>
                    </a>

                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/users*') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Utilisateurs</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 {{ request()->is('admin/settings*') ? 'text-gray-200' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Paramètres</span>
                    </a>
                </nav>

                <a href="#" onclick="toggleLogoutPopup()" class="flex items-center gap-3 text-gray-400 hover:text-gray-200 mt-8">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Déconnexion</span>
                </a>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-8">
                @yield('content')
            </div>
        </div>

        <!-- Logout Popup -->
        <div id="logoutPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
            <div class="logout-popup-inner">
                <h2 class="text-xl font-bold mb-4">Confirmation de déconnexion</h2>
                <p class="mb-4">Êtes-vous sûr de vouloir vous déconnecter?</p>
                <p class="text-gray-500 mb-6">Votre session sera terminée.</p>
                <div class="flex justify-center space-x-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded hover:bg-green-600 transition-colors">Confirmer</button>
                    </form>
                    <button onclick="toggleLogoutPopup()" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLogoutPopup() {
            const popup = document.getElementById('logoutPopup');
            popup.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>