<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\CartoonEpisode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Carbon\Carbon;

final class AdminEpisodeController extends Controller
{
    public function index(): View
    {
        $episodes = CartoonEpisode::query()->with('cartoon')->latest('id')->paginate(24);
        return view('admin.content.episodes.index', compact('episodes'));
    }

    public function create(Cartoon $cartoon): View
    {
        $next = ((int) $cartoon->episodes()->max('episode_number')) + 1;
        return view('admin.content.episodes.create', compact('cartoon', 'next'));
    }

    public function store(Request $request, Cartoon $cartoon): RedirectResponse
    {
        $data = $this->validateData($request, $cartoon);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if ($data['status'] === ContentStatus::Scheduled->value) {
            abort_unless(!empty($data['published_at']) && Carbon::parse($data['published_at'])->isFuture(), 422, 'A scheduled episode needs a future publish date.');
        }
        if ($data['status'] === ContentStatus::Published->value) $data['published_at'] ??= now();
        $episode = $cartoon->episodes()->create($data);
        return redirect()->route('admin.content.show', $cartoon)->with('status', "Episode {$episode->episode_number} was added.");
    }

    public function edit(Cartoon $cartoon, CartoonEpisode $episode): View
    {
        abort_unless($episode->cartoon_id === $cartoon->id, 404);
        return view('admin.content.episodes.edit', compact('cartoon', 'episode'));
    }

    public function update(Request $request, Cartoon $cartoon, CartoonEpisode $episode): RedirectResponse
    {
        abort_unless($episode->cartoon_id === $cartoon->id, 404);
        $data = $this->validateData($request, $cartoon, $episode);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if ($data['status'] === ContentStatus::Scheduled->value) {
            abort_unless(!empty($data['published_at']) && Carbon::parse($data['published_at'])->isFuture(), 422, 'A scheduled episode needs a future publish date.');
        }
        if ($data['status'] === ContentStatus::Published->value) $data['published_at'] ??= $episode->published_at ?? now();
        $episode->update($data);
        return redirect()->route('admin.content.show', $cartoon)->with('status', "Episode {$episode->episode_number} was updated.");
    }

    public function destroy(Cartoon $cartoon, CartoonEpisode $episode): RedirectResponse
    {
        abort_unless($episode->cartoon_id === $cartoon->id, 404);
        $number = $episode->episode_number;
        $episode->delete();
        return redirect()->route('admin.content.show', $cartoon)->with('status', "Episode {$number} was deleted.");
    }

    private function youtubeIdFromUrl(string $url): ?string
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        if (str_contains($host, 'youtu.be')) {
            $id = trim(explode('/', trim($parts['path'] ?? '', '/'))[0] ?? '');
            return preg_match('/^[A-Za-z0-9_-]{6,50}$/', $id) ? $id : null;
        }
        if (str_contains($host, 'youtube.com')) {
            parse_str($parts['query'] ?? '', $query);
            $id = $query['v'] ?? null;
            if (!$id && preg_match('#/(?:embed|shorts)/([^/]+)#', $parts['path'] ?? '', $match)) $id = $match[1];
            return $id && preg_match('/^[A-Za-z0-9_-]{6,50}$/', $id) ? $id : null;
        }
        return null;
    }

    private function validateData(Request $request, Cartoon $cartoon, ?CartoonEpisode $episode = null): array
    {
        $id = $episode?->id;
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:cartoon_episodes,slug,'.($id ?? 'NULL').',id,cartoon_id,'.$cartoon->id],
            'description' => ['nullable', 'string', 'max:10000'],
            'youtube_video_id' => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z0-9_-]{6,50}$/'],
            'youtube_url' => ['nullable', 'url', 'max:2048'],
            'thumbnail_url' => ['nullable', 'url', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'episode_number' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,published,scheduled,archived'],
            'published_at' => ['nullable', 'date'],
        ]);

        if (empty($data['youtube_video_id']) && !empty($data['youtube_url'])) {
            $data['youtube_video_id'] = $this->youtubeIdFromUrl($data['youtube_url']);
        }

        return $data;
    }
}
