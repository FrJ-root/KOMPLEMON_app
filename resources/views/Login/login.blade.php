<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KOMPLEMON - Connexion</title>
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #4834d4;
            --dark: #1e272e;
            --light: #2d3436;
            --gray: #34495e;
            --text: #dfe6e9;
            --text-light: #b2bec3;
            --danger: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #1e272e 0%, #2c3e50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: var(--text);
        }
        
        .login-container {
            width: 1000px;
            max-width: 100%;
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background-color: var(--light);
            position: relative;
            border: 1px solid #34495e;
        }
        
        .login-sidebar {
            width: 40%;
            padding: 3rem;
            background: linear-gradient(135deg, #2c3e50 0%, #1e272e 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            border-right: 1px solid #34495e;
        }
        
        .login-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        
        .logo-icon {
            font-size: 2rem;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--primary);
        }
        
        .sidebar-content h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .sidebar-content p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        .feature-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: var(--primary);
            color: var(--dark);
            border-radius: 4px;
            flex-shrink: 0;
        }
        
        .login-form-wrapper {
            width: 60%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            background-color: var(--light);
        }
        
        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1px solid var(--gray);
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Courier New', monospace;
            color: var(--text);
            transition: all 0.3s;
            background-color: rgba(52, 73, 94, 0.5);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            background-color: rgba(52, 73, 94, 0.8);
        }
        
        .login-button {
            padding: 1rem;
            background-color: var(--primary);
            color: var(--dark);
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: 'Courier New', monospace;
        }
        
        .login-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-sidebar">
            <div>
                <div class="logo">
                    <div class="logo-icon">🌿</div>
                    <div class="logo-text">KOMPLEMON</div>
                </div>
                
                <div class="sidebar-content">
                    <h1>Bienvenue sur votre espace administrateur</h1>
                    <p>Connectez-vous pour accéder à votre tableau de bord et gérer votre boutique en ligne.</p>
                    
                    <ul class="feature-list">
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Gérez vos produits et catégories</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Suivez vos commandes et clients</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Analysez vos statistiques de vente</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Configurez votre site et vos paramètres</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="sidebar-footer">
                &copy; {{ date('Y') }} KOMPLEMON. Tous droits réservés.
            </div>
        </div>
        
        <div class="login-form-wrapper">
            <div class="login-header">
                <h2>Connexion</h2>
                <p>Entrez vos identifiants pour accéder à votre compte</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                
                <div class="form-group">
                    <span class="form-icon">✉️</span>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Adresse email" value="{{ old('email') }}" required autofocus>
                </div>
                
                <div class="form-group">
                    <span class="form-icon">🔒</span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">👁️</button>
                </div>
                
                <div class="login-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" class="remember-checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="remember-label">Se souvenir de moi</label>
                    </div>
                    
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">Mot de passe oublié?</a>
                    @endif
                </div>
                
                <button type="submit" class="login-button">
                    <span class="login-button-icon">🔐</span>
                    <span>Se connecter</span>
                </button>
            </form>
            
            <div class="alt-login">
                <div class="alt-login-text">Ou se connecter avec</div>
                <div class="alt-login-buttons">
                    <button type="button" class="alt-login-button" title="Google">G</button>
                    <button type="button" class="alt-login-button" title="Facebook">f</button>
                    <button type="button" class="alt-login-button" title="Apple">🍎</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.textContent = '🔒';
            } else {
                passwordInput.type = 'password';
                passwordToggle.textContent = '👁️';
            }
        }
        
        // Add shake animation if there are validation errors
        document.addEventListener('DOMContentLoaded', function() {
            const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
            
            if (hasErrors) {
                const loginForm = document.querySelector('.login-form');
                loginForm.classList.add('shake');
                
                // Remove the class after animation completes
                setTimeout(() => {
                    loginForm.classList.remove('shake');
                }, 600);
            }
        });
    </script>
</body>
</html>