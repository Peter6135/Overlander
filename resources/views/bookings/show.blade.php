@extends('layouts.app')

@section('title', 'Booking Detail — Overlander')

@push('scripts')
<style>
@media print {
    nav, footer, .no-print { display: none !important; }
}
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('bookings.index') }}" class="text-sm text-gray-500 hover:text-brand-500">{{ __('booking.back_to_bookings') }}</a>
        <button type="button" onclick="window.print()" data-no-loading class="btn-pop text-sm px-3 py-1.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50">
            {{ __('booking.print_ticket') }}
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $booking->is_custom ? __('booking.custom_trip_request') : $booking->package?->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ __('booking.booking_number') }}{{ $booking->id }}</p>
            </div>
            <span class="text-xs font-medium px-2 py-1 rounded-full shrink-0
                {{ match($booking->status) {
                    'confirmed' => 'bg-green-100 text-green-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    'completed' => 'bg-blue-100 text-blue-700',
                    default => 'bg-amber-100 text-amber-700',
                } }}">
                {{ __('booking.status_' . $booking->status) }}
            </span>
        </div>

        <div class="grid sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs">{{ __('booking.trip_date') }}</p>
                <p class="text-gray-800 font-medium">{{ $booking->trip_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs">{{ __('booking.travelers') }}</p>
                <p class="text-gray-800 font-medium">{{ $booking->pax }}</p>
            </div>
            @if($booking->packagePlan)
            <div>
                <p class="text-gray-400 text-xs">{{ __('booking.plan') }}</p>
                <p class="text-gray-800 font-medium">{{ $booking->packagePlan->name }}</p>
            </div>
            @endif
            <div>
                <p class="text-gray-400 text-xs">{{ __('booking.payment') }}</p>
                <p class="text-gray-800 font-medium">{{ __('booking.payment_status_' . $booking->payment_status) }}</p>
            </div>
        </div>

        @if(in_array($booking->status, ['pending', 'confirmed']))
            @php
                $waMessage = $booking->is_custom
                    ? __('booking.whatsapp_message_custom', [
                        'id' => $booking->id,
                        'date' => $booking->trip_date->format('d M Y'),
                        'pax' => $booking->pax,
                        'name' => $booking->guest_name,
                    ])
                    : __('booking.whatsapp_message_package', [
                        'id' => $booking->id,
                        'package' => $booking->package?->name,
                        'plan' => $booking->packagePlan?->name,
                        'date' => $booking->trip_date->format('d M Y'),
                        'pax' => $booking->pax,
                        'name' => $booking->guest_name,
                    ]);
            @endphp
            <a href="https://wa.me/{{ config('booking.whatsapp_number') }}?text={{ urlencode($waMessage) }}"
               target="_blank" rel="noopener"
               class="btn-pop no-print flex items-center justify-center gap-2 w-full px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 18.07h-.01c-1.5 0-2.97-.4-4.25-1.16l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 01-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.83 2.42a8.18 8.18 0 012.41 5.83c0 4.55-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.53.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01a.92.92 0 00-.67.31c-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.57.12.17 1.75 2.67 4.25 3.74.59.26 1.06.41 1.42.52.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.14-1.18-.06-.11-.23-.17-.48-.29z"/></svg>
                {{ __('booking.continue_whatsapp') }}
            </a>
        @endif

        @if($booking->custom_request)
        <div>
            <p class="text-gray-400 text-xs mb-1">{{ __('booking.custom_request_label') }}</p>
            <p class="text-gray-700 text-sm">{{ $booking->custom_request }}</p>
        </div>
        @endif

        @if($booking->message)
        <div>
            <p class="text-gray-400 text-xs mb-1">{{ __('booking.message_label') }}</p>
            <p class="text-gray-700 text-sm">{{ $booking->message }}</p>
        </div>
        @endif

        <div class="border-t border-gray-100 pt-4">
            <p class="text-gray-400 text-xs mb-1">{{ __('booking.contact') }}</p>
            <p class="text-gray-700 text-sm">{{ $booking->guest_name }} · {{ $booking->guest_email }} · {{ $booking->guest_phone }}</p>
        </div>

        @if($booking->canModify())
        <div class="flex gap-3 border-t border-gray-100 pt-4">
            <a href="{{ route('bookings.edit', $booking) }}" class="btn-pop px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50">{{ __('booking.edit_reservation') }}</a>
            <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                  onsubmit="return confirm(@json(__('booking.cancel_confirm_js')))">
                @csrf @method('DELETE')
                <button type="submit" data-no-loading class="btn-pop px-4 py-2 border border-red-200 text-red-600 rounded-xl text-sm font-medium hover:bg-red-50">{{ __('booking.cancel') }}</button>
            </form>
        </div>
        <p class="text-xs text-gray-400">{{ __('booking.modify_note') }}</p>
        @else
        <p class="text-xs text-gray-400 border-t border-gray-100 pt-4">{{ __('booking.cannot_change_note') }}</p>
        @endif
    </div>
</div>
@endsection
