@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Ajouter un Utilisateur</h1>
            <a href="{{ route('users.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-gray-300 mb-2">Nom <span class="text-purple-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('name') border-red-500 @enderror" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-gray-300 mb-2">Email <span class="text-purple-500">*</span></label>
                    <input type="email" id="email" name="email" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('email') border-red-500 @enderror" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="password" class="block text-gray-300 mb-2">Mot de passe <span class="text-purple-500">*</span></label>
                    <input type="password" id="password" name="password" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                           @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-gray-300 mb-2">Confirmer le mot de passe <span class="text-purple-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="role" class="block text-gray-300 mb-2">Rôle <span class="text-purple-500">*</span></label>
                <select id="role" name="role" 
                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none
                       @error('role') border-red-500 @enderror" required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                    <option value="gestionnaire_produits" {{ old('role') == 'gestionnaire_produits' ? 'selected' : '' }}>Gestionnaire de produits</option>
                    <option value="gestionnaire_commandes" {{ old('role') == 'gestionnaire_commandes' ? 'selected' : '' }}>Gestionnaire de commandes</option>
                    <option value="editeur_contenu" {{ old('role') == 'editeur_contenu' ? 'selected' : '' }}>Éditeur de contenu</option>
                </select>
                @error('role')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="bg-gray-900/50 rounded-lg p-6 mb-6">
                <h3 class="text-white text-lg font-medium mb-4">Description du rôle</h3>
                
                <div id="role-descriptions" class="space-y-4">
                    <div id="administrateur-description" class="role-description hidden">
                        <div class="inline-block px-3 py-1 text-xs font-medium rounded bg-purple-500/20 text-purple-400 mb-2">Administrateur</div>
                        <p class="text-gray-300">L'administrateur a accès à toutes les fonctionnalités et peut :</p>
                        <ul class="list-disc text-gray-400 text-sm ml-6 mt-2 space-y-1">
                            <li>Gérer les coupons</li>
                            <li>Paramétrer le site</li>
                            <li>Gérer les utilisateurs</li>
                            <li>Voir les statistiques</li>
                        </ul>
                    </div>
                    
                    <div id="gestionnaire_produits-description" class="role-description hidden">
                        <div class="inline-block px-3 py-1 text-xs font-medium rounded bg-blue-500/20 text-blue-400 mb-2">Gestionnaire de produits</div>
                        <p class="text-gray-300">Le gestionnaire de produits peut :</p>
                        <ul class="list-disc text-gray-400 text-sm ml-6 mt-2 space-y-1">
                            <li>Gérer les produits</li>
                            <li>Gérer les catégories</li>
                            <li>Gérer les médias</li>
                        </ul>
                    </div>
                    
                    <div id="gestionnaire_commandes-description" class="role-description hidden">
                        <div class="inline-block px-3 py-1 text-xs font-medium rounded bg-green-500/20 text-green-400 mb-2">Gestionnaire de commandes</div>
                        <p class="text-gray-300">Le gestionnaire de commandes peut :</p>
                        <ul class="list-disc text-gray-400 text-sm ml-6 mt-2 space-y-1">
                            <li>Gérer les commandes</li>
                            <li>Exporter les commandes</li>
                            <li>Gérer les clients</li>
                        </ul>
                    </div>
                    
                    <div id="editeur_contenu-description" class="role-description hidden">
                        <div class="inline-block px-3 py-1 text-xs font-medium rounded bg-yellow-500/20 text-yellow-400 mb-2">Éditeur de contenu</div>
                        <p class="text-gray-300">L'éditeur de contenu peut :</p>
                        <ul class="list-disc text-gray-400 text-sm ml-6 mt-2 space-y-1">
                            <li>Gérer les articles</li>
                            <li>Gérer les témoignages</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show role description based on selection
        const roleSelect = document.getElementById('role');
        const roleDescriptions = document.querySelectorAll('.role-description');
        
        function updateRoleDescription() {
            // Hide all descriptions
            roleDescriptions.forEach(el => el.classList.add('hidden'));
            
            // Show selected role description
            const selectedRole = roleSelect.value;
            if (selectedRole) {
                const descriptionEl = document.getElementById(`${selectedRole}-description`);
                if (descriptionEl) {
                    descriptionEl.classList.remove('hidden');
                }
            }
        }
        
        roleSelect.addEventListener('change', updateRoleDescription);
        
        // Initial update
        updateRoleDescription();
    });
</script>
@endpush
@endsection
