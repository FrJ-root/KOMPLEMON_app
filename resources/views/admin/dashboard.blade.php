@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Dashboard Welcome Screen -->
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-xl mb-6 p-8 overflow-hidden border border-purple-500/20">
        <div class="hex-pattern absolute inset-0 opacity-5"></div>
        
        <!-- Animated circuit lines -->
        <div class="absolute inset-0 circuit-animation opacity-10"></div>
        
        <div class="relative z-10">
            <div class="typing-container overflow-hidden mb-4">
                <h1 class="text-3xl md:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-600 terminal-text">
                    Welcome to KOMPLEMON Admin Portal
                </h1>
            </div>
            
            <div class="max-w-3xl">
                <p class="text-gray-300 mb-6 fade-in-element">
                    Access granted to administration system. Current security level: <span class="text-purple-400 font-semibold">Maximum</span>
                </p>
                
                <div class="stats-overview grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 fade-in-element-delay">
                    <div class="stat-card flex items-center p-4 bg-gray-800/50 border border-purple-500/10 rounded-lg hover:border-purple-500/30 transition-all hover:translate-y-[-2px]">
                        <div class="mr-4 bg-purple-500/10 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400">Recent Orders</div>
                            <div class="text-xl font-bold text-white">{{ \App\Models\Order::count() }}</div>
                        </div>
                    </div>
                    
                    <div class="stat-card flex items-center p-4 bg-gray-800/50 border border-purple-500/10 rounded-lg hover:border-purple-500/30 transition-all hover:translate-y-[-2px]">
                        <div class="mr-4 bg-purple-500/10 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400">Total Products</div>
                            <div class="text-xl font-bold text-white">{{ \App\Models\Product::count() }}</div>
                        </div>
                    </div>
                    
                    <div class="stat-card flex items-center p-4 bg-gray-800/50 border border-purple-500/10 rounded-lg hover:border-purple-500/30 transition-all hover:translate-y-[-2px]">
                        <div class="mr-4 bg-purple-500/10 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400">Active Users</div>
                            <div class="text-xl font-bold text-white">{{ \App\Models\User::count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="quick-actions fade-in-element-delay-2">
                <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('products.index') }}" class="action-card p-4 bg-gray-800/40 border border-purple-500/10 rounded-lg hover:bg-gray-800/80 hover:border-purple-500/30 transition-all flex flex-col items-center text-center">
                        <div class="icon-container mb-3 bg-purple-500/10 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm text-gray-300">Add Product</span>
                    </a>
                    
                    <a href="{{ route('orders.index') }}" class="action-card p-4 bg-gray-800/40 border border-purple-500/10 rounded-lg hover:bg-gray-800/80 hover:border-purple-500/30 transition-all flex flex-col items-center text-center">
                        <div class="icon-container mb-3 bg-purple-500/10 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="text-sm text-gray-300">View Orders</span>
                    </a>
                    
                    <a href="{{ route('admin.settings.index') }}" class="action-card p-4 bg-gray-800/40 border border-purple-500/10 rounded-lg hover:bg-gray-800/80 hover:border-purple-500/30 transition-all flex flex-col items-center text-center">
                        <div class="icon-container mb-3 bg-purple-500/10 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-sm text-gray-300">Settings</span>
                    </a>
                    
                    <a href="{{ route('admin.statistics.index') }}" class="action-card p-4 bg-gray-800/40 border border-purple-500/10 rounded-lg hover:bg-gray-800/80 hover:border-purple-500/30 transition-all flex flex-col items-center text-center">
                        <div class="icon-container mb-3 bg-purple-500/10 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-sm text-gray-300">Analytics</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Status Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="system-status bg-gray-800 rounded-lg p-6 border border-purple-500/10 md:col-span-2 scale-in-element">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                System Status
            </h2>
            
            <div class="space-y-4">
                <div class="status-item flex justify-between items-center p-3 bg-gray-900/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="h-2 w-2 rounded-full bg-green-500 mr-3 pulse-animation"></div>
                        <span class="text-gray-300">Database Connection</span>
                    </div>
                    <span class="text-green-400 text-sm">Operational</span>
                </div>
                
                <div class="status-item flex justify-between items-center p-3 bg-gray-900/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="h-2 w-2 rounded-full bg-green-500 mr-3 pulse-animation"></div>
                        <span class="text-gray-300">API Services</span>
                    </div>
                    <span class="text-green-400 text-sm">Operational</span>
                </div>
                
                <div class="status-item flex justify-between items-center p-3 bg-gray-900/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="h-2 w-2 rounded-full bg-green-500 mr-3 pulse-animation"></div>
                        <span class="text-gray-300">Payment Processing</span>
                    </div>
                    <span class="text-green-400 text-sm">Operational</span>
                </div>
                
                <div class="status-item flex justify-between items-center p-3 bg-gray-900/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="h-2 w-2 rounded-full bg-green-500 mr-3 pulse-animation"></div>
                        <span class="text-gray-300">Email Services</span>
                    </div>
                    <span class="text-green-400 text-sm">Operational</span>
                </div>
            </div>
        </div>
        
        <div class="recent-activity bg-gray-800 rounded-lg p-6 border border-purple-500/10 scale-in-element-delay">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Recent Activity
            </h2>
            
            <div class="space-y-4">
                <div class="activity-item p-3 bg-gray-900/50 rounded-lg flex items-start">
                    <div class="mr-3 mt-1 bg-blue-500/20 text-blue-400 rounded-full p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-300">New user registered</p>
                        <p class="text-xs text-gray-500">2 minutes ago</p>
                    </div>
                </div>
                
                <div class="activity-item p-3 bg-gray-900/50 rounded-lg flex items-start">
                    <div class="mr-3 mt-1 bg-green-500/20 text-green-400 rounded-full p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-300">New order placed</p>
                        <p class="text-xs text-gray-500">15 minutes ago</p>
                    </div>
                </div>
                
                <div class="activity-item p-3 bg-gray-900/50 rounded-lg flex items-start">
                    <div class="mr-3 mt-1 bg-purple-500/20 text-purple-400 rounded-full p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-300">Product updated</p>
                        <p class="text-xs text-gray-500">35 minutes ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'administrateur')
    @include('admin.components.welcome-modal')
