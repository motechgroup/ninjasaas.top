@extends('layouts.public')

@section('title', 'Publications by ' . $author->name . ' - SaaSNinja Blog')

@section('content')
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <a href="{{ route('blog.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mb-6">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Blog
            </a>
            
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                @if($author->profile_image)
                    <img src="{{ $author->profile_image }}" class="w-20 h-20 rounded-full object-cover bg-slate-100 border border-slate-200 dark:border-slate-800 shadow-sm flex-shrink-0">
                @else
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-950 text-slate-400 border border-slate-250 dark:border-slate-800 flex items-center justify-center text-3xl font-bold flex-shrink-0">
                        {{ substr($author->name, 0, 1) }}
                    </div>
                @endif
                <div class="space-y-2 text-center sm:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 dark:bg-indigo-950/30 text-primary rounded-full text-xs font-semibold uppercase tracking-wider">
                        Technical Contributor
                    </div>
                    <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white tracking-tight">{{ $author->name }}</h1>
                    <p class="text-slate-550 dark:text-slate-400 text-sm max-w-2xl leading-relaxed">{{ $author->bio ?: 'Technical contributor at SaaSNinja Software. Publishing technical manuals, product updates, and software engineering guides.' }}</p>
                    
                    @if($author->twitter_handle || $author->github_handle)
                        <div class="flex items-center justify-center sm:justify-start gap-4 pt-2 text-xs">
                            @if($author->twitter_handle)
                                <a href="https://twitter.com/{{ $author->twitter_handle }}" target="_blank" class="text-slate-400 hover:text-primary font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">alternate_email</span> @X/Twitter
                                </a>
                            @endif
                            @if($author->github_handle)
                                <a href="https://github.com/{{ $author->github_handle }}" target="_blank" class="text-slate-400 hover:text-primary font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">code</span> GitHub Link
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white mb-6">Publications ({{ $posts->total() }})</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between group">
                    <div>
                        @if($post->featured_image)
                            <div class="h-48 w-full overflow-hidden border-b border-slate-100 dark:border-slate-800">
                                <img src="{{ $post->featured_image }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300" alt="{{ $post->title }}">
                            </div>
                        @endif
                        <div class="p-5 space-y-3">
                            <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white line-clamp-2 leading-snug">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-550 dark:text-slate-400 line-clamp-3 leading-relaxed">{{ $post->summary }}</p>
                        </div>
                    </div>

                    <div class="p-5 pt-0 border-t border-slate-50 dark:border-slate-800/80 flex items-center justify-between mt-4">
                        <div class="text-[10px] text-slate-400">
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                            <span>&bull;</span>
                            <span>{{ $post->reading_time }} min read</span>
                        </div>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-0.5">
                            Read <span class="material-symbols-outlined text-[14px]">arrow_right_alt</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500 italic">No publications authored by {{ $author->name }} yet.</div>
            @endforelse
        </div>

        <div class="pt-6">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
