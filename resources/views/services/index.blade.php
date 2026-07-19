@extends('layouts.public')

@section('title', 'SaaSNinja Premium Custom Development & Installation Services')

@section('content')
    <!-- Hero Section -->
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 rounded-full text-xs font-semibold mb-6">
                <span class="material-symbols-outlined text-[16px] text-primary">verified</span>
                Enterprise Excellence
            </div>
            <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-slate-900 dark:text-white mb-4 max-w-3xl mx-auto tracking-tight">Scalable Tech Solutions for the Modern Enterprise</h1>
            <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto">We provide the technical expertise and strategic consulting required to navigate complex digital transformations with absolute reliability.</p>
        </div>
    </header>

    <!-- Custom Development Section -->
    <section class="bg-white dark:bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md overflow-hidden aspect-video">
                        <img class="w-full h-full object-cover rounded-xl" alt="High-tech software development environment showing a dual monitor setup" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600"/>
                    </div>
                </div>
                
                <div class="order-1 lg:order-2 space-y-6">
                    <div>
                        <span class="material-symbols-outlined text-primary text-5xl mb-2" style="font-variation-settings: 'FILL' 1;">code</span>
                        <h2 class="font-outfit font-bold text-3xl text-slate-900 dark:text-white">Custom Development Services</h2>
                        <p class="text-sm sm:text-base text-slate-500 mt-2">Tailored software engineering designed to meet the unique architectural requirements of your business. We specialize in high-performance backends and intuitive user interfaces.</p>
                    </div>
                    
                    <ul class="space-y-4">
                        @foreach($services as $service)
                            <li class="flex items-start gap-3 text-sm">
                                <span class="material-symbols-outlined text-primary mt-0.5">check_circle</span>
                                <div>
                                    <strong class="text-slate-900 dark:text-white font-bold">{{ $service->name }}</strong>
                                    <span class="block text-xs text-slate-500 mt-1">{{ $service->short_description }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="pt-4">
                        <a href="{{ route('portal.services') }}" class="inline-flex bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-md">
                            Submit a Custom Request
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expanded Enterprise Services Section (Bento Style) -->
    <section class="bg-slate-50 dark:bg-slate-950 py-16 border-t border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="font-outfit font-bold text-3xl text-slate-900 dark:text-white">Our Complete Services Portfolio</h2>
                <p class="text-slate-500 mt-2 max-w-2xl mx-auto">Strategic implementation, custom engineering, and management services to cover every aspect of your enterprise software roadmap.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Custom Software Development -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">code</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Custom Software Development</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">High-performance custom software engineering tailored to your business needs.</p>
                </div>
                
                <!-- Website Development -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">web</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Website Development</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Stunning, high-conversion responsive corporate websites and landing experiences.</p>
                </div>
                
                <!-- Mobile App Development -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">stay_primary_portrait</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Mobile App Development</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Native and cross-platform mobile apps for iOS and Android environments.</p>
                </div>
                
                <!-- UI/UX Design -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">palette</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">UI/UX Design</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">User-centric designs, interactive wireframes, and modern branding systems.</p>
                </div>

                <!-- API Development -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">api</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">API Development</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Secure, document-first REST and GraphQL API gateways and microservices.</p>
                </div>

                <!-- System Integration -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">hub</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">System Integration</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Connecting disparate platforms, payment gateways, and CRM/ERP databases.</p>
                </div>

                <!-- Cloud Deployment -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">cloud</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Cloud Deployment</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Deploying containerized apps to AWS, DigitalOcean, Hetzner, or Google Cloud.</p>
                </div>

                <!-- Hosting -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">dns</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Hosting</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">High-availability VPS, Dedicated server, or Managed Shared hosting configurations.</p>
                </div>

                <!-- Maintenance -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">build</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Maintenance</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Regular package upgrades, server monitoring, bug fixes, and security patches.</p>
                </div>

                <!-- Technical Support -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">support_agent</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Technical Support</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">24/7 dedicated support desk SLA guarantees for enterprise applications.</p>
                </div>

                <!-- Software Consulting -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">chat</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Software Consulting</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Aligning your engineering tools and architecture with operational goals.</p>
                </div>

                <!-- Business Automation -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">smart_toy</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Business Automation</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Automating manual workflows, billing, invoicing, and reporting systems.</p>
                </div>

                <!-- Installation Services -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">download_for_offline</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Installation Services</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Professional installation of Envato templates and SaaS apps on your servers.</p>
                </div>

                <!-- Customization -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">edit_note</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Customization</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Modifying existing products to match your visual layout and logic rules.</p>
                </div>

                <!-- Migration -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">move_up</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Migration</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Transitioning database engines, filesystems, and hostings seamlessly.</p>
                </div>

                <!-- Training -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">school</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Training</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Upskilling dev and admin teams to operate SaaSNinja software products.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- System Integration Section -->
    <section class="bg-white dark:bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div>
                        <span class="material-symbols-outlined text-primary text-5xl mb-2" style="font-variation-settings: 'FILL' 1;">hub</span>
                        <h2 class="font-outfit font-bold text-3xl text-slate-900 dark:text-white">System Integration</h2>
                        <p class="text-sm sm:text-base text-slate-500 mt-2">Eliminate data silos by connecting disparate platforms into a unified, coherent ecosystem. We build robust API bridges and ETL pipelines.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex gap-4 items-start">
                            <div class="bg-indigo-50 dark:bg-indigo-950/40 p-2.5 rounded-xl text-primary">
                                <span class="material-symbols-outlined">api</span>
                            </div>
                            <div>
                                <h4 class="font-outfit font-bold text-slate-900 dark:text-white text-base">API Management</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Centralized gateway for all internal and third-party communications.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="bg-indigo-50 dark:bg-indigo-950/40 p-2.5 rounded-xl text-primary">
                                <span class="material-symbols-outlined">database</span>
                            </div>
                            <div>
                                <h4 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Data Synchronization</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Real-time consistency across CRM, ERP, and Financial systems.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative group">
                    <div class="absolute -inset-2 bg-indigo-500/5 rounded-3xl blur-xl group-hover:bg-indigo-500/10 transition-colors"></div>
                    <div class="relative bg-slate-50 dark:bg-slate-950 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
                                <span class="text-xs font-bold text-slate-850 dark:text-slate-250">ERP Core</span>
                                <span class="material-symbols-outlined text-primary">sync</span>
                                <span class="text-xs font-bold text-slate-850 dark:text-slate-250">Cloud Database</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
                                <span class="text-xs font-bold text-slate-850 dark:text-slate-250">Payment Gateway</span>
                                <span class="material-symbols-outlined text-primary">swap_horiz</span>
                                <span class="text-xs font-bold text-slate-850 dark:text-slate-250">Customer Portal</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-950/30 rounded-xl border border-indigo-100 dark:border-indigo-900 font-extrabold text-primary">
                                <span class="text-xs">Unified Dashboard</span>
                                <span class="material-symbols-outlined">check_circle</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-slate-50 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-slate-900 text-white p-8 sm:p-12 rounded-3xl flex flex-col items-center text-center shadow-xl space-y-6">
                <h2 class="font-outfit font-bold text-3xl sm:text-4xl text-white">Ready to elevate your infrastructure?</h2>
                <p class="text-sm sm:text-base max-w-xl text-slate-350 leading-relaxed">Our consultants are ready to discuss your project requirements and provide a detailed technical feasibility study.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('portal.services') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow">Schedule a Consultation</a>
                    <a href="{{ route('contact') }}" class="border border-slate-700 hover:border-slate-650 px-6 py-3 rounded-xl font-bold text-sm hover:bg-white/5 transition-all text-white">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
@endsection
