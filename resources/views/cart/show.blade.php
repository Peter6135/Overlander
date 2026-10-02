@extends('layouts.app')

@section('title', 'Review Your Selection — Overlander')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('cart.title') }}</h1>
    <p class="text-sm text-gray-500 mb-6">{{ __('cart.subtitle') }}</p>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="flex gap-4 p-5 border-b border-gray-100">
            <img src="{{ $package->cover_photo_url }}" class="w-24 h-24 rounded-xl object-cover shrink-0" alt="{{ $package->name }}">
            <div>
                <a href="{{ route('packages.show', $package) }}" class="font-semibold text-gray-900 hover:text-brand-500">{{ $package->name }}</a>
                <p class="text-sm text-gray-500 mt-1">{{ $plan->name }}</p>
                @if(! empty($cart['trip_date']))
                    <p class="text-sm text-gray-500 mt-0.5">{{ __('booking.trip_date') }}: {{ \Illuminate\Support\Carbon::parse($cart['trip_date'])->format('d M Y') }}</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('cart.store', $package) }}" class="p-5 border-b border-gray-100 flex items-end gap-3">
            @csrf
            <input type="hidden" name="package_plan_id" value="{{ $plan->id }}">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('cart.travelers') }}</label>
                <input type="number" name="pax" min="1" max="{{ $package->capacity ?? 20 }}" value="{{ $cart['pax'] }}" required
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
            <button type="submit" class="btn-pop px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50">
                {{ __('cart.update') }}
            </button>
        </form>
    </div>

    <div class="flex gap-3 mt-6">
        <a href="{{ route('bookings.create', $package) }}"
           class="btn-pop flex-1 text-center px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
            {{ __('cart.proceed') }}
        </a>
        <form method="POST" action="{{ route('cart.destroy') }}">
            @csrf @method('DELETE')
            <button type="submit" data-no-loading class="btn-pop px-6 py-3 border border-gray-300 text-gray-600 rounded-xl font-medium hover:bg-gray-50">
                {{ __('cart.clear') }}
            </button>
        </form>
    </div>
</div>

@endsection
