@extends('layouts.cartoon')
@section('content')
<div class="cartoon-shell tshirt-designer">
  <div class="tshirt-designer-head">
    <div>
      <div class="cartoon-eyebrow"><span></span>Kipanya Wear</div>
      <h1>Make this cartoon <em>wearable.</em></h1>
      <p>Turn a favorite Cartoon Archive story into a T-shirt concept. Your artwork stays connected to the original cartoon.</p>
    </div>
    <a href="{{ route('watch', $cartoon) }}" class="cartoon-btn cartoon-btn-glass">Back to cartoon</a>
  </div>

  @if(session('status'))<div class="tshirt-status" role="status"><x-icon name="check-circle" size="15"/> {{ session('status') }}</div>@endif

  <form method="POST" action="{{ route('wear.design.save', $cartoon) }}" class="tshirt-layout">
    @csrf
    <section class="tshirt-stage">
      <div class="tshirt-stage-label"><span>Live preview</span><span>{{ $cartoon->title }}</span></div>
      <div class="tshirt-canvas" data-shirt-stage data-shirt-color="{{ old('color', $design['color'] ?? 'ink') }}">
        <div class="tshirt-shirt" aria-hidden="true">
          <div class="tshirt-sleeve left"></div><div class="tshirt-sleeve right"></div>
          <div class="tshirt-neck"></div>
          <div class="tshirt-print" data-shirt-print>
            @if($cartoon->resolved_thumbnail_url)<img src="{{ $cartoon->resolved_thumbnail_url }}" alt="">@endif
          </div>
        </div>
        <div class="tshirt-stage-caption"><strong>{{ $cartoon->title }}</strong><span>Cartoon Archive × Kipanya Wear</span></div>
      </div>
    </section>

    <aside class="tshirt-panel">
      <div class="tshirt-panel-top"><div><span class="k-label k-muted">01 · Design</span><h2>Build your T-shirt</h2></div><span class="tshirt-step">01</span></div>
      <div class="tshirt-option"><label>Shirt color</label><div class="tshirt-swatches">@foreach(['ink'=>'Ink','white'=>'White','sand'=>'Sand'] as $value=>$label)<label class="tshirt-swatch"><input type="radio" name="color" value="{{ $value }}" @checked(old('color', $design['color'] ?? 'ink')===$value)><span class="swatch-dot {{ $value }}"></span><small>{{ $label }}</small></label>@endforeach</div></div>
      <div class="tshirt-option"><label>Size</label><div class="tshirt-pills">@foreach(['S','M','L','XL','XXL'] as $size)<label><input type="radio" name="size" value="{{ $size }}" @checked(old('size', $design['size'] ?? 'M')===$size)><span>{{ $size }}</span></label>@endforeach</div></div>
      <div class="tshirt-option"><label>Fit</label><div class="tshirt-pills two">@foreach(['classic'=>'Classic','oversized'=>'Oversized'] as $value=>$label)<label><input type="radio" name="fit" value="{{ $value }}" @checked(old('fit', $design['fit'] ?? 'classic')===$value)><span>{{ $label }}</span></label>@endforeach</div></div>
      <div class="tshirt-option"><label>Artwork placement</label><div class="tshirt-pills two">@foreach(['center'=>'Center','left'=>'Left chest'] as $value=>$label)<label><input type="radio" name="placement" value="{{ $value }}" @checked(old('placement', $design['placement'] ?? 'center')===$value)><span>{{ $label }}</span></label>@endforeach</div></div>
      <div class="tshirt-panel-note"><x-icon name="sparkles" size="15"/><div><strong>Artwork-first</strong><p>The preview uses the original Cartoon Archive artwork. Production mockups and checkout will connect here when Kipanya Wear goes live.</p></div></div>
      <button class="cartoon-btn cartoon-btn-primary w-full justify-center" type="submit"><x-icon name="shirt" size="15"/> Save design for Wear</button>
    </aside>
  </form>
</div>
<script>
document.addEventListener('DOMContentLoaded',()=>{
 const stage=document.querySelector('[data-shirt-stage]'), print=document.querySelector('[data-shirt-print]'); if(!stage||!print)return;
 const sync=()=>{
   const color=document.querySelector('input[name="color"]:checked')?.value||'ink';
   const placement=document.querySelector('input[name="placement"]:checked')?.value||'center';
   stage.dataset.shirtColor=color; print.dataset.placement=placement;
 };
 document.querySelectorAll('.tshirt-panel input').forEach(i=>i.addEventListener('change',sync)); sync();
});
</script>
@endsection
