@extends('layouts.public')

@section('title', $service->title)
@section('meta_description', $service->meta_description)
@section('meta_keywords', $service->keywords)

@section('schema')
@php
  $serviceSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'Service',
      'name' => $service->name,
      'provider' => [
          '@type' => 'Organization',
          'name' => 'SaaSNinja',
          'url' => route('home')
      ],
      'description' => $service->meta_description
  ];

  $faqJson = [];
  foreach($service->faqs as $faq) {
      $faqJson[] = [
          '@type' => 'Question',
          'name' => $faq['q'],
          'acceptedAnswer' => [
              '@type' => 'Answer',
              'text' => $faq['a']
          ]
      ];
  }

  $faqSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => $faqJson
  ];
@endphp
<script type="application/ld+json">
{!! json_encode($serviceSchema) !!}
</script>
<script type="application/ld+json">
{!! json_encode($faqSchema) !!}
</script>
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <nav class="bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 py-3">
        <div class="max-w-7xl mx-auto px-6">
            <ol class="flex items-center gap-2 text-xs font-semibold text-slate-550 dark:text-slate-400">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a></li>
                <li class="text-slate-400">/</li>
                <li><a href="{{ route('services.index') }}" class="hover:text-primary transition-colors">Services</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-800 dark:text-white font-bold">{{ $service->name }}</li>
            </ol>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800 relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary/10 dark:bg-primary-fixed-dim/20 text-primary dark:text-primary-fixed text-[10px] font-bold uppercase tracking-wider mb-4">
                SaaSNinja Technical Services
            </span>
            <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 dark:text-white mb-4 tracking-tight leading-tight">
                {{ $service->heading }}
            </h1>
            <p class="text-sm sm:text-base text-slate-500 max-w-2xl">
                {{ $service->tagline }}
            </p>
        </div>
    </section>

    <!-- Main Content & Sidebar -->
    <section class="py-16 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Left Column: Copy & FAQs -->
                <div class="lg:col-span-8 space-y-12">
                    <div class="space-y-6">
                        <h2 class="font-outfit font-bold text-2xl text-slate-900 dark:text-white tracking-tight">Overview</h2>
                        <p class="text-slate-655 dark:text-slate-350 text-sm sm:text-base leading-relaxed">
                            {{ $service->description }}
                        </p>
                        <p class="text-slate-655 dark:text-slate-350 text-sm sm:text-base leading-relaxed">
                            Our team collaborates directly with your developers and administrators to guarantee proper setups, configure Nginx virtual hosts, optimize database index loads, and write clean customizations adhering to Laravel's framework design guidelines.
                        </p>
                    </div>

                    <!-- FAQs Accordion -->
                    <div class="space-y-6">
                        <h2 class="font-outfit font-bold text-2xl text-slate-900 dark:text-white tracking-tight">Frequently Asked Questions</h2>
                        
                        <div class="space-y-3" x-data="{ active: null }">
                            @foreach($service->faqs as $index => $faq)
                                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-850 rounded-2xl overflow-hidden transition-colors">
                                    <button 
                                        x-on:click="active = (active === {{ $index }} ? null : {{ $index }})"
                                        class="w-full text-left px-6 py-4 flex items-center justify-between font-outfit font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-primary dark:hover:text-primary transition-colors focus:outline-none"
                                    >
                                        <span>{{ $faq['q'] }}</span>
                                        <span 
                                            class="material-symbols-outlined transition-transform duration-300 text-slate-400"
                                            x-bind:class="active === {{ $index }} ? 'rotate-180 text-primary' : ''"
                                        >
                                            expand_more
                                        </span>
                                    </button>
                                    
                                    <div 
                                        x-show="active === {{ $index }}" 
                                        x-collapse 
                                        class="px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-655 dark:text-slate-400 leading-relaxed border-t border-slate-200/40 dark:border-slate-800/40"
                                    >
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sidebar (CTA & Related Products) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- CTA Card -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-950 dark:from-slate-950 dark:to-slate-950 border border-slate-800/80 p-8 rounded-3xl text-white space-y-6 shadow-xl relative overflow-hidden">
                        <div class="absolute -right-12 -bottom-12 w-32 h-32 rounded-full bg-primary/10 blur-2xl pointer-events-none"></div>
                        
                        <div class="space-y-2 relative z-10">
                            <h3 class="font-outfit font-bold text-xl text-white leading-tight">Need a custom software solution?</h3>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Share your project requirements and operating targets with our core developers to get a tailored roadmap and audit.
                            </p>
                        </div>
                        
                        <div class="space-y-3 relative z-10 pt-2">
                            <a href="{{ route('portal.services') }}" class="block w-full text-center bg-primary text-white py-3 rounded-xl text-xs font-extrabold hover:opacity-90 transition-all hover:scale-[1.01]">
                                Submit Service Request
                            </a>
                            <a href="{{ route('contact') }}" class="block w-full text-center border border-slate-700 hover:border-slate-600 py-3 rounded-xl text-xs font-bold hover:bg-white/5 transition-all">
                                Request Free Feasibility Call
                            </a>
                        </div>
                    </div>

                    <!-- Related Products -->
                    <div class="space-y-4">
                        <h4 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Popular Software Products</h4>
                        <div class="space-y-4">
                            @foreach($relatedProducts as $prod)
                                <a href="{{ route('products.show', $prod->slug) }}" class="flex items-center gap-4 bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200/50 dark:border-slate-800/70 p-3.5 rounded-2xl hover:border-primary/20 hover:shadow-sm transition-all group">
                                    <div class="w-16 h-12 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 relative">
                                        @if($prod->image_url)
                                            <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=200" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <span class="block text-xs font-bold text-slate-900 dark:text-white leading-tight line-clamp-1 group-hover:text-primary transition-colors">
                                            {{ $prod->name }}
                                        </span>
                                        <span class="block text-[10px] text-slate-400 mt-1 uppercase font-semibold">
                                            {{ $prod->category->name }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
