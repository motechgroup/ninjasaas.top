@extends('layouts.public')

@section('title', 'SaaSNinja Company Blog')

@section('content')
    <div class="py-16 bg-slate-900 text-white text-center space-y-3 relative">
        <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl tracking-tight">SaaSNinja Blog</h1>
        <p class="text-slate-400 text-sm max-w-xl mx-auto font-medium">Read about our latest product releases, company updates, and engineering tips.</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
                    <div>
                        <div class="h-48 bg-slate-200 dark:bg-slate-850">
                            @if ($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-slate-100 to-slate-200 dark:from-slate-850 dark:to-slate-800 flex items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                            <h3 class="font-outfit font-extrabold text-base text-slate-950 dark:text-white mt-1 mb-2 line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                {{ $post->summary }}
                            </p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            Read Article &rarr;
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center text-slate-500 py-12">
                    No blog posts published yet.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
