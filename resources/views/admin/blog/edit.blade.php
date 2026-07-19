<x-app-layout>
    <x-slot name="header">
        Edit Blog Article: {{ $post->title }}
    </x-slot>

    <!-- Alpine State Wrapper -->
    <div x-data="{
        title: '{{ addslashes($post->title) }}',
        subtitle: '{{ addslashes($post->subtitle) }}',
        slug: '{{ addslashes($post->slug) }}',
        summary: '{{ addslashes($post->summary) }}',
        content: `{{ addslashes($post->content) }}`,
        seoTitle: '{{ addslashes($post->seo_title) }}',
        seoDescription: '{{ addslashes($post->seo_description) }}',
        isFeatured: '{{ $post->is_featured ? '1' : '0' }}',
        isPublished: '{{ $post->is_published ? '1' : '0' }}',
        publishedAt: '{{ $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '' }}',
        faqs: {{ json_encode($post->faq ?: []) }},
        attachments: {{ json_encode($post->attachments ?: []) }},

        generateSlug() {
            this.slug = this.title
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        },

        addFaq() {
            this.faqs.push({ question: '', answer: '' });
        },

        removeFaq(index) {
            this.faqs.splice(index, 1);
        },

        addAttachment() {
            this.attachments.push({ name: '', url: '' });
        },

        removeAttachment(index) {
            this.attachments.splice(index, 1);
        },

        get wordCount() {
            return this.content ? this.content.trim().split(/\s+/).filter(Boolean).length : 0;
        },

        get estReadingTime() {
            return Math.max(1, Math.ceil(this.wordCount / 200));
        },

        get seoScore() {
            let score = 100;
            if (this.title.length < 10) score -= 15;
            if (this.title.length > 70) score -= 10;
            if (this.seoDescription.length < 50) score -= 20;
            if (this.seoDescription.length > 160) score -= 10;
            if (this.wordCount < 300) score -= 30;
            return Math.max(0, score);
        }
    }" class="space-y-6">

        <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            @method('PUT')

            <!-- Left Form Area (2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Main details -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Article Specifications</h3>

                    <!-- Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Article Title</label>
                        <input type="text" name="title" x-model="title" @input="generateSlug()" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-955 text-slate-900 dark:text-white text-sm py-2.5 px-3">
                    </div>

                    <!-- Subtitle -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Subtitle / Excerpt Banner</label>
                        <input type="text" name="subtitle" x-model="subtitle" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2.5 px-3">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Slug Identifier</label>
                        <input type="text" name="slug" x-model="slug" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                    </div>

                    <!-- Summary -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Short Excerpt (SEO/Meta summaries)</label>
                        <textarea name="summary" x-model="summary" rows="2" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3"></textarea>
                    </div>
                </div>

                <!-- Markdown Content & Live Preview -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Long-Form Markdown Editor</h3>
                        <div class="text-xs font-semibold text-slate-500">
                            Word Count: <span x-text="wordCount"></span> | Est. Reading Time: <span x-text="estReadingTime"></span> min
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Markdown text area -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase">Markdown Editor</label>
                            <textarea name="content" x-model="content" required rows="16" class="block w-full font-mono rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-955 text-slate-900 dark:text-white text-xs py-2 px-3" placeholder="# Heading 1&#10;Write content in Markdown..."></textarea>
                        </div>
                        <!-- Live preview box -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase">Live Side-by-Side Preview</label>
                            <div class="border border-slate-200 dark:border-slate-800 dark:bg-slate-950 rounded-lg p-3 min-h-[300px] h-[340px] overflow-y-auto prose prose-xs dark:prose-invert">
                                <div class="text-slate-655 dark:text-slate-400 text-xs whitespace-pre-wrap" x-text="content || 'Editor preview will compile here...' "></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic FAQ Generator -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">FAQ Accordions</h3>
                        <button type="button" @click="addFaq()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-lg hover:opacity-90 transition-opacity">
                            Add FAQ Row
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(faq, index) in faqs" :key="index">
                            <div class="p-4 bg-slate-550 dark:bg-slate-950 rounded-xl space-y-3 relative">
                                <button type="button" @click="removeFaq(index)" class="absolute top-2 right-2 text-slate-400 hover:text-red-500">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Question</label>
                                    <input type="text" name="faq_questions[]" x-model="faq.question" class="block w-full rounded-lg border-slate-250 dark:border-slate-800 dark:bg-slate-900 text-slate-900 dark:text-white text-xs py-1.5 px-3">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Answer</label>
                                    <textarea name="faq_answers[]" x-model="faq.answer" rows="2" class="block w-full rounded-lg border-slate-250 dark:border-slate-800 dark:bg-slate-900 text-slate-900 dark:text-white text-xs py-1.5 px-3"></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Dynamic Attachments -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Downloadable Resource Attachments</h3>
                        <button type="button" @click="addAttachment()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-lg hover:opacity-90 transition-opacity">
                            Add Attachment
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(att, index) in attachments" :key="index">
                            <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-950 p-3 rounded-xl relative">
                                <div class="flex-1">
                                    <input type="text" name="attachment_names[]" x-model="att.name" placeholder="File Display Name" class="block w-full rounded-lg border-slate-250 dark:border-slate-800 dark:bg-slate-900 text-slate-900 dark:text-white text-xs py-1.5 px-3">
                                </div>
                                <div class="flex-1">
                                    <input type="text" name="attachment_urls[]" x-model="att.url" placeholder="Direct Download URL" class="block w-full rounded-lg border-slate-250 dark:border-slate-800 dark:bg-slate-900 text-slate-900 dark:text-white text-xs py-1.5 px-3">
                                </div>
                                <button type="button" @click="removeAttachment(index)" class="text-slate-400 hover:text-red-500 pr-1">
                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar Area (1 column) -->
            <div class="space-y-6">
                
                <!-- Publishing & Status -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">Status & Categories</h3>

                    <!-- Is Published -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Publishing Status</label>
                        <select name="is_published" x-model="isPublished" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                            <option value="1">Published (Publicly Visible)</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>

                    <!-- Scheduled Publishing Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Scheduled Date (Optional)</label>
                        <input type="datetime-local" name="published_at" x-model="publishedAt" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3">
                        <p class="text-[10px] text-slate-400 mt-1">Leave empty to publish immediately.</p>
                    </div>

                    <!-- Is Featured -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Featured Placement</label>
                        <select name="is_featured" x-model="isFeatured" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-955 text-slate-900 dark:text-white text-sm py-2 px-3">
                            <option value="0">Standard Post</option>
                            <option value="1">Featured (Top Carousel)</option>
                        </select>
                    </div>

                    <!-- Featured Image Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Featured Cover Image</label>
                        @if($post->featured_image)
                            <div class="mb-2">
                                <img src="{{ $post->featured_image }}" class="w-full h-24 object-cover rounded-lg border border-slate-200 dark:border-slate-800">
                                <p class="text-[10px] text-slate-400 mt-1">Current cover image</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="block w-full rounded-lg border border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2 px-3">
                    </div>

                    <!-- Category checkboxes -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Category Assignment</label>
                        <div class="space-y-2 border border-slate-200 dark:border-slate-800 rounded-lg p-3 max-h-40 overflow-y-auto bg-slate-50 dark:bg-slate-950">
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" 
                                           @if($post->categories->contains($cat->id)) checked @endif
                                           class="rounded text-primary focus:ring-primary">
                                    {{ $cat->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tags checkboxes -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Tags Assignment</label>
                        <div class="space-y-2 border border-slate-200 dark:border-slate-800 rounded-lg p-3 max-h-40 overflow-y-auto bg-slate-50 dark:bg-slate-950">
                            @foreach($tags as $tag)
                                <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                           @if($post->tags->contains($tag->id)) checked @endif
                                           class="rounded text-primary focus:ring-primary">
                                    #{{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SEO Panel & Analysis -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-sm">SEO Metadata</h3>
                        <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-primary rounded text-xs font-bold font-mono">
                            Score: <span x-text="seoScore"></span>%
                        </span>
                    </div>

                    <!-- SEO Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">SEO Title Tag</label>
                        <input type="text" name="seo_title" x-model="seoTitle" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3" placeholder="If empty, matches title...">
                    </div>

                    <!-- SEO Description -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Meta Description</label>
                        <textarea name="seo_description" x-model="seoDescription" rows="3" class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-905 text-slate-900 dark:text-white text-sm py-2 px-3" placeholder="Type Meta Description (Ideal length: 120-160 characters)..."></textarea>
                        <div class="text-[10px] text-right mt-1" :class="seoDescription.length >= 120 && seoDescription.length <= 160 ? 'text-emerald-500' : 'text-slate-400'">
                            Length: <span x-text="seoDescription.length"></span> chars (Target: 120-160)
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm">
                    <button type="submit" class="w-full py-3 bg-primary text-white font-bold text-xs rounded-xl shadow-md uppercase tracking-wider">
                        Update Article
                    </button>
                    <a href="{{ route('admin.blog.index') }}" class="block text-center mt-3 text-xs text-slate-500 hover:text-slate-800 dark:hover:text-white">
                        Discard & Back
                    </a>
                </div>

            </div>

        </form>

    </div>
</x-app-layout>
