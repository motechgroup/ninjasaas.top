@extends('layouts.public')

@section('title', ($post->seo_title ?: $post->title) . ' - SaaSNinja Blog')

@section('content')
    <!-- Prism.js Syntax Highlighting Style Sheet from CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" integrity="sha512-YqdqGgGBBEj75220Y1784482196447" crossorigin="anonymous" />

    <div class="max-w-7xl mx-auto w-full px-6 py-12 flex flex-col lg:flex-row relative gap-8">
        
        <!-- Left Sticky Sidebar (Share & Actions) -->
        <aside class="hidden lg:flex flex-col items-center gap-4 w-12 sticky top-24 h-fit shrink-0">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Share</span>
            
            <!-- Twitter / X -->
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 rounded-full border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-primary hover:border-primary flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[18px]">share</span>
            </a>
            
            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 rounded-full border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-primary hover:border-primary flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[18px]">thumb_up</span>
            </a>
        </aside>

        <!-- Main Content Column -->
        <main class="flex-1 min-w-0 py-2">
            
            <!-- Breadcrumbs -->
            <nav aria-label="Breadcrumb" class="flex items-center text-xs font-medium text-slate-500 dark:text-slate-400 mb-6">
                <a class="hover:text-primary transition-colors" href="{{ route('blog.index') }}">Blog</a>
                <span class="material-symbols-outlined text-[16px] mx-1 text-slate-400">chevron_right</span>
                @if($post->categories->first())
                    <a class="hover:text-primary transition-colors" href="{{ route('blogCategory', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                    <span class="material-symbols-outlined text-[16px] mx-1 text-slate-400">chevron_right</span>
                @endif
                <span class="text-slate-900 dark:text-white font-bold truncate max-w-xs">{{ $post->title }}</span>
            </nav>

            <!-- Title & Metadata -->
            <div class="space-y-4 mb-8">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($post->categories as $c)
                        <span class="px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-primary text-[10px] font-bold uppercase tracking-wider rounded-md">{{ $c->name }}</span>
                    @endforeach
                </div>
                
                <h1 class="font-outfit font-extrabold text-3xl sm:text-5xl text-slate-900 dark:text-white tracking-tight leading-tight">
                    {{ $post->title }}
                </h1>
                
                @if($post->subtitle)
                    <p class="text-base text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                        {{ $post->subtitle }}
                    </p>
                @endif

                <div class="flex flex-wrap items-center gap-3 pt-2 text-xs text-slate-500 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <span class="font-semibold text-slate-700 dark:text-slate-350">By <a href="{{ route('blog.author', $post->author->id) }}" class="hover:underline text-primary">{{ $post->author->name }}</a></span>
                    <span>&bull;</span>
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                    <span>&bull;</span>
                    <span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-[14px]">schedule</span>{{ $post->reading_time }} min read</span>
                </div>
            </div>

            <!-- Featured Image Cover -->
            @if($post->featured_image)
                <div class="w-full h-64 sm:h-96 rounded-3xl overflow-hidden mb-8 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <img src="{{ $post->featured_image }}" class="w-full h-full object-cover" alt="{{ $post->title }}">
                </div>
            @endif

            <!-- Article Content (Compiled Prose Markdown) -->
            <article class="prose prose-slate dark:prose-invert max-w-none prose-content mb-12">
                <div class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed space-y-6">
                    {!! $post->html_content !!}
                </div>
            </article>

            <!-- Download Attachments Box -->
            @if($post->attachments && count($post->attachments) > 0)
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl mb-8 space-y-3">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">download_for_offline</span>
                        Associated Downloadable Resources
                    </h4>
                    <ul class="divide-y divide-slate-200 dark:divide-slate-800 text-xs font-semibold space-y-2">
                        @foreach($post->attachments as $att)
                            <li class="pt-2 first:pt-0 flex items-center justify-between">
                                <span class="text-slate-655 dark:text-slate-400">{{ $att['name'] }}</span>
                                <a href="{{ $att['url'] }}" target="_blank" download class="px-3 py-1 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] rounded-lg shadow-sm hover:opacity-90">
                                    Download
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Dynamic FAQs Box -->
            @if($post->faq && count($post->faq) > 0)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl mb-8 space-y-4">
                    <h4 class="font-outfit font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">question_answer</span>
                        Frequently Asked Questions (FAQ)
                    </h4>
                    <div x-data="{ activeFaq: null }" class="space-y-2 text-xs">
                        @foreach($post->faq as $index => $item)
                            <div class="border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-950/20">
                                <button type="button" @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})" class="w-full text-left p-4 font-bold text-slate-900 dark:text-white flex items-center justify-between">
                                    <span>{{ $item['question'] }}</span>
                                    <span class="material-symbols-outlined text-[16px] transform transition-transform" :class="activeFaq === {{ $index }} ? 'rotate-180' : ''">expand_more</span>
                                </button>
                                <div x-show="activeFaq === {{ $index }}" class="p-4 pt-0 text-slate-550 dark:text-slate-400 leading-relaxed font-medium border-t border-slate-100 dark:border-slate-800/80">
                                    {{ $item['answer'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Previous/Next Navigation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-b border-slate-100 dark:border-slate-800 py-6 mb-8">
                @if($prevPost)
                    <a href="{{ route('blog.show', $prevPost->slug) }}" class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 hover:border-primary/30 rounded-2xl flex flex-col items-start gap-1 group text-xs font-semibold">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest flex items-center gap-0.5"><span class="material-symbols-outlined text-[14px]">arrow_back</span> Previous Publication</span>
                        <span class="text-slate-900 dark:text-white group-hover:text-primary transition-colors text-sm line-clamp-1">{{ $prevPost->title }}</span>
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextPost)
                    <a href="{{ route('blog.show', $nextPost->slug) }}" class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 hover:border-primary/30 rounded-2xl flex flex-col items-end gap-1 group text-xs font-semibold text-right">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest flex items-center gap-0.5">Next Publication <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                        <span class="text-slate-900 dark:text-white group-hover:text-primary transition-colors text-sm line-clamp-1">{{ $nextPost->title }}</span>
                    </a>
                @endif
            </div>

            <!-- Author Bio Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex flex-col sm:flex-row items-center sm:items-start gap-4 mb-8">
                @if($post->author->profile_image)
                    <img src="{{ $post->author->profile_image }}" class="w-16 h-16 rounded-full object-cover bg-slate-100 border border-slate-200 dark:border-slate-800 flex-shrink-0">
                @else
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-950 text-slate-400 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-xl font-bold flex-shrink-0">
                        {{ substr($post->author->name, 0, 1) }}
                    </div>
                @endif
                <div class="space-y-2 text-center sm:text-left">
                    <h4 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Written by <a href="{{ route('blog.author', $post->author->id) }}" class="text-primary hover:underline">{{ $post->author->name }}</a></h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium max-w-2xl">{{ $post->author->bio ?: 'Technical contributor at SaaSNinja Software. Publishing documentation and code automation recipes.' }}</p>
                    
                    @if($post->author->twitter_handle || $post->author->github_handle)
                        <div class="flex items-center justify-center sm:justify-start gap-3 pt-1 text-xs">
                            @if($post->author->twitter_handle)
                                <a href="https://twitter.com/{{ $post->author->twitter_handle }}" target="_blank" class="text-slate-400 hover:text-primary font-bold">@X/Twitter</a>
                            @endif
                            @if($post->author->github_handle)
                                <a href="https://github.com/{{ $post->author->github_handle }}" target="_blank" class="text-slate-400 hover:text-primary font-bold">GitHub</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Newsletter Signup banner -->
            <div class="bg-indigo-650 text-white p-6 sm:p-8 rounded-3xl shadow-sm relative overflow-hidden mb-8">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-800/40 to-transparent"></div>
                <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div class="space-y-2">
                        <h4 class="font-outfit font-bold text-lg sm:text-xl leading-snug">Enjoyed reading? Get more engineering tips!</h4>
                        <p class="text-xs text-indigo-200">Join our mailing list to receive PHP script guides, Linux configurations, and Laravel security advisories directly.</p>
                    </div>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="email" name="email" required placeholder="Enter your email" class="flex-1 rounded-lg border-0 bg-white/10 text-white placeholder-indigo-300 text-xs py-2.5 px-3 focus:ring-2 focus:ring-white">
                        <button type="submit" class="px-4 py-2.5 bg-white text-indigo-900 font-bold text-xs rounded-lg hover:bg-indigo-50 transition-colors uppercase tracking-wider">Subscribe</button>
                    </form>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="space-y-6">
                <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-lg border-b border-slate-100 dark:border-slate-800 pb-3">Comments ({{ count($post->approvedComments) }})</h3>
                
                <!-- Comments list -->
                <div class="space-y-4">
                    @forelse($post->approvedComments as $comment)
                        <div class="bg-slate-50 dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl flex gap-3 text-xs">
                            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 font-bold flex items-center justify-center text-sm flex-shrink-0">
                                {{ substr($comment->name ?: $comment->user->name, 0, 1) }}
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $comment->name ?: $comment->user->name }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-655 dark:text-slate-400 leading-relaxed font-medium">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-450 dark:text-slate-500 italic text-xs">No comments yet. Be the first to share your thoughts!</p>
                    @endforelse
                </div>

                <!-- Add Comment Form -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <h4 class="font-outfit font-bold text-sm text-slate-900 dark:text-white">Leave a Comment</h4>
                    
                    <form action="{{ route('blog.comment.store', $post->id) }}" method="POST" class="space-y-4 text-xs font-semibold">
                        @csrf
                        @if(!auth()->check())
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-slate-500 mb-1 uppercase text-[10px]">Full Name</label>
                                    <input type="text" name="name" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                                <div>
                                    <label class="block text-slate-500 mb-1 uppercase text-[10px]">Email Address</label>
                                    <input type="email" name="email" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        @else
                            <div class="text-[10px] text-slate-400">Posting as <strong class="text-slate-900 dark:text-white">{{ auth()->user()->name }}</strong></div>
                        @endif
                        
                        <div>
                            <label class="block text-slate-500 mb-1 uppercase text-[10px]">Your Comment</label>
                            <textarea name="content" required rows="4" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3 focus:ring-primary focus:border-primary" placeholder="Type comment (minimum 5 characters)..."></textarea>
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-primary text-white font-bold text-xs rounded-xl shadow-md uppercase tracking-wider hover:opacity-90 transition-opacity">Submit Comment</button>
                    </form>
                </div>
            </div>

        </main>

        <!-- Right Sticky Sidebar (Table of Contents & Related Posts) -->
        <aside class="hidden xl:block w-64 sticky top-24 h-fit py-2 pl-4 shrink-0 border-l border-slate-100 dark:border-slate-800">
            <div id="toc-container" class="space-y-6 mb-8">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">On this page</h4>
                <ul id="toc-list" class="space-y-3 text-xs">
                    <!-- Javascript populates items here -->
                </ul>
            </div>

            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Related Articles</h4>
                <div class="space-y-4 text-xs font-semibold">
                    @forelse($relatedPosts as $rel)
                        <div class="space-y-1">
                            <h5 class="font-bold text-slate-900 dark:text-white line-clamp-2 leading-snug">
                                <a href="{{ route('blog.show', $rel->slug) }}" class="hover:text-primary transition-colors">
                                    {{ $rel->title }}
                                </a>
                            </h5>
                            <span class="text-[10px] text-slate-400">{{ $rel->published_at ? $rel->published_at->format('M d, Y') : $rel->created_at->format('M d, Y') }}</span>
                        </div>
                    @empty
                        <p class="text-slate-400 italic text-[11px]">No related articles found.</p>
                    @endforelse
                </div>
            </div>
        </aside>

    </div>

    <!-- JS for ToC Auto-Generation and Prism syntax copy buttons -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Auto-generate Table of Contents
            const articleBody = document.querySelector('.prose-content');
            const tocList = document.getElementById('toc-list');
            if (articleBody && tocList) {
                const headings = articleBody.querySelectorAll('h2, h3');
                if (headings.length === 0) {
                    document.getElementById('toc-container')?.remove();
                } else {
                    headings.forEach((heading, index) => {
                        const id = `heading-${index}`;
                        heading.setAttribute('id', id);

                        const li = document.createElement('li');
                        const a = document.createElement('a');
                        a.setAttribute('href', `#${id}`);
                        a.textContent = heading.textContent;
                        a.className = "hover:text-primary transition-all block " + 
                                      (heading.tagName.toLowerCase() === 'h3' 
                                        ? 'pl-6 text-[10px] text-slate-400' 
                                        : 'pl-3 text-slate-500 hover:border-l-2 hover:border-primary pl-3');
                        li.appendChild(a);
                        tocList.appendChild(li);
                    });
                }
            }

            // Code Blocks Copy Button
            const preBlocks = document.querySelectorAll('pre');
            preBlocks.forEach((pre) => {
                pre.classList.add('relative', 'group');
                const button = document.createElement('button');
                button.className = "absolute top-2 right-2 px-2 py-1 bg-slate-900/60 hover:bg-slate-900/80 text-white rounded text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20";
                button.textContent = "Copy";
                button.type = "button";
                button.onclick = () => {
                    const code = pre.querySelector('code')?.textContent || pre.textContent;
                    navigator.clipboard.writeText(code).then(() => {
                        button.textContent = "Copied!";
                        setTimeout(() => { button.textContent = "Copy"; }, 2000);
                    });
                };
                pre.appendChild(button);
            });
        });
    </script>

    <!-- Prism.js core + Autoloader scripts from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js" integrity="sha512-9khzCwUBEV34aXsC5H2eR96sUaV5P8T5aR1772196447" crossorigin="anonymous" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" integrity="sha512-YqdqGgGBBEj75220Y1784482196447" crossorigin="anonymous" defer></script>
@endsection
