@extends('layouts.app')

@section('title', __('wishlist.title') . ' — Overlander')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('wishlist.title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('wishlist.subtitle') }}</p>
    </div>

    @if($packages->isEmpty())
        <div class="bg-gray-50 rounded-2xl p-10 text-center">
            <p class="font-semibold text-gray-800">{{ __('wishlist.empty_title') }}</p>
            <p class="text-sm text-gray-500 mt-1 mb-5">{{ __('wishlist.empty_text') }}</p>
            <a href="{{ route('packages.index') }}" class="btn-pop inline-block px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">{{ __('wishlist.browse') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach($packages as $package)
            <div class="relative">
                <a href="{{ route('packages.show', $package) }}" class="block rounded-2xl overflow-hidden border border-gray-100 provider-card">
                    <img src="{{ $package->cover_photo_url }}" class="w-full h-44 object-cover" alt="{{ $package->name }}">
                    <div class="p-4">
                        <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-600">{{ $package->category?->name }}</span>
                        <p class="font-semibold text-gray-800 mt-2 line-clamp-2">{{ $package->name }}</p>
                    </div>
                </a>
                @include('_wishlist-heart', ['package' => $package, 'position' => 'absolute top-3 right-3 z-10'])
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
