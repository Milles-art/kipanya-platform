<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\CartoonEpisode;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\View\View;

final class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'cartoons' => Cartoon::count(),
                'published' => Cartoon::where('status', 'published')->count(),
                'episodes' => CartoonEpisode::count(),
                'categories' => Category::count(),
                'collections' => Collection::count(),
            ],
            'recent' => Cartoon::with('category')->latest()->take(8)->get(),
        ]);
    }
}