@endif

<style>
    /* Terminal text effect */
    .terminal-text {
        position: relative;
        display: inline-block;
    }
    
    .terminal-text::after {
        content: '|';
        position: absolute;
        right: -10px;
        animation: blink 1s step-end infinite;
    }
    
    @keyframes blink {
        from, to { opacity: 1; }
        50% { opacity: 0; }
    }
    
    /* Circuit background animation */
    .circuit-animation {
        background-image: 
            linear-gradient(rgba(139, 92, 246, 0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(139, 92, 246, 0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        animation: circuitMove 20s linear infinite;
    }
    
    @keyframes circuitMove {
        0% { background-position: 0 0; }
        100% { background-position: 40px 40px; }
    }
    
    /* Fade in animations */
    .fade-in-element {
        animation: fadeIn 1s ease-in-out forwards 0.5s;
        opacity: 0;
    }
    
    .fade-in-element-delay {
        animation: fadeIn 1s ease-in-out forwards 1s;
        opacity: 0;
    }
    
    .fade-in-element-delay-2 {
        animation: fadeIn 1s ease-in-out forwards 1.5s;
        opacity: 0;
    }
    
    .scale-in-element {
        animation: scaleIn 0.5s ease-in-out forwards 1.5s;
        opacity: 0;
        transform: scale(0.95);
    }
    
    .scale-in-element-delay {
        animation: scaleIn 0.5s ease-in-out forwards 2s;
        opacity: 0;
        transform: scale(0.95);
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes scaleIn {
        from { 
            opacity: 0; 
            transform: scale(0.95);
        }
        to { 
            opacity: 1; 
            transform: scale(1);
        }
    }
    
    /* Pulse animation for status indicators */
    .pulse-animation {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* Modal animations */
    .typing-modal-effect {
        overflow: hidden;
        white-space: nowrap;
        margin: 0 auto;
        letter-spacing: 0.15em;
        animation: typing 2.5s steps(40, end), blink-caret 0.75s step-end infinite;
    }
    
    .fade-in-modal-effect {
        opacity: 0;
        animation: fadeIn 1s ease-in 2.5s forwards;
    }
    
    .fade-in-modal-effect-delay {
        opacity: 0;
        animation: fadeIn 1s ease-in 3.5s forwards;
    }
    
    .fade-in-modal-effect-delay-2 {
        opacity: 0;
        animation: fadeIn 1s ease-in 4.5s forwards;
    }
    
    .loading-progress {
        width: 0%;
        animation: loading 3s ease-in-out 4.5s forwards;
    }
    
    @keyframes typing {
        from { width: 0; }
        to { width: 100%; }
    }
    
    @keyframes loading {
        from { width: 0%; }
        to { width: 100%; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show welcome modal with delay
        const welcomeModal = document.getElementById('welcomeModal');
        
        if (welcomeModal) {
            setTimeout(() => {
                welcomeModal.classList.remove('opacity-0', 'pointer-events-none');
            }, 500);
            
            // Auto close modal after animations
            setTimeout(() => {
                welcomeModal.classList.add('opacity-0', 'pointer-events-none');
            }, 10000);
        }
    });
</script>
@endsection
