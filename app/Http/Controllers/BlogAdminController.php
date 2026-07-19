<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogComment;
use App\Models\NewsletterSubscriber;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogAdminController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['author', 'categories'])->latest()->get();
        $categories = BlogCategory::withCount('posts')->get();
        $tags = BlogTag::withCount('posts')->get();
        $comments = BlogComment::with(['post', 'user'])->latest()->get();
        $subscribers = NewsletterSubscriber::latest()->get();
        
        // Fetch media files uploaded for blog
        $media = MediaFile::latest()->get();

        // Calculate simple blog metrics
        $metrics = [
            'posts_count' => BlogPost::count(),
            'published_count' => BlogPost::where('is_published', true)->count(),
            'comments_count' => BlogComment::count(),
            'pending_comments' => BlogComment::where('is_approved', false)->count(),
            'subscribers_count' => NewsletterSubscriber::where('is_active', true)->count(),
        ];

        return view('admin.blog.index', compact('posts', 'categories', 'tags', 'comments', 'subscribers', 'media', 'metrics'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'slug' => 'required|string|unique:blog_posts,slug|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'reading_time' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'required|boolean',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'faq_questions' => 'nullable|array',
            'faq_answers' => 'nullable|array',
            'attachment_names' => 'nullable|array',
            'attachment_urls' => 'nullable|array',
        ]);

        $data = $request->except(['image', 'categories', 'tags', 'faq_questions', 'faq_answers', 'attachment_names', 'attachment_urls']);
        $data['user_id'] = auth()->id();

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog', 'public');
            $data['featured_image'] = '/storage/' . $path;
        }

        // Format FAQs to JSON
        $faq = [];
        if ($request->has('faq_questions') && $request->has('faq_answers')) {
            foreach ($request->input('faq_questions') as $index => $question) {
                if (!empty($question) && isset($request->input('faq_answers')[$index])) {
                    $faq[] = [
                        'question' => $question,
                        'answer' => $request->input('faq_answers')[$index]
                    ];
                }
            }
        }
        $data['faq'] = $faq;

        // Format Attachments to JSON
        $attachments = [];
        if ($request->has('attachment_names') && $request->has('attachment_urls')) {
            foreach ($request->input('attachment_names') as $index => $name) {
                if (!empty($name) && isset($request->input('attachment_urls')[$index])) {
                    $attachments[] = [
                        'name' => $name,
                        'url' => $request->input('attachment_urls')[$index]
                    ];
                }
            }
        }
        $data['attachments'] = $attachments;

        $post = BlogPost::create($data);

        // Sync Relations
        if ($request->has('categories')) {
            $post->categories()->sync($request->input('categories'));
        }
        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        return redirect()->route('admin.blog.index')->with('success', 'Article published/saved successfully.');
    }

    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        return view('admin.blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'slug' => 'required|string|unique:blog_posts,slug,' . $post->id . '|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'reading_time' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'required|boolean',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'faq_questions' => 'nullable|array',
            'faq_answers' => 'nullable|array',
            'attachment_names' => 'nullable|array',
            'attachment_urls' => 'nullable|array',
        ]);

        $data = $request->except(['image', 'categories', 'tags', 'faq_questions', 'faq_answers', 'attachment_names', 'attachment_urls']);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old file
            if ($post->featured_image && str_starts_with($post->featured_image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $post->featured_image));
            }
            $path = $request->file('image')->store('blog', 'public');
            $data['featured_image'] = '/storage/' . $path;
        }

        // Format FAQs to JSON
        $faq = [];
        if ($request->has('faq_questions') && $request->has('faq_answers')) {
            foreach ($request->input('faq_questions') as $index => $question) {
                if (!empty($question) && isset($request->input('faq_answers')[$index])) {
                    $faq[] = [
                        'question' => $question,
                        'answer' => $request->input('faq_answers')[$index]
                    ];
                }
            }
        }
        $data['faq'] = $faq;

        // Format Attachments to JSON
        $attachments = [];
        if ($request->has('attachment_names') && $request->has('attachment_urls')) {
            foreach ($request->input('attachment_names') as $index => $name) {
                if (!empty($name) && isset($request->input('attachment_urls')[$index])) {
                    $attachments[] = [
                        'name' => $name,
                        'url' => $request->input('attachment_urls')[$index]
                    ];
                }
            }
        }
        $data['attachments'] = $attachments;

        $post->update($data);

        // Sync Relations
        $post->categories()->sync($request->input('categories', []));
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.blog.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        // Delete image
        if ($post->featured_image && str_starts_with($post->featured_image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $post->featured_image));
        }

        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Article deleted successfully.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_categories,slug|max:255',
            'description' => 'nullable|string',
        ]);

        BlogCategory::create($request->all());

        return redirect()->back()->with('success', 'Category registered successfully.');
    }

    public function destroyCategory(BlogCategory $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category removed successfully.');
    }

    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_tags,slug|max:255',
        ]);

        BlogTag::create($request->all());

        return redirect()->back()->with('success', 'Tag registered successfully.');
    }

    public function destroyTag(BlogTag $tag)
    {
        $tag->delete();
        return redirect()->back()->with('success', 'Tag removed successfully.');
    }

    public function approveComment(BlogComment $comment)
    {
        $comment->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Comment approved successfully.');
    }

    public function destroyComment(BlogComment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted.');
    }

    public function destroySubscriber(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber removed.');
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|max:3072', // Max 3MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('media', 'public');

            MediaFile::create([
                'user_id' => auth()->id(),
                'file_path' => '/storage/' . $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return redirect()->back()->with('success', 'Media file uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload media file.');
    }

    public function destroyMedia(MediaFile $media)
    {
        if (str_starts_with($media->file_path, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $media->file_path));
        }

        $media->delete();

        return redirect()->back()->with('success', 'Media file deleted.');
    }
}
