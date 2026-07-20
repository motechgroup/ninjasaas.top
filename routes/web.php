<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogAdminController;
use App\Http\Controllers\DeployController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/deploy/run', [DeployController::class, 'handle'])->name('deploy.run');
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/products', [PublicController::class, 'products'])->name('products.index');
Route::get('/products/{slug}', [PublicController::class, 'productShow'])->name('products.show');
Route::get('/services', [PublicController::class, 'services'])->name('services.index');
Route::get('/services/{slug}', [PublicController::class, 'serviceShow'])->name('services.show');
Route::get('/search', [PublicController::class, 'search'])->name('search');

// Root-level SEO Software Categories
Route::get('/law-firm-management-software', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'law-firm-management-software')->name('seo.category.law');
Route::get('/hospital-management-system', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'hospital-management-system')->name('seo.category.hospital');
Route::get('/isp-billing-software', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'isp-billing-software')->name('seo.category.isp');
Route::get('/point-of-sale-software', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'point-of-sale-software')->name('seo.category.pos');
Route::get('/crm-software', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'crm-software')->name('seo.category.crm');
Route::get('/erp-software', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'erp-software')->name('seo.category.erp');
Route::get('/school-management-system', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'school-management-system')->name('seo.category.school');
Route::get('/property-management-software', [PublicController::class, 'seoCategoryShow'])->defaults('slug', 'property-management-software')->name('seo.category.property');

Route::get('/docs', [PublicController::class, 'docs'])->name('docs.index');
Route::get('/docs/{product_slug}/{category_slug}/{article_slug}', [PublicController::class, 'docShow'])->name('docs.show');
Route::get('/blog', [PublicController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [PublicController::class, 'blogShow'])->name('blog.show');
Route::get('/blog/category/{slug}', [PublicController::class, 'blogCategory'])->name('blogCategory');
Route::get('/blog/tag/{slug}', [PublicController::class, 'blogTag'])->name('blogTag');
Route::get('/blog/author/{id}', [PublicController::class, 'blogAuthor'])->name('blog.author');
Route::post('/blog/comment/{post}', [PublicController::class, 'storeComment'])->middleware('throttle:5,1')->name('blog.comment.store');
Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])->middleware('throttle:5,1')->name('newsletter.subscribe');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSubmit'])->middleware('throttle:5,1')->name('contact.submit');
Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::get('/refunds', [PublicController::class, 'refunds'])->name('refunds');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');
Route::get('/feed', [PublicController::class, 'rssFeed'])->name('rss.feed');

