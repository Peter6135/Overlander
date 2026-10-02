@extends('layouts.app')
@section('title', 'Page Not Found')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center py-20">
    <div class="text-8xl font-black text-gray-100 mb-2" style="letter-spacing: -0.04em;">404</div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Page not found</h1>
    <p class="text-gray-500 text-sm mb-8 max-w-sm">The page you're looking for doesn't exist or has been moved.</p>
    <div class="flex gap-3">
        <a href="{{ route('home') }}"
           class="btn-pop px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600 transition-colors">
            Go Home
        </a>
        <a href="{{ route('destinations.index') }}"
           class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
            Explore Destinations
        </a>
    </div>
</div>
@endsection
