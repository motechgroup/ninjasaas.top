<x-app-layout>
    <x-slot name="header">
        Docs & Blog CMS
    </x-slot>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Blog Articles</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $blogPostsCount }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Documentation articles</span>
                <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $docArticlesCount }}</span>
            </div>
        </div>
    </div>

    <!-- Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Blog posts -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Recent Blog Posts</h3>
            </div>
            <div class="p-6">
                @if ($blogPosts->isEmpty())
                    <p class="text-slate-500 text-sm text-center py-6">No blog posts written yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($blogPosts as $post)
                            <li class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-3 last:border-0 last:pb-0 text-xs">
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $post->title }}</span>
                                    <span class="text-slate-400 mt-0.5 block">Published {{ $post->created_at->format('Y-m-d') }}</span>
                                </div>
                                <div>
                                    @if ($post->is_published)
                                        <x-badge color="green">Published</x-badge>
                                    @else
                                        <x-badge color="gray">Draft</x-badge>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Documentation Articles -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Recent Docs Articles</h3>
            </div>
            <div class="p-6">
                @if ($docArticles->isEmpty())
                    <p class="text-slate-500 text-sm text-center py-6">No documentation articles added yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($docArticles as $art)
                            <li class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-3 last:border-0 last:pb-0 text-xs">
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $art->title }}</span>
                                    <span class="text-slate-400 mt-0.5 block">Product: {{ $art->category->product->name }}</span>
                                </div>
                                <div>
                                    @if ($art->is_published)
                                        <x-badge color="green">Published</x-badge>
                                    @else
                                        <x-badge color="gray">Draft</x-badge>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
