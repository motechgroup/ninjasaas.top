<x-app-layout>
    <x-slot name="header">
        SaaSNinja Technical Blog CMS
    </x-slot>

    <!-- Alpine State Wrapper -->
    <div x-data="{
        tab: 'articles',
        categoryModalOpen: false,
        tagModalOpen: false,
        categoryName: '',
        categorySlug: '',
        tagName: '',
        tagSlug: '',

        generateCategorySlug() {
            this.categorySlug = this.categoryName
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        },

        generateTagSlug() {
            this.tagSlug = this.tagName
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    }" class="space-y-6">

        <!-- Top Overview Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-5 rounded-2xl shadow-sm">
                <div class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Articles</div>
                <div class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $metrics['posts_count'] }}</div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-5 rounded-2xl shadow-sm">
                <div class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Published</div>
                <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-450 mt-1">{{ $metrics['published_count'] }}</div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-5 rounded-2xl shadow-sm">
                <div class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Comments</div>
                <div class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $metrics['comments_count'] }}</div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-5 rounded-2xl shadow-sm">
                <div class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Pending Comments</div>
                <div class="text-2xl font-extrabold text-amber-500 mt-1">{{ $metrics['pending_comments'] }}</div>
            </div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-5 rounded-2xl shadow-sm">
                <div class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Subscribers</div>
                <div class="text-2xl font-extrabold text-indigo-650 dark:text-indigo-400 mt-1">{{ $metrics['subscribers_count'] }}</div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-slate-200 dark:border-slate-800 flex flex-wrap gap-2">
            <button @click="tab = 'articles'" :class="tab === 'articles' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-4 py-2.5 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors">
                Articles
            </button>
            <button @click="tab = 'categories'" :class="tab === 'categories' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-4 py-2.5 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors">
                Categories
            </button>
            <button @click="tab = 'tags'" :class="tab === 'tags' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-4 py-2.5 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors">
                Tags
            </button>
            <button @click="tab = 'comments'" :class="tab === 'comments' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-4 py-2.5 border-b-2 font-bold text-xs uppercase tracking-wider relative transition-colors">
                Comments
                @if($metrics['pending_comments'] > 0)
                    <span class="absolute -top-1 -right-1 px-1.5 py-0.5 bg-amber-500 text-white font-bold text-[9px] rounded-full">{{ $metrics['pending_comments'] }}</span>
                @endif
            </button>
            <button @click="tab = 'subscribers'" :class="tab === 'subscribers' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-4 py-2.5 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors">
                Subscribers
            </button>
            <button @click="tab = 'media'" :class="tab === 'media' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-4 py-2.5 border-b-2 font-bold text-xs uppercase tracking-wider transition-colors">
                Media Library
            </button>
        </div>

        <!-- Tab contents -->
        <div>
            
            <!-- Tab: Articles -->
            <div x-show="tab === 'articles'" class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Manage Blog Articles</h3>
                    <a href="{{ route('admin.blog.create') }}" class="px-4 py-2 bg-primary hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Write Article
                    </a>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-bold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-6 py-3.5">Cover & Title</th>
                                    <th class="px-6 py-3.5">Author</th>
                                    <th class="px-6 py-3.5">Categories</th>
                                    <th class="px-6 py-3.5">Status</th>
                                    <th class="px-6 py-3.5">Published At</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-sm">
                                @forelse($posts as $post)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($post->featured_image)
                                                    <img src="{{ $post->featured_image }}" class="w-12 h-12 rounded-lg object-cover bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                                                @else
                                                    <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-950 text-slate-400 border border-slate-200 dark:border-slate-850 flex items-center justify-center font-bold text-xs">TXT</div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                                        {{ $post->title }}
                                                        @if($post->is_featured)
                                                            <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 font-bold text-[9px] rounded-md uppercase tracking-wider">Featured</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5 truncate max-w-sm">{{ $post->slug }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-655 dark:text-slate-400">{{ $post->author->name }}</td>
                                        <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                                            @foreach($post->categories as $c)
                                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-full">{{ $c->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($post->is_published)
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-450 rounded-full font-bold text-xs">Published</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-slate-100 text-slate-800 dark:bg-slate-950/40 dark:text-slate-400 rounded-full font-bold text-xs">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-500">{{ $post->published_at ? $post->published_at->format('M d, Y H:i') : 'Immediate' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.blog.edit', $post->id) }}" class="p-1.5 text-slate-400 hover:text-primary transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </a>
                                                <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this article completely?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-650 transition-colors">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 italic">No blog posts registered yet. Click 'Write Article' to start.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Categories -->
            <div x-show="tab === 'categories'" class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Post Categories</h3>
                    <button @click="categoryModalOpen = true" class="px-4 py-2 bg-primary hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Add Category
                    </button>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-bold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-3.5">Category Name</th>
                                <th class="px-6 py-3.5">Slug</th>
                                <th class="px-6 py-3.5">Description</th>
                                <th class="px-6 py-3.5">Articles Count</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($categories as $cat)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $cat->name }}</td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $cat->slug }}</td>
                                    <td class="px-6 py-4 text-slate-550 dark:text-slate-400 max-w-xs truncate">{{ $cat->description ?: 'N/A' }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-500">{{ $cat->posts_count }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.blog.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category? Posts inside will not be deleted.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">No categories created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Tags -->
            <div x-show="tab === 'tags'" class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Post Tags</h3>
                    <button @click="tagModalOpen = true" class="px-4 py-2 bg-primary hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Add Tag
                    </button>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-bold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-3.5">Tag Name</th>
                                <th class="px-6 py-3.5">Slug</th>
                                <th class="px-6 py-3.5">Used In</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($tags as $tag)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">#{{ $tag->name }}</td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $tag->slug }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $tag->posts_count }} articles</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.blog.tag.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Delete this tag?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No tags registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Comments -->
            <div x-show="tab === 'comments'" class="space-y-4">
                <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Post Comments</h3>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-bold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-3.5">Author</th>
                                <th class="px-6 py-3.5">Article</th>
                                <th class="px-6 py-3.5">Comment</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($comments as $comment)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $comment->name ?: $comment->user->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono">{{ $comment->email ?: $comment->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-650 dark:text-slate-400">
                                        <a href="{{ route('products.show', $comment->post->slug) }}" target="_blank" class="hover:underline hover:text-primary font-medium">
                                            {{ $comment->post->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-550 dark:text-slate-400 max-w-sm leading-relaxed">{{ $comment->content }}</td>
                                    <td class="px-6 py-4">
                                        @if($comment->is_approved)
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-850 dark:bg-emerald-950/40 dark:text-emerald-400 rounded-full font-bold text-xs">Approved</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-850 dark:bg-amber-950/40 dark:text-amber-450 rounded-full font-bold text-xs">Pending Approval</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(!$comment->is_approved)
                                                <form action="{{ route('admin.blog.comment.approve', $comment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-emerald-600 hover:opacity-90 text-white font-bold text-[10px] rounded-lg shadow-sm">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.blog.comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-450 hover:text-red-500 transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">No comments received yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Subscribers -->
            <div x-show="tab === 'subscribers'" class="space-y-4">
                <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Newsletter Subscribers</h3>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-bold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-3.5">Email Address</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5">Subscribed Date</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($subscribers as $sub)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $sub->email }}</td>
                                    <td class="px-6 py-4">
                                        @if($sub->is_active)
                                            <span class="px-2 py-0.5 bg-indigo-105 text-indigo-805 dark:bg-indigo-950/40 dark:text-indigo-400 rounded-full font-bold text-xs">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full font-bold text-xs">Unsubscribed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-550">{{ $sub->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.blog.subscriber.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Remove subscriber?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No active newsletter subscribers.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Media Library -->
            <div x-show="tab === 'media'" class="space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Media Library</h3>
                        <p class="text-xs text-slate-400 mt-1">Upload images to get public URLs for embedding into Markdown articles.</p>
                    </div>

                    <!-- Media Upload Form -->
                    <form action="{{ route('admin.blog.media.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <input type="file" name="file" required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-950 file:text-slate-700 dark:file:text-slate-300 hover:file:opacity-90">
                        <button type="submit" class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-xs rounded-xl hover:opacity-90 transition-opacity">
                            Upload File
                        </button>
                    </form>
                </div>

                <!-- Grid of Files -->
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                    @forelse($media as $m)
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-3 shadow-sm flex flex-col justify-between group relative overflow-hidden">
                            <div class="aspect-square rounded-lg bg-slate-50 dark:bg-slate-950 overflow-hidden border border-slate-100 dark:border-slate-800 flex items-center justify-center relative">
                                @if(str_starts_with($m->mime_type, 'image/'))
                                    <img src="{{ $m->file_path }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-[36px] text-slate-400">description</span>
                                @endif
                            </div>
                            <div class="mt-2 text-xs">
                                <div class="font-bold text-slate-900 dark:text-white truncate" title="{{ $m->file_name }}">{{ $m->file_name }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ number_format($m->file_size / 1024, 1) }} KB</div>
                            </div>
                            
                            <!-- Overlay actions on hover -->
                            <div class="absolute inset-0 bg-slate-950/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button onclick="navigator.clipboard.writeText('{{ $m->file_path }}'); alert('File path copied to clipboard: {{ $m->file_path }}')" class="p-2 bg-white text-slate-900 rounded-xl hover:bg-primary hover:text-white transition-colors" title="Copy Path">
                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                </button>
                                <form action="{{ route('admin.blog.media.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Delete this media file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-650 text-white rounded-xl hover:bg-red-700 transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-6 text-center py-8 text-slate-400 italic">No media library files uploaded.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Modal: Category Registration -->
        <div x-show="categoryModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/40 backdrop-blur-sm" style="display: none;">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Register New Category</h3>
                    <button @click="categoryModalOpen = false" class="text-slate-400 hover:text-slate-650">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.blog.category.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Category Name</label>
                        <input type="text" name="name" x-model="categoryName" @input="generateCategorySlug()" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-950 dark:text-white text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Slug URL</label>
                        <input type="text" name="slug" x-model="categorySlug" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-950 dark:text-white text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Description</label>
                        <textarea name="description" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-950 dark:text-white text-sm py-2 px-3" rows="2"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-primary text-white font-bold text-xs rounded-xl shadow-md uppercase tracking-wider">Save Category</button>
                </form>
            </div>
        </div>

        <!-- Modal: Tag Registration -->
        <div x-show="tagModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/40 backdrop-blur-sm" style="display: none;">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Register New Tag</h3>
                    <button @click="tagModalOpen = false" class="text-slate-400 hover:text-slate-650">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.blog.tag.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Tag Name</label>
                        <input type="text" name="name" x-model="tagName" @input="generateTagSlug()" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-950 dark:text-white text-sm py-2 px-3" placeholder="e.g. Laravel">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Slug URL</label>
                        <input type="text" name="slug" x-model="tagSlug" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-950 dark:text-white text-sm py-2 px-3">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-primary text-white font-bold text-xs rounded-xl shadow-md uppercase tracking-wider">Save Tag</button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
