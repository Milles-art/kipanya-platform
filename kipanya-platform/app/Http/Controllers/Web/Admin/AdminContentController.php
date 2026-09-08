<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\CartoonRequest;
use App\Models\Cartoon;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\Content\CartoonMediaService;

final class AdminContentController extends Controller
{
    public function __construct(private readonly CartoonMediaService $media) {}

    public function index(Request $request): View
    {
        $query = Cartoon::query()->with('category')->latest('id');

        if ($request->filled('q')) {
            $term = trim((string) $request->string('q'));
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status') && in_array($request->string('status')->value(), array_map(fn ($status) => $status->value, ContentStatus::cases()), true)) {
            $query->where('status', $request->string('status')->value());
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        return view('admin.content.index', [
            'cartoons' => $query->paginate(14)->withQueryString(),
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
            'statuses' => ContentStatus::cases(),
            'filters' => [
                'q' => $request->string('q')->value(),
                'status' => $request->string('status')->value(),
                'category' => $request->integer('category') ?: null,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.content.create', [
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'statuses' => ContentStatus::cases(),
            'collections' => Collection::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(CartoonRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_daily'] = (bool) ($data['is_daily'] ?? false);
        $data['daily_date'] = $data['is_daily']
            ? ($data['daily_date'] ?? (($data['published_at'] ?? null) ? Carbon::parse($data['published_at'])->toDateString() : today()->toDateString()))
            : null;
        $data['slug'] = $this->uniqueCartoonSlug(Str::slug($data['slug'] ?? $data['title']));
        unset($data['thumbnail']);

        if (($data['status'] ?? ContentStatus::Draft->value) === ContentStatus::Published->value) {
            $data['published_at'] ??= now();
        }

        if (($data['status'] ?? null) === ContentStatus::Scheduled->value) {
            abort_unless(!empty($data['published_at']) && Carbon::parse($data['published_at'])->isFuture(), 422, 'A scheduled cartoon needs a future publish date.');
        }

        $cartoon = Cartoon::create($data);
        if ($cartoon->is_daily && $cartoon->daily_date) {
            Cartoon::query()->where('id', '!=', $cartoon->id)->where('daily_date', $cartoon->daily_date)->update(['is_daily' => false, 'daily_date' => null]);
        }
        $cartoon->collections()->sync($request->input('collection_ids', []));

        if ($request->hasFile('thumbnail')) {
            $data['artwork_format'] = $this->detectArtworkFormat($request);
            $cartoon->update(['artwork_format' => $data['artwork_format']]);
            $this->media->replaceThumbnail($cartoon, $request->file('thumbnail'));
        }

        return redirect()
            ->route('admin.content')
            ->with('status', "{$cartoon->title} was added to the library.");
    }

    public function show(Cartoon $cartoon): View
    {
        $cartoon->load([
            'category',
            'collections',
        ]);

        return view('admin.content.show', [
            'cartoon' => $cartoon,
        ]);
    }

    public function edit(Cartoon $cartoon): View
    {
        return view('admin.content.edit', [
            'cartoon' => $cartoon->load('collections'),
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'statuses' => ContentStatus::cases(),
            'collections' => Collection::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function update(CartoonRequest $request, Cartoon $cartoon): RedirectResponse
    {
        $data = $request->validated();
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_daily'] = (bool) ($data['is_daily'] ?? false);
        $data['daily_date'] = $data['is_daily']
            ? ($data['daily_date'] ?? (($data['published_at'] ?? $cartoon->published_at) ? Carbon::parse($data['published_at'] ?? $cartoon->published_at)->toDateString() : today()->toDateString()))
            : null;
        $data['slug'] = $this->uniqueCartoonSlug(Str::slug($data['slug'] ?? $cartoon->title), $cartoon->id);
        unset($data['thumbnail']);

        if (($data['status'] ?? $cartoon->status->value) === ContentStatus::Scheduled->value) {
            abort_unless(!empty($data['published_at']) && Carbon::parse($data['published_at'])->isFuture(), 422, 'A scheduled cartoon needs a future publish date.');
        }

        if (
            ($data['status'] ?? $cartoon->status->value) === ContentStatus::Published->value
            && ! $cartoon->published_at
        ) {
            $data['published_at'] = now();
        }

        $cartoon->update($data);
        if ($cartoon->is_daily && $cartoon->daily_date) {
            Cartoon::query()->where('id', '!=', $cartoon->id)->where('daily_date', $cartoon->daily_date)->update(['is_daily' => false, 'daily_date' => null]);
        }
        $cartoon->collections()->sync($request->input('collection_ids', []));

        if ($request->hasFile('thumbnail')) {
            $data['artwork_format'] = $this->detectArtworkFormat($request);
            $cartoon->update(['artwork_format' => $data['artwork_format']]);
            $this->media->replaceThumbnail($cartoon, $request->file('thumbnail'));
        }

        return redirect()
            ->route('admin.content.show', $cartoon)
            ->with('status', "{$cartoon->title} was updated.");
    }

    public function removeThumbnail(Cartoon $cartoon): RedirectResponse
    {
        $this->media->removeThumbnail($cartoon);

        return redirect()
            ->route('admin.content.edit', $cartoon)
            ->with('status', 'Artwork removed.');
    }

    public function feature(Cartoon $cartoon): RedirectResponse
    {
        abort_unless($cartoon->thumbnail_path || $cartoon->thumbnail_url, 422, 'A cartoon needs artwork before it can be featured.');
        $cartoon->update(['is_featured' => true]);
        return redirect()->route('admin.content.show', $cartoon)->with('status', "{$cartoon->title} is now featured.");
    }

    public function unfeature(Cartoon $cartoon): RedirectResponse
    {
        $cartoon->update(['is_featured' => false]);
        return redirect()->route('admin.content.show', $cartoon)->with('status', "{$cartoon->title} was removed from featured.");
    }

    public function archive(Cartoon $cartoon): RedirectResponse
    {
        $cartoon->update([
            'status' => ContentStatus::Archived,
            'is_featured' => false,
        ]);

        return redirect()
            ->route('admin.content.show', $cartoon)
            ->with('status', "{$cartoon->title} was archived.");
    }

    public function destroy(Cartoon $cartoon): RedirectResponse
    {
        $title = $cartoon->title;
        $this->media->deleteAll($cartoon);
        $cartoon->delete();

        return redirect()
            ->route('admin.content')
            ->with('status', "{$title} was deleted.");
    }


    public function calendar(Request $request): View
    {
        $month = $request->date('month') ?: now()->startOfMonth();
        $month = $month->copy()->startOfMonth();
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $cartoons = Cartoon::query()->with('category')
            ->whereIn('status', [ContentStatus::Scheduled->value, ContentStatus::Published->value])
            ->whereNotNull('published_at')
            ->whereBetween('published_at', [$start, $end])
            ->orderBy('published_at')->get();


        $calendarItems = $cartoons->map(fn (Cartoon $cartoon) => [
            'type' => 'cartoon',
            'title' => $cartoon->title,
            'published_at' => $cartoon->published_at,
            'status' => $cartoon->status?->value,
            'url' => route('admin.content.show', $cartoon),
            'meta' => $cartoon->category?->name,
        ])->sortBy('published_at')->values();

        return view('admin.content.calendar', compact('calendarItems', 'month'));
    }

    public function categories(): View
    {
        return view('admin.content.categories', ['categories' => Category::withCount('cartoons')->orderBy('sort_order')->paginate(18)]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        $data['slug'] = $this->uniqueCategorySlug(Str::slug($data['name']));
        $data['sort_order'] = (int) Category::max('sort_order') + 1;
        Category::create($data + ['is_active' => true]);

        return redirect()->route('admin.categories')->with('status', 'Category created.');
    }

    public function collections(): View
    {
        return view('admin.content.collections', ['collections' => Collection::withCount('cartoons')->orderBy('sort_order')->paginate(18)]);
    }

    public function storeCollection(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:collections,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'cover_url' => ['nullable', 'url', 'max:2048'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['slug'] = $data['slug'] ?? $this->uniqueCollectionSlug(Str::slug($data['name']));
        unset($data['cover']);
        $data['is_active'] = true;
        $data['sort_order'] = $data['sort_order'] ?? ((int) Collection::max('sort_order') + 1);
        $collection = Collection::create($data);
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('collections/'.$collection->id, 'public');
            $collection->update(['cover_path' => $path]);
        }
        return redirect()->route('admin.collections.edit', $collection)->with('status', 'Collection created.');
    }

    public function editCollection(Collection $collection): View
    {
        $collection->load(['cartoons.category']);
        $cartoons = Cartoon::with('category')->orderByDesc('published_at')->orderByDesc('id')->get();
        return view('admin.content.collections-edit', compact('collection', 'cartoons'));
    }

    public function updateCollection(Request $request, Collection $collection): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:200', Rule::unique('collections', 'slug')->ignore($collection->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'cover_url' => ['nullable', 'url', 'max:2048'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'remove_cover' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'cartoon_ids' => ['nullable', 'array'],
            'cartoon_ids.*' => ['integer', 'exists:cartoons,id'],
        ]);
        $ids = $data['cartoon_ids'] ?? [];
        unset($data['cartoon_ids'], $data['cover'], $data['remove_cover']);
        $collection->update($data + ['is_active' => $request->boolean('is_active')]);
        if ($request->hasFile('cover')) {
            if ($collection->cover_path) Storage::disk('public')->delete($collection->cover_path);
            $collection->update(['cover_path' => $request->file('cover')->store('collections/'.$collection->id, 'public')]);
        }
        if ($request->boolean('remove_cover') && $collection->cover_path) {
            Storage::disk('public')->delete($collection->cover_path);
            $collection->update(['cover_path' => null]);
        }
        $pivot = [];
        foreach ($ids as $index => $id) $pivot[$id] = ['sort_order' => $index];
        $collection->cartoons()->sync($pivot);
        return redirect()->route('admin.collections.edit', $collection)->with('status', 'Collection updated.');
    }

    public function destroyCollection(Collection $collection): RedirectResponse
    {
        $name = $collection->name;
        $collection->cartoons()->detach();
        if ($collection->cover_path) Storage::disk('public')->delete($collection->cover_path);
        $collection->delete();
        return redirect()->route('admin.collections')->with('status', "{$name} was deleted.");
    }

    public function editCategory(Category $category): View
    {
        return view('admin.content.category-edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $data['slug'] = $this->uniqueCategorySlug(Str::slug($data['name']), $category->id);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        return redirect()->route('admin.categories')->with('status', 'Category updated.');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        if ($category->cartoons()->exists()) {
            return back()->withErrors(['category' => 'Move or delete the cartoons in this category before deleting it.']);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Category deleted.');
    }

    private function uniqueCartoonSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug ?: 'cartoon';
        $candidate = $base;
        $suffix = 2;
        while (Cartoon::query()->where('slug', $candidate)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $candidate = $base.'-'.$suffix++;
        }
        return $candidate;
    }

    private function uniqueCategorySlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug ?: 'category';
        $candidate = $base;
        $suffix = 2;
        while (Category::where('slug', $candidate)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $candidate = $base.'-'.$suffix++;
        }
        return $candidate;
    }

    private function uniqueCollectionSlug(string $slug): string
    {
        $base = $slug ?: 'collection';
        $candidate = $base;
        $suffix = 2;
        while (Collection::where('slug', $candidate)->exists()) {
            $candidate = $base.'-'.$suffix++;
        }
        return $candidate;
    }
    private function detectArtworkFormat(Request $request): string
    {
        $file = $request->file('thumbnail');
        if (!$file) return 'landscape';
        $size = @getimagesize($file->getRealPath());
        if (!$size || empty($size[0]) || empty($size[1])) return 'landscape';
        $ratio = $size[0] / $size[1];
        return $ratio > 1.15 ? 'landscape' : ($ratio < 0.85 ? 'portrait' : 'square');
    }

}
