@props(['title' => 'Cartoon Archive'])
<button type="button" class="cartoon-share-btn" data-share-url="{{ url()->current() }}" data-share-title="{{ $title }}" aria-label="Share {{ $title }}"><x-icon name="share" size="16"/> <span>Share</span></button>
<script>
if(!window.__kipanyaShareBound){window.__kipanyaShareBound=true;document.addEventListener('click',async e=>{const b=e.target.closest('[data-share-url]');if(!b)return;const url=b.dataset.shareUrl,title=b.dataset.shareTitle;if(navigator.share){try{await navigator.share({title,url})}catch(_){}return}try{await navigator.clipboard.writeText(url);const old=b.innerHTML;b.innerHTML='<span>Copied</span>';setTimeout(()=>b.innerHTML=old,1400)}catch(_){}})}
</script>
