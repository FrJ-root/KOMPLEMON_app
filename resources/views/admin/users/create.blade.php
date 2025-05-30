@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Ajouter un Utilisateur</h1>
        <div class="page-actions">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="card-body">
                <div class="form-group">
                    <label for="name" class="form-label">Nom <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe <span class="required">*</span></label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="required">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="role" class="form-label">Rôle <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                        <option value="gestionnaire_produits" {{ old('role') == 'gestionnaire_produits' ? 'selected' : '' }}>Gestionnaire de produits</option>
                        <option value="gestionnaire_commandes" {{ old('role') == 'gestionnaire_commandes' ? 'selected' : '' }}>Gestionnaire de commandes</option>
                        <option value="editeur_contenu" {{ old('role') == 'editeur_contenu' ? 'selected' : '' }}>Éditeur de contenu</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="role-description mt-4">
                    <h4 class="role-description-title">Description du rôle</h4>
                    <div id="administrateur-description" class="role-description-content" style="display: none;">
                        <div class="role-badge role-administrateur">Administrateur</div>
                        <p>L'administrateur a accès à toutes les fonctionnalités et peut :</p>
                        <ul>
                            <li>Gérer les coupons</li>
                            <li>Paramétrer le site</li>
                            <li>Gérer les utilisateurs</li>
                            <li>Voir les statistiques</li>
                        </ul>
                    </div>
                    <div id="gestionnaire_produits-description" class="role-description-content" style="display: none;">
                        <div class="role-badge role-gestionnaire_produits">Gestionnaire de produits</div>
                        <p>Le gestionnaire de produits peut :</p>
                        <ul>
                            <li>Gérer les produits</li>
                            <li>Gérer les catégories</li>
                            <li>Gérer les médias</li>
                        </ul>
                    </div>
                    <div id="gestionnaire_commandes-description" class="role-description-content" style="display: none;">
                        <div class="role-badge role-gestionnaire_commandes">Gestionnaire de commandes</div>
                        <p>Le gestionnaire de commandes peut :</p>
                        <ul>
                            <li>Gérer les commandes</li>
                            <li>Exporter les commandes</li>
                            <li>Gérer les clients</li>
                        </ul>
                    </div>
                    <div id="editeur_contenu-description" class="role-description-content" style="display: none;">
                        <div class="role-badge role-editeur_contenu">Éditeur de contenu</div>
                        <p>L'éditeur de contenu peut :</p>
                        <ul>
                            <li>Gérer les articles</li>
                            <li>Gérer les témoignages</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #1e293b;
    }
    
    .form-control {
        display: block;
        width: 100%;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        color: #1e293b;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus {
        border-color: #3b82f6;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
    
    .is-invalid {
        border-color: #ef4444;
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #ef4444;
    }
    
    .required {
        color: #ef4444;
    }
    
    .role-description {
        background-color: #f8fafc;
        border-radius: 0.5rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    
    .role-description-title {
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.125rem;
        font-weight: 600;
    }
    
    .role-description-content {
        margin-top: 1rem;
    }
    
    .role-description-content ul {
        margin-top: 0.5rem;
        padding-left: 1.5rem;
    }
    
    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script>
    // Show role description based on selection
    document.getElementById('role').addEventListener('change', function() {
        // Hide all descriptions
        document.querySelectorAll('.role-description-content').forEach(el => {
            el.style.display = 'none';
        });
        
        // Show selected role description
        const selectedRole = this.value;
        if (selectedRole) {
            const descriptionEl = document.getElementById(`${selectedRole}-description`);
            if (descriptionEl) {
                descriptionEl.style.display = 'block';
            }
        }
    });
    
    // Trigger change event to show description for pre-selected role
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('role').dispatchEvent(new Event('change'));
    });
</script>
@endsection
