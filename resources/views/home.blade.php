@extends('layouts.app')

@section('title', 'Overlander — Start Your Journey')
@section('main-class', '')

@section('content')

{{-- Hero --}}
<section class="relative bg-neutral-900 text-white">
    <div class="absolute inset-0 opacity-40">
        <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1600" class="w-full h-full object-cover" alt="">
    </div>
    <div class="relative max-w-6xl mx-auto px-4 py-24 sm:py-32 text-center">
        <p class="inline-block px-3 py-1 rounded-full bg-brand-500/20 border border-brand-500/40 text-brand-300 text-xs font-medium mb-4">
            {{ __('home.hero_badge') }}
        </p>
        <h1 class="text-4xl sm:text-6xl font-black tracking-tight mb-4">{{ __('home.hero_title') }}</h1>
        <p class="text-neutral-300 max-w-xl mx-auto mb-8">{{ __('home.hero_subtitle') }}</p>

        <form method="GET" action="{{ route('destinations.index') }}" class="max-w-lg mx-auto flex gap-2">
            <input type="text" name="search" placeholder="{{ __('home.hero_search_placeholder') }}"
                   class="flex-1 rounded-xl px-4 py-3 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
            <button type="submit" class="px-6 py-3 bg-brand-500 hover:bg-brand-600 rounded-xl text-sm font-semibold transition-colors">{{ __('home.search') }}</button>
        </form>
    </div>
</section>

{{-- Top Values --}}
<section class="max-w-6xl mx-auto px-4 py-14">
    <h2 class="text-center text-2xl font-bold text-gray-900 mb-2">{{ __('home.top_values_title') }}</h2>
    <p class="text-center text-gray-500 text-sm mb-10">{{ __('home.top_values_subtitle') }}</p>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
        @foreach([
            ['icon' => 'map', 'label' => __('home.value_curated_routes')],
            ['icon' => 'star', 'label' => __('home.value_experienced_guides')],
            ['icon' => 'chat', 'label' => __('home.value_flexible_booking')],
            ['icon' => 'shield', 'label' => __('home.value_safe_travel')],
        ] as $value)
        <div class="text-center">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 flex items-center justify-center text-brand-500 mb-3">
                @switch($value['icon'])
                    @case('map')
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 6L3 8v10l6-2 6 2 6-2V6l-6 2-6-2z"/>
                            <path d="M9 6v10M15 8v10"/>
                        </svg>
                        @break
                    @case('star')
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l2.9 6.26L21.8 9l-5 4.87L18.1 21 12 17.27 5.9 21l1.3-7.13-5-4.87 6.9-.74L12 2z"/>
                        </svg>
                        @break
                    @case('chat')
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                        </svg>
                        @break
                    @case('shield')
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        @break
                @endswitch
            </div>
            <p class="text-sm font-medium text-gray-700">{{ $value['label'] }}</p>
        </div>
        @endforeach
    </div>
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
            <img src="{{ $destination->cover_photo }}" class="w-full h-44 object-cover" alt="{{ $destination->name }}">
            <div class="p-4">
                <p class="font-semibold text-gray-800">{{ $destination->name }}</p>
                <p class="text-xs text-gray-500">{{ $destination->location }}</p>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- What They Say About Us --}}
<section class="bg-neutral-900 text-white py-14">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-center text-2xl font-bold mb-2">{{ __('home.testimonials_title') }}</h2>
        <p class="text-center text-neutral-400 text-sm mb-10">{{ __('home.testimonials_subtitle') }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @forelse($testimonials as $review)
            <div class="bg-neutral-800 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center font-bold shrink-0">
                        {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ $review->reviewer_name }}</p>
                        <p class="text-yellow-400 text-xs">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                    </div>
                </div>
                <p class="text-neutral-300 text-sm leading-relaxed">"{{ $review->comment }}"</p>
            </div>
            @empty
            <p class="col-span-3 text-center text-neutral-500 text-sm">{{ __('home.testimonials_empty') }}</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Blog About Travelling --}}
<section class="max-w-6xl mx-auto px-4 py-14">
    <h2 class="text-center text-2xl font-bold text-gray-900 mb-2">{{ __('home.blog_title') }}</h2>
    <p class="text-center text-gray-500 text-sm mb-10">{{ __('home.blog_subtitle') }}</p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @foreach($latestArticles as $article)
        <a href="{{ route('articles.show', $article) }}" class="block rounded-2xl overflow-hidden border border-gray-100 provider-card">
            <img src="{{ $article->cover_photo }}" class="w-full h-40 object-cover" alt="{{ $article->title }}">
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
            <a href="{{ route('packages.index') }}" class="inline-block px-6 py-3 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600 transition-colors">{{ __('home.about_cta') }}</a>
        </div>
        <img src="https://images.unsplash.com/photo-1533105079780-92b9be482077?w=800" class="rounded-2xl w-full h-64 object-cover" alt="">
    </div>
</section>

@endsection
