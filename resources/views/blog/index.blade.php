@extends('layouts.public')

@section('title', 'SaaSNinja Developer Blog & Resource Center')

@section('content')
    <!-- Blog Hero header -->
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 rounded-full text-xs font-semibold mb-6">
                <span class="material-symbols-outlined text-[16px] text-primary">terminal</span>
                Technical Publications
            </div>
            <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-slate-900 dark:text-white mb-4 tracking-tight">SaaSNinja Engineering Blog</h1>
            <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto">Deep dives into Laravel, DevOps, API automation, server optimizations, and software business insights.</p>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Side: Article Grid & Pagination -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Featured Article (if matching page 1 and present) -->
                @if(request()->get('page', 1) == 1 && $featuredPost && !request()->has('q') && !request()->has('category') && !request()->has('tag'))
                    <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow relative group">
                        @if($featuredPost->featured_image)
                            <div class="h-64 sm:h-96 w-full overflow-hidden relative">
                                <img src="{{ $featuredPost->featured_image }}" class="w-full h-full object-cover group-hover:scale-[1.01] transition-transform duration-500" alt="{{ $featuredPost->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                                <div class="absolute bottom-6 left-6 right-6 text-white space-y-2">
                                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-indigo-300">
                                        <span class="px-2 py-0.5 bg-primary/80 rounded-md text-[10px] text-white">Featured Post</span>
                                        @foreach($featuredPost->categories as $c)
                                            <span>&bull; {{ $c->name }}</span>
                                        @endforeach
                                    </div>
                                    <h2 class="font-outfit font-extrabold text-xl sm:text-3xl text-white tracking-tight">
                                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="hover:text-indigo-200">
                                            {{ $featuredPost->title }}
                                        </a>
                                    </h2>
                                    <p class="text-slate-250 text-xs sm:text-sm line-clamp-2 max-w-2xl leading-relaxed">{{ $featuredPost->summary }}</p>
                                </div>
                            </div>
                        @else
                            <div class="p-6 sm:p-8 space-y-4">
                                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-primary">
                                    <span class="px-2 py-0.5 bg-primary text-white rounded-md text-[10px]">Featured Post</span>
                                    @foreach($featuredPost->categories as $c)
                                        <span>&bull; {{ $c->name }}</span>
                                    @endforeach
                                </div>
                                <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white tracking-tight">
                                    <a href="{{ route('blog.show', $featuredPost->slug) }}" class="hover:text-primary transition-colors">
                                        {{ $featuredPost->title }}
                                    </a>
                                </h2>
                                <p class="text-slate-500 text-sm leading-relaxed">{{ $featuredPost->summary }}</p>
                            </div>
                        @endif
                        
                        <div class="p-6 flex items-center justify-between border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20">
                            <div class="flex items-center gap-2.5 text-xs text-slate-500">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">By {{ $featuredPost->author->name }}</span>
                                <span>&bull;</span>
                                <span>{{ $featuredPost->published_at ? $featuredPost->published_at->format('M d, Y') : $featuredPost->created_at->format('M d, Y') }}</span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-[14px]">schedule</span>{{ $featuredPost->reading_time }} min read</span>
                            </div>
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-0.5">
                                Read Article <span class="material-symbols-outlined text-[16px]">arrow_right_alt</span>
                            </a>
                        </div>
                    </article>
                @endif

                <!-- Standard Article Listing Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($posts as $post)
                        <!-- If featured article was displayed on page 1, skip repeating it here -->
                        @if(request()->get('page', 1) == 1 && $featuredPost && $post->id === $featuredPost->id && !request()->has('q') && !request()->has('category') && !request()->has('tag'))
                            @continue
                        @endif
                        <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between group">
                            <div>
                                @if($post->featured_image)
                                    <div class="h-48 w-full overflow-hidden border-b border-slate-100 dark:border-slate-800">
                                        <img src="{{ $post->featured_image }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300" alt="{{ $post->title }}">
                                    </div>
                                @endif
                                <div class="p-5 space-y-3">
                                    <div class="flex flex-wrap items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-primary">
                                        @foreach($post->categories as $c)
                                            <a href="{{ route('blog.index', ['category' => $c->slug]) }}" class="hover:underline">{{ $c->name }}</a>
                                        @endforeach
                                    </div>
                                    <h3 class="font-outfit font-bold text-lg text-slate-900 dark:text-white line-clamp-2 leading-snug">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-550 dark:text-slate-400 line-clamp-3 leading-relaxed">{{ $post->summary }}</p>
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
                        <div class="col-span-2 text-center py-12 text-slate-500 italic">No publications matched your search query or filters.</div>
                    @endforelse
                </div>

                <!-- Pagination Links -->
                <div class="pt-6">
                    {{ $posts->links() }}
                </div>

            </div>

            <!-- Right Side: Sidebar Widgets -->
            <div class="space-y-6">
                
                <!-- Search Widget -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm space-y-4">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Search Publications</h4>
                    <form action="{{ route('blog.index') }}" method="GET" class="relative">
                        <!-- Keep category/tag filters if already active -->
                        @if(request()->has('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request()->has('tag')) <input type="hidden" name="tag" value="{{ request('tag') }}"> @endif
                        
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles, code, logs..." class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 pr-10 focus:ring-primary focus:border-primary">
                        <button type="submit" class="absolute top-2 right-2.5 text-slate-400 hover:text-primary">
                            <span class="material-symbols-outlined text-[20px]">search</span>
                        </button>
                    </form>
                    @if(request()->has('q') || request()->has('category') || request()->has('tag'))
                        <div class="text-xs">
                            <a href="{{ route('blog.index') }}" class="text-red-500 hover:underline flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[14px]">close</span> Clear Search & Filters
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Sorting and Popularity Filters -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm space-y-3">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Sort By</h4>
                    <div class="flex flex-col gap-2 text-xs">
                        <a href="{{ route('blog.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg {{ request('sort') !== 'popular' ? 'bg-slate-50 dark:bg-slate-800 font-bold text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-950/40' }}">
                            Latest Articles
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                        </a>
                        <a href="{{ route('blog.index', array_merge(request()->query(), ['sort' => 'popular'])) }}" class="flex items-center justify-between px-3 py-2 rounded-lg {{ request('sort') === 'popular' ? 'bg-slate-50 dark:bg-slate-800 font-bold text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-950/40' }}">
                            Most Popular (Comments count)
                            <span class="material-symbols-outlined text-[16px]">trending_up</span>
                        </a>
                    </div>
                </div>

                <!-- Newsletter Subscriber -->
                <div class="bg-indigo-650 text-white p-6 rounded-2xl shadow-sm space-y-4 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-800/40 to-transparent"></div>
                    <div class="relative z-10 space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">mail</span>
                        </div>
                        <h4 class="font-outfit font-bold text-base leading-snug">Subscribe to SaaSNinja Newsletter</h4>
                        <p class="text-xs text-indigo-200 leading-relaxed">Weekly technical digests covering Laravel configurations, server optimization scripts, and SaaS dev tips.</p>
                        
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                            @csrf
                            <input type="email" name="email" required placeholder="Enter your email" class="block w-full rounded-lg border-0 bg-white/10 text-white placeholder-indigo-300 text-xs py-2 px-3 focus:ring-2 focus:ring-white">
                            <button type="submit" class="w-full py-2 bg-white text-indigo-900 font-bold text-xs rounded-lg hover:bg-indigo-50 transition-colors uppercase tracking-wider">Join Newsletter</button>
                        </form>
                    </div>
                </div>

                <!-- Popular Articles list -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm space-y-4">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Popular Publications</h4>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 space-y-3">
                        @foreach($popularPosts as $pop)
                            <div class="pt-3 first:pt-0 flex items-start gap-3">
                                @if($pop->featured_image)
                                    <img src="{{ $pop->featured_image }}" class="w-12 h-12 rounded-lg object-cover bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                @endif
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-2 leading-snug">
                                        <a href="{{ route('blog.show', $pop->slug) }}" class="hover:text-primary transition-colors">
                                            {{ $pop->title }}
                                        </a>
                                    </h5>
                                    <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                        <span>{{ $pop->published_at ? $pop->published_at->format('M d, Y') : $pop->created_at->format('M d, Y') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $pop->comments_count }} comments</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Categories list -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm space-y-4">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Categories</h4>
                    <ul class="space-y-2 text-xs font-semibold">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('blogCategory', $cat->slug) }}" class="flex items-center justify-between text-slate-655 dark:text-slate-400 hover:text-primary transition-colors">
                                    <span>{{ $cat->name }}</span>
                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] text-slate-500 rounded-md font-mono">{{ $cat->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Tags list -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm space-y-4">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Tags Cloud</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <a href="{{ route('blogTag', $tag->slug) }}" class="px-2.5 py-1 bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white border border-slate-200/60 dark:border-slate-800 text-[11px] rounded-lg transition-colors font-medium">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
