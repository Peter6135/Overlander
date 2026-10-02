@extends('layouts.app')

@section('title', __('contact.title') . ' — Overlander')
@section('meta_description', __('contact.subtitle'))

@section('content')
@php
    $waNumber = config('booking.whatsapp_number');
    $channels = [
        ['label' => __('contact.whatsapp'), 'value' => '+62 856-4103-4599', 'hint' => __('contact.whatsapp_hint'), 'href' => "https://wa.me/{$waNumber}?text=" . urlencode(__('nav.whatsapp_float_message')), 'highlight' => true],
        ['label' => __('contact.email'), 'value' => config('booking.email'), 'hint' => null, 'href' => 'mailto:' . config('booking.email'), 'highlight' => false],
    ];
    foreach (config('booking.socials') as $name => $social) {
        if ($social) {
            $channels[] = ['label' => __('contact.' . $name), 'value' => $social['label'], 'hint' => null, 'href' => $social['url'], 'highlight' => false];
        }
    }
@endphp

<div class="max-w-4xl mx-auto space-y-10">

    <div class="text-center">
        <h1 class="text-3xl font-black text-gray-900">{{ __('contact.title') }}</h1>
        <p class="text-gray-500 mt-2 max-w-xl mx-auto">{{ __('contact.subtitle') }}</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <div class="space-y-3">
            <h2 class="text-lg font-bold text-gray-900">{{ __('contact.channels_title') }}</h2>
            @foreach($channels as $c)
                @if($c['href'])
                <a href="{{ $c['href'] }}" @if(str_starts_with($c['href'], 'http')) target="_blank" rel="noopener" @endif
                   class="block rounded-2xl border p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $c['highlight'] ? 'border-green-300 bg-green-50 hover:border-green-400' : 'border-gray-200 bg-white hover:border-brand-300' }}">
                @else
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                @endif
                    <p class="text-xs font-medium text-gray-400">{{ $c['label'] }}</p>
                    <p class="font-semibold text-gray-900">{{ $c['value'] }}</p>
                    @if($c['hint'])<p class="text-xs text-green-700 mt-0.5">{{ $c['hint'] }}</p>@endif
                @if($c['href'])
                </a>
                @else
                </div>
                @endif
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900">{{ __('contact.form_title') }}</h2>
            <p class="text-sm text-gray-500 mt-1 mb-4">{{ __('contact.form_subtitle') }}</p>

            <form id="contact-form" class="space-y-4" data-number="{{ $waNumber }}" data-intro="{{ __('contact.wa_intro') }}">
                <div>
                    <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.name_label') }}</label>
                    <input type="text" id="contact-name" required maxlength="80"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div>
                    <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.message_label') }}</label>
                    <textarea id="contact-message" rows="4" required maxlength="800" placeholder="{{ __('contact.message_placeholder') }}"
                              class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100"></textarea>
                </div>
                <button type="submit" data-no-loading
                        class="btn-pop w-full px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                    {{ __('contact.send_cta') }}
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('contact-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const name = document.getElementById('contact-name').value.trim();
    const message = document.getElementById('contact-message').value.trim();
    const text = this.dataset.intro.replace(':name', name) + '\n\n' + message;
    window.open('https://wa.me/' + this.dataset.number + '?text=' + encodeURIComponent(text), '_blank', 'noopener');
});
</script>
@endpush
