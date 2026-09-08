<?php

use App\Http\Controllers\Web\Admin\AdminAuthController;
use App\Http\Controllers\Web\Admin\AdminContentController;
use App\Http\Controllers\Web\Admin\AdminDashboardController;
use App\Http\Controllers\Web\Admin\AdminUserController;
use App\Http\Controllers\Web\Admin\AdminActivityLogController;
use App\Http\Controllers\Web\Admin\AdminProfileController;
use App\Http\Controllers\Web\Admin\AdminCartoonDashboardController;
use App\Http\Controllers\Web\PublicContentController;
use App\Http\Controllers\Web\TshirtDesignController;
use App\Http\Controllers\Web\WearController;
use App\Http\Controllers\Web\WearCartController;
use App\Http\Controllers\Web\WearCheckoutController;
use App\Http\Middleware\EnsureAdminWebAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicContentController::class, 'home'])->name('home');
Route::get('/discover', [PublicContentController::class, 'discover'])->name('discover');
Route::get('/cartoon', [PublicContentController::class, 'cartoon'])->name('cartoon');
Route::get('/cartoon/search', [PublicContentController::class, 'search'])->name('cartoon.search');
Route::get('/cartoon/{cartoon:slug}', [PublicContentController::class, 'detail'])->name('cartoon.detail');
Route::get('/categories/{category:slug}', [PublicContentController::class, 'category'])->name('category');
Route::get('/collections/{collection:slug}', [PublicContentController::class, 'collection'])->name('collection');
Route::get('/collections', [PublicContentController::class, 'collections'])->name('collections');
Route::get('/wear', [WearController::class, 'index'])->name('wear');
Route::get('/wear/product/{product:slug}', [WearController::class, 'show'])->name('wear.product');
Route::get('/wear/cart', [WearCartController::class, 'index'])->name('wear.cart');
Route::post('/wear/cart/items', [WearCartController::class, 'store'])->name('wear.cart.items.store');
Route::patch('/wear/cart/items/{variant}', [WearCartController::class, 'update'])->name('wear.cart.items.update');
Route::delete('/wear/cart/items/{variant}', [WearCartController::class, 'remove'])->name('wear.cart.items.remove');
Route::get('/wear/checkout', [WearCheckoutController::class, 'create'])->name('wear.checkout');
Route::post('/wear/checkout', [WearCheckoutController::class, 'store'])->name('wear.checkout.store');
Route::get('/wear/orders/{order}', [WearCheckoutController::class, 'show'])->name('wear.order');
Route::get('/wear/from-cartoon/{cartoon:slug}', [TshirtDesignController::class, 'create'])->name('wear.design');
Route::post('/wear/from-cartoon/{cartoon:slug}', [TshirtDesignController::class, 'save'])->name('wear.design.save');
Route::post('/wear/from-cartoon/{cartoon:slug}/state', [TshirtDesignController::class, 'state'])->name('wear.design.state');

