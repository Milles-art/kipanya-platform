@extends('layouts.cartoon')
@section('content')
@php
$selectedColor = old('color', $design['color'] ?? 'black');
$selectedColor = array_key_exists($selectedColor, $colors) ? $selectedColor : 'black';
$selectedSize = old('size', $design['size'] ?? 'M');
$selectedSize = in_array($selectedSize, $sizes, true) ? $selectedSize : 'M';
$selectedPlacement = old('placement', $design['placement'] ?? 'front-center');
$selectedPlacement = array_key_exists($selectedPlacement, $placements) ? $selectedPlacement : 'front-center';
$config = $design['configuration'] ?? [];
$artworkEnabled = false; // Presentation pass: keep the shirt preview clean; restore live artwork after presentation.
@endphp
<div class="cartoon-tshirt-page cartoon-shell">
    <a href="{{ route('cartoon.detail',$cartoon) }}" class="cartoon-back"><x-icon name="chevron-left" size="15"/> Back to Cartoon</a>
    @if(session('status'))<div class="cartoon-notice"><x-icon name="check-circle" size="15"/> {{ session('status') }}</div>@endif
    <header class="cartoon-tshirt-heading"><div><span class="cartoon-kicker"><x-icon name="shirt" size="14"/> Kipanya Wear</span><h1>T-shirt Designer</h1><p>Customize how <strong>{{ $cartoon->title }}</strong> appears on the shirt, then continue to Kipanya Wear.</p></div></header>
    <form method="POST" action="{{ route('wear.design.save',$cartoon) }}" class="cartoon-tshirt-grid" data-tshirt-form data-state-url="{{ route('wear.design.state',$cartoon) }}" data-has-server-state="{{ $design ? '1' : '0' }}">
        @csrf
        <section class="cartoon-tshirt-stage">
            <div class="cartoon-stage-top"><span>Live preview</span><span data-preview-color>{{ $colors[$selectedColor]['label'] ?? 'Black' }}</span></div>
            <div class="cartoon-tshirt-canvas" data-shirt-canvas data-color="{{ $selectedColor }}" data-placement="{{ $selectedPlacement }}" data-artwork-enabled="{{ $artworkEnabled ? 'true' : 'false' }}">
                <div class="wear-glow"></div>
                <div class="real-shirt-photo" aria-label="Realistic T-shirt preview">
                    <img src="{{ asset('assets/wear/shirts/'.$selectedColor.'.png') }}" alt="Kipanya T-shirt in {{ $colors[$selectedColor]['label'] ?? 'Black' }}" data-shirt-base="{{ asset('assets/wear/shirts') }}">
                    <span class="wear-print is-hidden" data-shirt-print aria-hidden="true"></span>
                </div>
                <div class="cartoon-stage-caption"><strong>{{ $cartoon->title }}</strong><span>Blank shirt preview</span></div>
            </div>
        </section>
        <aside class="cartoon-tshirt-controls">
            <div class="cartoon-control-card">
                <div class="cartoon-control-title"><div><span>01 · Artwork</span><h2>Selected Cartoon</h2></div><span class="cartoon-control-number">01</span></div>
                <div class="selected-cartoon"><div>@if($cartoon->resolved_thumbnail_url)<img src="{{ $cartoon->resolved_thumbnail_url }}" alt="">@endif</div><span><strong>{{ $cartoon->title }}</strong><small>{{ $cartoon->category?->name ?? 'Cartoon' }}</small></span></div>
                <div class="artwork-actions artwork-actions-presentation">
                    <span class="cartoon-artwork-status">Artwork preview paused for presentation</span>
                    <a href="{{ route('cartoon.search') }}" class="cartoon-artwork-change">Choose another</a>
                </div>
                <input type="hidden" name="artwork_enabled" value="0" data-artwork-input>
            </div>
            <div class="cartoon-control-card"><label class="cartoon-option-label">Shirt color</label><div class="shirt-color-grid">@foreach($colors as $key=>$color)<label class="shirt-color-option"><input type="radio" name="color" value="{{ $key }}" @checked($selectedColor===$key)><span style="--swatch:{{ $color['hex'] }}"></span><small>{{ $color['label'] }}</small></label>@endforeach</div></div>
            <div class="cartoon-control-card"><label class="cartoon-option-label">Size</label><div class="choice-pills">@foreach($sizes as $size)<label><input type="radio" name="size" value="{{ $size }}" @checked($selectedSize===$size)><span>{{ $size }}</span></label>@endforeach</div></div>
            <div class="cartoon-control-card"><label class="cartoon-option-label">Artwork placement</label><div class="placement-list">@foreach($placements as $key=>$placement)<label><input type="radio" name="placement" value="{{ $key }}" @checked($selectedPlacement===$key)><span><strong>{{ $placement['label'] }}</strong><small>{{ $placement['description'] }}</small></span><b>✓</b></label>@endforeach</div></div>
            <input type="hidden" name="scale" value="{{ $config['scale'] ?? 1 }}"><input type="hidden" name="offset_x" value="{{ $config['offset_x'] ?? 0 }}"><input type="hidden" name="offset_y" value="{{ $config['offset_y'] ?? 0 }}"><input type="hidden" name="rotation" value="{{ $config['rotation'] ?? 0 }}">
            <div class="cartoon-wear-note"><x-icon name="sparkles" size="16"/><div><strong>Presentation preview</strong><p>The selected Cartoon stays attached to this design, while the shirt preview remains clean until the full Wear customizer is finished.</p></div></div>
            <button type="submit" class="cartoon-btn cartoon-btn-primary cartoon-save-design"><span>Save design &amp; continue</span><x-icon name="arrow-right" size="17"/></button>
        </aside>
    </form>
