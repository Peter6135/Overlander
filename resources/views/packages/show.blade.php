@extends('layouts.app')

@section('title', $package->name . ' — Overlander')
@section('main-class', '')

@section('content')

<div class="relative h-72 sm:h-96">
    <img src="{{ $package->cover_photo }}" class="w-full h-full object-cover" alt="{{ $package->name }}">
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
</div>

<div class="max-w-6xl mx-auto px-4 py-10 grid sm:grid-cols-3 gap-10">
    <div class="sm:col-span-2 space-y-8">

        @if($package->description)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('packages.about_title') }}</h2>
            <p class="text-gray-600 leading-relaxed">{{ $package->description }}</p>
        </div>
        @endif

        @if($package->destinations->count() > 1)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('packages.trip_route_title') }}</h2>
            <div class="flex flex-wrap items-center gap-2">
                @foreach($package->destinations as $i => $dest)
                    <a href="{{ route('destinations.show', $dest) }}" class="px-3 py-1.5 rounded-full bg-brand-50 text-brand-600 text-sm font-medium hover:bg-brand-100">{{ $dest->name }}</a>
                    @if(! $loop->last)<span class="text-gray-300">→</span>@endif
                @endforeach
            </div>
        </div>
        @endif

        @if($package->itineraries->count())
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('packages.itinerary_title') }}</h2>
            <div class="space-y-4">
                @foreach($package->itineraries as $day)
                <div class="flex gap-4">
                    <div class="shrink-0 w-20 text-brand-500 font-bold text-sm pt-0.5">{{ $day->day_label }}</div>
                    <div class="border-l-2 border-brand-100 pl-4 pb-4">
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $day->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="font-semibold text-gray-800 mb-3">{{ __('packages.choose_plan_title') }}</p>

            @auth
            <form method="POST" action="{{ route('cart.store', $package) }}">
                @csrf
                <div class="space-y-3">
                    @forelse($package->plans as $plan)
                    <label class="block border rounded-xl p-3 relative cursor-pointer {{ $plan->is_recommended ? 'border-brand-400 bg-brand-50' : 'border-gray-200' }}">
                        @if($plan->is_recommended)
                            <span class="absolute -top-2 right-3 text-xs bg-brand-500 text-white px-2 py-0.5 rounded-full">{{ __('packages.recommended') }}</span>
                        @endif
                        <div class="flex items-start gap-2">
                            <input type="radio" name="package_plan_id" value="{{ $plan->id }}" class="mt-1" @checked($plan->is_recommended) required>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $plan->name }}</p>
                                <p class="text-brand-600 font-bold">${{ number_format($plan->price, 0) }}<span class="text-xs text-gray-400 font-normal">/person</span></p>
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

                <button type="submit" class="block w-full text-center mt-4 px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
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
                        <p class="text-brand-600 font-bold">${{ number_format($plan->price, 0) }}<span class="text-xs text-gray-400 font-normal">/person</span></p>
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
                   class="block text-center mt-4 px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
                    {{ __('packages.login_to_book') }}
                </a>
            @endauth
        </div>
    </div>
</div>

@endsection
