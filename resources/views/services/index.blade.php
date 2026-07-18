@extends('layouts.public')

@section('title', 'SaaSNinja Premium Custom Development & Installation Services')

@section('content')
    <!-- Hero Section -->
    <header class="bg-surface-container-lowest py-xl">
        <div class="max-w-7xl mx-auto px-margin-page text-center">
            <div class="inline-flex items-center gap-xs px-sm py-base bg-secondary-container text-on-secondary-fixed-variant rounded-full font-label-sm mb-md">
                <span class="material-symbols-outlined text-[16px] text-primary">verified</span>
                Enterprise Excellence
            </div>
            <h1 class="font-headline-xl text-headline-xl mb-md max-w-3xl mx-auto">Scalable Tech Solutions for the Modern Enterprise</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">We provide the technical expertise and strategic consulting required to navigate complex digital transformations with absolute reliability.</p>
        </div>
    </header>

    <!-- Custom Development Section -->
    <section class="bg-surface-container-low py-xl border-t border-outline-variant">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="grid lg:grid-cols-2 gap-xl items-center">
                <div class="order-2 lg:order-1">
                    <div class="bg-surface-container-lowest p-sm rounded-xl border border-outline-variant shadow-sm overflow-hidden aspect-video">
                        <img class="w-full h-full object-cover rounded-lg" alt="High-tech software development environment showing a dual monitor setup" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600"/>
                    </div>
                </div>
                
                <div class="order-1 lg:order-2">
                    <span class="material-symbols-outlined text-primary text-4xl mb-sm" style="font-variation-settings: 'FILL' 1;">code</span>
                    <h2 class="font-headline-lg text-headline-lg mb-sm">Custom Development</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-md">Tailored software engineering designed to meet the unique architectural requirements of your business. We specialize in high-performance backends and intuitive user interfaces.</p>
                    <ul class="space-y-sm">
                        @foreach($services as $service)
                            <li class="flex items-start gap-xs font-body-sm text-body-sm">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                <div>
                                    <strong class="text-on-surface">{{ $service->name }}</strong>
                                    <span class="block text-xs text-on-surface-variant mt-0.5">{{ $service->short_description }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-lg">
                        <a href="{{ route('portal.services') }}" class="bg-primary text-on-primary px-xl py-md rounded-lg font-label-md text-label-md hover:bg-primary-container transition-all">
                            Submit a Custom Request
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enterprise Consulting Section (Bento Style) -->
    <section class="bg-surface-container-lowest py-xl border-t border-outline-variant">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="text-center mb-xl">
                <h2 class="font-headline-lg text-headline-lg mb-sm">Enterprise Consulting</h2>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mx-auto">Strategic guidance to align your technology roadmap with core business objectives and operational goals.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <!-- Bento Item 1 -->
                <div class="md:col-span-2 bg-surface p-md rounded-xl border border-outline-variant flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <span class="material-symbols-outlined text-primary mb-sm text-4xl">strategy</span>
                        <h3 class="font-headline-md text-headline-md mb-xs">Digital Roadmap Planning</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">A comprehensive 3-5 year technical strategy tailored to your industry's evolving landscape.</p>
                    </div>
                    <div class="mt-xl pt-md border-t border-outline-variant flex justify-between items-center">
                        <span class="font-label-sm text-secondary">Advanced Strategic Framework</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </div>
                </div>
                
                <!-- Bento Item 2 -->
                <div class="bg-primary text-on-primary p-md rounded-xl flex flex-col justify-between shadow-lg">
                    <div>
                        <span class="material-symbols-outlined mb-sm text-4xl text-white" style="font-variation-settings: 'FILL' 1;">security</span>
                        <h3 class="font-headline-md text-headline-md mb-xs text-white">Security Auditing</h3>
                        <p class="font-body-sm text-body-sm opacity-90 text-white/95">Deep-dive vulnerability assessments and enterprise compliance alignment (SOC2, GDPR).</p>
                    </div>
                    <a href="{{ route('contact') }}" class="mt-xl w-full py-xs bg-surface-container-lowest text-primary rounded-lg font-label-md text-center">Book Audit</a>
                </div>
                
                <!-- Bento Item 3 -->
                <div class="bg-surface p-md rounded-xl border border-outline-variant hover:shadow-md transition-shadow">
                    <span class="material-symbols-outlined text-primary mb-sm text-4xl">cloud_sync</span>
                    <h3 class="font-label-md text-label-md mb-xs uppercase tracking-wider font-bold">Cloud Migration</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Seamlessly moving infrastructure to AWS, Azure, or GCP with zero downtime.</p>
                </div>
                
                <!-- Bento Item 4 -->
                <div class="bg-surface p-md rounded-xl border border-outline-variant hover:shadow-md transition-shadow">
                    <span class="material-symbols-outlined text-primary mb-sm text-4xl">analytics</span>
                    <h3 class="font-label-md text-label-md mb-xs uppercase tracking-wider font-bold">Big Data Strategy</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Harnessing corporate data for actionable insights and predictive modeling.</p>
                </div>
                
                <!-- Bento Item 5 -->
                <div class="bg-surface p-md rounded-xl border border-outline-variant hover:shadow-md transition-shadow">
                    <span class="material-symbols-outlined text-primary mb-sm text-4xl">groups</span>
                    <h3 class="font-label-md text-label-md mb-xs uppercase tracking-wider font-bold">Agile Training</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Upskilling your internal dev teams with modern DevOps methodologies.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- System Integration Section -->
    <section class="bg-surface-container-low py-xl border-t border-outline-variant">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="grid lg:grid-cols-2 gap-xl items-center">
                <div>
                    <span class="material-symbols-outlined text-primary text-4xl mb-sm" style="font-variation-settings: 'FILL' 1;">hub</span>
                    <h2 class="font-headline-lg text-headline-lg mb-sm">System Integration</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-md">Eliminate data silos by connecting disparate platforms into a unified, coherent ecosystem. We build robust API bridges and ETL pipelines.</p>
                    <div class="space-y-md">
                        <div class="flex gap-md items-start">
                            <div class="bg-primary-container p-xs rounded-lg text-on-primary-container">
                                <span class="material-symbols-outlined">api</span>
                            </div>
                            <div>
                                <h4 class="font-label-md text-label-md">API Management</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Centralized gateway for all internal and third-party communications.</p>
                            </div>
                        </div>
                        <div class="flex gap-md items-start">
                            <div class="bg-primary-container p-xs rounded-lg text-on-primary-container">
                                <span class="material-symbols-outlined">database</span>
                            </div>
                            <div>
                                <h4 class="font-label-md text-label-md">Data Synchronization</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Real-time consistency across CRM, ERP, and Financial systems.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative group">
                    <div class="absolute -inset-2 bg-primary/5 rounded-2xl blur-xl group-hover:bg-primary/10 transition-colors"></div>
                    <div class="relative bg-surface-container-lowest p-lg rounded-2xl border border-outline-variant shadow-xl">
                        <div class="space-y-sm">
                            <div class="flex items-center justify-between p-sm bg-surface-container rounded-lg border border-outline-variant">
                                <span class="font-label-sm">ERP Core</span>
                                <span class="material-symbols-outlined text-primary">sync</span>
                                <span class="font-label-sm">Cloud Database</span>
                            </div>
                            <div class="flex items-center justify-between p-sm bg-surface-container rounded-lg border border-outline-variant">
                                <span class="font-label-sm">Payment Gateway</span>
                                <span class="material-symbols-outlined text-primary">swap_horiz</span>
                                <span class="font-label-sm">Customer Portal</span>
                            </div>
                            <div class="flex items-center justify-between p-sm bg-surface-container-highest rounded-lg border border-outline-variant font-bold">
                                <span class="font-label-sm">Unified Dashboard</span>
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-xl bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="bg-inverse-surface text-inverse-on-surface p-xl rounded-2xl flex flex-col items-center text-center">
                <h2 class="font-headline-lg text-headline-lg mb-md text-white">Ready to elevate your infrastructure?</h2>
                <p class="font-body-lg text-body-lg mb-xl max-w-xl opacity-80 text-white">Our consultants are ready to discuss your project requirements and provide a detailed technical feasibility study.</p>
                <div class="flex flex-wrap justify-center gap-md">
                    <a href="{{ route('portal.services') }}" class="bg-primary text-on-primary px-xl py-md rounded-lg font-label-md hover:brightness-110 transition-all">Schedule a Consultation</a>
                    <a href="{{ route('contact') }}" class="border border-outline px-xl py-md rounded-lg font-label-md hover:bg-white/5 transition-all text-white">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
@endsection
