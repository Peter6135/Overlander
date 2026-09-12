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

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.custom_details_label') }} <span class="text-red-500">*</span></label>
            <textarea name="custom_request" rows="5" required placeholder="{{ __('booking.custom_details_placeholder') }}"
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

        <div>
            <p class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.payment_method') }} <span class="text-red-500">*</span></p>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-2 border rounded-xl p-3 cursor-pointer has-[:checked]:border-brand-400 has-[:checked]:bg-brand-50">
                    <input type="radio" name="payment_method" value="paypal" required>
                    <span class="text-sm">{{ __('booking.paypal') }}</span>
                </label>
                <label class="flex-1 flex items-center gap-2 border rounded-xl p-3 cursor-pointer has-[:checked]:border-brand-400 has-[:checked]:bg-brand-50">
                    <input type="radio" name="payment_method" value="whatsapp">
                    <span class="text-sm">{{ __('booking.confirm_whatsapp') }}</span>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
            {{ __('booking.send_request') }}
        </button>
    </form>
</div>
@endsection
