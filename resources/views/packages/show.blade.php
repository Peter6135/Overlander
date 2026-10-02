@extends('layouts.app')

@section('title', $package->name . ' — Overlander')
@section('main-class', '')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')

@php
    $galleryPhotos = $package->galleryPhotoUrls();
@endphp
<div class="relative h-72 sm:h-96 overflow-hidden" id="package-gallery">
    @foreach($galleryPhotos as $i => $photo)
    <img src="{{ $photo }}" class="package-gallery-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-500 {{ $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" alt="{{ $package->name }}">
    @endforeach
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 max-w-6xl mx-auto px-4 pb-6 text-white">
        @if($package->category)
            <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 backdrop-blur">{{ $package->category->name }}</span>
        @endif
        <h1 class="text-3xl sm:text-4xl font-black mt-2">{{ $package->name }}</h1>
        @if($package->destinations->count() > 1)
            <p class="text-neutral-200 text-sm mt-1">📍 {{ $package->destinations->pluck('name')->join(' → ') }}</p>
        @endif
    </div>

    @if(count($galleryPhotos) > 1)
    <button type="button" id="gallery-prev" aria-label="Previous photo"
            class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-all duration-200 hover:scale-110">
        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </button>
    <button type="button" id="gallery-next" aria-label="Next photo"
            class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-all duration-200 hover:scale-110">
        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
    </button>
    <div class="absolute top-4 right-4 flex gap-1.5 z-20">
        @foreach($galleryPhotos as $i => $photo)
        <button type="button" class="gallery-dot w-1.5 h-1.5 rounded-full transition-colors {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}" data-dot="{{ $i }}" aria-label="Go to photo {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif
</div>

<div class="max-w-6xl mx-auto px-4 py-10">

    @if($package->duration_days || $package->start_city || $package->end_city)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
        @if($package->duration_days)
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="18" height="16" rx="2"/>
                <path d="M3 10h18M8 3v4M16 3v4"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_duration') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ trans_choice('packages.trip_facts_duration_value', $package->duration_days, ['n' => $package->duration_days]) }}</p>
            </div>
        </div>
        @endif
        @if($package->start_city)
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 21s7-6.5 7-12a7 7 0 10-14 0c0 5.5 7 12 7 12z"/>
                <circle cx="12" cy="9" r="2.5"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_start') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->start_city }}</p>
            </div>
        </div>
        @endif
        @if($package->end_city)
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 21V4l14 7-14 7"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_end') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->end_city }}</p>
            </div>
        </div>
        @endif
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11"/>
                <rect x="3" y="11" width="18" height="6" rx="1.5"/>
                <circle cx="7" cy="19" r="1.3"/>
                <circle cx="17" cy="19" r="1.3"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_transportation') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ __('packages.trip_facts_transportation_value') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 3v6a2 2 0 002 2v10M7 3a2 2 0 00-2 2v4M11 3v8M17 3c-1.5 0-2.5 1.5-2.5 4s1 4 2.5 4v10"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_meals') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->hasMealsOption() ? __('packages.trip_facts_included') : __('packages.trip_facts_not_included') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 21V9l8-5 8 5v12"/>
                <path d="M9 21v-6h6v6"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_accommodation') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->hasAccommodationOption() ? __('packages.trip_facts_included') : __('packages.trip_facts_not_included') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid sm:grid-cols-3 gap-10">
    <div class="sm:col-span-2 space-y-8">

        @if($package->description)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('packages.about_title') }}</h2>
            <p class="text-gray-600 leading-relaxed">{{ $package->description }}</p>
        </div>
        @endif

        @php
            $routePoints = $package->destinations->map(fn ($d) => [
                'lat' => $d->latitude ? (float) $d->latitude : null,
                'lng' => $d->longitude ? (float) $d->longitude : null,
                'name' => $d->name,
            ])->filter(fn ($p) => $p['lat'] && $p['lng'])->values();
        @endphp

        @if($package->destinations->count() >= 1)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('packages.trip_route_title') }}</h2>
            @if($package->destinations->count() > 1)
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @foreach($package->destinations as $i => $dest)
                    <a href="{{ route('destinations.show', $dest) }}" class="px-3 py-1.5 rounded-full bg-brand-50 text-brand-600 text-sm font-medium hover:bg-brand-100">{{ $dest->name }}</a>
                    @if(! $loop->last)<span class="text-gray-300">→</span>@endif
                @endforeach
            </div>
            @endif
            @if($routePoints->isNotEmpty())
            <div id="route-map" class="rounded-2xl overflow-hidden border border-gray-200" style="height: 320px;"></div>
            @endif
        </div>
        @endif

        @if($package->itineraries->count())
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('packages.itinerary_title') }}</h2>
            <div class="space-y-4">
                @foreach($package->itineraries as $day)
                <div class="flex gap-4">
                    <div class="shrink-0 w-20 text-brand-500 font-bold text-sm pt-0.5">{{ $day->day_label }}</div>
                    <div class="border-l-2 border-brand-100 pl-4 pb-4 flex-1">
                        <div class="flex items-start gap-4">
                            <p class="text-gray-600 text-sm leading-relaxed flex-1">{{ $day->description }}</p>
                            @if($day->photo)
                            <img src="{{ asset('storage/' . $day->photo) }}" class="w-20 h-20 rounded-xl object-cover shrink-0" alt="{{ $day->day_label }}">
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <label for="availability-date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('packages.check_availability_label') }}</label>
            <input type="date" id="availability-date" min="{{ now()->addDay()->toDateString() }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            <p id="availability-preview-msg" class="text-xs mt-2"></p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="font-semibold text-gray-800 mb-3">{{ __('packages.choose_plan_title') }}</p>

            @auth
            <form method="POST" action="{{ route('cart.store', $package) }}">
                @csrf
                <div class="space-y-3">
                    @forelse($package->plans as $plan)
                    <label class="block border rounded-xl p-3 relative cursor-pointer transition-all duration-200 hover:border-brand-300 hover:shadow-md {{ $plan->is_recommended ? 'border-brand-400 bg-brand-50' : 'border-gray-200' }}">
                        @if($plan->is_recommended)
                            <span class="absolute -top-2 right-3 text-xs bg-brand-500 text-white px-2 py-0.5 rounded-full">{{ __('packages.recommended') }}</span>
                        @endif
                        <div class="flex items-start gap-2">
                            <input type="radio" name="package_plan_id" value="{{ $plan->id }}" class="mt-1" @checked($plan->is_recommended) required>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $plan->name }}</p>
                                @if($plan->features)
                                <ul class="text-xs text-gray-500 mt-2 space-y-0.5">
                                    @foreach($plan->features as $f)
                                        <li>✓ {{ $f }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </label>
                    @empty
                    <p class="text-sm text-gray-400">{{ __('packages.no_pricing') }}</p>
                    @endforelse
                </div>

                @if($package->plans->count())
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('packages.travelers_label') }}</label>
                    <input type="number" name="pax" min="1" max="{{ $package->capacity ?? 20 }}" value="1" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>

                <button type="submit" class="btn-pop block w-full text-center mt-4 px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
                    {{ __('packages.add_to_cart') }}
                </button>
                @endif
            </form>
            @else
                <div class="space-y-3">
                    @forelse($package->plans as $plan)
                    <div class="border rounded-xl p-3 relative {{ $plan->is_recommended ? 'border-brand-400 bg-brand-50' : 'border-gray-200' }}">
                        @if($plan->is_recommended)
                            <span class="absolute -top-2 right-3 text-xs bg-brand-500 text-white px-2 py-0.5 rounded-full">{{ __('packages.recommended') }}</span>
                        @endif
                        <p class="font-semibold text-gray-800 text-sm">{{ $plan->name }}</p>
                        @if($plan->features)
                        <ul class="text-xs text-gray-500 mt-2 space-y-0.5">
                            @foreach($plan->features as $f)
                                <li>✓ {{ $f }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">{{ __('packages.no_pricing') }}</p>
                    @endforelse
                </div>

                <a href="{{ route('login') }}"
                   class="btn-pop block text-center mt-4 px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
                    {{ __('packages.login_to_book') }}
                </a>
            @endauth
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const root = document.getElementById('package-gallery');
    if (! root) return;

    const slides = Array.from(root.querySelectorAll('.package-gallery-slide'));
    const dots = Array.from(root.querySelectorAll('.gallery-dot'));
    const prevBtn = document.getElementById('gallery-prev');
    const nextBtn = document.getElementById('gallery-next');
    if (slides.length < 2) return;

    let current = 0;

    function show(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === current);
            slide.classList.toggle('z-10', i === current);
            slide.classList.toggle('opacity-0', i !== current);
            slide.classList.toggle('z-0', i !== current);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === current);
            dot.classList.toggle('bg-white/40', i !== current);
        });
    }

    prevBtn?.addEventListener('click', () => show(current - 1));
    nextBtn?.addEventListener('click', () => show(current + 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));

    let touchStartX = null;
    root.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    root.addEventListener('touchend', (e) => {
        if (touchStartX === null) return;
        const diff = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(diff) > 40) { diff > 0 ? show(current - 1) : show(current + 1); }
        touchStartX = null;
    }, { passive: true });
})();
</script>
@endpush

