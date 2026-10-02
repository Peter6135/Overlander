@extends('layouts.app')

@section('title', 'Overlander — Start Your Journey')
@section('main-class', '')

@section('content')

{{-- Hero Carousel --}}
@php
    $heroSlides = collect();

    $heroSlides->push([
        'type' => 'brand',
        'image' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1600',
        'badge' => __('home.hero_badge'),
        'title' => __('home.hero_title'),
        'subtitle' => __('home.hero_subtitle'),
        'cta_label' => null,
        'cta_url' => null,
    ]);

    foreach ($featuredPackages->take(2) as $pkg) {
        $heroSlides->push([
            'type' => 'promo',
            'image' => $pkg->cover_photo_url,
            'badge' => __('home.hero_badge_promo'),
            'title' => $pkg->name,
            'subtitle' => \Illuminate\Support\Str::limit(strip_tags($pkg->description ?? ''), 110),
            'cta_label' => __('home.hero_cta_promo'),
            'cta_url' => route('packages.show', $pkg),
        ]);
    }

    foreach ($upcomingEvents as $event) {
        $heroSlides->push([
            'type' => 'event',
            'image' => $event->cover_photo_url,
            'badge' => __('home.hero_badge_event'),
            'title' => $event->title,
            'subtitle' => \Illuminate\Support\Str::limit(strip_tags($event->description ?? ''), 110),
            'cta_label' => null,
            'cta_url' => null,
        ]);
    }

    if ($testimonials->isNotEmpty()) {
        $review = $testimonials->first();
        $heroSlides->push([
            'type' => 'review',
            'image' => $review->destination?->cover_photo_url ?? $heroSlides->first()['image'],
            'badge' => __('home.hero_badge_review'),
            'title' => '"' . \Illuminate\Support\Str::limit($review->comment, 140) . '"',
            'subtitle' => '— ' . $review->reviewer_name,
            'cta_label' => __('home.hero_cta_review'),
            'cta_url' => route('reviews.index'),
        ]);
    }
@endphp

<section class="relative bg-neutral-900 text-white overflow-hidden" id="hero-carousel">
    <div class="relative h-[460px] sm:h-[560px]">
        @foreach($heroSlides as $i => $slide)
        <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}">
            <img src="{{ $slide['image'] }}" class="absolute inset-0 w-full h-full object-cover opacity-40" alt="">
            <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-neutral-900/40 to-neutral-900/10"></div>
            <div class="relative h-full max-w-6xl mx-auto px-12 sm:px-4 flex flex-col items-center justify-center text-center pb-20">
                <p class="inline-block px-3 py-1 rounded-full bg-brand-500/20 border border-brand-500/40 text-brand-300 text-xs font-medium mb-4">
                    {{ $slide['badge'] }}
                </p>
                <h1 class="font-black tracking-tight mb-4 max-w-2xl {{ $slide['type'] === 'review' ? 'text-xl sm:text-2xl font-bold italic' : 'text-3xl sm:text-5xl' }}">{{ $slide['title'] }}</h1>
                <p class="text-neutral-300 max-w-xl mx-auto mb-6">{{ $slide['subtitle'] }}</p>
                @if($slide['cta_url'])
                <a href="{{ $slide['cta_url'] }}" class="btn-pop px-6 py-2.5 bg-brand-500 hover:bg-brand-600 rounded-xl text-sm font-semibold transition-colors">
                    {{ $slide['cta_label'] }}
                </a>
                @endif
            </div>
        </div>
        @endforeach

        @if($heroSlides->count() > 1)
        <button type="button" id="hero-prev" aria-label="Previous slide"
                class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-all duration-200 hover:scale-110">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button type="button" id="hero-next" aria-label="Next slide"
                class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-all duration-200 hover:scale-110">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </button>

        <div class="absolute bottom-24 sm:bottom-28 left-0 right-0 flex justify-center gap-2 z-20">
            @foreach($heroSlides as $i => $slide)
            <button type="button" class="hero-dot w-2 h-2 rounded-full transition-colors {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}" data-dot="{{ $i }}" aria-label="Go to slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
        @endif
    </div>

    <div class="absolute bottom-6 left-0 right-0 z-20 px-4">
        <form method="GET" action="{{ route('destinations.index') }}" class="max-w-lg mx-auto flex gap-2">
            <input type="text" name="search" placeholder="{{ __('home.hero_search_placeholder') }}"
                   class="flex-1 rounded-xl px-4 py-3 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
            <button type="submit" class="btn-pop px-6 py-3 bg-brand-500 hover:bg-brand-600 rounded-xl text-sm font-semibold transition-colors">{{ __('home.search') }}</button>
        </form>
    </div>
