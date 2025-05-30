@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Modifier l'Utilisateur</h1>
        <div class="page-actions">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <div class="user-profile-header">
                    <div class="user-avatar large">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                    <div class="user-info">
                        <h2>{{ $user->name }}</h2>
                        <p>{{ $user->email }}</p>
                        <span class="role-badge role-{{ $user->role }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name" class="form-label">Nom <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="role" class="form-label">Rôle <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                        <option value="gestionnaire_produits" {{ old('role', $user->role) == 'gestionnaire_produits' ? 'selected' : '' }}>Gestionnaire de produits</option>
                        <option value="gestionnaire_commandes" {{ old('role', $user->role) == 'gestionnaire_commandes' ? 'selected' : '' }}>Gestionnaire de commandes</option>
                        <option value="editeur_contenu" {{ old('role', $user->role) == 'editeur_contenu' ? 'selected' : '' }}>Éditeur de contenu</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="alert alert-info mt-4" role="alert">
                    <i class="icon-info-circle"></i>
                    <div>
                        <strong>Information</strong>
                        <p>La modification du rôle changera les permissions accordées à cet utilisateur.</p>
                    </div>
                </div>
                
                @if($user->id === auth()->id())
                <div class="alert alert-warning mt-4" role="alert">
                    <i class="icon-warning"></i>
                    <div>
                        <strong>Attention</strong>
                        <p>Vous êtes en train de modifier votre propre compte. Les changements de rôle prendront effet à la prochaine connexion.</p>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-save"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .user-profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .user-avatar.large {
        width: 80px;
        height: 80px;
        background-color: #3b82f6;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.5rem;
        margin-right: 1.5rem;
    }
    
    .user-info h2 {
        margin: 0 0 0.25rem 0;
        font-size: 1.5rem;
    }
    
    .user-info p {
        margin: 0 0 0.5rem 0;
        color: #64748b;
    }
    
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
    
    .form-text {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .alert {
        position: relative;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        display: flex;
        align-items: flex-start;
    }
    
    .alert i {
        margin-right: 0.75rem;
        font-size: 1.25rem;
        margin-top: 0.125rem;
    }
    
    .alert-info {
        color: #0369a1;
        background-color: #e0f2fe;
        border-color: #bae6fd;
    }
    
    .alert-warning {
        color: #92400e;
        background-color: #fef3c7;
        border-color: #fde68a;
    }
    
    .mt-4 {
        margin-top: 1.5rem;
    }
</style>
@endsection
