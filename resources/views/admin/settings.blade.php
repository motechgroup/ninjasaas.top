<x-app-layout>
    <x-slot name="header">
        Global Settings Hub
    </x-slot>

    <!-- Tabbed Settings Layout -->
    <div x-data="{ activeTab: localStorage.getItem('active_settings_tab') || 'branding' }" x-init="$watch('activeTab', value => localStorage.setItem('active_settings_tab', value))" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Sidebar Navigation Tabs -->
        <div class="lg:col-span-3 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl p-4 shadow-sm space-y-1">
            <button @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">branding_watermark</span>
                Branding & General
            </button>
            <button @click="activeTab = 'theme'" :class="activeTab === 'theme' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">palette</span>
                Theme Colors
            </button>
            <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">travel_explore</span>
                SEO Metadata
            </button>
            <button @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">payments</span>
                Payment Gateways
            </button>
            <button @click="activeTab = 'smtp'" :class="activeTab === 'smtp' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">mail</span>
                SMTP Email
            </button>
            <button @click="activeTab = 'templates'" :class="activeTab === 'templates' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">drafts</span>
                Email Templates
            </button>
            <button @click="activeTab = 'footer'" :class="activeTab === 'footer' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">view_column</span>
                Contact & Footer
            </button>
        </div>

        <!-- Right Content Panels -->
        <div class="lg:col-span-9 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <!-- Tab 1: Branding & General -->
                <div x-show="activeTab === 'branding'" class="space-y-6">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Branding & General Setup</h3>
                        <p class="text-xs text-slate-500 mt-1">Upload company logos, favicons, set logo dimensions, and configure public behaviors.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Company Name</label>
                            <input type="text" id="company_name" name="company_name" value="{{ \App\Models\Setting::get('company_name', 'SaaSNinja') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="support_email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Support Email Address</label>
                            <input type="email" id="support_email" name="support_email" value="{{ \App\Models\Setting::get('support_email', 'support@saasninja.top') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Header Logo File Upload & Dimensions -->
                        <div class="md:col-span-2 bg-slate-50 dark:bg-slate-950/60 p-5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Header Brand Logo & Dimensions</h4>
                                <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-900 px-2.5 py-1 rounded-full">
                                    💡 Recommended: 180px – 250px Width × 36px – 48px Height (PNG, SVG, WEBP)
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Upload Header Logo File</label>
                                    <input type="file" name="site_logo_file" accept="image/*"
                                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:opacity-90">
                                </div>
                                <div>
                                    <label for="site_logo_height" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Rendered Height (px)</label>
                                    <input type="number" id="site_logo_height" name="site_logo_height" value="{{ \App\Models\Setting::get('site_logo_height', '36') }}" min="16" max="120" placeholder="36"
                                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                                <div>
                                    <label for="site_logo_width" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Rendered Width (px or auto)</label>
                                    <input type="text" id="site_logo_width" name="site_logo_width" value="{{ \App\Models\Setting::get('site_logo_width', 'auto') }}" placeholder="auto"
                                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                            </div>

                            @if(\App\Models\Setting::get('site_logo'))
                            <div class="pt-2 flex items-center gap-3">
                                <span class="text-xs font-semibold text-slate-500">Current Header Logo Preview:</span>
                                <div class="p-2.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 inline-block shadow-sm">
                                    <img src="{{ \App\Models\Setting::get('site_logo') }}" alt="Site Header Logo" style="height: {{ \App\Models\Setting::get('site_logo_height', '36') }}px; width: {{ \App\Models\Setting::get('site_logo_width', 'auto') }}; object-fit: contain;">
                                </div>
                                <input type="hidden" name="site_logo" value="{{ \App\Models\Setting::get('site_logo') }}">
                            </div>
                            @endif
                        </div>

                        <!-- Footer Logo File Upload & Dimensions -->
                        <div class="md:col-span-2 bg-slate-50 dark:bg-slate-950/60 p-5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Site Footer Logo & Dimensions</h4>
                                <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900 px-2.5 py-1 rounded-full">
                                    💡 Recommended: 160px – 220px Width × 32px – 44px Height (PNG, SVG, WEBP)
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Upload Footer Logo File (Optional)</label>
                                    <input type="file" name="site_footer_logo_file" accept="image/*"
                                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:opacity-90">
                                    <p class="text-[11px] text-slate-400 mt-1">If left empty, the site will automatically use the header logo in the footer.</p>
                                </div>
                                <div>
                                    <label for="site_footer_logo_height" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Footer Logo Height (px)</label>
                                    <input type="number" id="site_footer_logo_height" name="site_footer_logo_height" value="{{ \App\Models\Setting::get('site_footer_logo_height', '36') }}" min="16" max="120" placeholder="36"
                                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                            </div>

                            @if(\App\Models\Setting::get('site_footer_logo'))
                            <div class="pt-2 flex items-center gap-3">
                                <span class="text-xs font-semibold text-slate-500">Current Footer Logo Preview:</span>
                                <div class="p-2.5 bg-slate-900 rounded-xl border border-slate-800 inline-block shadow-sm">
                                    <img src="{{ \App\Models\Setting::get('site_footer_logo') }}" alt="Site Footer Logo" style="height: {{ \App\Models\Setting::get('site_footer_logo_height', '36') }}px; width: auto; object-fit: contain;">
                                </div>
                                <input type="hidden" name="site_footer_logo" value="{{ \App\Models\Setting::get('site_footer_logo') }}">
                            </div>
                            @endif
                        </div>

                        <!-- Favicon File Upload -->
                        <div class="md:col-span-2 bg-slate-50 dark:bg-slate-950/60 p-5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Site Favicon Upload</h4>
                                <span class="text-[11px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-900 px-2.5 py-1 rounded-full">
                                    💡 Recommended: 32×32 px or 64×64 px (ICO, PNG, SVG)
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Upload Favicon File</label>
                                    <input type="file" name="site_favicon_file" accept="image/*"
                                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:opacity-90">
                                </div>
                                @if(\App\Models\Setting::get('site_favicon'))
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-semibold text-slate-500">Current Favicon:</span>
                                    <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 inline-block shadow-sm">
                                        <img src="{{ \App\Models\Setting::get('site_favicon') }}" alt="Favicon" class="w-8 h-8 object-contain">
                                    </div>
                                    <input type="hidden" name="site_favicon" value="{{ \App\Models\Setting::get('site_favicon') }}">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label for="allow_registrations" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">User Registration</label>
                            <select id="allow_registrations" name="allow_registrations" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="true" {{ \App\Models\Setting::get('allow_registrations', 'true') === 'true' ? 'selected' : '' }}>Enabled (Open to Public)</option>
                                <option value="false" {{ \App\Models\Setting::get('allow_registrations', 'true') === 'false' ? 'selected' : '' }}>Disabled (Closed)</option>
                            </select>
                        </div>
                        <div>
                            <label for="enable_google_login" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Google OAuth Login</label>
                            <select id="enable_google_login" name="enable_google_login" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="true" {{ \App\Models\Setting::get('enable_google_login', 'true') === 'true' ? 'selected' : '' }}>Enabled (Show Google OAuth Buttons)</option>
                                <option value="false" {{ \App\Models\Setting::get('enable_google_login', 'true') === 'false' ? 'selected' : '' }}>Disabled (Hide Google OAuth Buttons)</option>
                            </select>
                        </div>
                        <div>
                            <label for="enable_envato_login" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Envato Market OAuth Login</label>
                            <select id="enable_envato_login" name="enable_envato_login" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="true" {{ \App\Models\Setting::get('enable_envato_login', 'true') === 'true' ? 'selected' : '' }}>Enabled (Show Envato OAuth Buttons)</option>
                                <option value="false" {{ \App\Models\Setting::get('enable_envato_login', 'true') === 'false' ? 'selected' : '' }}>Disabled (Hide Envato OAuth Buttons)</option>
                            </select>
                        </div>
                        <div>
                            <label for="envato_sandbox_mode" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Envato Sandbox Simulation Mode</label>
                            <select id="envato_sandbox_mode" name="envato_sandbox_mode" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="true" {{ \App\Models\Setting::get('envato_sandbox_mode', 'true') === 'true' ? 'selected' : '' }}>Active (Allow Mock Verification for Testing)</option>
                                <option value="false" {{ \App\Models\Setting::get('envato_sandbox_mode', 'true') === 'false' ? 'selected' : '' }}>Inactive (Production API Verification Only)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Envato OAuth & Personal Token Setup -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-4">
                        <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Envato API & OAuth App Credentials</h4>
                        <p class="text-xs text-slate-500">Register your OAuth App on <a href="https://build.envato.com" target="_blank" class="text-primary underline font-bold">build.envato.com</a>. Set Redirect URI to: <code class="bg-slate-100 dark:bg-slate-950 px-1.5 py-0.5 rounded font-mono text-primary">{{ url('/auth/envato/callback') }}</code></p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <label for="envato_client_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Envato Client ID</label>
                                <input type="text" id="envato_client_id" name="envato_client_id" value="{{ \App\Models\Setting::get('envato_client_id') }}" placeholder="saasninja-app-client-id"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="envato_client_secret" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Envato Client Secret</label>
                                <input type="password" id="envato_client_secret" name="envato_client_secret" value="{{ \App\Models\Setting::get('envato_client_secret') }}" placeholder="••••••••••••••••"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="envato_redirect_uri" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Envato OAuth Redirect URI</label>
                                <input type="text" id="envato_redirect_uri" name="envato_redirect_uri" value="{{ \App\Models\Setting::get('envato_redirect_uri', url('/auth/envato/callback')) }}" placeholder="http://..."
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="envato_personal_token" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Envato Personal API Token</label>
                                <input type="password" id="envato_personal_token" name="envato_personal_token" value="{{ \App\Models\Setting::get('envato_personal_token') }}" placeholder="Author Token for Verification"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Theme Colors -->
                <div x-show="activeTab === 'theme'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Site Theme & Color Customizer</h3>
                        <p class="text-xs text-slate-500 mt-1">Directly overrides Tailwind CSS styling variables across the site.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="theme_primary_color" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Primary Color</label>
                            <div class="flex gap-2">
                                <input type="color" name="theme_primary_color" value="{{ \App\Models\Setting::get('theme_primary_color', '#004ac6') }}" class="h-10 w-12 rounded border border-slate-200 cursor-pointer">
                                <input type="text" name="theme_primary_color" value="{{ \App\Models\Setting::get('theme_primary_color', '#004ac6') }}" class="flex-grow rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label for="theme_secondary_color" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Secondary Color</label>
                            <div class="flex gap-2">
                                <input type="color" name="theme_secondary_color" value="{{ \App\Models\Setting::get('theme_secondary_color', '#515f74') }}" class="h-10 w-12 rounded border border-slate-200 cursor-pointer">
                                <input type="text" name="theme_secondary_color" value="{{ \App\Models\Setting::get('theme_secondary_color', '#515f74') }}" class="flex-grow rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label for="theme_background_color" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Background Color</label>
                            <div class="flex gap-2">
                                <input type="color" name="theme_background_color" value="{{ \App\Models\Setting::get('theme_background_color', '#f7f9fb') }}" class="h-10 w-12 rounded border border-slate-200 cursor-pointer">
                                <input type="text" name="theme_background_color" value="{{ \App\Models\Setting::get('theme_background_color', '#f7f9fb') }}" class="flex-grow rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label for="theme_surface_color" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Surface Container Color</label>
                            <div class="flex gap-2">
                                <input type="color" name="theme_surface_color" value="{{ \App\Models\Setting::get('theme_surface_color', '#eceef0') }}" class="h-10 w-12 rounded border border-slate-200 cursor-pointer">
                                <input type="text" name="theme_surface_color" value="{{ \App\Models\Setting::get('theme_surface_color', '#eceef0') }}" class="flex-grow rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: SEO Configuration -->
                <div x-show="activeTab === 'seo'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Search Engine Optimization (SEO)</h3>
                        <p class="text-xs text-slate-500 mt-1">Configure global meta values for search indexes.</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="seo_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Default Meta Title</label>
                            <input type="text" id="seo_title" name="seo_title" value="{{ \App\Models\Setting::get('seo_title', 'SaaSNinja - Premium Software & Envato Customer Portal') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="seo_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Default Meta Description</label>
                            <textarea id="seo_description" name="seo_description" rows="3"
                                      class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">{{ \App\Models\Setting::get('seo_description', 'SaaSNinja develops premium applications sold exclusively through Envato Market. Access documentation, get customer support, and request customization.') }}</textarea>
                        </div>
                        <div>
                            <label for="seo_keywords" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Keywords (Comma separated)</label>
                            <input type="text" id="seo_keywords" name="seo_keywords" value="{{ \App\Models\Setting::get('seo_keywords', 'laravel, saas, envato, codecanyon, support, software') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="seo_google_analytics" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Google Analytics Tracking ID (G-XXXXXXX)</label>
                            <input type="text" id="seo_google_analytics" name="seo_google_analytics" value="{{ \App\Models\Setting::get('seo_google_analytics') }}" placeholder="G-..."
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Payment Gateways -->
                <div x-show="activeTab === 'payments'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Payment Gateways Configuration</h3>
                        <p class="text-xs text-slate-500 mt-1">Setup Stripe and PayPal endpoints for paid customization services.</p>
                    </div>
                    <div class="space-y-6">
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white uppercase tracking-wider mb-4">Stripe API</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="stripe_public_key" class="block text-xs text-slate-500 mb-1">Stripe Publishable Key</label>
                                    <input type="text" id="stripe_public_key" name="stripe_public_key" value="{{ \App\Models\Setting::get('stripe_public_key') }}" placeholder="pk_test_..."
                                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                                <div>
                                    <label for="stripe_secret_key" class="block text-xs text-slate-500 mb-1">Stripe Secret Key</label>
                                    <input type="password" id="stripe_secret_key" name="stripe_secret_key" value="{{ \App\Models\Setting::get('stripe_secret_key') }}" placeholder="sk_test_..."
                                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white uppercase tracking-wider mb-4">PayPal Checkout</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="paypal_client_id" class="block text-xs text-slate-500 mb-1">PayPal Client ID</label>
                                    <input type="text" id="paypal_client_id" name="paypal_client_id" value="{{ \App\Models\Setting::get('paypal_client_id') }}"
                                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                                <div>
                                    <label for="paypal_secret" class="block text-xs text-slate-500 mb-1">PayPal Secret</label>
                                    <input type="password" id="paypal_secret" name="paypal_secret" value="{{ \App\Models\Setting::get('paypal_secret') }}"
                                           class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: SMTP & Mail Server -->
                <div x-show="activeTab === 'smtp'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">SMTP & Mail Server Configurations</h3>
                        <p class="text-xs text-slate-500 mt-1">Configure outgoing email settings for transactional system notifications.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mail_host" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Mail Host</label>
                            <input type="text" id="mail_host" name="mail_host" value="{{ \App\Models\Setting::get('mail_host', 'smtp.mailtrap.io') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="mail_port" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Mail Port</label>
                            <input type="text" id="mail_port" name="mail_port" value="{{ \App\Models\Setting::get('mail_port', '2525') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="mail_username" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Mail Username</label>
                            <input type="text" id="mail_username" name="mail_username" value="{{ \App\Models\Setting::get('mail_username') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="mail_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Mail Password</label>
                            <input type="password" id="mail_password" name="mail_password" value="{{ \App\Models\Setting::get('mail_password') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="mail_encryption" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Mail Encryption</label>
                            <input type="text" id="mail_encryption" name="mail_encryption" value="{{ \App\Models\Setting::get('mail_encryption', 'tls') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="mail_from_address" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Mail From Address</label>
                            <input type="text" id="mail_from_address" name="mail_from_address" value="{{ \App\Models\Setting::get('mail_from_address', 'hello@saasninja.top') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-850 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="mail_verify_ssl" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">SSL Certificate Verification</label>
                            <select id="mail_verify_ssl" name="mail_verify_ssl" class="block w-full rounded-lg border-slate-200 dark:border-slate-850 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="true" {{ \App\Models\Setting::get('mail_verify_ssl', 'true') === 'true' ? 'selected' : '' }}>Verify SSL Certificate (Recommended for Production)</option>
                                <option value="false" {{ \App\Models\Setting::get('mail_verify_ssl', 'true') === 'false' ? 'selected' : '' }}>Disable SSL Verification (Bypass OpenSSL CA errors)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tab: Email Templates -->
                <div x-show="activeTab === 'templates'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">System Email Templates</h3>
                        <p class="text-xs text-slate-500 mt-1">Configure layout HTML markup and standard variables (e.g. {name}, {ticket_id}, {reply_message}, {site_url}) for automated messages.</p>
                    </div>
                    <div class="space-y-6">
                        @foreach ($emailTemplates as $tpl)
                            <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-xl p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $tpl->name }}</h4>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">Template Key: {{ $tpl->key }}</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Subject Line</label>
                                        <input type="text" name="templates[{{ $tpl->id }}][subject]" value="{{ $tpl->subject }}"
                                               class="block w-full rounded-lg border-slate-200 dark:border-slate-805 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-primary focus:border-primary">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">HTML Body</label>
                                        <textarea name="templates[{{ $tpl->id }}][body]" rows="6"
                                                  class="block w-full rounded-lg border-slate-200 dark:border-slate-805 dark:bg-slate-950 text-slate-900 dark:text-white text-xs font-mono py-2 px-3 focus:ring-primary focus:border-primary">{{ $tpl->body }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tab 6: Contact & Footer -->
                <div x-show="activeTab === 'footer'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Contact Information & Footer Copyright</h3>
                        <p class="text-xs text-slate-500 mt-1">Configure company location, phone contacts, and social media handles.</p>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_phone" class="block text-xs text-slate-500 mb-1">Contact Phone</label>
                                <input type="text" id="contact_phone" name="contact_phone" value="{{ \App\Models\Setting::get('contact_phone', '+1 (555) 123-4567') }}"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="contact_address" class="block text-xs text-slate-500 mb-1">Contact Address</label>
                                <input type="text" id="contact_address" name="contact_address" value="{{ \App\Models\Setting::get('contact_address', '100 Software Blvd, Suite 400, San Francisco, CA') }}"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="social_facebook" class="block text-xs text-slate-500 mb-1">Facebook URL</label>
                                <input type="text" id="social_facebook" name="social_facebook" value="{{ \App\Models\Setting::get('social_facebook', 'https://facebook.com/saasninja') }}"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="social_twitter" class="block text-xs text-slate-500 mb-1">Twitter/X URL</label>
                                <input type="text" id="social_twitter" name="social_twitter" value="{{ \App\Models\Setting::get('social_twitter', 'https://twitter.com/saasninja') }}"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="social_github" class="block text-xs text-slate-500 mb-1">GitHub URL</label>
                                <input type="text" id="social_github" name="social_github" value="{{ \App\Models\Setting::get('social_github', 'https://github.com/saasninja') }}"
                                       class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label for="site_footer_copyright" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Footer Copyright Text</label>
                            <input type="text" id="site_footer_copyright" name="site_footer_copyright" value="{{ \App\Models\Setting::get('site_footer_copyright', '© ' . date('Y') . ' SaaSNinja Software. All rights reserved. Sold exclusively on Envato Market.') }}"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:opacity-90 text-white text-sm font-semibold rounded-lg shadow-md transition-all">
                        Save Configuration Updates
                    </button>
                </div>
            </form>

            <!-- SMTP Test Form (displayed only when activeTab is smtp) -->
            <form x-show="activeTab === 'smtp'" action="{{ route('admin.settings.test-smtp') }}" method="POST" class="p-8 border-t border-slate-200 dark:border-slate-800 space-y-4 bg-slate-50/50 dark:bg-slate-950/20" style="display: none;">
                @csrf
                <div>
                    <h4 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Verify SMTP Connectivity</h4>
                    <p class="text-xs text-slate-500 mt-1">Send a trial email using the saved configuration to verify your SMTP mailer connection works.</p>
                </div>
                <div class="flex gap-4 items-center max-w-md">
                    <input type="email" name="test_email" required placeholder="destination@example.com" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-primary focus:border-primary">
                    <button type="submit" class="bg-indigo-650 text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:opacity-90 shadow-sm transition-all whitespace-nowrap">
                        Send Test Mail
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
