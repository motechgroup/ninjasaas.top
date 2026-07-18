<x-app-layout>
    <x-slot name="header">
        Docs & Blog CMS
    </x-slot>

    <!-- Alpine Wrapper for Workspace & Modal Management -->
    <div x-data="{ 
        tab: 'blog',
        
        // Blog Modal State
        blogModalOpen: false,
        isBlogEdit: false,
        blogActionUrl: '',
        blogTitle: '',
        blogSlug: '',
        blogSummary: '',
        blogContent: '',
        blogActive: '1',

        // Docs Modal State
        docModalOpen: false,
        isDocEdit: false,
        docActionUrl: '',
        docTitle: '',
        docSlug: '',
        docCategory: '',
        docOrder: '1',
        docContent: '',
        docActive: '1',

        openBlogCreate() {
            this.isBlogEdit = false;
            this.blogActionUrl = '{{ route('admin.cms.blog.store') }}';
            this.blogTitle = '';
            this.blogSlug = '';
            this.blogSummary = '';
            this.blogContent = '';
            this.blogActive = '1';
            this.blogModalOpen = true;
        },

        openBlogEdit(post) {
            this.isBlogEdit = true;
            this.blogActionUrl = '/admin/cms/blog/' + post.id;
            this.blogTitle = post.title;
            this.blogSlug = post.slug;
            this.blogSummary = post.summary || '';
            this.blogContent = post.content || '';
            this.blogActive = post.is_published ? '1' : '0';
            this.blogModalOpen = true;
        },

        generateBlogSlug() {
            this.blogSlug = this.blogTitle
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        },

        openDocCreate() {
            this.isDocEdit = false;
            this.docActionUrl = '{{ route('admin.cms.docs.store') }}';
            this.docTitle = '';
            this.docSlug = '';
            this.docCategory = '';
            this.docOrder = '1';
            this.docContent = '';
            this.docActive = '1';
            this.docModalOpen = true;
        },

        openDocEdit(art) {
            this.isDocEdit = true;
            this.docActionUrl = '/admin/cms/docs/' + art.id;
            this.docTitle = art.title;
            this.docSlug = art.slug;
            this.docCategory = art.documentation_category_id;
            this.docOrder = art.sort_order;
            this.docContent = art.content || '';
            this.docActive = art.is_published ? '1' : '0';
            this.docModalOpen = true;
        },

        generateDocSlug() {
            this.docSlug = this.docTitle
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    }" class="space-y-6">

        <!-- Top Overview Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">article</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Blog Posts</span>
                    <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $blogPostsCount }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 p-6 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">library_books</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Documentation Articles</span>
                    <span class="text-2xl font-outfit font-extrabold text-slate-950 dark:text-white">{{ $docArticlesCount }}</span>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs Bar -->
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
            <div class="flex gap-2">
                <button @click="tab = 'blog'" :class="tab === 'blog' ? 'bg-primary text-white font-bold' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">feed</span>
                    Blog CMS Management
                </button>
                <button @click="tab = 'docs'" :class="tab === 'docs' ? 'bg-primary text-white font-bold' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                    Docs CMS Management
                </button>
            </div>
            
            <!-- Contextual Add Buttons -->
            <div>
                <button x-show="tab === 'blog'" @click="openBlogCreate()" class="px-4 py-2 bg-primary hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Create Blog Post
                </button>
                <button x-show="tab === 'docs'" @click="openDocCreate()" class="px-4 py-2 bg-primary hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5" style="display: none;">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Write Doc Article
                </button>
            </div>
        </div>

        <!-- Section 1: Blog CMS Table -->
        <div x-show="tab === 'blog'" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3.5">Post Title</th>
                            <th class="px-6 py-3.5">Slug</th>
                            <th class="px-6 py-3.5">Date Created</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @forelse ($blogPosts as $post)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $post->title }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                    {{ $post->slug }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $post->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($post->is_published)
                                        <x-badge color="green">Published</x-badge>
                                    @else
                                        <x-badge color="gray">Draft</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2.5">
                                    <button @click="openBlogEdit({{ $post->toJson() }})" class="p-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg transition-colors flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <form action="{{ route('admin.cms.blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog post?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/50 text-red-600 rounded-lg transition-colors flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">
                                    No blog posts created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Docs CMS Table -->
        <div x-show="tab === 'docs'" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden" style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3.5">Article Title</th>
                            <th class="px-6 py-3.5">Product & Category</th>
                            <th class="px-6 py-3.5">Sort Order</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @forelse ($docArticles as $art)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $art->title }}
                                    <div class="text-[11px] text-slate-500 font-mono mt-0.5">{{ $art->slug }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    <span class="font-semibold block text-slate-700 dark:text-slate-300">{{ $art->category->product->name }}</span>
                                    <span class="text-xs text-slate-400 block">{{ $art->category->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-semibold">
                                    {{ $art->sort_order }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($art->is_published)
                                        <x-badge color="green">Published</x-badge>
                                    @else
                                        <x-badge color="gray">Draft</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2.5">
                                    <button @click="openDocEdit({{ $art->toJson() }})" class="p-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg transition-colors flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <form action="{{ route('admin.cms.docs.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this doc article?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/50 text-red-600 rounded-lg transition-colors flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">
                                    No doc articles created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AlpineJS Blog Modal -->
        <div x-show="blogModalOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4" style="display: none;">
            <div @click="blogModalOpen = false" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm transition-opacity"></div>
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-2xl z-10 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/20">
                    <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base" x-text="isBlogEdit ? 'Edit Blog Article' : 'Write New Blog Article'"></h3>
                    <button @click="blogModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form :action="blogActionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isBlogEdit">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="blog_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Post Title</label>
                            <input type="text" id="blog_title" name="title" x-model="blogTitle" @input="generateBlogSlug()" required
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="blog_slug" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Slug URL</label>
                            <input type="text" id="blog_slug" name="slug" x-model="blogSlug" required
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label for="blog_summary" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Summary / Excerpt</label>
                        <input type="text" id="blog_summary" name="summary" x-model="blogSummary" required placeholder="A brief excerpt to display on home listings..."
                               class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label for="blog_content" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Content (Markdown supported)</label>
                        <textarea id="blog_content" name="content" x-model="blogContent" rows="6" required placeholder="Write blog post article body..."
                                  class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary"></textarea>
                    </div>

                    <div>
                        <label for="blog_active" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Publish Status</label>
                        <select id="blog_active" name="is_published" x-model="blogActive" required
                                class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            <option value="1">Published (Visible to Public)</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="button" @click="blogModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 text-slate-700 dark:text-slate-350 text-sm font-semibold rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary text-white text-sm font-bold rounded-lg shadow-md hover:opacity-90">
                            Save Post
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- AlpineJS Docs Modal -->
        <div x-show="docModalOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4" style="display: none;">
            <div @click="docModalOpen = false" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm transition-opacity"></div>
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-2xl z-10 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/20">
                    <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base" x-text="isDocEdit ? 'Edit Documentation Article' : 'Write Documentation Article'"></h3>
                    <button @click="docModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form :action="docActionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isDocEdit">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="doc_title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Article Title</label>
                            <input type="text" id="doc_title" name="title" x-model="docTitle" @input="generateDocSlug()" required
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="doc_slug" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Slug URL</label>
                            <input type="text" id="doc_slug" name="slug" x-model="docSlug" required
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="doc_category" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Product Category Group</label>
                            <select id="doc_category" name="documentation_category_id" x-model="docCategory" required
                                    class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="">Select Category</option>
                                @foreach ($docCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->product->name }} &raquo; {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="doc_order" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Sort Order</label>
                            <input type="number" id="doc_order" name="sort_order" x-model="docOrder" required placeholder="1"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label for="doc_content" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Article Body (Markdown supported)</label>
                        <textarea id="doc_content" name="content" x-model="docContent" rows="6" required placeholder="Detailed guide step explanations with code snippets..."
                                  class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary"></textarea>
                    </div>

                    <div>
                        <label for="doc_active" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Publish Status</label>
                        <select id="doc_active" name="is_published" x-model="docActive" required
                                class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                            <option value="1">Published (Visible to Public)</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="button" @click="docModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 text-slate-700 dark:text-slate-350 text-sm font-semibold rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary text-white text-sm font-bold rounded-lg shadow-md hover:opacity-90">
                            Save Article
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
