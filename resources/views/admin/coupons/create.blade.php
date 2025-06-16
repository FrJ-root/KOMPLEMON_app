@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-900/70 via-black to-cyan-900/70 rounded-lg blur-sm"></div>
            <div class="relative bg-gray-900 border-2 border-purple-500/50 rounded-lg p-6 overflow-hidden shadow-lg">
                <div class="cyber-grid absolute inset-0 opacity-20"></div>
                
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-white relative z-10 cyberpunk-text">
                        Créer un coupon
                        <span class="absolute -bottom-1 left-0 h-1 bg-gradient-to-r from-purple-500 to-cyan-500 w-full"></span>
                    </h1>
                    <a href="{{ route('coupons.index') }}" class="cyber-button-small">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </a>
                </div>
                
                <div class="mt-4 flex items-center text-sm font-mono bg-black/50 p-2 rounded-md border border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="text-cyan-400 hover:text-cyan-300 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3"></path>
                        </svg>
                        SYSTEM:/
                    </a>
                    <div class="mx-2 text-green-500 terminal-cursor">&gt;</div>
                    <a href="{{ route('coupons.index') }}" class="text-cyan-400 hover:text-cyan-300 transition-colors">COUPONS</a>
                    <div class="mx-2 text-green-500 terminal-cursor">&gt;</div>
                    <span class="text-purple-300 typing-text">CREATE_NEW</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 shadow-xl rounded-lg overflow-hidden border-2 border-purple-500/30 relative">
            <div class="scanner-line absolute left-0 right-0 h-0.5 bg-cyan-400 shadow-glow z-10"></div>
            
            <div class="p-6">
                <form action="{{ route('coupons.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="mb-8 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-lg p-6 border border-purple-500/20 relative overflow-hidden">
                        <div class="absolute inset-0 cyber-grid opacity-10"></div>
                        <div class="flex flex-col md:flex-row gap-6 items-center justify-between relative">
                            <div class="flex-1">
                                <label for="code" class="block text-base font-medium text-white flex items-center mb-3">
                                    <span class="text-purple-400 mr-1">[</span>
                                    COUPON_CODE
                                    <span class="text-purple-400 ml-1">]</span>
                                    <span class="text-red-400 ml-1">*</span>
                                </label>
                                <div class="mt-1 relative group">
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/30 to-cyan-500/30 rounded-md blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                                    <input type="text" name="code" id="code" value="{{ old('code') }}" 
                                        class="relative block w-full rounded-md border-2 border-gray-700 bg-gray-800 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-white font-mono placeholder-gray-500 text-xl py-3 px-4 tracking-widest @error('code') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                                        placeholder="CYBER25" required>
                                </div>
                                <p class="mt-2 text-sm text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Format recommandé: lettres majuscules et chiffres, sans espaces.</span>
                                </p>
                            </div>
                            <div class="w-full md:w-auto">
                                <button type="button" id="generateCode" class="cyber-button-generate w-full md:w-auto py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                        </svg>
                                        <span>GÉNÉRER UN CODE</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="block text-base font-medium text-white flex items-center">
                                    <span class="text-purple-400 mr-1">[</span>
                                    TYPE_REDUCTION
                                    <span class="text-purple-400 ml-1">]</span>
                                    <span class="text-red-400 ml-1">*</span>
                                </label>
                                <div class="cyber-tabs p-1 bg-gray-800 border-2 border-gray-700 rounded-lg" x-data="{ activeTab: '{{ old('discount_type', 'percent') }}' }">
                                    <div class="flex relative">
                                        <div class="absolute inset-y-0 w-1/2 transition-all duration-300 ease-out-expo"
                                            :class="{'translate-x-0': activeTab === 'percent', 'translate-x-full': activeTab === 'amount'}">
                                            <div class="h-full w-full bg-gradient-to-r from-purple-800/60 to-cyan-800/60 rounded-md"></div>
                                        </div>
                                        
                                        <button type="button" @click="activeTab = 'percent'" 
                                                :class="{'text-white font-bold': activeTab === 'percent', 'text-gray-300 hover:text-white': activeTab !== 'percent'}"
                                                class="relative flex-1 py-3 px-4 text-sm font-medium rounded-md focus:outline-none transition-colors duration-200 ease-in-out flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V9a2 2 0 00-2-2m2 4v4a2 2 0 104 0v-1m-4-3H9m2 0h4m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Pourcentage
                                        </button>
                                        <button type="button" @click="activeTab = 'amount'" 
                                                :class="{'text-white font-bold': activeTab === 'amount', 'text-gray-300 hover:text-white': activeTab !== 'amount'}"
                                                class="relative flex-1 py-3 px-4 text-sm font-medium rounded-md focus:outline-none transition-colors duration-200 ease-in-out flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Montant fixe
                                        </button>
                                        <input type="hidden" name="discount_type" x-model="activeTab">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 bg-gray-800/50 p-4 rounded-lg border border-gray-700" x-show="activeTab === 'percent'">
                                <label for="discount_percent" class="block text-base font-medium text-white flex items-center">
                                    <span class="text-purple-400 mr-1">[</span>
                                    REDUCTION_POURCENTAGE
                                    <span class="text-purple-400 ml-1">]</span>
                                    <span class="text-red-400 ml-1">*</span>
                                </label>
                                <div class="mt-1 relative group">
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/30 to-cyan-500/30 rounded-md blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                                    <input type="number" name="discount_percent" id="discount_percent" 
                                        value="{{ old('discount_percent') }}" min="1" max="100"
                                        class="relative block w-full rounded-md border-2 border-gray-700 bg-gray-800 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-white font-mono placeholder-gray-500 text-base py-3 px-4 @error('discount_percent') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                                        placeholder="25">
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-cyan-400 font-bold">
                                        %
                                    </div>
                                </div>
                                @error('discount_percent')
                                    <p class="mt-2 text-sm text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2 bg-gray-800/50 p-4 rounded-lg border border-gray-700" x-show="activeTab === 'amount'">
                                <label for="discount_amount" class="block text-base font-medium text-white flex items-center">
                                    <span class="text-purple-400 mr-1">[</span>
                                    REDUCTION_MONTANT
                                    <span class="text-purple-400 ml-1">]</span>
                                    <span class="text-red-400 ml-1">*</span>
                                </label>
                                <div class="mt-1 relative group">
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/30 to-cyan-500/30 rounded-md blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-cyan-400 font-bold">
                                        €
                                    </div>
                                    <input type="number" name="discount_amount" id="discount_amount" 
                                        value="{{ old('discount_amount') }}" step="0.01" min="0.01"
                                        class="relative block w-full pl-7 rounded-md border-2 border-gray-700 bg-gray-800 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-white font-mono placeholder-gray-500 text-base py-3 px-4 @error('discount_amount') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                                        placeholder="10.00">
                                </div>
                                @error('discount_amount')
                                    <p class="mt-2 text-sm text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="description" class="block text-base font-medium text-white flex items-center">
                                    <span class="text-purple-400 mr-1">[</span>
                                    DESCRIPTION
                                    <span class="text-purple-400 ml-1">]</span>
                                </label>
                                <div class="mt-1 relative group">
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/30 to-cyan-500/30 rounded-md blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                                    <textarea id="description" name="description" rows="3" 
                                        class="relative block w-full rounded-md border-2 border-gray-700 bg-gray-800 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-white font-mono placeholder-gray-500 text-base py-3 px-4 @error('description') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                                        placeholder="Description optionnelle du coupon">{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="expires_at" class="block text-base font-medium text-white flex items-center">
                                    <span class="text-purple-400 mr-1">[</span>
                                    DATE_EXPIRATION
                                    <span class="text-purple-400 ml-1">]</span>
                                </label>
                                <div class="mt-1 relative group">
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/30 to-cyan-500/30 rounded-md blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                                    <input type="datetime-local" name="expires_at" id="expires_at" 
                                        value="{{ old('expires_at') }}" 
                                        class="relative block w-full rounded-md border-2 border-gray-700 bg-gray-800 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-white font-mono placeholder-gray-500 text-base py-3 px-4 @error('expires_at') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                                </div>
                                @error('expires_at')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Laissez vide pour un coupon sans date d'expiration.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 p-4 rounded-lg border-2 border-gray-700 relative overflow-hidden">
                        <div class="cyber-grid absolute inset-0 opacity-10"></div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_active" name="is_active" type="checkbox" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="cyber-checkbox h-5 w-5 rounded">
                            </div>
                            <div class="ml-3 flex flex-col">
                                <label for="is_active" class="font-medium text-cyan-300 flex items-center gap-2">
                                    <span class="text-purple-400 mr-1">[</span>
                                    STATUS
                                    <span class="text-purple-400 ml-1">]</span>
                                    <span class="text-green-400 text-xs bg-green-900/30 px-2 py-0.5 rounded-sm">ACTIVE</span>
                                </label>
                                <p class="text-gray-300 text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span>Ce coupon sera immédiatement disponible pour utilisation.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800/60 p-6 rounded-lg border-2 border-gray-700 mt-6 relative overflow-hidden">
                        <div class="absolute inset-0 cyber-grid opacity-10"></div>
                        <h3 class="text-lg font-medium text-white mb-4 relative">
                            <span class="text-purple-400 mr-1">[</span>
                            APERÇU DU COUPON
                            <span class="text-purple-400 ml-1">]</span>
                        </h3>
                        
                        <div class="relative bg-gradient-to-r from-gray-900 via-black to-gray-900 p-4 rounded-lg border border-purple-500/30">
                            <div class="absolute top-0 left-0 w-full h-full bg-grid-pattern opacity-10"></div>
                            <div class="relative">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-400 text-xs">CODE</p>
                                        <p class="text-xl font-bold text-white tracking-widest font-mono coupon-preview-code">CYBER25</p>
                                    </div>
                                    <div class="cyber-badge">
                                        <span class="coupon-preview-type">POURCENTAGE</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex justify-between">
                                    <div>
                                        <p class="text-gray-400 text-xs">VALEUR</p>
                                        <p class="text-lg text-cyan-400 font-bold coupon-preview-value">25%</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-xs">EXPIRE LE</p>
                                        <p class="text-sm text-white coupon-preview-expiry">Non défini</p>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-gray-400 text-xs">DESCRIPTION</p>
                                    <p class="text-sm text-gray-300 coupon-preview-desc">Description du coupon</p>
                                </div>
                                
                                <div class="mt-3 flex items-center">
                                    <div class="w-3 h-3 rounded-full coupon-preview-status-dot bg-green-500"></div>
                                    <span class="text-xs text-gray-300 ml-2 coupon-preview-status">Actif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-5 border-t-2 border-gray-700 flex justify-end space-x-3">
                        <a href="{{ route('coupons.index') }}" class="cyber-button-secondary">
                            CANCEL_OPERATION
                        </a>
                        <button type="submit" class="cyber-button-primary">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            EXECUTE::create_coupon()
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .cyberpunk-text {
        text-shadow: 0 0 5px rgba(139, 92, 246, 0.5), 0 0 10px rgba(139, 92, 246, 0.3);
        letter-spacing: 0.5px;
    }
    
    .cyber-grid {
        background-image: 
            linear-gradient(to right, rgba(139, 92, 246, 0.2) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(139, 92, 246, 0.2) 1px, transparent 1px);
        background-size: 20px 20px;
    }
    
    .bg-grid-pattern {
        background-size: 25px 25px;
        background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                         linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    }
    
    .scanner-line {
        animation: scan 3s linear infinite;
    }
    
    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }
    
    .terminal-cursor {
        animation: blink 1s step-end infinite;
    }
    
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    
    .typing-text {
        overflow: hidden;
        border-right: 2px solid rgba(6, 182, 212, 0.75);
        white-space: nowrap;
        animation: typing 3s steps(14, end), blink-caret 0.75s step-end infinite;
    }
    
    @keyframes typing {
        from { width: 0 }
        to { width: 100% }
    }
    
    @keyframes blink-caret {
        from, to { border-color: transparent }
        50% { border-color: rgba(6, 182, 212, 0.75) }
    }
    
    .shadow-glow {
        box-shadow: 0 0 8px rgba(34, 211, 238, 0.8);
    }
    
    .cyber-checkbox {
        -webkit-appearance: none;
        appearance: none;
        background-color: rgba(31, 41, 55, 0.9);
        border: 2px solid #6366f1;
        position: relative;
        cursor: pointer;
        overflow: hidden;
    }
    
    .cyber-checkbox:checked {
        background-color: #8b5cf6;
        border-color: #8b5cf6;
    }
    
    .cyber-checkbox:checked::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60%;
        height: 60%;
        background-color: white;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }
    
    .cyber-button-primary {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(90deg, rgba(139, 92, 246, 0.3) 0%, rgba(6, 182, 212, 0.3) 100%);
        color: white;
        font-weight: 600;
        border: 2px solid rgba(139, 92, 246, 0.7);
        border-radius: 0.375rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
        font-family: monospace;
        letter-spacing: 0.5px;
        text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
    }
    
    .cyber-button-primary:hover {
        background: linear-gradient(90deg, rgba(139, 92, 246, 0.5) 0%, rgba(6, 182, 212, 0.5) 100%);
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.5), 0 0 30px rgba(6, 182, 212, 0.3);
        transform: translateY(-2px);
    }
    
    .cyber-button-primary::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        z-index: -1;
        background: linear-gradient(90deg, #8b5cf6, #06b6d4, #8b5cf6);
        background-size: 200%;
        animation: border-animation 3s linear infinite;
        filter: blur(10px);
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .cyber-button-primary:hover::before {
        opacity: 1;
    }
    
    @keyframes border-animation {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .cyber-button-secondary {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1.25rem;
        background-color: rgba(31, 41, 55, 0.8);
        color: rgba(209, 213, 219, 1);
        font-weight: 500;
        border: 2px solid rgba(75, 85, 99, 0.5);
        border-radius: 0.375rem;
        transition: all 0.3s;
        font-family: monospace;
        letter-spacing: 0.5px;
    }
    
    .cyber-button-secondary:hover {
        background-color: rgba(55, 65, 81, 0.8);
        color: rgba(243, 244, 246, 1);
        box-shadow: 0 0 10px rgba(75, 85, 99, 0.3);
        transform: translateY(-2px);
    }
    
    .cyber-button-small {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: rgba(31, 41, 55, 0.8);
        color: rgba(209, 213, 219, 1);
        font-size: 0.875rem;
        font-weight: 500;
        border: 2px solid rgba(75, 85, 99, 0.5);
        border-radius: 0.375rem;
        transition: all 0.3s;
        font-family: monospace;
    }
    
    .cyber-button-small:hover {
        background-color: rgba(55, 65, 81, 0.8);
        color: rgba(243, 244, 246, 1);
        border-color: rgba(139, 92, 246, 0.5);
        box-shadow: 0 0 10px rgba(139, 92, 246, 0.3);
    }
    
    .cyber-button-generate {
        background: linear-gradient(to right, #1a1a3a, #242463);
        color: #06b6d4;
        border: 1px solid #06b6d4;
        font-family: monospace;
        font-weight: bold;
        position: relative;
        overflow: hidden;
        border-radius: 0.375rem;
        transition: all 0.3s;
    }
    
    .cyber-button-generate:hover {
        color: white;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.5);
    }
    
    .cyber-button-generate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.3), transparent);
        transition: left 0.7s;
    }
    
    .cyber-button-generate:hover::before {
        left: 100%;
    }
    
    .cyber-badge {
        background: linear-gradient(45deg, #1f2937, #111827);
        color: #a78bfa;
        border: 1px solid #7c3aed;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-family: monospace;
        letter-spacing: 0.5px;
        box-shadow: 0 0 5px rgba(124, 58, 237, 0.3);
    }
    
    .ease-out-expo {
        transition-timing-function: cubic-bezier(0.19, 1, 0.22, 1);
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateBtn = document.getElementById('generateCode');
        const codeInput = document.getElementById('code');
        
        generateBtn.addEventListener('click', function() {
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <div class="flex items-center justify-center">
                    <svg class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>GÉNÉRATION...</span>
                </div>
            `;
            
            setTimeout(() => {
                const prefix = ["CYBER", "TECH", "NEON", "SYNTH", "DIGI", "PULSE", "VOLT", "WAVE", "HACK"][Math.floor(Math.random() * 9)];
                const number = Math.floor(Math.random() * 90) + 10;
                const randomCode = `${prefix}${number}`;
                
                let i = 0;
                codeInput.value = "";
                const typeInterval = setInterval(() => {
                    if (i < randomCode.length) {
                        codeInput.value += randomCode.charAt(i);
                        i++;
                    } else {
                        clearInterval(typeInterval);
                        updatePreview();
                    }
                }, 50);
                
                generateBtn.disabled = false;
                generateBtn.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                        <span>GÉNÉRER UN CODE</span>
                    </div>
                `;
            }, 800);
        });
        
        const codePreview = document.querySelector('.coupon-preview-code');
        const typePreview = document.querySelector('.coupon-preview-type');
        const valuePreview = document.querySelector('.coupon-preview-value');
        const expiryPreview = document.querySelector('.coupon-preview-expiry');
        const descPreview = document.querySelector('.coupon-preview-desc');
        const statusDot = document.querySelector('.coupon-preview-status-dot');
        const statusText = document.querySelector('.coupon-preview-status');
        const discountType = document.querySelector('input[name="discount_type"]');
        const discountPercent = document.getElementById('discount_percent');
        const discountAmount = document.getElementById('discount_amount');
        const description = document.getElementById('description');
        const expiresAt = document.getElementById('expires_at');
        const isActive = document.getElementById('is_active');
        
        updatePreview();
        
        codeInput.addEventListener('input', updatePreview);
        discountPercent.addEventListener('input', updatePreview);
        discountAmount.addEventListener('input', updatePreview);
        description.addEventListener('input', updatePreview);
        expiresAt.addEventListener('input', updatePreview);
        isActive.addEventListener('change', updatePreview);
        
        function updatePreview() {
            codePreview.textContent = codeInput.value || 'CYBER25';
            
            const activeTabValue = discountType.value;
            
            if (activeTabValue === 'percent') {
                typePreview.textContent = 'POURCENTAGE';
                valuePreview.textContent = (discountPercent.value || '25') + '%';
            } else {
                typePreview.textContent = 'MONTANT FIXE';
                valuePreview.textContent = (discountAmount.value || '10') + ' €';
            }
            
            if (expiresAt.value) {
                const date = new Date(expiresAt.value);
                expiryPreview.textContent = date.toLocaleDateString('fr-FR');
            } else {
                expiryPreview.textContent = 'Non défini';
            }
            
            descPreview.textContent = description.value || 'Description du coupon';
            
            if (isActive.checked) {
                statusDot.classList.remove('bg-red-500');
                statusDot.classList.add('bg-green-500');
                statusText.textContent = 'Actif';
            } else {
                statusDot.classList.remove('bg-green-500');
                statusDot.classList.add('bg-red-500');
                statusText.textContent = 'Inactif';
            }
        }
    });
</script>
@endpush
@endsection