@push('scripts')
<script>
(function () {
    const dateInput = document.getElementById('availability-date');
    const msgEl = document.getElementById('availability-preview-msg');
    if (! dateInput) return;

    const availabilityUrl = '{{ route('packages.availability', $package) }}';
    const i18n = {
        fullyBooked: @json(__('booking.fully_booked_js')),
        slotsLeft: @json(__('booking.slots_left_js')),
        noLimit: @json(__('packages.availability_no_limit_js')),
    };

    dateInput.addEventListener('change', function () {
        if (! dateInput.value) { msgEl.textContent = ''; return; }

        fetch(availabilityUrl + '?date=' + dateInput.value)
            .then(res => res.json())
            .then(data => {
                if (data.remaining === null) {
                    msgEl.textContent = i18n.noLimit;
                    msgEl.className = 'text-xs mt-2 text-green-600';
                } else if (data.remaining <= 0) {
                    msgEl.textContent = i18n.fullyBooked;
                    msgEl.className = 'text-xs mt-2 text-red-500';
                } else {
                    msgEl.textContent = i18n.slotsLeft.replace(':n', data.remaining);
                    msgEl.className = 'text-xs mt-2 text-green-600';
                }
            });
    });
})();
</script>
@endpush

@if($routePoints->isNotEmpty())
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    const points = @json($routePoints);
    const mapEl = document.getElementById('route-map');
    if (! mapEl || ! points.length) return;

    const map = L.map('route-map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    const latLngs = points.map(p => [p.lat, p.lng]);

    points.forEach((p, i) => {
        L.marker([p.lat, p.lng])
            .addTo(map)
            .bindPopup(`${i + 1}. ${p.name}`);
    });

    if (latLngs.length > 1) {
        L.polyline(latLngs, { color: '#f9530f', weight: 3, dashArray: '6 6' }).addTo(map);
        map.fitBounds(latLngs, { padding: [30, 30] });
    } else {
        map.setView(latLngs[0], 11);
    }
})();
</script>
@endpush
@endif

@endsection
