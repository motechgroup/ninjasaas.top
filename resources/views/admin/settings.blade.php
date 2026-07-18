<x-app-layout>
    <x-slot name="header">
        Global Settings Hub
    </x-slot>

    <!-- Tabbed Settings Layout -->
    <div x-data="{ activeTab: 'branding' }" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
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
            <button @click="activeTab = 'footer'" :class="activeTab === 'footer' ? 'bg-primary text-white font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-left text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">view_column</span>
                Contact & Footer
            </button>
        </div>

        <!-- Right Content Panels -->
        <div class="lg:col-span-9 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <!-- Tab 1: Branding & General -->
                <div x-show="activeTab === 'branding'" class="space-y-6">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Branding & General Setup</h3>
                        <p class="text-xs text-slate-500 mt-1">Configure company identifiers, logos, and public behaviors.</p>
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
                        <div>
                            <label for="site_logo" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Logo URL</label>
                            <input type="text" id="site_logo" name="site_logo" value="{{ \App\Models\Setting::get('site_logo') }}" placeholder="https://..."
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="site_favicon" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Favicon URL</label>
                            <input type="text" id="site_favicon" name="site_favicon" value="{{ \App\Models\Setting::get('site_favicon') }}" placeholder="https://..."
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="allow_registrations" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">User Registration</label>
                            <select id="allow_registrations" name="allow_registrations" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="true" {{ \App\Models\Setting::get('allow_registrations', 'true') === 'true' ? 'selected' : '' }}>Enabled (Open to Public)</option>
                                <option value="false" {{ \App\Models\Setting::get('allow_registrations', 'true') === 'false' ? 'selected' : '' }}>Disabled (Closed)</option>
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
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
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
        </div>

    </div>
</x-app-layout>
