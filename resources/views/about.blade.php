@extends('layouts.app')

@section('title', __('about.title') . ' — Overlander')
@section('main-class', '')

@section('content')

<section class="bg-neutral-900 text-white py-20">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h1 class="text-4xl sm:text-5xl font-black mb-4">{{ __('about.title') }}</h1>
        <p class="text-neutral-300 max-w-xl mx-auto">{{ __('about.subtitle') }}</p>
    </div>
</section>

<section class="py-14">
    <div class="max-w-6xl mx-auto px-4 grid sm:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('about.story_title') }}</h2>
            <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ __('about.story_body') }}</p>
        </div>
        <img src="https://images.unsplash.com/photo-1533105079780-92b9be482077?w=800" class="rounded-2xl w-full h-72 object-cover" alt="">
    </div>
</section>

<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-center text-2xl font-bold text-gray-900 mb-10">{{ __('about.values_title') }}</h2>
        @include('_value-props')
    </div>
</section>

<section class="py-16 text-center">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('about.cta_title') }}</h2>
        <p class="text-gray-500 mb-6">{{ __('about.cta_subtitle') }}</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('packages.index') }}" class="btn-pop px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">{{ __('about.cta_packages') }}</a>
            <a href="https://wa.me/{{ config('booking.whatsapp_number') }}" target="_blank" rel="noopener" class="btn-pop px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">{{ __('about.cta_contact') }}</a>
        </div>
    </div>
</section>

@endsection
