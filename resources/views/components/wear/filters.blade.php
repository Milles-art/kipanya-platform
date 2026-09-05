@props([
    'categoryMeta' => [],
    'categoryCounts' => collect(),
    'sizeOptions' => [],
    'colorOptions' => [],
    'priceMin' => 0,
    'priceMax' => 0,
    'colorHex' => [],
])

<aside class="wear-store-sidebar" aria-label="Wear shop filters">
    <a href="{{ route('wear') }}" class="wear-store-side-logo">
        <span class="wear-store-side-logo-mark">K</span>
        <span><strong>KIPANYA</strong><small>WEAR</small></span>
    </a>

    <form class="wear-filter-panel" data-wear-filters>
        <div class="wear-filter-head">
            <div>
                <span>SHOP</span>
                <strong>Find your fit</strong>
            </div>
            <button type="button" data-wear-clear>Clear</button>
        </div>

        {{-- Category --}}
        <div class="wear-filter-group">
            <div class="wear-filter-group-head"><strong>Category</strong></div>
            <label class="wear-check-row">
                <input type="radio" name="category" value="" @checked(!request('category'))>
                <span>All products</span>
                <b>{{ $categoryCounts->sum() }}</b>
            </label>
            @foreach($categoryMeta as $category => $meta)
                <label class="wear-check-row">
                    <input type="radio" name="category" value="{{ $category }}" @checked(request('category') === $category)>
                    <span>{{ $category }}</span>
                    <b>{{ $categoryCounts[$category] ?? 0 }}</b>
                </label>
            @endforeach
        </div>

        {{-- Price --}}
        <div class="wear-filter-group">
            <div class="wear-filter-group-head">
                <strong>Price range</strong>
                <span>TSh {{ number_format((int) $priceMin, 0) }} – TSh {{ number_format((int) $priceMax, 0) }}</span>
            </div>
            <div class="wear-price-range">
                <input type="range" min="{{ (int) $priceMin }}" max="{{ (int) $priceMax }}" step="1000"
                       value="{{ request('min_price', (int) $priceMin) }}" data-price-min>
                <input type="range" min="{{ (int) $priceMin }}" max="{{ (int) $priceMax }}" step="1000"
                       value="{{ request('max_price', (int) $priceMax) }}" data-price-max>
            </div>
            <div class="wear-price-inputs">
                <input type="number" min="0" step="1000" name="min_price" value="{{ request('min_price') }}"
                       placeholder="Min" data-price-min-input>
                <span>—</span>
                <input type="number" min="0" step="1000" name="max_price" value="{{ request('max_price') }}"
                       placeholder="Max" data-price-max-input>
            </div>
        </div>

        {{-- Size --}}
        <div class="wear-filter-group">
            <div class="wear-filter-group-head"><strong>Size</strong></div>
            <div class="wear-filter-chips">
                @foreach($sizeOptions as $size)
                    <label>
                        <input type="checkbox" name="sizes[]" value="{{ $size }}"
                               @checked(in_array($size, (array) request('sizes', []), true))>
                        <span>{{ $size }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Color --}}
        <div class="wear-filter-group">
            <div class="wear-filter-group-head"><strong>Color</strong></div>
            <div class="wear-color-filter">
                @foreach($colorOptions as $color)
                    <label title="{{ ucfirst($color) }}">
                        <input type="checkbox" name="colors[]" value="{{ $color }}"
                               @checked(in_array($color, (array) request('colors', []), true))>
                        <span style="--swatch: {{ $colorHex[strtolower($color)] ?? '#111827' }}"></span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Availability --}}
        <div class="wear-filter-group">
            <div class="wear-filter-group-head"><strong>Availability</strong></div>
            <label class="wear-check-row">
                <input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock'))>
                <span>In stock</span>
            </label>
            <label class="wear-check-row">
                <input type="checkbox" name="on_sale" value="1" @checked(request()->boolean('on_sale'))>
                <span>On sale</span>
            </label>
        </div>
    </form>

    <div class="wear-side-trust">
        <div>
            <span><x-icon name="shield" size="16"/></span>
            <strong>Original Art</strong>
            <small>Kipanya designs</small>
        </div>
        <div>
            <span><x-icon name="package" size="16"/></span>
            <strong>Fast Delivery</strong>
            <small>Across Tanzania</small>
        </div>
        <div>
            <span><x-icon name="lock" size="16"/></span>
            <strong>Secure Payment</strong>
            <small>Protected checkout</small>
        </div>
    </div>
</aside>
