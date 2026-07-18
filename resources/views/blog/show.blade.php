@extends('layouts.public')

@section('title', $post->title . ' - SaaSNinja Blog')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Breadcrumbs -->
        <x-breadcrumb :items="[
            'Blog' => route('blog.index'),
            $post->title => ''
        ]" />

        <!-- Article Card Wrapper -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 sm:p-10 shadow-sm mt-4">
            
            <div class="text-center space-y-3 mb-8">
                <span class="text-xs font-bold text-indigo-650 dark:text-indigo-400 uppercase tracking-widest">
                    {{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}
                </span>
                <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-950 dark:text-white leading-tight">
                    {{ $post->title }}
                </h1>
                <div class="text-xs text-slate-500 font-semibold">
                    Written by: <strong class="text-slate-700 dark:text-slate-300">{{ $post->author->name }}</strong>
                </div>
            </div>

            @if($post->featured_image)
                <div class="rounded-xl overflow-hidden mb-8 max-h-[400px]">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full object-cover">
                </div>
            @endif

            <article class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 text-sm sm:text-base leading-relaxed whitespace-pre-wrap">
                {!! $post->content !!}
            </article>

        </div>
    </div>
@endsection