</section>

@if($heroSlides->count() > 1)
@push('scripts')
<script>
(function () {
    const root = document.getElementById('hero-carousel');
    if (! root) return;

    const slides = Array.from(root.querySelectorAll('.hero-slide'));
    const dots = Array.from(root.querySelectorAll('.hero-dot'));
    const prevBtn = document.getElementById('hero-prev');
    const nextBtn = document.getElementById('hero-next');
    let current = 0;
    let timer = null;

    function show(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === current);
            slide.classList.toggle('z-10', i === current);
            slide.classList.toggle('opacity-0', i !== current);
            slide.classList.toggle('z-0', i !== current);
            slide.classList.toggle('pointer-events-none', i !== current);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === current);
            dot.classList.toggle('bg-white/40', i !== current);
        });
    }

    function next() { show(current + 1); }
    function prev() { show(current - 1); }

    function startAutoplay() {
        stopAutoplay();
        timer = setInterval(next, 6000);
    }
    function stopAutoplay() {
        if (timer) clearInterval(timer);
    }

    prevBtn?.addEventListener('click', () => { prev(); startAutoplay(); });
    nextBtn?.addEventListener('click', () => { next(); startAutoplay(); });
    dots.forEach((dot, i) => dot.addEventListener('click', () => { show(i); startAutoplay(); }));

    root.addEventListener('mouseenter', stopAutoplay);
    root.addEventListener('mouseleave', startAutoplay);

    startAutoplay();
})();
</script>
@endpush
@endif

{{-- Top Values --}}
<section class="max-w-6xl mx-auto px-4 py-14">
    <h2 class="text-center text-2xl font-bold text-gray-900 mb-2">{{ __('home.top_values_title') }}</h2>
    <p class="text-center text-gray-500 text-sm mb-10">{{ __('home.top_values_subtitle') }}</p>
    @include('_value-props')
</section>

{{-- Our Packages --}}
<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-center text-2xl font-bold text-gray-900 mb-2">{{ __('home.packages_title') }}</h2>
        <p class="text-center text-gray-500 text-sm mb-10">{{ __('home.packages_subtitle') }}</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $categoryPhotos = [
                    'Cave & Beach' => 'https://images.unsplash.com/photo-1640809305430-97601f8ce861?w=600',
                    'One Day Tour' => 'https://images.unsplash.com/photo-1539635278303-d4002c07eae3?w=600',
                    'Overland' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=600',
                    'Sunrise' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?w=600',
                ];
            @endphp
            @foreach($packageCategories as $cat)
            <a href="{{ route('packages.index', ['category' => $cat->slug]) }}"
               class="group relative rounded-2xl overflow-hidden h-40 flex items-end p-4 text-white category-card">
                <img src="{{ $categoryPhotos[$cat->name] ?? $categoryPhotos['One Day Tour'] }}"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <span class="relative font-semibold">{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Get closer to your destination --}}
<section class="max-w-6xl mx-auto px-4 py-14">
    <h2 class="text-center text-2xl font-bold text-gray-900 mb-2">{{ __('home.destinations_title') }}</h2>
    <p class="text-center text-gray-500 text-sm mb-10">{{ __('home.destinations_subtitle') }}</p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @foreach($featuredDestinations as $destination)
        <a href="{{ route('destinations.show', $destination) }}" class="block rounded-2xl overflow-hidden border border-gray-100 provider-card">
            <img src="{{ $destination->cover_photo_url }}" class="w-full h-44 object-cover" alt="{{ $destination->name }}">
            <div class="p-4">
                <p class="font-semibold text-gray-800">{{ $destination->name }}</p>
                <p class="text-xs text-gray-500">{{ $destination->location }}</p>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- What They Say About Us --}}
