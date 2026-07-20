@extends('layouts.public')

@section('title', $category->title)
@section('meta_description', $category->meta_description)
@section('meta_keywords', $category->keywords)

@section('schema')
@php
  $productSchema = null;
  if($relatedProduct) {
      $productSchema = [
          '@context' => 'https://schema.org',
          '@type' => 'Product',
          'name' => $category->name . ' Solutions',
          'image' => [
              $relatedProduct->image_url ?: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600'
          ],
          'description' => $category->meta_description,
          'brand' => [
              '@type' => 'Brand',
              'name' => 'SaaSNinja'
          ],
          'offers' => [
              '@type' => 'Offer',
              'url' => route('products.show', $relatedProduct->slug),
              'priceCurrency' => 'USD',
              'price' => $relatedProduct->salesChannels->first()->pivot->price ?? '49.00',
              'availability' => 'https://schema.org/InStock'
          ]
      ];
  }

  $faqJson = [];
  foreach($category->faqs as $faq) {
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

@if($productSchema)
<script type="application/ld+json">
{!! json_encode($productSchema) !!}
</script>
@endif

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
                <li><a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Products</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-800 dark:text-white font-bold">{{ $category->name }}</li>
            </ol>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-slate-50 to-white dark:from-slate-950 dark:to-slate-900 py-20 border-b border-slate-200 dark:border-slate-800 relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center lg:text-left">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 text-xs font-bold border border-indigo-100/50 dark:border-indigo-900/30">
                        Corporate Tech Suite
                    </span>
                    <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl lg:text-6xl text-slate-900 dark:text-white leading-none tracking-tight">
                        {{ $category->heading }}
                    </h1>
                    <p class="text-base sm:text-lg text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        {{ $category->subheading }}
                    </p>
                </div>
                
                @if($relatedProduct)
                    <!-- CTA Right Box -->
                    <div class="lg:col-span-5 bg-slate-50 dark:bg-slate-950 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-6 text-left relative group">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none rounded-3xl"></div>
                        <div class="space-y-2 relative z-10">
                            <span class="text-[10px] font-bold text-primary uppercase tracking-widest block">Available Now</span>
                            <h3 class="font-outfit font-bold text-xl text-slate-900 dark:text-white line-clamp-1">
                                {{ $relatedProduct->name }}
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                {{ $relatedProduct->short_description }}
                            </p>
                        </div>
                        <div class="border-t border-slate-200/60 dark:border-slate-800/80 pt-4 flex items-center justify-between relative z-10">
                            <div>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wide">Single License From</span>
                                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">${{ $relatedProduct->salesChannels->first()->pivot->price ?? '49.00' }}</span>
                            </div>
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="bg-primary text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:opacity-90 transition-all hover:scale-[1.02]">
                                Explore Product
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Operational Problems We Solve -->
    <section class="py-16 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-5 space-y-4">
                    <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white tracking-tight">The Challenges We Address</h2>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        SaaSNinja builds systems designed to target the core inefficiencies and database overheads of digital organizations.
                    </p>
                </div>
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($category->problems as $prob)
                        <div class="p-5 bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100/60 dark:border-rose-900/20 rounded-2xl flex gap-3">
                            <span class="material-symbols-outlined text-rose-500 text-[20px] flex-shrink-0">error_outline</span>
                            <span class="text-slate-700 dark:text-slate-350 text-xs sm:text-sm leading-snug">{{ $prob }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Core Features -->
    <section class="py-16 bg-slate-50/50 dark:bg-slate-950/10 border-y border-slate-200/60 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-950 dark:text-white tracking-tight">Core Specifications</h2>
                <p class="text-slate-500 text-sm sm:text-base">Modern solutions constructed for stability, security-first performance, and scale.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($category->features as $fTitle => $fDesc)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 p-6 rounded-2xl space-y-2">
                        <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                            {{ $fTitle }}
                        </h3>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">{{ $fDesc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Vertical Targets -->
    <section class="py-16 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-slate-50 dark:bg-slate-950 p-8 rounded-3xl border border-slate-200/60 dark:border-slate-850 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-3">
                    <h3 class="font-outfit font-extrabold text-xl text-slate-900 dark:text-white">Target Verticals & Organizations</h3>
                    <p class="text-slate-550 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                        Our {{ $category->name }} framework is engineered to adapt cleanly to various operating scales. It is widely deployed across:
                    </p>
                    <div class="font-bold text-slate-700 dark:text-slate-300 text-xs tracking-wide">
                        {{ $category->industries }}
                    </div>
                </div>
                <div class="lg:col-span-4 flex justify-end">
                    <a href="{{ route('contact') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-all hover:scale-[1.01] shadow-sm">
                        Request Feasibility Consultation
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="py-16 bg-slate-50/50 dark:bg-slate-950/10 border-t border-slate-200/60 dark:border-slate-800/60">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-12 space-y-2">
                <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-950 dark:text-white tracking-tight">Frequently Asked Questions</h2>
                <p class="text-slate-500 text-sm">Answers to common setup, customization, and licensing queries.</p>
            </div>

            <div class="space-y-3" x-data="{ active: null }">
                @foreach($category->faqs as $index => $faq)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/65 dark:border-slate-850 rounded-2xl overflow-hidden">
                        <button 
                            @click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full text-left px-6 py-4 flex items-center justify-between font-outfit font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-primary dark:hover:text-primary transition-colors focus:outline-none"
                        >
                            <span>{{ $faq['q'] }}</span>
                            <span 
                                class="material-symbols-outlined transition-transform duration-300 text-slate-400"
                                :class="active === {{ $index }} ? 'rotate-180 text-primary' : ''"
                            >
                                expand_more
                            </span>
                        </button>
                        
                        <div 
                            x-show="active === {{ $index }}" 
                            x-collapse 
                            class="px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-slate-800"
                        >
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
