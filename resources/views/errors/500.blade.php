@extends('layouts.app')
@section('title', 'Something Went Wrong')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center py-20">
    <div class="text-8xl font-black text-gray-100 mb-2" style="letter-spacing: -0.04em;">500</div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Server error</h1>
    <p class="text-gray-500 text-sm mb-8 max-w-sm">Sorry, something went wrong on our end. Please try again shortly.</p>
    <a href="{{ route('home') }}"
       class="btn-pop px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-semibold hover:bg-brand-600 transition-colors">
        Go Home
    </a>
</div>
@endsection
