@extends('layouts.app')

@section('title', 'Custom Trip Request — Overlander')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('booking.custom_title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('booking.custom_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('bookings.store-custom') }}" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        @csrf

        @if($destinations->isNotEmpty())
        <div>
            <div class="flex items-baseline justify-between gap-3">
                <label class="block text-sm font-medium text-gray-700">{{ __('booking.custom_places_label') }}</label>
                <span id="places-count" class="text-xs font-medium text-brand-600" data-template="{{ __('booking.custom_places_selected') }}"></span>
            </div>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('booking.custom_places_hint') }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-3">
                @foreach($destinations as $destination)
                <label class="relative cursor-pointer">
                    <input type="checkbox" name="destinations[]" value="{{ $destination->id }}" class="place-input peer sr-only"
                           @checked(in_array($destination->id, array_map('intval', old('destinations', []))))>
                    <div class="rounded-xl overflow-hidden border-2 border-gray-200 bg-white transition-all duration-200 hover:border-brand-300 peer-checked:border-brand-500 peer-checked:shadow-md peer-focus-visible:ring-2 peer-focus-visible:ring-brand-300">
                        <img src="{{ $destination->cover_photo_url }}" alt="{{ $destination->name }}" class="w-full h-24 object-cover">
                        <div class="p-2">
                            <p class="text-xs font-semibold text-gray-800 leading-tight">{{ $destination->name }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $destination->location }}</p>
                        </div>
                    </div>
                    <span class="absolute top-2 right-2 w-6 h-6 rounded-full bg-brand-500 text-white text-xs font-bold items-center justify-center shadow hidden peer-checked:flex">✓</span>
                </label>
                @endforeach
            </div>
            @error('destinations') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            @error('destinations.*') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $destinations->isNotEmpty() ? __('booking.custom_or_write') : __('booking.custom_details_label') }}
            </label>
            <textarea name="custom_request" rows="5" placeholder="{{ __('booking.custom_details_placeholder') }}"
                      class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('custom_request') }}</textarea>
            @error('custom_request') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.estimated_date') }} <span class="text-red-500">*</span></label>
                <input type="date" name="trip_date" value="{{ old('trip_date') }}" min="{{ now()->addDay()->toDateString() }}" required
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.travelers') }} <span class="text-red-500">*</span></label>
                <input type="number" name="pax" min="1" max="20" value="{{ old('pax', 1) }}" required
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-sm font-medium text-gray-700 mb-3">{{ __('booking.contact_detail') }}</p>
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.full_name') }}</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', auth()->user()->name) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.email') }}</label>
                    <input type="email" name="guest_email" value="{{ old('guest_email', auth()->user()->email) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.phone_number') }}</label>
                    <input type="text" name="guest_phone" value="{{ old('guest_phone', auth()->user()->phone) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-500 bg-gray-50 rounded-xl p-3">{{ __('booking.whatsapp_followup_note') }}</p>

        <button type="submit" class="btn-pop w-full px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
            {{ __('booking.send_request') }}
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const inputs = Array.from(document.querySelectorAll('.place-input'));
    const counter = document.getElementById('places-count');
    if (! inputs.length || ! counter) return;

    function update() {
        const n = inputs.filter(i => i.checked).length;
        counter.textContent = n ? counter.dataset.template.replace(':n', n) : '';
    }

    inputs.forEach(i => i.addEventListener('change', update));
    update();
})();
</script>
@endpush
