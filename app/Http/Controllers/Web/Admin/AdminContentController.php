<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\View\View;

final class AdminContentController extends Controller
{
    public function index(): View
    {
        return view('admin.content.index', [
            'cartoons' => Cartoon::with('category')->latest()->paginate(14),
        ]);
    }

    public function categories(): View
    {
        return view('admin.content.categories', ['categories' => Category::withCount('cartoons')->orderBy('sort_order')->paginate(18)]);
    }

    public function collections(): View
    {
        return view('admin.content.collections', ['collections' => Collection::withCount('cartoons')->orderBy('sort_order')->paginate(18)]);
    }
}
