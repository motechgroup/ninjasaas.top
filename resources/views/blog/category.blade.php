@extends('layouts.public')

@section('title', 'Category: ' . $category->name . ' - SaaSNinja Blog')

@section('content')
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <a href="{{ route('blog.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mb-4">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Blog
            </a>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 dark:bg-indigo-950/30 text-primary rounded-full text-xs font-semibold uppercase tracking-wider mb-3">
                Category Folder
            </div>
            <h1 class="font-outfit font-extrabold text-4xl text-slate-900 dark:text-white mb-2">{{ $category->name }}</h1>
            <p class="text-slate-500 text-sm max-w-xl">{{ $category->description ?: 'Browse all publications filed under the ' . $category->name . ' technology segment.' }}</p>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Side: Article Grid -->
            <div class="lg:col-span-2 space-y-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($posts as $post)
                        <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between group">
                            <div>
                                @if($post->featured_image)
                                    <div class="h-48 w-full overflow-hidden border-b border-slate-100 dark:border-slate-800">
                                        <img src="{{ $post->featured_image }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300" alt="{{ $post->title }}">
                                    </div>
                                @endif
                                <div class="p-5 space-y-3">
                                    <h3 class="font-outfit font-bold text-lg text-slate-900 dark:text-white line-clamp-2 leading-snug">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">{{ $post->summary }}</p>
                                </div>
                            </div>

                            <div class="p-5 pt-0 border-t border-slate-50 dark:border-slate-800/80 flex items-center justify-between mt-4">
                                <div class="text-[10px] text-slate-400 space-y-0.5">
                                    <div class="font-semibold text-slate-650 dark:text-slate-350">By <a href="{{ route('blog.author', $post->author->id) }}" class="hover:underline text-slate-800 dark:text-white">{{ $post->author->name }}</a></div>
                                    <div class="flex items-center gap-1">
                                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $post->reading_time }} min read</span>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-0.5">
                                    Read <span class="material-symbols-outlined text-[14px]">arrow_right_alt</span>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-2 text-center py-12 text-slate-500 italic">No publications created under this category yet.</div>
                    @endforelse
                </div>

                <div class="pt-6">
                    {{ $posts->links() }}
                </div>
            </div>

            <!-- Right Side: Sidebar categories -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm space-y-4">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">All Categories</h4>
                    <ul class="space-y-2 text-xs font-semibold">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('blogCategory', $cat->slug) }}" class="flex items-center justify-between {{ $category->id === $cat->id ? 'text-primary font-bold' : 'text-slate-655 dark:text-slate-400 hover:text-primary' }} transition-colors">
                                    <span>{{ $cat->name }}</span>
                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] text-slate-500 rounded-md font-mono">{{ $cat->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection
