@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Modifier l'Utilisateur</h1>
            <a href="{{ route('users.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="flex items-center gap-6 mb-8 pb-6 border-b border-gray-700">
                <div class="w-16 h-16 rounded-full bg-purple-600/20 flex items-center justify-center text-purple-400 text-xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                    <p class="text-gray-400">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded
                        @if($user->role === 'administrateur') bg-purple-500/20 text-purple-400
                        @elseif($user->role === 'gestionnaire_produits') bg-blue-500/20 text-blue-400
                        @elseif($user->role === 'gestionnaire_commandes') bg-green-500/20 text-green-400
                        @elseif($user->role === 'editeur_contenu') bg-yellow-500/20 text-yellow-400
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-gray-300 mb-2">Nom <span class="text-purple-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('name') border-red-500 @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-gray-300 mb-2">Email <span class="text-purple-500">*</span></label>
                    <input type="email" id="email" name="email" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('email') border-red-500 @enderror" 
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="password" class="block text-gray-300 mb-2">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('password') border-red-500 @enderror">
                    <div class="text-gray-500 text-xs mt-1">Laissez vide pour conserver le mot de passe actuel</div>
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-gray-300 mb-2">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="role" class="block text-gray-300 mb-2">Rôle <span class="text-purple-500">*</span></label>
                <select id="role" name="role" 
                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                       @error('role') border-red-500 @enderror" required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                    <option value="gestionnaire_produits" {{ old('role', $user->role) == 'gestionnaire_produits' ? 'selected' : '' }}>Gestionnaire de produits</option>
                    <option value="gestionnaire_commandes" {{ old('role', $user->role) == 'gestionnaire_commandes' ? 'selected' : '' }}>Gestionnaire de commandes</option>
                    <option value="editeur_contenu" {{ old('role', $user->role) == 'editeur_contenu' ? 'selected' : '' }}>Éditeur de contenu</option>
                </select>
                @error('role')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="bg-blue-900/20 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-lg mb-6 flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium">Information</p>
                    <p class="text-sm">La modification du rôle changera les permissions accordées à cet utilisateur.</p>
                </div>
            </div>
            
            @if($user->id === auth()->id())
            <div class="bg-yellow-900/20 border border-yellow-500/30 text-yellow-400 px-4 py-3 rounded-lg mb-6 flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="font-medium">Attention</p>
                    <p class="text-sm">Vous êtes en train de modifier votre propre compte. Les changements de rôle prendront effet à la prochaine connexion.</p>
                </div>
            </div>
            @endif
            
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Mettre à jour</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
