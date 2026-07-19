<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\BlogPost;
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

    public function blog()
    {
        $posts = BlogPost::where('is_published', true)->orderByDesc('published_at')->paginate(6);
        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('blog.show', compact('post'));
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
}