Route::prefix('account')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Web\UserAccountController::class, 'showLogin'])->name('account.login');
        Route::get('/register', [\App\Http\Controllers\Web\UserAccountController::class, 'showRegister'])->name('account.register');
        Route::post('/login/request-otp', [\App\Http\Controllers\Web\UserAccountController::class, 'requestOtp'])->name('account.login.request-otp');
        Route::post('/register/request-otp', [\App\Http\Controllers\Web\UserAccountController::class, 'requestRegistrationOtp'])->name('account.register.request-otp');
        Route::post('/login', [\App\Http\Controllers\Web\UserAccountController::class, 'login'])->name('account.login.verify');
        Route::post('/register', [\App\Http\Controllers\Web\UserAccountController::class, 'register'])->name('account.register.verify');
    });
    Route::middleware('auth:web')->group(function () {
        Route::get('/', [\App\Http\Controllers\Web\UserAccountController::class, 'index'])->name('account');
        Route::get('/favorites', [\App\Http\Controllers\Web\UserAccountController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/{cartoon:slug}/toggle', [\App\Http\Controllers\Web\UserAccountController::class, 'toggleFavorite'])->name('favorites.toggle');
        Route::patch('/profile', [\App\Http\Controllers\Web\UserAccountController::class, 'updateProfile'])->name('account.profile.update');
        Route::post('/logout', [\App\Http\Controllers\Web\UserAccountController::class, 'logout'])->name('account.logout');
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
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
        Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::patch('/users/{user}/password', [AdminUserController::class, 'resetPassword'])->name('admin.users.password');
        Route::get('/activity', [AdminActivityLogController::class, 'index'])->name('admin.activity');
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::patch('/profile/password', [AdminProfileController::class, 'password'])->name('admin.profile.password');
        Route::get('/cartoon', AdminCartoonDashboardController::class)->name('admin.cartoon.dashboard');
        Route::get('/content', [AdminContentController::class, 'index'])->name('admin.content');
        Route::get('/content/create', [AdminContentController::class, 'create'])->name('admin.content.create');
        Route::post('/content', [AdminContentController::class, 'store'])->name('admin.content.store');
        Route::get('/content/{cartoon}', [AdminContentController::class, 'show'])->name('admin.content.show');
        Route::get('/content/{cartoon}/edit', [AdminContentController::class, 'edit'])->name('admin.content.edit');
        Route::put('/content/{cartoon}', [AdminContentController::class, 'update'])->name('admin.content.update');
        Route::post('/content/{cartoon}/thumbnail/remove', [AdminContentController::class, 'removeThumbnail'])->name('admin.content.thumbnail.remove');
        Route::post('/content/{cartoon}/archive', [AdminContentController::class, 'archive'])->name('admin.content.archive');
        Route::post('/content/{cartoon}/feature', [AdminContentController::class, 'feature'])->name('admin.content.feature');
        Route::delete('/content/{cartoon}/feature', [AdminContentController::class, 'unfeature'])->name('admin.content.unfeature');
        Route::delete('/content/{cartoon}', [AdminContentController::class, 'destroy'])->name('admin.content.destroy');
        Route::get('/calendar', [AdminContentController::class, 'calendar'])->name('admin.calendar');
        Route::get('/episodes', [\App\Http\Controllers\Web\Admin\AdminEpisodeController::class, 'index'])->name('admin.episodes');
        Route::get('/content/{cartoon}/episodes/create', [\App\Http\Controllers\Web\Admin\AdminEpisodeController::class, 'create'])->name('admin.episodes.create');
        Route::post('/content/{cartoon}/episodes', [\App\Http\Controllers\Web\Admin\AdminEpisodeController::class, 'store'])->name('admin.episodes.store');
        Route::get('/content/{cartoon}/episodes/{episode}/edit', [\App\Http\Controllers\Web\Admin\AdminEpisodeController::class, 'edit'])->name('admin.episodes.edit');
        Route::put('/content/{cartoon}/episodes/{episode}', [\App\Http\Controllers\Web\Admin\AdminEpisodeController::class, 'update'])->name('admin.episodes.update');
        Route::delete('/content/{cartoon}/episodes/{episode}', [\App\Http\Controllers\Web\Admin\AdminEpisodeController::class, 'destroy'])->name('admin.episodes.destroy');
        Route::get('/categories', [AdminContentController::class, 'categories'])->name('admin.categories');
        Route::post('/categories', [AdminContentController::class, 'storeCategory'])->name('admin.categories.store');
        Route::get('/categories/{category}/edit', [AdminContentController::class, 'editCategory'])->name('admin.categories.edit');
        Route::put('/categories/{category}', [AdminContentController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [AdminContentController::class, 'destroyCategory'])->name('admin.categories.destroy');
        Route::get('/collections', [AdminContentController::class, 'collections'])->name('admin.collections');
        Route::post('/collections', [AdminContentController::class, 'storeCollection'])->name('admin.collections.store');
        Route::get('/collections/{collection}/edit', [AdminContentController::class, 'editCollection'])->name('admin.collections.edit');
        Route::put('/collections/{collection}', [AdminContentController::class, 'updateCollection'])->name('admin.collections.update');
        Route::delete('/collections/{collection}', [AdminContentController::class, 'destroyCollection'])->name('admin.collections.destroy');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
