@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Paramètres du Site</h1>
            <button type="submit" form="settings-form" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Enregistrer</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-900/30 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
        <button class="ml-auto hover:text-green-200" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Tabs Navigation -->
            <div class="w-full lg:w-64 bg-gray-800 rounded-lg p-2 h-min">
                <div class="flex lg:flex-col rounded-md overflow-hidden">
                    <button type="button" class="tab-btn active w-full text-left p-3 flex items-center gap-3" data-tab="general">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Général</span>
                    </button>
                    <button type="button" class="tab-btn w-full text-left p-3 flex items-center gap-3" data-tab="appearance">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        <span>Apparence</span>
                    </button>
                    <button type="button" class="tab-btn w-full text-left p-3 flex items-center gap-3" data-tab="contact">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Contact</span>
                    </button>
                    <button type="button" class="tab-btn w-full text-left p-3 flex items-center gap-3" data-tab="social">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span>Réseaux Sociaux</span>
                    </button>
                    <button type="button" class="tab-btn w-full text-left p-3 flex items-center gap-3" data-tab="maintenance">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Maintenance</span>
                    </button>
                </div>
            </div>
            
            <!-- Tab Content -->
            <div class="flex-1">
                <div class="tab-panel active" id="general-panel">
                    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-1">Information Générales</h2>
                        <p class="text-gray-400 text-sm mb-4">Configurez les informations de base de votre site web</p>
                        
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="site_name" class="block text-gray-300 mb-2">Nom du site <span class="text-purple-500">*</span></label>
                                <input type="text" id="site_name" name="site_name" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['site_name'] ?? 'KOMPLEMON' }}" required>
                                <div class="text-gray-500 text-xs mt-1">Le nom qui sera affiché dans le titre du site et les emails</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_description" class="block text-gray-300 mb-2">Description du site</label>
                                <textarea id="site_description" name="site_description" 
                                          class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" 
                                          rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                                <div class="text-gray-500 text-xs mt-1">Une brève description de votre site pour les moteurs de recherche</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_keywords" class="block text-gray-300 mb-2">Mots clés</label>
                                <input type="text" id="site_keywords" name="site_keywords" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['site_keywords'] ?? '' }}">
                                <div class="text-gray-500 text-xs mt-1">Séparés par des virgules (exemple: santé, bien-être, produits naturels)</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel hidden" id="appearance-panel">
                    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-1">Logo et Favicon</h2>
                        <p class="text-gray-400 text-sm mb-4">Personnalisez l'apparence de votre site</p>
                        
                        <div class="space-y-6">
                            <div class="form-group">
                                <label class="block text-gray-300 mb-3">Logo du site</label>
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="w-full md:w-1/3">
                                        @if(!empty($settings['logo']))
                                            <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex items-center justify-center h-40">
                                                <img src="{{ asset($settings['logo']) }}" alt="Logo actuel" class="max-h-full max-w-full">
                                            </div>
                                            <div class="mt-2 text-center">
                                                <button type="button" class="text-red-400 hover:text-red-300 text-sm" data-action="remove-logo">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </div>
                                        @else
                                            <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex flex-col items-center justify-center h-40 text-gray-500">
                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>Aucun logo défini</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <label for="logo" class="block w-full cursor-pointer">
                                            <div class="bg-gray-900 border border-gray-700 border-dashed rounded-lg p-6 flex flex-col items-center justify-center h-40 hover:border-purple-500 transition-colors">
                                                <svg class="w-10 h-10 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <span class="text-gray-400">Cliquez pour télécharger un logo</span>
                                                <span class="text-gray-500 text-sm mt-2">PNG, JPG ou SVG. Max 2MB.</span>
                                            </div>
                                            <input type="file" id="logo" name="logo" class="hidden" accept="image/*">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="block text-gray-300 mb-3">Favicon</label>
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="w-full md:w-1/3">
                                        @if(!empty($settings['favicon']))
                                            <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex items-center justify-center h-20">
                                                <img src="{{ asset($settings['favicon']) }}" alt="Favicon actuel" class="max-h-full max-w-full">
                                            </div>
                                            <div class="mt-2 text-center">
                                                <button type="button" class="text-red-400 hover:text-red-300 text-sm" data-action="remove-favicon">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </div>
                                        @else
                                            <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex flex-col items-center justify-center h-20 text-gray-500">
                                                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs">Aucun favicon</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <label for="favicon" class="block w-full cursor-pointer">
                                            <div class="bg-gray-900 border border-gray-700 border-dashed rounded-lg p-6 flex flex-col items-center justify-center h-20 hover:border-purple-500 transition-colors">
                                                <span class="text-gray-400 text-sm">Cliquez pour télécharger un favicon</span>
                                                <span class="text-gray-500 text-xs mt-1">ICO ou PNG. Max 1MB.</span>
                                            </div>
                                            <input type="file" id="favicon" name="favicon" class="hidden" accept="image/x-icon,image/png">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="primary_color" class="block text-gray-300 mb-2">Couleur principale</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="primary_color" name="primary_color" 
                                           class="h-10 w-20 rounded bg-transparent cursor-pointer"
                                           value="{{ $settings['primary_color'] ?? '#10b981' }}">
                                    <input type="text" class="bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 w-32"
                                           value="{{ $settings['primary_color'] ?? '#10b981' }}" readonly>
                                </div>
                                <div class="text-gray-500 text-xs mt-1">Couleur principale du thème</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel hidden" id="contact-panel">
                    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-1">Informations de Contact</h2>
                        <p class="text-gray-400 text-sm mb-4">Définissez les coordonnées affichées sur votre site</p>
                        
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="contact_email" class="block text-gray-300 mb-2">Email de contact <span class="text-purple-500">*</span></label>
                                <input type="email" id="contact_email" name="contact_email" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['contact_email'] ?? '' }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone_number" class="block text-gray-300 mb-2">Numéro de téléphone</label>
                                <input type="text" id="phone_number" name="phone_number" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['phone_number'] ?? '' }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="address" class="block text-gray-300 mb-2">Adresse</label>
                                <textarea id="address" name="address" 
                                          class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" 
                                          rows="3">{{ $settings['address'] ?? '' }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_page_text" class="block text-gray-300 mb-2">Texte de la page Contact</label>
                                <textarea id="contact_page_text" name="contact_page_text" 
                                          class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" 
                                          rows="4">{{ $settings['contact_page_text'] ?? '' }}</textarea>
                                <div class="text-gray-500 text-xs mt-1">Texte d'introduction sur la page contact</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel hidden" id="social-panel">
                    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-1">Réseaux Sociaux</h2>
                        <p class="text-gray-400 text-sm mb-4">Configurez les liens vers vos réseaux sociaux</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="social_facebook" class="block text-gray-300 mb-2">
                                    <svg class="w-5 h-5 inline-block mr-1 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </label>
                                <input type="url" id="social_facebook" name="social_facebook" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['social_facebook'] ?? '' }}" 
                                       placeholder="https://facebook.com/votrepage">
                            </div>
                            
                            <div class="form-group">
                                <label for="social_twitter" class="block text-gray-300 mb-2">
                                    <svg class="w-5 h-5 inline-block mr-1 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </label>
                                <input type="url" id="social_twitter" name="social_twitter" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['social_twitter'] ?? '' }}" 
                                       placeholder="https://twitter.com/votrecompte">
                            </div>
                            
                            <div class="form-group">
                                <label for="social_instagram" class="block text-gray-300 mb-2">
                                    <svg class="w-5 h-5 inline-block mr-1 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.261-2.148-.558-2.913-.306-.789-.717-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                    </svg>
                                    Instagram
                                </label>
                                <input type="url" id="social_instagram" name="social_instagram" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['social_instagram'] ?? '' }}" 
                                       placeholder="https://instagram.com/votrecompte">
                            </div>
                            
                            <div class="form-group">
                                <label for="social_linkedin" class="block text-gray-300 mb-2">
                                    <svg class="w-5 h-5 inline-block mr-1 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    LinkedIn
                                </label>
                                <input type="url" id="social_linkedin" name="social_linkedin" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['social_linkedin'] ?? '' }}" 
                                       placeholder="https://linkedin.com/company/votreentreprise">
                            </div>
                            
                            <div class="form-group">
                                <label for="social_youtube" class="block text-gray-300 mb-2">
                                    <svg class="w-5 h-5 inline-block mr-1 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                    YouTube
                                </label>
                                <input type="url" id="social_youtube" name="social_youtube" 
                                       class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none"
                                       value="{{ $settings['social_youtube'] ?? '' }}" 
                                       placeholder="https://youtube.com/c/votrechaîne">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-panel hidden" id="maintenance-panel">
                    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
                        <h2 class="text-lg font-semibold text-white mb-1">Maintenance</h2>
                        <p class="text-gray-400 text-sm mb-4">Options de maintenance du site</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-700">
                                <div>
                                    <label for="maintenance_mode" class="text-gray-300 font-medium">Mode maintenance</label>
                                    <div class="text-gray-500 text-xs mt-1">Activer pour rendre le site indisponible aux visiteurs</div>
                                </div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" 
                                          {{ isset($settings['maintenance_mode']) && $settings['maintenance_mode'] ? 'checked' : '' }}
                                          class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="maintenance_message" class="block text-gray-300 mb-2">Message de maintenance</label>
                                <textarea id="maintenance_message" name="maintenance_message" 
                                          class="w-full bg-gray-900 text-white px-4 py-2 rounded-md border border-gray-700 focus:border-purple-500 focus:outline-none" 
                                          rows="4">{{ $settings['maintenance_message'] ?? 'Notre site est actuellement en maintenance. Merci de revenir plus tard.' }}</textarea>
                                <div class="text-gray-500 text-xs mt-1">Affiché aux visiteurs pendant la maintenance</div>
                            </div>
                            
                            <div class="flex items-center justify-between py-3 border-b border-gray-700">
                                <div>
                                    <label for="enable_cache" class="text-gray-300 font-medium">Activer le cache</label>
                                    <div class="text-gray-500 text-xs mt-1">Améliore les performances du site</div>
                                </div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="enable_cache" name="enable_cache" value="1" 
                                          {{ isset($settings['enable_cache']) && $settings['enable_cache'] ? 'checked' : '' }}
                                          class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10">
                        <h2 class="text-lg font-semibold text-white mb-1">Outils d'administration</h2>
                        <p class="text-gray-400 text-sm mb-4">Actions de maintenance avancées</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button type="button" class="maintenance-tool bg-gray-900 hover:bg-gray-700 border border-gray-700 hover:border-purple-500 p-4 rounded-lg transition-all" data-action="clear-cache">
                                <svg class="w-8 h-8 text-gray-400 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span class="block text-center text-gray-300">Vider le cache</span>
                            </button>
                            
                            <button type="button" class="maintenance-tool bg-gray-900 hover:bg-gray-700 border border-gray-700 hover:border-purple-500 p-4 rounded-lg transition-all" data-action="optimize">
                                <svg class="w-8 h-8 text-gray-400 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="block text-center text-gray-300">Optimiser</span>
                            </button>
                            
                            <button type="button" class="maintenance-tool bg-gray-900 hover:bg-gray-700 border border-gray-700 hover:border-purple-500 p-4 rounded-lg transition-all" data-action="backup">
                                <svg class="w-8 h-8 text-gray-400 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span class="block text-center text-gray-300">Sauvegarder la BDD</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    /* Tab buttons */
    .tab-btn {
        color: #9ca3af;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .tab-btn:hover {
        color: #f9fafb;
        background-color: rgba(139, 92, 246, 0.1);
    }
    
    .tab-btn.active {
        color: #8b5cf6;
        background-color: rgba(139, 92, 246, 0.1);
    }
    
    .tab-btn.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background-color: #8b5cf6;
    }
    
    /* Tab panels animation */
    .tab-panel {
        animation: fadeIn 0.3s ease forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Maintenance tools animation */
    .maintenance-tool {
        transition: all 0.3s ease;
    }
    
    .maintenance-tool:hover svg {
        color: #8b5cf6;
    }
    
    /* Notification animation */
    @keyframes slideInDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 4px;
        background-color: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #10b981;
        z-index: 100;
        animation: slideInDown 0.3s ease forwards;
        transition: opacity 0.3s ease;
    }
    
    .notification-success {
        background-color: rgba(16, 185, 129, 0.2);
        border-color: rgba(16, 185, 129, 0.4);
        color: #10b981;
    }
