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

    <!-- Enterprise Consulting Section (Bento Style) -->
    <section class="bg-slate-50 dark:bg-slate-950 py-16 border-t border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="font-outfit font-bold text-3xl text-slate-900 dark:text-white">Enterprise Consulting</h2>
                <p class="text-slate-500 mt-2 max-w-2xl mx-auto">Strategic guidance to align your technology roadmap with core business objectives and operational goals.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bento Item 1 -->
                <div class="md:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <span class="material-symbols-outlined text-primary mb-4 text-4xl">strategy</span>
                        <h3 class="font-outfit font-bold text-xl text-slate-900 dark:text-white mb-2">Digital Roadmap Planning</h3>
                        <p class="text-slate-500 text-sm">A comprehensive 3-5 year technical strategy tailored to your industry's evolving landscape.</p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400">Advanced Strategic Framework</span>
                        <span class="material-symbols-outlined text-slate-400">arrow_forward</span>
                    </div>
                </div>
                
                <!-- Bento Item 2 -->
                <div class="bg-gradient-to-br from-indigo-650 to-violet-600 text-white p-6 rounded-2xl flex flex-col justify-between shadow-lg">
                    <div>
                        <span class="material-symbols-outlined mb-4 text-4xl text-white/90" style="font-variation-settings: 'FILL' 1;">security</span>
                        <h3 class="font-outfit font-bold text-xl text-white mb-2">Security Auditing</h3>
                        <p class="text-sm text-indigo-50/90 leading-relaxed">Deep-dive vulnerability assessments and enterprise compliance alignment (SOC2, GDPR).</p>
                    </div>
                    <a href="{{ route('contact') }}" class="mt-8 w-full py-2 bg-white text-indigo-650 hover:bg-indigo-50 transition-colors rounded-xl font-bold text-xs text-center shadow">Book Audit</a>
                </div>
                
                <!-- Bento Item 3 -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">cloud_sync</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Cloud Migration</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Seamlessly moving infrastructure to AWS, Azure, or GCP with zero downtime.</p>
                </div>
                
                <!-- Bento Item 4 -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">analytics</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Big Data Strategy</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Harnessing corporate data for actionable insights and predictive modeling.</p>
                </div>
                
                <!-- Bento Item 5 -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 hover:shadow-md transition-shadow space-y-2">
                    <span class="material-symbols-outlined text-primary text-4xl">groups</span>
                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">Agile Training</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Upskilling your internal dev teams with modern DevOps methodologies.</p>
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
