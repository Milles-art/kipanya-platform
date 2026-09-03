<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Request;

final class PublicContentController extends Controller
{
    public function home()
    {
        $featured = Cartoon::query()->with('category')
            ->where('status', ContentStatus::Published->value)
            ->where('is_featured', true)
            ->latest('published_at')->take(6)->get();

        $latest = Cartoon::query()->with('category')
            ->where('status', ContentStatus::Published->value)
            ->latest('published_at')->take(12)->get();

        $categories = Category::query()->where('is_active', true)
            ->withCount(['cartoons' => fn ($q) => $q->where('status', ContentStatus::Published->value)])
            ->orderBy('sort_order')->orderBy('name')->take(8)->get();

        $collections = Collection::query()->where('is_active', true)
            ->with(['cartoons' => fn ($q) => $q->where('status', ContentStatus::Published->value)->with('category')->orderBy('cartoon_collection.sort_order')->take(6)])
            ->orderBy('sort_order')->orderBy('name')->take(3)->get();

        return view('pages.public.home', compact('featured', 'latest', 'categories', 'collections'));
    }

    public function cartoon()
    {
        $featured = Cartoon::query()->with('category')
            ->where('status', ContentStatus::Published->value)
            ->where('is_featured', true)
            ->latest('published_at')->take(6)->get();

        $latest = Cartoon::query()->with('category')
            ->where('status', ContentStatus::Published->value)
            ->latest('published_at')->take(12)->get();

        $categories = Category::query()->where('is_active', true)
            ->withCount(['cartoons' => fn ($q) => $q->where('status', ContentStatus::Published->value)])
            ->orderBy('sort_order')->orderBy('name')->take(8)->get();

        $collections = Collection::query()->where('is_active', true)
            ->with(['cartoons' => fn ($q) => $q->where('status', ContentStatus::Published->value)->with('category')->orderBy('cartoon_collection.sort_order')->take(6)])
            ->orderBy('sort_order')->orderBy('name')->take(4)->get();

        return view('pages.public.cartoon', compact('featured', 'latest', 'categories', 'collections'));
    }

    public function discover(Request $request)
    {
        $query = Cartoon::query()->with('category')
            ->where('status', ContentStatus::Published->value)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim($request->string('q')->toString());
                $query->where(function ($builder) use ($term) {
                    $builder->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->string('category')->toString())))
            ->orderByDesc('published_at')->orderBy('sort_order');

        $cartoons = $query->paginate(18)->withQueryString();
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('pages.public.discover', compact('cartoons', 'categories'));
    }

    public function category(Category $category)
    {
        abort_unless($category->is_active, 404);

        $cartoons = Cartoon::query()->with('category')
            ->where('status', ContentStatus::Published->value)
            ->where('category_id', $category->id)
            ->orderByDesc('published_at')->orderBy('sort_order')
            ->paginate(18)->withQueryString();

        return view('pages.public.category', compact('category', 'cartoons'));
    }

    public function collection(Collection $collection)
    {
        abort_unless($collection->is_active, 404);

        $collection->load(['cartoons' => fn ($q) => $q->where('status', ContentStatus::Published->value)->with('category')->orderBy('cartoon_collection.sort_order')]);

        return view('pages.public.collection', compact('collection'));
    }

    public function watch(Cartoon $cartoon, Request $request)
    {
        abort_unless($cartoon->status === ContentStatus::Published, 404);

        $cartoon->load(['category', 'episodes' => fn ($query) => $query
            ->where('status', ContentStatus::Published->value)
            ->orderBy('episode_number')]);

        $selectedEpisode = $cartoon->episodes->firstWhere('slug', $request->string('episode')->toString())
            ?? $cartoon->episodes->first();

        return view('pages.public.watch', compact('cartoon', 'selectedEpisode'));
    }
}
