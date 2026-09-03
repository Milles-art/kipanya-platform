<form method="POST" action="{{ $action }}" class="mt-7 max-w-4xl k-card k-glass p-6 sm:p-7">@csrf @if($method==='PUT') @method('PUT') @endif
<div class="grid gap-5 sm:grid-cols-2"><label class="studio-field"><span>Episode number</span><input type="number" min="1" name="episode_number" required value="{{ old('episode_number',$episode?->episode_number ?? $next) }}"></label><label class="studio-field"><span>Status</span><select name="status">@foreach(['draft','scheduled','published','archived'] as $status)<option value="{{ $status }}" @selected(old('status',$episode?->status?->value ?? 'draft')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></label><label class="studio-field sm:col-span-2"><span>Title</span><input name="title" required maxlength="180" value="{{ old('title',$episode?->title) }}" placeholder="Episode title"></label><label class="studio-field sm:col-span-2"><span>Slug <em>Optional</em></span><input name="slug" maxlength="200" value="{{ old('slug',$episode?->slug) }}" placeholder="episode-one"></label><label class="studio-field sm:col-span-2"><span>Description <em>Optional</em></span><textarea name="description" rows="5" maxlength="10000">{{ old('description',$episode?->description) }}</textarea></label></div>
<div class="mt-7 border-t k-divider pt-6"><div class="k-section-label">Video source</div><div class="mt-4 grid gap-5 sm:grid-cols-2"><label class="studio-field"><span>YouTube video ID <em>Optional if URL is supplied</em></span><input id="episode-youtube-id" name="youtube_video_id" maxlength="50" value="{{ old('youtube_video_id',$episode?->youtube_video_id) }}" placeholder="dQw4w9WgXcQ"></label><label class="studio-field"><span>YouTube URL <em>Paste a watch or youtu.be link</em></span><input id="episode-youtube-url" type="url" name="youtube_url" value="{{ old('youtube_url',$episode?->youtube_url) }}" placeholder="https://www.youtube.com/watch?v=..."></label><label class="studio-field"><span>Thumbnail URL <em>Optional</em></span><input id="episode-thumbnail-url" type="url" name="thumbnail_url" value="{{ old('thumbnail_url',$episode?->thumbnail_url) }}"></label><label class="studio-field"><span>Duration seconds <em>Optional</em></span><input type="number" min="0" name="duration_seconds" value="{{ old('duration_seconds',$episode?->duration_seconds) }}"></label></div><div id="episode-video-preview" class="mt-5 overflow-hidden rounded-2xl border k-divider bg-[var(--surface-2)] {{ old('youtube_video_id',$episode?->youtube_video_id) ? '' : 'hidden' }}"><div class="aspect-video bg-black"><iframe id="episode-youtube-frame" class="h-full w-full" title="YouTube preview" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div><div class="flex items-center justify-between gap-3 p-3"><span class="text-xs k-muted">Private preview · not visible to the public until published</span><button type="button" id="episode-clear-preview" class="k-btn k-btn-light px-3 py-2">Clear preview</button></div></div></div>
<div class="mt-7 border-t k-divider pt-6"><div class="k-section-label">Publishing</div><div class="mt-4 grid gap-5 sm:grid-cols-2"><label class="studio-field"><span>Publish date & time</span><input type="datetime-local" name="published_at" value="{{ old('published_at',$episode?->published_at?->format('Y-m-d\TH:i')) }}"></label><div class="rounded-2xl bg-[var(--surface-2)] p-4 text-xs leading-5 k-muted">Scheduled episodes are stored for later publishing. Scheduled episodes are published automatically when their publish time arrives.</div></div></div>
<div class="mt-7 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"><a href="{{ route('admin.content.show',$cartoon) }}" class="k-btn k-btn-light">Cancel</a><button class="k-btn k-btn-primary" type="submit">{{ $episode ? 'Save episode' : 'Add episode' }}</button></div>
</form>
<script>
(() => {
    const title = document.querySelector('input[name="title"]');
    const slug = document.querySelector('input[name="slug"]');
    const id = document.getElementById('episode-youtube-id');
    const url = document.getElementById('episode-youtube-url');
    const preview = document.getElementById('episode-video-preview');
    const frame = document.getElementById('episode-youtube-frame');
    const clear = document.getElementById('episode-clear-preview');
    if (!title || !slug) return;
    let slugTouched = slug.value.length > 0;
    slug.addEventListener('input', () => slugTouched = slug.value.length > 0);
    title.addEventListener('input', () => {
        if (!slugTouched) slug.value = title.value.toLowerCase().trim().replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'');
    });
    const extract = value => {
        try {
            const u = new URL(value);
            if (u.hostname.includes('youtu.be')) return u.pathname.slice(1).split('/')[0];
            if (u.hostname.includes('youtube.com')) return u.searchParams.get('v') || (u.pathname.match(/\/shorts\/([^/]+)/)?.[1]) || (u.pathname.match(/\/embed\/([^/]+)/)?.[1]);
        } catch (_) {}
        return null;
    };
    const render = value => {
        const videoId = (value || '').trim();
        if (!videoId) { preview.classList.add('hidden'); frame.src=''; return; }
        frame.src = 'https://www.youtube.com/embed/' + encodeURIComponent(videoId) + '?rel=0';
        preview.classList.remove('hidden');
    };
    url?.addEventListener('input', () => { const videoId=extract(url.value); if(videoId){id.value=videoId;render(videoId);} });
    id?.addEventListener('input', () => render(id.value));
    clear?.addEventListener('click', () => { id.value=''; frame.src=''; preview.classList.add('hidden'); });
    if (id?.value) render(id.value); else if (url?.value) { const videoId=extract(url.value); if(videoId){id.value=videoId;render(videoId);} }
})();
</script>