<section class="bg-neutral-900 text-white py-14 overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 text-center mb-10">
        <h2 class="text-2xl font-bold mb-2">{{ __('home.testimonials_title') }}</h2>
        <p class="text-neutral-400 text-sm">{{ __('home.testimonials_subtitle') }}</p>
    </div>

    @if($testimonials->isEmpty())
        <p class="text-center text-neutral-500 text-sm">{{ __('home.testimonials_empty') }}</p>
    @else
        @php
            $tHalf = (int) ceil($testimonials->count() / 2);
            $tRow1 = $testimonials->slice(0, $tHalf)->values();
            $tRow2 = $testimonials->slice($tHalf)->values();
            if ($tRow2->isEmpty()) { $tRow2 = $tRow1; }
        @endphp
        <div class="relative space-y-4">
            <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-12 sm:w-32 z-10" style="background: linear-gradient(to right, #171717, transparent);"></div>
            <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-12 sm:w-32 z-10" style="background: linear-gradient(to left, #171717, transparent);"></div>

            @foreach([[$tRow1, 'testimonial-track'], [$tRow2, 'testimonial-track-reverse']] as [$row, $cls])
            <div class="flex gap-5 px-4 {{ $cls }}">
                @foreach($row->concat($row) as $review)
                <a href="{{ route('reviews.index') }}" class="block bg-neutral-800 hover:bg-neutral-700 transition-colors rounded-2xl p-6 shrink-0 w-80">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center font-bold shrink-0">
                            {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $review->reviewer_name }}</p>
                            <p class="text-yellow-400 text-xs">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                        </div>
                    </div>
                    <p class="text-neutral-300 text-sm leading-relaxed line-clamp-4">"{{ $review->comment }}"</p>
                </a>
                @endforeach
            </div>
            @endforeach
        </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 text-center mt-10">
        <a href="{{ route('reviews.index') }}" class="text-brand-400 font-medium text-sm hover:underline">{{ __('home.testimonials_view_all') }}</a>
    </div>
</section>

<style>
@keyframes testimonial-marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.testimonial-track,
.testimonial-track-reverse {
    width: max-content;
    animation: testimonial-marquee 40s linear infinite;
}
.testimonial-track-reverse {
    animation-direction: reverse;
}
.testimonial-track:hover,
.testimonial-track-reverse:hover {
    animation-play-state: paused;
}
</style>

{{-- Blog About Travelling --}}
<section class="max-w-6xl mx-auto px-4 py-14">
    <h2 class="text-center text-2xl font-bold text-gray-900 mb-2">{{ __('home.blog_title') }}</h2>
    <p class="text-center text-gray-500 text-sm mb-10">{{ __('home.blog_subtitle') }}</p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @foreach($latestArticles as $article)
        <a href="{{ route('articles.show', $article) }}" class="block rounded-2xl overflow-hidden border border-gray-100 provider-card">
            <img src="{{ $article->cover_photo_url }}" class="w-full h-40 object-cover" alt="{{ $article->title }}">
            <div class="p-4">
                <p class="font-semibold text-gray-800 line-clamp-2">{{ $article->title }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $article->published_at?->format('d M Y') }}</p>
            </div>
        </a>
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('articles.index') }}" class="text-brand-500 font-medium text-sm hover:underline">{{ __('home.blog_view_all') }}</a>
    </div>
</section>

{{-- About --}}
<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4 grid sm:grid-cols-2 gap-8 items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('home.about_title') }}</h2>
            <p class="text-gray-600 leading-relaxed mb-4">{{ __('home.about_body') }}</p>
            <a href="{{ route('packages.index') }}" class="btn-pop inline-block px-6 py-3 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600 transition-colors">{{ __('home.about_cta') }}</a>
        </div>
        <img src="https://images.unsplash.com/photo-1533105079780-92b9be482077?w=800" class="rounded-2xl w-full h-64 object-cover" alt="">
    </div>
</section>

@endsection
