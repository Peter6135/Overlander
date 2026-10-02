@extends('layouts.app')

@section('title', __('faq.title') . ' — Overlander')
@section('meta_description', __('faq.subtitle'))

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div class="text-center">
        <h1 class="text-3xl font-black text-gray-900">{{ __('faq.title') }}</h1>
        <p class="text-gray-500 mt-2">{{ __('faq.subtitle') }}</p>
    </div>

    <div class="space-y-3">
        @foreach(__('faq.items') as $item)
        <details class="group bg-white rounded-2xl border border-gray-200 px-5 py-4 transition-shadow hover:shadow-md open:border-brand-300 open:shadow-md">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none [&::-webkit-details-marker]:hidden font-semibold text-gray-900">
                <span>{{ $item['q'] }}</span>
                <svg class="w-5 h-5 shrink-0 text-brand-500 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>
            <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ $item['a'] }}</p>
        </details>
        @endforeach
    </div>

    <div class="bg-gray-50 rounded-2xl p-6 text-center">
        <p class="font-semibold text-gray-800 mb-1">{{ __('faq.still_title') }}</p>
        <p class="text-sm text-gray-500 mb-4">{{ __('faq.still_subtitle') }}</p>
        <a href="https://wa.me/{{ config('booking.whatsapp_number') }}?text={{ urlencode(__('nav.whatsapp_float_message')) }}"
           target="_blank" rel="noopener"
           class="btn-pop inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
            {{ __('nav.whatsapp_chat') }}
        </a>
    </div>

</div>
@endsection
