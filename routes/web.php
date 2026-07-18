<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
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
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update');
    });

    // Support & Product Management (Super Admin + Support Staff)
    Route::middleware(['role:Super Admin|Support Staff'])->group(function () {
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
        Route::get('/purchases', [AdminController::class, 'purchases'])->name('purchases');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::patch('/services/{serviceRequest}', [AdminController::class, 'updateServiceRequest'])->name('services.update');
    });

    // CMS Management (Super Admin + Content Manager)
    Route::middleware(['role:Super Admin|Content Manager'])->group(function () {
        Route::get('/cms', [AdminController::class, 'cms'])->name('cms');
    });
});

require __DIR__.'/auth.php';
