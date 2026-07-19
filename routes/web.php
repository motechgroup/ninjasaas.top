<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\AdminController;
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
Route::get('/docs', [PublicController::class, 'docs'])->name('docs.index');
Route::get('/docs/{product_slug}/{category_slug}/{article_slug}', [PublicController::class, 'docShow'])->name('docs.show');
Route::get('/blog', [PublicController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [PublicController::class, 'blogShow'])->name('blog.show');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSubmit'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Customer Portal Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/portal/purchases', [PortalController::class, 'purchases'])->name('portal.purchases');
    Route::get('/portal/tickets', [PortalController::class, 'tickets'])->name('portal.tickets');
    Route::get('/portal/services', [PortalController::class, 'services'])->name('portal.services');
    Route::post('/portal/services', [PortalController::class, 'submitServiceRequest'])->name('portal.services.submit');
    Route::post('/portal/services/{serviceRequest}/pay', [PortalController::class, 'payServiceRequest'])->name('portal.services.pay');
    Route::get('/portal/services/{serviceRequest}/payment-success', [PortalController::class, 'paymentSuccess'])->name('portal.services.payment-success');
    Route::get('/portal/services/{serviceRequest}/payment-cancel', [PortalController::class, 'paymentCancel'])->name('portal.services.payment-cancel');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
        Route::get('/purchases', [AdminController::class, 'purchases'])->name('purchases');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::patch('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::patch('/services/{serviceRequest}', [AdminController::class, 'updateServiceRequest'])->name('services.update');
    });

    // CMS Management (Super Admin + Content Manager)
    Route::middleware(['role:Super Admin|Content Manager'])->group(function () {
        Route::get('/cms', [AdminController::class, 'cms'])->name('cms');
        
        // Blog CRUD
        Route::post('/cms/blog', [AdminController::class, 'storeBlogPost'])->name('cms.blog.store');
        Route::patch('/cms/blog/{blogPost}', [AdminController::class, 'updateBlogPost'])->name('cms.blog.update');
        Route::delete('/cms/blog/{blogPost}', [AdminController::class, 'destroyBlogPost'])->name('cms.blog.destroy');
        
        // Docs CRUD
        Route::post('/cms/docs', [AdminController::class, 'storeDocArticle'])->name('cms.docs.store');
        Route::patch('/cms/docs/{docArticle}', [AdminController::class, 'updateDocArticle'])->name('cms.docs.update');
        Route::delete('/cms/docs/{docArticle}', [AdminController::class, 'destroyDocArticle'])->name('cms.docs.destroy');
    });
});

require __DIR__.'/auth.php';
