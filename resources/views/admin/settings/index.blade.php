@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Paramètres du Site</h1>
        <div class="page-actions">
            <button type="button" class="btn-save animated-btn" form="settings-form">
                <i class="icon-save"></i> Enregistrer
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="icon-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button class="alert-close">&times;</button>
    </div>
    @endif

    <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="settings-tabs">
            <div class="tabs-navigation">
                <button type="button" class="tab-btn active" data-tab="general">
                    <i class="icon-globe"></i>
                    <span>Général</span>
                </button>
                <button type="button" class="tab-btn" data-tab="appearance">
                    <i class="icon-paint"></i>
                    <span>Apparence</span>
                </button>
                <button type="button" class="tab-btn" data-tab="contact">
                    <i class="icon-phone"></i>
                    <span>Contact</span>
                </button>
                <button type="button" class="tab-btn" data-tab="social">
                    <i class="icon-share"></i>
                    <span>Réseaux Sociaux</span>
                </button>
                <button type="button" class="tab-btn" data-tab="maintenance">
                    <i class="icon-tools"></i>
                    <span>Maintenance</span>
                </button>
            </div>
            
            <div class="tabs-content">
                <div class="tab-panel active" id="general-panel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2>Information Générales</h2>
                            <p>Configurez les informations de base de votre site web</p>
                        </div>
                        
                        <div class="settings-card-body">
                            <div class="form-group">
                                <label for="site_name">Nom du site <span class="required">*</span></label>
                                <input type="text" id="site_name" name="site_name" class="form-control" 
                                       value="{{ $settings['site_name'] ?? 'KOMPLEMON' }}" required>
                                <div class="form-text">Le nom qui sera affiché dans le titre du site et les emails</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_description">Description du site</label>
                                <textarea id="site_description" name="site_description" class="form-control" rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                                <div class="form-text">Une brève description de votre site pour les moteurs de recherche</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_keywords">Mots clés</label>
                                <input type="text" id="site_keywords" name="site_keywords" class="form-control" 
                                       value="{{ $settings['site_keywords'] ?? '' }}">
                                <div class="form-text">Séparés par des virgules (exemple: santé, bien-être, produits naturels)</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel" id="appearance-panel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2>Logo et Favicon</h2>
                            <p>Personnalisez l'apparence de votre site</p>
                        </div>
                        
                        <div class="settings-card-body">
                            <div class="form-group">
                                <label>Logo du site</label>
                                <div class="file-upload-container">
                                    <div class="current-file">
                                        @if(!empty($settings['logo']))
                                            <div class="preview-image">
                                                <img src="{{ asset($settings['logo']) }}" alt="Logo actuel">
                                            </div>
                                            <div class="file-info">
                                                <p>Logo actuel</p>
                                                <button type="button" class="btn-link text-danger" data-action="remove-logo">
                                                    <i class="icon-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        @else
                                            <div class="no-file">
                                                <i class="icon-image"></i>
                                                <p>Aucun logo défini</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="file-upload-field">
                                        <input type="file" id="logo" name="logo" class="file-input" accept="image/*">
                                        <label for="logo" class="file-label">
                                            <i class="icon-upload"></i>
                                            <span>Choisir un fichier</span>
                                        </label>
                                        <div class="file-info">PNG, JPG ou SVG. Max 2MB.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Favicon</label>
                                <div class="file-upload-container">
                                    <div class="current-file">
                                        @if(!empty($settings['favicon']))
                                            <div class="preview-image favicon-preview">
                                                <img src="{{ asset($settings['favicon']) }}" alt="Favicon actuel">
                                            </div>
                                            <div class="file-info">
                                                <p>Favicon actuel</p>
                                                <button type="button" class="btn-link text-danger" data-action="remove-favicon">
                                                    <i class="icon-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        @else
                                            <div class="no-file">
                                                <i class="icon-image"></i>
                                                <p>Aucun favicon défini</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="file-upload-field">
                                        <input type="file" id="favicon" name="favicon" class="file-input" accept="image/x-icon,image/png">
                                        <label for="favicon" class="file-label">
                                            <i class="icon-upload"></i>
                                            <span>Choisir un fichier</span>
                                        </label>
                                        <div class="file-info">ICO ou PNG. Max 1MB.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="primary_color">Couleur principale</label>
                                <div class="color-picker-container">
                                    <input type="color" id="primary_color" name="primary_color" class="color-input" 
                                           value="{{ $settings['primary_color'] ?? '#10b981' }}">
                                    <input type="text" class="color-text" value="{{ $settings['primary_color'] ?? '#10b981' }}" readonly>
                                </div>
                                <div class="form-text">Couleur principale du thème</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel" id="contact-panel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2>Informations de Contact</h2>
                            <p>Définissez les coordonnées affichées sur votre site</p>
                        </div>
                        
                        <div class="settings-card-body">
                            <div class="form-group">
                                <label for="contact_email">Email de contact <span class="required">*</span></label>
                                <input type="email" id="contact_email" name="contact_email" class="form-control" 
                                       value="{{ $settings['contact_email'] ?? '' }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone_number">Numéro de téléphone</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" 
                                       value="{{ $settings['phone_number'] ?? '' }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <textarea id="address" name="address" class="form-control" rows="3">{{ $settings['address'] ?? '' }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_page_text">Texte de la page Contact</label>
                                <textarea id="contact_page_text" name="contact_page_text" class="form-control" rows="4">{{ $settings['contact_page_text'] ?? '' }}</textarea>
                                <div class="form-text">Texte d'introduction sur la page contact</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel" id="social-panel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2>Réseaux Sociaux</h2>
                            <p>Configurez les liens vers vos réseaux sociaux</p>
                        </div>
                        
                        <div class="settings-card-body">
                            <div class="social-links">
                                <div class="form-group">
                                    <label for="social_facebook">
                                        <i class="icon-facebook"></i> Facebook
                                    </label>
                                    <input type="url" id="social_facebook" name="social_facebook" class="form-control" 
                                           value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/votrepage">
                                </div>
                                
                                <div class="form-group">
                                    <label for="social_twitter">
                                        <i class="icon-twitter"></i> Twitter
                                    </label>
                                    <input type="url" id="social_twitter" name="social_twitter" class="form-control" 
                                           value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/votrecompte">
                                </div>
                                
                                <div class="form-group">
                                    <label for="social_instagram">
                                        <i class="icon-instagram"></i> Instagram
                                    </label>
                                    <input type="url" id="social_instagram" name="social_instagram" class="form-control" 
                                           value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/votrecompte">
                                </div>
                                
                                <div class="form-group">
                                    <label for="social_linkedin">
                                        <i class="icon-linkedin"></i> LinkedIn
                                    </label>
                                    <input type="url" id="social_linkedin" name="social_linkedin" class="form-control" 
                                           value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/votreentreprise">
                                </div>
                                
                                <div class="form-group">
                                    <label for="social_youtube">
                                        <i class="icon-youtube"></i> YouTube
                                    </label>
                                    <input type="url" id="social_youtube" name="social_youtube" class="form-control" 
                                           value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/c/votrechaîne">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel" id="maintenance-panel">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2>Maintenance</h2>
                            <p>Options de maintenance du site</p>
                        </div>
                        
                        <div class="settings-card-body">
                            <div class="form-group switch-group">
                                <div class="switch-label">
                                    <label for="maintenance_mode">Mode maintenance</label>
                                    <div class="form-text">Activer pour rendre le site indisponible aux visiteurs</div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" 
                                          {{ isset($settings['maintenance_mode']) && $settings['maintenance_mode'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="maintenance_message">Message de maintenance</label>
                                <textarea id="maintenance_message" name="maintenance_message" class="form-control" rows="4">{{ $settings['maintenance_message'] ?? 'Notre site est actuellement en maintenance. Merci de revenir plus tard.' }}</textarea>
                                <div class="form-text">Affiché aux visiteurs pendant la maintenance</div>
                            </div>
                            
                            <div class="form-group switch-group">
                                <div class="switch-label">
                                    <label for="enable_cache">Activer le cache</label>
                                    <div class="form-text">Améliore les performances du site</div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="enable_cache" name="enable_cache" value="1" 
                                          {{ isset($settings['enable_cache']) && $settings['enable_cache'] ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h2>Outils d'administration</h2>
                            <p>Actions de maintenance avancées</p>
                        </div>
                        
                        <div class="settings-card-body">
                            <div class="maintenance-tools">
                                <button type="button" class="btn maintenance-tool" data-action="clear-cache">
                                    <i class="icon-refresh"></i>
                                    <span>Vider le cache</span>
                                </button>
                                
                                <button type="button" class="btn maintenance-tool" data-action="optimize">
                                    <i class="icon-bolt"></i>
                                    <span>Optimiser</span>
                                </button>
                                
                                <button type="button" class="btn maintenance-tool" data-action="backup">
                                    <i class="icon-database"></i>
                                    <span>Sauvegarder la base de données</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="icon-save"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<style>
    /* General styles */
    .settings-tabs {
        display: flex;
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    /* Tabs navigation */
    .tabs-navigation {
        width: 260px;
        background-color: #f8fafc;
        border-right: 1px solid #e2e8f0;
    }
    
    .tab-btn {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 1rem;
        background: none;
        border: none;
        text-align: left;
        font-size: 0.875rem;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    
    .tab-btn i {
        margin-right: 0.75rem;
        font-size: 1.125rem;
        width: 1.5rem;
        text-align: center;
    }
    
    .tab-btn:hover {
        background-color: #f1f5f9;
        color: #334155;
    }
    
    .tab-btn.active {
        background-color: white;
        color: #10b981;
        font-weight: 600;
        border-left-color: #10b981;
    }
    
    /* Tab content */
    .tabs-content {
        flex: 1;
        padding: 1.5rem;
    }
    
    .tab-panel {
        display: none;
    }
    
    .tab-panel.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Settings cards */
    .settings-card {
        background-color: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .settings-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .settings-card-header h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .settings-card-header p {
        margin: 0.5rem 0 0;
        font-size: 0.875rem;
        color: #64748b;
    }
    
    .settings-card-body {
        padding: 1.5rem;
    }
    
    /* Form elements */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #334155;
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
        border-color: #10b981;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }
    
    .form-text {
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .required {
        color: #ef4444;
    }
    
    /* File upload */
    .file-upload-container {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }
    
    .current-file {
        width: 150px;
    }
    
    .preview-image {
        width: 100%;
        height: 100px;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8fafc;
        margin-bottom: 0.5rem;
    }
    
    .preview-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .favicon-preview {
        height: 64px;
    }
    
    .file-info {
        font-size: 0.75rem;
        color: #64748b;
        text-align: center;
    }
    
    .no-file {
        width: 100%;
        height: 100px;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #f8fafc;
        color: #94a3b8;
    }
    
    .no-file i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .no-file p {
        margin: 0;
        font-size: 0.75rem;
    }
    
    .file-upload-field {
        flex: 1;
    }
    
    .file-input {
        display: none;
    }
    
    .file-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background-color: #f1f5f9;
        border: 1px dashed #cbd5e1;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.2s;
        color: #64748b;
        font-size: 0.875rem;
    }
    
    .file-label:hover {
        background-color: #e2e8f0;
        color: #334155;
    }
    
    /* Color picker */
    .color-picker-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .color-input {
        width: 40px;
        height: 40px;
        padding: 0;
        border: none;
        border-radius: 0.25rem;
        overflow: hidden;
        cursor: pointer;
    }
    
    .color-text {
        width: auto;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        color: #64748b;
        background-color: #f8fafc;
    }
    
    /* Social links */
    .social-links {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }
    
    .social-links .form-group {
        margin-bottom: 1rem;
    }
    
    .social-links label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .social-links i {
        font-size: 1.25rem;
    }
    
    /* Switch */
    .switch-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .switch-label {
        flex: 1;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: .4s;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
    }
    
    input:checked + .slider {
        background-color: #10b981;
    }
    
    input:focus + .slider {
        box-shadow: 0 0 1px #10b981;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .slider.round {
        border-radius: 24px;
    }
    
    .slider.round:before {
        border-radius: 50%;
    }
    
    /* Maintenance tools */
    .maintenance-tools {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .maintenance-tool {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background-color: white;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
        min-width: 150px;
    }
    
    .maintenance-tool i {
        font-size: 1.5rem;
        color: #64748b;
    }
    
    .maintenance-tool:hover {
        border-color: #10b981;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Form actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    
    /* Buttons */
    .btn {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.375rem;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    
    .btn-primary {
        color: white;
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .btn-primary:hover {
        background-color: #059669;
        border-color: #059669;
    }
    
    .btn-link {
        background: none;
        border: none;
        padding: 0;
        font-size: 0.75rem;
        text-decoration: underline;
        cursor: pointer;
    }
    
    .text-danger {
        color: #ef4444;
    }
    
    /* Animated save button */
    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #10b981;
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    .btn-save:hover {
        background-color: #059669;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-save:after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }
    
    .btn-save:hover:after {
        transform: translateX(100%);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .settings-tabs {
            flex-direction: column;
        }
        
        .tabs-navigation {
            width: 100%;
            display: flex;
            overflow-x: auto;
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .tab-btn {
            flex-direction: column;
            padding: 0.75rem;
            border-left: none;
            border-bottom: 3px solid transparent;
        }
        
        .tab-btn i {
            margin-right: 0;
            margin-bottom: 0.25rem;
        }
        
        .tab-btn.active {
            border-left-color: transparent;
            border-bottom-color: #10b981;
        }
        
        .file-upload-container {
            flex-direction: column;
        }
        
        .current-file {
            width: 100%;
            margin-bottom: 1rem;
        }
    }

    /* Icons */
    .icon-globe:before { content: "🌐"; }
    .icon-paint:before { content: "🎨"; }
    .icon-phone:before { content: "📞"; }
    .icon-share:before { content: "🔗"; }
    .icon-tools:before { content: "🔧"; }
    .icon-image:before { content: "🖼️"; }
    .icon-upload:before { content: "📤"; }
    .icon-trash:before { content: "🗑️"; }
    .icon-facebook:before { content: "f"; }
    .icon-twitter:before { content: "t"; }
    .icon-instagram:before { content: "📷"; }
    .icon-linkedin:before { content: "in"; }
    .icon-youtube:before { content: "▶️"; }
    .icon-refresh:before { content: "🔄"; }
    .icon-bolt:before { content: "⚡"; }
    .icon-database:before { content: "💾"; }
    .icon-save:before { content: "💾"; }
    .icon-check-circle:before { content: "✓"; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                
                // Update active tab button
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update active tab panel
                tabPanels.forEach(panel => panel.classList.remove('active'));
                document.getElementById(targetTab + '-panel').classList.add('active');
            });
        });
        
        // Color picker sync
        const colorInputs = document.querySelectorAll('.color-input');
        colorInputs.forEach(input => {
            const textInput = input.nextElementSibling;
            
            input.addEventListener('input', () => {
                textInput.value = input.value;
            });
        });
        
        // File upload preview
        const fileInputs = document.querySelectorAll('.file-input');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    const preview = input.closest('.file-upload-container').querySelector('.preview-image img');
                    
                    reader.onload = function(event) {
                        if (preview) {
                            preview.src = event.target.result;
                            preview.closest('.current-file').querySelector('.no-file')?.remove();
                        }
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
        
        // Remove file buttons
        const removeButtons = document.querySelectorAll('[data-action="remove-logo"], [data-action="remove-favicon"]');
        removeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const fieldName = button.getAttribute('data-action').replace('remove-', '');
                const container = button.closest('.current-file');
                
                // Create hidden input to signal removal
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_' + fieldName;
                hiddenInput.value = '1';
                document.getElementById('settings-form').appendChild(hiddenInput);
                
                // Update UI
                container.innerHTML = `
                    <div class="no-file">
                        <i class="icon-image"></i>
                        <p>Aucun fichier défini</p>
                    </div>
                `;
            });
        });
        
        // Maintenance tool actions
        const maintenanceTools = document.querySelectorAll('.maintenance-tool');
        maintenanceTools.forEach(tool => {
            tool.addEventListener('click', () => {
                const action = tool.getAttribute('data-action');
                
                // Show loading state
                const originalContent = tool.innerHTML;
                tool.innerHTML = '<div class="spinner"></div><span>Traitement...</span>';
                tool.disabled = true;
                
                // Simulate API call - in a real app, this would be an actual fetch call
                setTimeout(() => {
                    tool.innerHTML = originalContent;
                    tool.disabled = false;
                    
                    // Show success notification
                    const notification = document.createElement('div');
                    notification.className = 'notification notification-success';
                    
                    switch(action) {
                        case 'clear-cache':
                            notification.textContent = 'Cache vidé avec succès';
                            break;
                        case 'optimize':
                            notification.textContent = 'Application optimisée avec succès';
                            break;
                        case 'backup':
                            notification.textContent = 'Sauvegarde créée avec succès';
                            break;
                    }
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        setTimeout(() => notification.remove(), 300);
                    }, 3000);
                }, 1500);
            });
        });
        
        // Close alerts
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', () => {
                button.closest('.alert').remove();
            });
        });
    });
</script>
@endsection
