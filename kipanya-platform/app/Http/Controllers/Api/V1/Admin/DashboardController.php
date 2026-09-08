<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\Category;
use App\Models\CartoonEpisode;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(['data' => [
            'users' => User::count(),
            'categories' => Category::count(),
            'cartoons' => Cartoon::count(),
            'published_cartoons' => Cartoon::where('status', ContentStatus::Published->value)->count(),
            'draft_cartoons' => Cartoon::where('status', ContentStatus::Draft->value)->count(),
            'episodes' => CartoonEpisode::count(),
            'collections' => Collection::count(),
        ]]);
    }
}
