<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WearProduct;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class WearController extends Controller
{
    private function baseQuery(Request $request)
    {
        return WearProduct::query()->where('is_active', true)->with('variants');
    }

    public function index(Request $request): View
    {
        $query = $this->baseQuery($request);
        if ($request->filled('category')) $query->where('category', $request->string('category')->toString());
        if ($request->filled('q')) {
            $term = trim($request->string('q')->toString());
            $query->where(fn($q) => $q->where('name','like',"%{$term}%")->orWhere('description','like',"%{$term}%"));
        }

        $products = (clone $query)->orderBy('sort_order')->orderByDesc('created_at')->paginate(12)->withQueryString();
        $featured = $this->baseQuery($request)->where('is_featured', true)->orderBy('sort_order')->limit(8)->get();
        $new = $this->baseQuery($request)->orderByDesc('created_at')->limit(8)->get();
        $deals = $this->baseQuery($request)->whereColumn('compare_at_price','>','price')->orderBy('sort_order')->limit(8)->get();

        return view('pages.public.wear', compact('products','featured','new','deals'));
    }

    public function catalog(Request $request): View { return $this->listing($request, 'categories', 'Shop all categories', 'Browse every active Kipanya Wear piece.'); }
    public function deals(Request $request): View { return $this->listing($request, 'deals', 'Deals', 'Limited pieces with current markdowns.'); }
    public function newArrivals(Request $request): View { return $this->listing($request, 'new', 'New Arrivals', 'Fresh pieces added most recently.'); }
    public function bestSellers(Request $request): View { return $this->listing($request, 'bestsellers', 'Best Sellers', 'The pieces currently featured by Kipanya.'); }

    private function listing(Request $request, string $type, string $title, string $copy): View
    {
        $query = $this->baseQuery($request);
        if ($request->filled('category')) $query->where('category', $request->string('category')->toString());
        if ($request->filled('q')) { $term=trim($request->string('q')->toString()); $query->where(fn($q)=>$q->where('name','like',"%{$term}%")->orWhere('description','like',"%{$term}%")); }
        if ($type === 'deals') $query->whereColumn('compare_at_price','>','price');
        if ($type === 'bestsellers') $query->where('is_featured', true);
        if ($type === 'new') $query->orderByDesc('created_at'); else $query->orderBy('sort_order')->orderByDesc('created_at');
        $products=$query->paginate(16)->withQueryString();
        $categories=WearProduct::query()->where('is_active',true)->distinct()->orderBy('category')->pluck('category');
        return view('pages.public.wear-catalog', compact('products','categories','title','copy','type'));
    }

    public function search(Request $request): View { return $this->listing($request, 'categories', 'Search results', $request->filled('q') ? 'Results for “'.$request->string('q').'”.' : 'Search Kipanya Wear.'); }

    public function brands(): View { return view('pages.public.wear-simple', ['title'=>'Brands','copy'=>'Explore the brands and product stories available through Kipanya Wear.','eyebrow'=>'Discover']); }
    public function collections(): View { return view('pages.public.wear-simple', ['title'=>'Collections','copy'=>'Explore curated collections and the latest Kipanya Wear edits.','eyebrow'=>'Discover']); }
    public function wishlist(): View { return view('pages.public.wear-wishlist'); }
    public function simple(string $page): View
    {
        $map=['orders'=>['My Orders','Review your Kipanya Wear purchases.','Your orders'], 'coupons'=>['Coupons','Save more with offers available to you.','Available offers'], 'addresses'=>['Addresses','Manage delivery details for future orders.','Your addresses'], 'settings'=>['Account Settings','Manage your shopping preferences.','Shopping preferences']];
        abort_unless(isset($map[$page]),404);
        [$title,$copy,$card]=$map[$page];
        return view('pages.public.wear-simple',compact('title','copy','card'));
    }

    public function show(WearProduct $product): View
    {
        abort_unless($product->is_active, 404);
        $product->load('variants');
        $related=WearProduct::query()->where('is_active',true)->where('category',$product->category)->whereKeyNot($product->id)->with('variants')->orderBy('sort_order')->limit(4)->get();
        return view('pages.public.wear-product',compact('product','related'));
    }
}
