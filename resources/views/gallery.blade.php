@extends('layouts.app')

@section('title', __('gallery.title') . ' — Overlander')
@section('meta_description', __('gallery.subtitle'))
@section('og_image', $items->first()['url'] ?? '')

@section('content')
<div class="space-y-8">

    <div class="text-center">
        <h1 class="text-3xl font-black text-gray-900">{{ __('gallery.title') }}</h1>
        <p class="text-gray-500 mt-2">{{ __('gallery.subtitle') }}</p>
    </div>

    @if($items->isEmpty())
        <p class="text-center text-gray-400 py-12">{{ __('gallery.empty') }}</p>
    @else
    <div class="flex flex-wrap justify-center gap-2" id="gallery-filters">
        <button type="button" data-filter="" class="gallery-chip category-chip px-3 py-1.5 rounded-full text-xs font-medium border bg-brand-500 text-white border-brand-500">{{ __('gallery.all') }}</button>
        @foreach($groups as $group)
            <button type="button" data-filter="{{ $group }}" class="gallery-chip category-chip px-3 py-1.5 rounded-full text-xs font-medium border border-gray-200 text-gray-600 hover:bg-gray-50">{{ $group }}</button>
        @endforeach
    </div>

    <div id="gallery-grid" class="columns-2 md:columns-3 gap-3 space-y-3">
        @foreach($items as $item)
        <button type="button" class="gallery-item group relative block w-full break-inside-avoid rounded-2xl overflow-hidden bg-gray-100"
                data-group="{{ $item['group'] }}" data-src="{{ $item['url'] }}" data-caption="{{ $item['caption'] }}">
            <img src="{{ $item['url'] }}" alt="{{ $item['caption'] }}" loading="lazy" class="w-full h-auto transition-transform duration-300 group-hover:scale-105">
            <span class="absolute inset-x-0 bottom-0 p-3 text-left text-xs font-medium text-white bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">{{ $item['caption'] }}</span>
        </button>
        @endforeach
    </div>

    <div id="lightbox" class="hidden fixed inset-0 z-[60] bg-black/90 flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <button type="button" id="lb-close" aria-label="{{ __('gallery.close') }}" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xl transition-colors">&times;</button>
        <button type="button" id="lb-prev" aria-label="{{ __('gallery.prev') }}" class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <figure class="max-w-5xl w-full text-center">
            <img id="lb-img" src="" alt="" class="max-h-[80vh] w-auto mx-auto rounded-xl">
            <figcaption id="lb-caption" class="mt-3 text-sm text-white/80"></figcaption>
        </figure>
        <button type="button" id="lb-next" aria-label="{{ __('gallery.next') }}" class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </button>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
(function () {
    const grid = document.getElementById('gallery-grid');
    if (! grid) return;

    const items = Array.from(grid.querySelectorAll('.gallery-item'));
    const chips = Array.from(document.querySelectorAll('.gallery-chip'));
    const box = document.getElementById('lightbox');
    const img = document.getElementById('lb-img');
    const caption = document.getElementById('lb-caption');
    let visible = items;
    let index = 0;

    chips.forEach(chip => chip.addEventListener('click', () => {
        const filter = chip.dataset.filter;
        chips.forEach(c => {
            const on = c === chip;
            c.classList.toggle('bg-brand-500', on);
            c.classList.toggle('text-white', on);
            c.classList.toggle('border-brand-500', on);
            c.classList.toggle('text-gray-600', ! on);
            c.classList.toggle('border-gray-200', ! on);
        });
        items.forEach(it => it.classList.toggle('hidden', filter !== '' && it.dataset.group !== filter));
        visible = items.filter(it => ! it.classList.contains('hidden'));
    }));

    function open(i) {
        index = (i + visible.length) % visible.length;
        img.src = visible[index].dataset.src;
        img.alt = caption.textContent = visible[index].dataset.caption;
        box.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function close() {
        box.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    items.forEach(it => it.addEventListener('click', () => open(visible.indexOf(it))));
    document.getElementById('lb-close').addEventListener('click', close);
    document.getElementById('lb-prev').addEventListener('click', () => open(index - 1));
    document.getElementById('lb-next').addEventListener('click', () => open(index + 1));
    box.addEventListener('click', e => { if (e.target === box) close(); });
    document.addEventListener('keydown', e => {
        if (box.classList.contains('hidden')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') open(index - 1);
        if (e.key === 'ArrowRight') open(index + 1);
    });
})();
</script>
@endpush
