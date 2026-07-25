<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\ContactController;
use App\Http\Controllers\Store\BlogController as StoreBlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC — Storefront
// ============================================================

Route::get('/', HomeController::class)->name('home');
Route::get('/products', [StoreProductController::class, 'index'])->name('products.index');
Route::get('/products/suggest', [StoreProductController::class, 'suggest'])->name('products.suggest');
Route::get('/products/{product}', [StoreProductController::class, 'show'])->name('products.show');
Route::get('/contact', ContactController::class)->name('contact');

Route::get('/blog', [StoreBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category}', [StoreBlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{blog}', [StoreBlogController::class, 'show'])->name('blog.show');

// Thank You Page (legacy — الطلبات تتم عبر واتساب حاليًا)
Route::get('/thank-you/{order}', [OrderController::class, 'thankYou'])->name('orders.thank-you');

// Legacy order endpoint (kept for admin/compat; storefront uses WhatsApp)
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// ============================================================
// ADMIN
// ============================================================

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\StaffOnly::class])->group(function () {
    // Orders — admin + moderator
    Route::get('orders', [OrderController::class, 'dashboard'])->name('orders.dashboard');
    Route::get('orders/logs', [OrderController::class, 'logs'])->name('orders.logs');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/drawer', [OrderController::class, 'drawer'])->name('orders.drawer');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Users & Products — admin only
    Route::middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Products CRUD
        Route::post('products/reorder',       [ProductController::class, 'reorder'])->name('products.reorder');
        Route::get('products',                [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create',         [ProductController::class, 'create'])->name('products.create');
        Route::post('products',               [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}',      [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}',   [ProductController::class, 'destroy'])->name('products.destroy');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('collections', CollectionController::class)->except(['show']);

        Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);
        Route::post('blogs/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blogs.toggle-status');
        Route::resource('blogs', BlogController::class)->except(['show']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