</div>
<script>
(() => {
    const form = document.querySelector('[data-tshirt-form]');
    const canvas = document.querySelector('[data-shirt-canvas]');
    const shirt = document.querySelector('[data-shirt-base]');
    const print = document.querySelector('[data-shirt-print]');
    const artworkInput = document.querySelector('[data-artwork-input]');
    if (!form || !canvas || !shirt || !artworkInput) return;

    const stateUrl = form.dataset.stateUrl;
    const storageKey = `kipanya-wear-design:${@json($cartoon->slug)}`;
    let saveTimer = null;
    let saveSequence = 0;
    let activeRequest = null;

    const readState = () => ({
        color: form.querySelector('input[name="color"]:checked')?.value || 'black',
        size: form.querySelector('input[name="size"]:checked')?.value || 'M',
        placement: form.querySelector('input[name="placement"]:checked')?.value || 'front-center',
        scale: form.querySelector('input[name="scale"]')?.value || '1',
        offset_x: form.querySelector('input[name="offset_x"]')?.value || '0',
        offset_y: form.querySelector('input[name="offset_y"]')?.value || '0',
        rotation: form.querySelector('input[name="rotation"]')?.value || '0',
        artwork_enabled: artworkInput.value === '1' ? '1' : '0',
    });

    const restoreLocalState = () => {
        if (form.dataset.hasServerState === '1') return;
        try {
            const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
            if (!saved) return;
            const setRadio = (name, value) => {
                const input = form.querySelector(`input[name="${name}"][value="${CSS.escape(value)}"]`);
                if (input) input.checked = true;
            };
            setRadio('color', saved.color);
            setRadio('size', saved.size);
            setRadio('placement', saved.placement);
            if (saved.artwork_enabled === '0' || saved.artwork_enabled === '1') artworkInput.value = saved.artwork_enabled;
        } catch (_) {}
    };

    const persistLocal = () => {
        try { localStorage.setItem(storageKey, JSON.stringify(readState())); } catch (_) {}
    };

    const saveServerState = () => {
        if (!stateUrl) return;
        const sequence = ++saveSequence;
        activeRequest?.abort();
        const controller = new AbortController();
        activeRequest = controller;
        const body = new URLSearchParams(readState());
        fetch(stateUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body,
            keepalive: true,
            signal: controller.signal,
        }).then((response) => {
            if (!response.ok) throw new Error('Unable to save design state');
            if (sequence === saveSequence) form.dataset.stateSaved = '1';
        }).catch(() => {
            // localStorage remains the immediate offline/refresh fallback.
        }).finally(() => {
            if (activeRequest === controller) activeRequest = null;
        });
    };

    const queuePersist = () => {
        persistLocal();
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveServerState, 120);
    };

    const sync = ({persist = true} = {}) => {
        const colorInput = form.querySelector('input[name="color"]:checked');
        const placementInput = form.querySelector('input[name="placement"]:checked');
        const color = colorInput?.value || 'black';
        const placement = placementInput?.value || 'front-center';
        const colorLabel = colorInput?.closest('label')?.querySelector('small')?.textContent?.trim() || color;
        const enabled = artworkInput.value === '1';

        canvas.dataset.color = color;
        canvas.dataset.placement = placement;
        canvas.dataset.artworkEnabled = enabled ? 'true' : 'false';
        shirt.src = `${shirt.dataset.shirtBase}/${color}.png`;
        shirt.alt = `Kipanya T-shirt in ${colorLabel}`;

        if (print) {
            print.hidden = true;
            print.classList.add('is-hidden');
            print.setAttribute('aria-hidden', 'true');
        }
        artworkInput.value = '0';

        document.querySelector('[data-preview-color]')?.replaceChildren(document.createTextNode(colorLabel));
        document.querySelector('.cartoon-stage-caption span')?.replaceChildren(document.createTextNode('Blank shirt preview'));

        if (persist) queuePersist();
    };

    restoreLocalState();
    sync({persist: false});


    form.querySelectorAll('input[type="radio"]').forEach((input) => input.addEventListener('change', () => sync()));
})();
</script>
@endsection
