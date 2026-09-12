@extends('layouts.app')

@section('title', 'Edit Booking — Overlander')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('bookings.show', $booking) }}" class="text-sm text-gray-500 hover:text-brand-500">{{ __('booking.back') }}</a>

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-4">{{ __('booking.edit_title') }}{{ $booking->id }}</h1>

        <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.trip_date') }}</label>
                    <input type="date" name="trip_date" value="{{ old('trip_date', $booking->trip_date->toDateString()) }}" min="{{ now()->addDays(14)->toDateString() }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                    @error('trip_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.travelers') }}</label>
                    <input type="number" name="pax" min="1" max="{{ $booking->package?->capacity ?? 20 }}" value="{{ old('pax', $booking->pax) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                    @error('pax') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.full_name') }}</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', $booking->guest_name) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.email') }}</label>
                    <input type="email" name="guest_email" value="{{ old('guest_email', $booking->guest_email) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.phone_number') }}</label>
                    <input type="text" name="guest_phone" value="{{ old('guest_phone', $booking->guest_phone) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.message_label') }}</label>
                <textarea name="message" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('message', $booking->message) }}</textarea>
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">{{ __('booking.save_changes') }}</button>
        </form>
    </div>
</div>
@endsection
