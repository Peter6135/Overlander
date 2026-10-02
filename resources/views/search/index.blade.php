@extends('layouts.app')

@section('title', 'Search — Overlander')

@section('content')

<div class="max-w-4xl mx-auto">
    <form method="GET" action="{{ route('search') }}" class="flex gap-2 mb-8">
        <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('search.placeholder') }}"
               class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        <button type="submit" class="btn-pop px-6 py-3 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600">{{ __('search.search') }}</button>
    </form>

    @if($q === '')
        <p class="text-gray-400 text-center py-12">{{ __('search.prompt') }}</p>
    @else
        <p class="text-sm text-gray-500 mb-6">{{ __('search.results_for') }} "<span class="font-medium text-gray-800">{{ $q }}</span>"</p>

        <div class="mb-10">
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('search.destinations') }}</h2>
            @forelse($destinations as $destination)
                <a href="{{ route('destinations.show', $destination) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50">
                    <img src="{{ $destination->cover_photo_url }}" class="w-16 h-16 rounded-lg object-cover" alt="{{ $destination->name }}">
                    <div>
                        <p class="font-medium text-gray-900">{{ $destination->name }}</p>
                        <p class="text-sm text-gray-500">{{ $destination->location }}</p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-gray-400">{{ __('search.no_destinations') }}</p>
            @endforelse
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('search.packages') }}</h2>
            @forelse($packages as $package)
                <a href="{{ route('packages.show', $package) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50">
                    <img src="{{ $package->cover_photo_url }}" class="w-16 h-16 rounded-lg object-cover" alt="{{ $package->name }}">
                    <p class="font-medium text-gray-900">{{ $package->name }}</p>
                </a>
            @empty
                <p class="text-sm text-gray-400">{{ __('search.no_packages') }}</p>
            @endforelse
        </div>
    @endif
</div>

@endsection
