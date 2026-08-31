<?php

use App\Http\Controllers\Web\Admin\AdminAuthController;
use App\Http\Controllers\Web\Admin\AdminContentController;
use App\Http\Controllers\Web\Admin\AdminDashboardController;
use App\Http\Controllers\Web\PublicContentController;
use App\Http\Middleware\EnsureAdminWebAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicContentController::class, 'home'])->name('home');
Route::get('/discover', [PublicContentController::class, 'discover'])->name('discover');
Route::get('/categories/{category:slug}', [PublicContentController::class, 'category'])->name('category');
Route::get('/collections/{collection:slug}', [PublicContentController::class, 'collection'])->name('collection');
Route::get('/watch/{cartoon:slug}', [PublicContentController::class, 'watch'])->name('watch');

Route::prefix('account')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Web\UserAccountController::class, 'showLogin'])->name('account.login');
        Route::post('/login/request-otp', [\App\Http\Controllers\Web\UserAccountController::class, 'requestOtp'])->name('account.login.request-otp');
        Route::post('/login', [\App\Http\Controllers\Web\UserAccountController::class, 'login'])->name('account.login.verify');
    });
    Route::middleware('auth:web')->group(function () {
        Route::get('/', [\App\Http\Controllers\Web\UserAccountController::class, 'index'])->name('account');
        Route::get('/favorites', [\App\Http\Controllers\Web\UserAccountController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/{cartoon:slug}/toggle', [\App\Http\Controllers\Web\UserAccountController::class, 'toggleFavorite'])->name('favorites.toggle');
        Route::patch('/profile', [\App\Http\Controllers\Web\UserAccountController::class, 'updateProfile'])->name('account.profile.update');
        Route::post('/logout', [\App\Http\Controllers\Web\UserAccountController::class, 'logout'])->name('account.logout');
        Route::post('/watch/{cartoon:slug}/progress', [\App\Http\Controllers\Web\UserAccountController::class, 'markWatched'])->name('watch.progress');
    });
});

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login/request-otp', [AdminAuthController::class, 'requestOtp'])->name('admin.login.request-otp');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.verify');
    });

    Route::middleware(EnsureAdminWebAccess::class)->group(function () {
        Route::get('/', AdminDashboardController::class)->name('admin.dashboard');
        Route::get('/content', [AdminContentController::class, 'index'])->name('admin.content');
        Route::get('/categories', [AdminContentController::class, 'categories'])->name('admin.categories');
        Route::get('/collections', [AdminContentController::class, 'collections'])->name('admin.collections');
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
