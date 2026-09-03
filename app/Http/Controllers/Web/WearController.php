<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WearProduct;
use App\Models\Cartoon;
use App\Enums\ContentStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class WearController extends Controller
{
    public function index(Request $request): View
    {
        $query = WearProduct::query()->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('q')) {
            $term = trim($request->string('q')->toString());
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%"));
        }

        $products = (clone $query)->orderBy('sort_order')->orderByDesc('created_at')->paginate(12)->withQueryString();
        $featured = WearProduct::query()->where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->limit(5)->get();
        $featuredCartoon = Cartoon::query()->where('status', ContentStatus::Published)->whereNotNull('thumbnail_path')->latest()->first();

        return view('pages.public.wear', [
            'products' => $products,
            'featured' => $featured,
            'categories' => ['T-Shirts', 'Hoodies', 'Caps', 'Kids', 'Accessories', 'Custom Wear'],
            'featuredCartoon' => $featuredCartoon,
        ]);
    }

    public function show(WearProduct $product): View
    {
        abort_unless($product->is_active, 404);

        return view('pages.public.wear-product', [
            'product' => $product->load('variants'),
            'related' => WearProduct::query()
                ->where('is_active', true)
                ->where('category', $product->category)
                ->whereKeyNot($product->id)
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
        ]);
    }
}