</style>
@endpush

@push('scripts')
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
                tabPanels.forEach(panel => panel.classList.add('hidden'));
                document.getElementById(targetTab + '-panel').classList.remove('hidden');
            });
        });
        
        // Color picker sync
        const colorInput = document.getElementById('primary_color');
        if (colorInput) {
            const textInput = colorInput.nextElementSibling;
            
            colorInput.addEventListener('input', () => {
                textInput.value = colorInput.value;
            });
        }
        
        // File upload preview
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    const previewContainer = input.closest('.form-group').querySelector('.w-full.md\\:w-1\\/3');
                    
                    reader.onload = function(event) {
                        previewContainer.innerHTML = `
                            <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex items-center justify-center ${input.id === 'favicon' ? 'h-20' : 'h-40'}">
                                <img src="${event.target.result}" alt="Preview" class="max-h-full max-w-full">
                            </div>
                            <div class="mt-2 text-center">
                                <span class="text-gray-400 text-sm">Nouveau fichier sélectionné</span>
                            </div>
                        `;
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
                const container = button.closest('.w-full.md\\:w-1\\/3');
                
                // Create hidden input to signal removal
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_' + fieldName;
                hiddenInput.value = '1';
                document.getElementById('settings-form').appendChild(hiddenInput);
                
                // Update UI
                container.innerHTML = `
                    <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden p-4 flex flex-col items-center justify-center ${fieldName === 'favicon' ? 'h-20 text-xs' : 'h-40'} text-gray-500">
                        <svg class="w-${fieldName === 'favicon' ? '6' : '12'} h-${fieldName === 'favicon' ? '6' : '12'} mb-${fieldName === 'favicon' ? '1' : '2'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Aucun ${fieldName === 'favicon' ? 'favicon' : 'logo'} défini</span>
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
                tool.innerHTML = `
                    <svg class="animate-spin w-8 h-8 text-purple-500 mb-3 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="block text-center text-gray-300">Traitement...</span>
                `;
                tool.disabled = true;
                
                // Simulate API call - in a real app, this would be an actual fetch call
                setTimeout(() => {
                    tool.innerHTML = originalContent;
                    tool.disabled = false;
                    
                    // Show success notification
                    const notification = document.createElement('div');
                    notification.className = 'notification notification-success flex items-center';
                    
                    let message = '';
                    switch(action) {
                        case 'clear-cache':
                            message = 'Cache vidé avec succès';
                            break;
                        case 'optimize':
                            message = 'Application optimisée avec succès';
                            break;
                        case 'backup':
                            message = 'Sauvegarde créée avec succès';
                            break;
                    }
                    
                    notification.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>${message}</span>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        setTimeout(() => notification.remove(), 300);
                    }, 3000);
                }, 1500);
            });
        });
    });
</script>
@endpush
@endsection
