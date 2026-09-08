<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Http\Controllers\Web\TshirtDesignController;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Relations\Relation;

final class PublicContentController extends Controller
{
    private function published(): \Illuminate\Database\Eloquent\Builder
    {
        return Cartoon::query()->where('status', ContentStatus::Published->value);
    }

    private function withFavoriteState(\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $query): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation
    {
        if (!auth()->check()) {
            return $query;
        }

        // Eager-loading a belongsToMany relation gives us a Relation instance, while
        // normal model queries give us an Eloquent Builder. Resolve the underlying
        // Eloquent builder before applying withExists so both call sites are safe.
        $builder = $query instanceof \Illuminate\Database\Eloquent\Relations\Relation
            ? $query->getQuery()
            : $query;

        $builder->withCount(['likes', 'comments'])->withExists([
            'likes as is_liked' => fn ($likeQuery) => $likeQuery->whereKey(auth()->id()),
            'favorites as is_favorited' => fn ($favoriteQuery) => $favoriteQuery->whereKey(auth()->id()),
        ]);

        return $builder;
    }

    private function hasArtwork(\Illuminate\Database\Eloquent\Builder|Relation $query): \Illuminate\Database\Eloquent\Builder|Relation
    {
        return $query->where(function ($builder) {
            $builder->whereNotNull('thumbnail_path')->orWhereNotNull('thumbnail_url');
        });
    }

    public function home(): View
    {
        // Keep the approved ecosystem homepage data contract intact. The homepage itself owns its presentation.
        $featured = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')))->where('is_featured', true)->latest('published_at')->take(6)->get();
        $latest = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')))->latest('published_at')->take(12)->get();
        $categories = Category::query()->where('is_active', true)->withCount(['cartoons' => fn ($q) => $q->where('status', ContentStatus::Published->value)])->orderBy('sort_order')->orderBy('name')->take(8)->get();
        $collections = Collection::query()->where('is_active', true)->with(['cartoons' => fn ($q) => $this->withFavoriteState($this->hasArtwork($q->where('status', ContentStatus::Published->value))->with('category'))->orderBy('cartoon_collection.sort_order')->take(6)])->orderBy('sort_order')->orderBy('name')->take(3)->get();
        return view('pages.public.home', compact('featured', 'latest', 'categories', 'collections'));
    }

    public function cartoon(): View
    {
        $featured = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')->where('is_featured', true)))->latest('published_at')->first();
        $daily = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')))
            ->where('is_daily', true)
            ->where(function ($query) {
                $query->whereNotNull('daily_date')->where('daily_date', '<=', today())
                    ->orWhere(function ($fallback) {
                        $fallback->whereNull('daily_date')->whereDate('published_at', '<=', today());
                    });
            })
            ->orderByDesc('daily_date')->orderByDesc('published_at')->orderByDesc('id')
            ->take(10)->get();
        $latest = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')))->latest('published_at')->latest('id')->take(12)->get();
        $categories = Category::query()->where('is_active', true)
            ->with(['cartoons' => fn ($q) => $this->hasArtwork($q->where('status', ContentStatus::Published->value))->latest('published_at')->latest('id')->take(1)])
            ->withCount(['cartoons' => fn ($q) => $this->hasArtwork($q->where('status', ContentStatus::Published->value))])
            ->orderBy('sort_order')->orderBy('name')->get();
        $collections = Collection::query()->where('is_active', true)
            ->with(['cartoons' => fn ($q) => $this->withFavoriteState($this->hasArtwork($q->where('status', ContentStatus::Published->value))->with('category'))->orderBy('cartoon_collection.sort_order')->take(6)])
            ->withCount(['cartoons' => fn ($q) => $this->hasArtwork($q->where('status', ContentStatus::Published->value))])
            ->orderBy('sort_order')->orderBy('name')->take(4)->get();

        return view('pages.public.cartoon', compact('featured', 'daily', 'latest', 'categories', 'collections') + ['wearColors' => TshirtDesignController::COLORS]);
    }

    public function collections(): View
    {
        $collections = Collection::query()
            ->where('is_active', true)
            ->withCount(['cartoons' => fn ($q) => $this->hasArtwork($q->where('status', ContentStatus::Published->value))])
            ->with(['cartoons' => fn ($q) => $this->hasArtwork($q->where('status', ContentStatus::Published->value))->latest('published_at')->take(1)])
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(12);

        return view('pages.public.collections', compact('collections'));
    }

    public function search(Request $request): View
    {
        $query = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim($request->string('q')->toString());
                $query->where(function ($builder) use ($term) {
                    $builder->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('caption', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->string('category')->toString())))
            ->when($request->boolean('daily'), fn ($query) => $query->where('is_daily', true)->where(function ($builder) {
                $builder->whereNotNull('daily_date')->where('daily_date', '<=', today())
                    ->orWhere(function ($fallback) {
                        $fallback->whereNull('daily_date')->whereDate('published_at', '<=', today());
                    });
            }))
            ->when($request->get('sort') === 'popular', fn ($query) => $query->withCount('favorites')->orderByDesc('favorites_count')->orderByDesc('published_at'))
            ->when($request->get('sort') !== 'popular', fn ($query) => $query->orderByDesc('published_at')->orderBy('sort_order')->orderByDesc('id'));

        $cartoons = $query->paginate(18)->withQueryString();
        $categories = Category::query()->where('is_active', true)->withCount(['cartoons' => fn ($q) => $this->hasArtwork($q->where('status', ContentStatus::Published->value))])->orderBy('sort_order')->orderBy('name')->get();

        return view('pages.public.discover', compact('cartoons', 'categories'));
    }

    public function discover(Request $request): View
    {
        return $this->search($request);
    }

    public function category(Category $category): View
    {
        abort_unless($category->is_active, 404);
        $cartoons = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')))
            ->where('category_id', $category->id)
            ->orderByDesc('published_at')->orderBy('sort_order')->orderByDesc('id')
            ->paginate(18)->withQueryString();
        return view('pages.public.category', compact('category', 'cartoons'));
    }

    public function collection(Collection $collection): View
    {
        abort_unless($collection->is_active, 404);
        $collection->load(['cartoons' => fn ($q) => $this->withFavoriteState($this->hasArtwork($q->where('status', ContentStatus::Published->value))->with('category'))->orderBy('cartoon_collection.sort_order')->orderByDesc('published_at')]);
        return view('pages.public.collection', compact('collection'));
    }

    public function detail(Cartoon $cartoon): View
    {
        abort_unless($cartoon->status === ContentStatus::Published && $cartoon->resolved_thumbnail_url, 404);
        $cartoon->load(['category', 'collections']);
        $cartoon->loadCount(['likes', 'comments']);
        if (auth()->check()) { $cartoon->setAttribute('is_liked', $cartoon->likes()->where('user_id', auth()->id())->exists()); }
        $comments = $cartoon->comments()->with('user')->whereNull('parent_id')->latest()->take(50)->get();
        $related = $this->hasArtwork($this->withFavoriteState($this->published()->with('category')))
            ->where('category_id', $cartoon->category_id)
            ->where('id', '!=', $cartoon->id)
            ->latest('published_at')->take(6)->get();
        return view('pages.public.detail', compact('cartoon', 'related', 'comments')); 
    }

}
