<?php

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\CartoonController;
use App\Http\Controllers\Api\V1\Admin\CollectionController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\EpisodeController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:sanctum', EnsureUserIsAdmin::class])->group(function () {
    Route::get('/dashboard', DashboardController::class);

    Route::apiResource('categories', CategoryController::class);

    Route::apiResource('cartoons', CartoonController::class);
    Route::post('/cartoons/{cartoon}/publish', [CartoonController::class, 'publish']);
    Route::post('/cartoons/{cartoon}/archive', [CartoonController::class, 'archive']);
    Route::post('/cartoons/{cartoon}/feature', [CartoonController::class, 'feature']);
    Route::delete('/cartoons/{cartoon}/feature', [CartoonController::class, 'unfeature']);

    Route::apiResource('cartoons.episodes', EpisodeController::class);

    Route::apiResource('collections', CollectionController::class);
    Route::put('/collections/{collection}/cartoons', [CollectionController::class, 'syncCartoons']);
});