/*
|--------------------------------------------------------------------------
| Customer Portal Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', '2fa'])->group(function () {
    Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/portal/purchases', [PortalController::class, 'purchases'])->name('portal.purchases');
    Route::get('/portal/tickets', [PortalController::class, 'tickets'])->name('portal.tickets');
    Route::get('/portal/services', [PortalController::class, 'services'])->name('portal.services');
    Route::post('/portal/services', [PortalController::class, 'submitServiceRequest'])->middleware('throttle:10,1')->name('portal.services.submit');
    Route::post('/portal/services/{serviceRequest}/pay', [PortalController::class, 'payServiceRequest'])->name('portal.services.pay');
    Route::get('/portal/services/{serviceRequest}/payment-success', [PortalController::class, 'paymentSuccess'])->name('portal.services.payment-success');
    Route::get('/portal/services/{serviceRequest}/payment-cancel', [PortalController::class, 'paymentCancel'])->name('portal.services.payment-cancel');

    // Secure Product Package Downloads
    Route::get('/portal/products/{product:slug}/download', [PortalController::class, 'downloadProduct'])
        ->middleware('throttle:10,1')
        ->name('portal.products.download');

    // Direct Product Checkout Routes
    Route::get('/checkout/{product:slug}', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{product:slug}', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{license}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Impersonation Exit
    Route::get('/impersonate/stop', [\App\Http\Controllers\CustomerAdminController::class, 'stopImpersonating'])->name('impersonate.stop');
});

/*
|--------------------------------------------------------------------------
| Admin & Staff Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Super Admin|Support Staff|Content Manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Super Admin Only
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/test-smtp', [AdminController::class, 'testSmtp'])->name('settings.test-smtp');
        Route::patch('/settings/templates/{emailTemplate}', [AdminController::class, 'updateEmailTemplate'])->name('settings.templates.update');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update');
    });

    // Support & Product Management (Super Admin + Support Staff)
    Route::middleware(['role:Super Admin|Support Staff'])->group(function () {
        // Customer Management
        Route::get('/customers', [\App\Http\Controllers\CustomerAdminController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [\App\Http\Controllers\CustomerAdminController::class, 'show'])->name('customers.show');
        Route::patch('/customers/{customer}/status', [\App\Http\Controllers\CustomerAdminController::class, 'updateStatus'])->name('customers.status');
        Route::patch('/customers/{customer}/profile', [\App\Http\Controllers\CustomerAdminController::class, 'updateProfile'])->name('customers.profile');
        Route::delete('/customers/{customer}', [\App\Http\Controllers\CustomerAdminController::class, 'destroy'])->name('customers.destroy');
        Route::post('/customers/{customer}/impersonate', [\App\Http\Controllers\CustomerAdminController::class, 'impersonate'])->name('customers.impersonate');

        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
        Route::get('/purchases', [AdminController::class, 'purchases'])->name('purchases');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::patch('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::patch('/services/{serviceRequest}', [AdminController::class, 'updateServiceRequest'])->name('services.update');

        // Licensing & Channel Administration
        Route::get('/licensing', [\App\Http\Controllers\LicenseAdminController::class, 'index'])->name('licensing.index');
        Route::post('/licensing/channel', [\App\Http\Controllers\LicenseAdminController::class, 'storeChannel'])->name('licensing.channel.store');
        Route::post('/licensing/provider', [\App\Http\Controllers\LicenseAdminController::class, 'storeProvider'])->name('licensing.provider.store');
        Route::post('/licensing/assign', [\App\Http\Controllers\LicenseAdminController::class, 'assignProductChannel'])->name('licensing.assign');
        Route::post('/licensing/generate', [\App\Http\Controllers\LicenseAdminController::class, 'generateLicenseKey'])->name('licensing.generate');
        Route::post('/licensing/{license}/toggle', [\App\Http\Controllers\LicenseAdminController::class, 'toggleLicense'])->name('licensing.toggle');
    });

    // CMS Management (Super Admin + Content Manager)
    Route::middleware(['role:Super Admin|Content Manager'])->group(function () {
        Route::get('/cms', [AdminController::class, 'cms'])->name('cms');
        
        // Blog CRUD
        Route::post('/cms/blog', [AdminController::class, 'storeBlogPost'])->name('cms.blog.store');
        Route::patch('/cms/blog/{blogPost}', [AdminController::class, 'updateBlogPost'])->name('cms.blog.update');
        Route::delete('/cms/blog/{blogPost}', [AdminController::class, 'destroyBlogPost'])->name('cms.blog.destroy');
        
        // Advanced Blog CMS Routes
        Route::get('/blog', [BlogAdminController::class, 'index'])->name('blog.index');
        Route::get('/blog/create', [BlogAdminController::class, 'create'])->name('blog.create');
        Route::post('/blog', [BlogAdminController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit', [BlogAdminController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}', [BlogAdminController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [BlogAdminController::class, 'destroy'])->name('blog.destroy');
        
        Route::post('/blog/category', [BlogAdminController::class, 'storeCategory'])->name('blog.category.store');
        Route::delete('/blog/category/{category}', [BlogAdminController::class, 'destroyCategory'])->name('blog.category.destroy');
        
        Route::post('/blog/tag', [BlogAdminController::class, 'storeTag'])->name('blog.tag.store');
        Route::delete('/blog/tag/{tag}', [BlogAdminController::class, 'destroyTag'])->name('blog.tag.destroy');
        
        Route::post('/blog/comment/{comment}/approve', [BlogAdminController::class, 'approveComment'])->name('blog.comment.approve');
        Route::delete('/blog/comment/{comment}', [BlogAdminController::class, 'destroyComment'])->name('blog.comment.destroy');
        
        Route::delete('/blog/subscriber/{subscriber}', [BlogAdminController::class, 'destroySubscriber'])->name('blog.subscriber.destroy');
        
        Route::post('/blog/media', [BlogAdminController::class, 'uploadMedia'])->name('blog.media.upload');
        Route::delete('/blog/media/{media}', [BlogAdminController::class, 'destroyMedia'])->name('blog.media.destroy');

        // Docs CRUD
        Route::post('/cms/docs', [AdminController::class, 'storeDocArticle'])->name('cms.docs.store');
        Route::patch('/cms/docs/{docArticle}', [AdminController::class, 'updateDocArticle'])->name('cms.docs.update');
        Route::delete('/cms/docs/{docArticle}', [AdminController::class, 'destroyDocArticle'])->name('cms.docs.destroy');
    });
});

require __DIR__.'/auth.php';
