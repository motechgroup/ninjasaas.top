<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogComment;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Models\DocumentationCategory;
use App\Models\DocumentationArticle;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $products = Product::where('is_active', true)->with('category')->take(4)->get();
        $recentPosts = BlogPost::where('is_published', true)->orderByDesc('published_at')->take(3)->get();
        
        return view('home', compact('products', 'recentPosts'));
    }

    public function products()
    {
        $products = Product::where('is_active', true)->with('category')->get();
        $categories = ProductCategory::withCount('products')->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function productShow(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['features', 'screenshots', 'versions.changelogs'])
            ->firstOrFail();
            
        return view('products.show', compact('product'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->get();
        return view('services.index', compact('services'));
    }

    public function docs()
    {
        $products = Product::where('is_active', true)->with('docCategories.articles')->get();
        return view('docs.index', compact('products'));
    }

    public function docShow(string $productSlug, string $categorySlug, string $articleSlug)
    {
        $product = Product::where('slug', $productSlug)->where('is_active', true)->firstOrFail();
        
        $category = DocumentationCategory::where('product_id', $product->id)
            ->where('slug', $categorySlug)
            ->firstOrFail();
            
        $article = DocumentationArticle::where('documentation_category_id', $category->id)
            ->where('slug', $articleSlug)
            ->where('is_published', true)
            ->firstOrFail();

        $allCategories = DocumentationCategory::where('product_id', $product->id)
            ->with(['articles' => function($q) {
                $q->where('is_published', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('docs.show', compact('product', 'category', 'article', 'allCategories'));
    }

    public function blog(Request $request)
    {
        $query = BlogPost::published()->with(['author', 'categories']);

        // Full-text search
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->input('tag'));
            });
        }

        // Date filter (Year-Month)
        if ($request->filled('date')) {
            $date = $request->input('date');
            $parts = explode('-', $date);
            if (count($parts) === 2) {
                $query->whereYear('published_at', $parts[0])
                      ->whereMonth('published_at', $parts[1]);
            }
        }

        // Popularity / Date Sorting
        if ($request->input('sort') === 'popular') {
            $query->withCount('comments')->orderByDesc('comments_count');
        } else {
            $query->latest('published_at');
        }

        $posts = $query->paginate(6)->withQueryString();

        // Get sidebar widgets context
        $featuredPost = BlogPost::published()->where('is_featured', true)->latest()->first();
        $popularPosts = BlogPost::published()->withCount('comments')->orderByDesc('comments_count')->take(4)->get();
        $categories = BlogCategory::withCount('posts')->get();
        $tags = BlogTag::take(20)->get();

        return view('blog.index', compact('posts', 'featuredPost', 'popularPosts', 'categories', 'tags'));
    }

    public function blogShow(string $slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['author', 'categories', 'tags', 'approvedComments.user'])
            ->firstOrFail();

        // Convert Markdown content to HTML using Laravel's built-in parser
        $post->html_content = \Illuminate\Support\Str::markdown($post->content);

        // Fetch related posts (same category or tags)
        $categoryIds = $post->categories->pluck('id');
        $tagIds = $post->tags->pluck('id');
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($categoryIds, $tagIds) {
                $q->whereHas('categories', function ($sq) use ($categoryIds) {
                    $sq->whereIn('blog_categories.id', $categoryIds);
                })->orWhereHas('tags', function ($sq) use ($tagIds) {
                    $sq->whereIn('blog_tags.id', $tagIds);
                });
            })
            ->take(3)
            ->get();

        // Previous and Next Articles
        $prevPost = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first();

        $nextPost = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first();

        return view('blog.show', compact('post', 'relatedPosts', 'prevPost', 'nextPost'));
    }

    public function blogCategory(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $posts = BlogPost::published()
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('blog_categories.id', $category->id);
            })
            ->latest('published_at')
            ->paginate(6);

        $categories = BlogCategory::withCount('posts')->get();

        return view('blog.category', compact('category', 'posts', 'categories'));
    }

    public function blogTag(string $slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();
        $posts = BlogPost::published()
            ->whereHas('tags', function ($q) use ($tag) {
                $q->where('blog_tags.id', $tag->id);
            })
            ->latest('published_at')
            ->paginate(6);

        $tags = BlogTag::all();

        return view('blog.tag', compact('tag', 'posts', 'tags'));
    }

    public function blogAuthor(int $id)
    {
        $author = User::findOrFail($id);
        $posts = BlogPost::published()
            ->where('user_id', $author->id)
            ->latest('published_at')
            ->paginate(6);

        return view('blog.author', compact('author', 'posts'));
    }

    public function storeComment(Request $request, BlogPost $post)
    {
        $rules = [
            'content' => 'required|string|min:5|max:1000',
        ];

        // Validate guest details if not authenticated
        if (!auth()->check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $request->validate($rules);

        $commentData = [
            'blog_post_id' => $post->id,
            'content' => $request->input('content'),
            'is_approved' => auth()->check(), // Auto-approve registered users
        ];

        if (auth()->check()) {
            $commentData['user_id'] = auth()->id();
        } else {
            $commentData['name'] = $request->input('name');
            $commentData['email'] = $request->input('email');
        }

        BlogComment::create($commentData);

        $msg = auth()->check() 
            ? 'Comment posted successfully.' 
            : 'Comment submitted. It will be visible after admin approval.';

        return redirect()->back()->with('success', $msg);
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->input('email')],
            ['is_active' => true]
        );

        return redirect()->back()->with('success', 'Thank you for subscribing to our technical newsletter!');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // In a production app, we would fire a notification/email here
        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you shortly.');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function refunds()
    {
        return view('refunds');
    }

    public function sitemap()
    {
        $posts = BlogPost::published()->latest('published_at')->get();
        $products = Product::where('is_active', true)->get();
        
        $content = view('sitemap', compact('posts', 'products'));
        
        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function rssFeed()
    {
        $posts = BlogPost::published()->latest('published_at')->take(20)->get();
        
        $content = view('rss', compact('posts'));
        
        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
