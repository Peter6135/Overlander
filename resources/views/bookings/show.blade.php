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
        <button type="button" onclick="window.print()" data-no-loading class="text-sm px-3 py-1.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50">
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
            @if($booking->total_price)
            <div>
                <p class="text-gray-400 text-xs">{{ __('booking.total_price') }}</p>
                <p class="text-gray-800 font-medium">${{ number_format($booking->total_price, 0) }}</p>
            </div>
            @endif
            <div>
                <p class="text-gray-400 text-xs">{{ __('booking.payment') }}</p>
                <p class="text-gray-800 font-medium">{{ $booking->payment_method ? __('booking.method_' . $booking->payment_method) : '-' }} · {{ __('booking.payment_status_' . $booking->payment_status) }}</p>
            </div>
            @if(! $booking->is_custom)
            <div>
                <p class="text-gray-400 text-xs">{{ __('booking.payment_scheme') }}</p>
                <p class="text-gray-800 font-medium">
                    {{ __('booking.scheme_' . $booking->payment_scheme) }}
                    @if($booking->payment_scheme === 'deposit' && $booking->deposit_amount)
                        (${{ number_format($booking->deposit_amount, 0) }} {{ __('booking.due_now') }})
                    @endif
                </p>
            </div>
            @endif
        </div>

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
            <a href="{{ route('bookings.edit', $booking) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50">{{ __('booking.edit_reservation') }}</a>
            <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                  onsubmit="return confirm(@json(__('booking.cancel_confirm_js')))">
                @csrf @method('DELETE')
                <button type="submit" data-no-loading class="px-4 py-2 border border-red-200 text-red-600 rounded-xl text-sm font-medium hover:bg-red-50">{{ __('booking.cancel') }}</button>
            </form>
        </div>
        <p class="text-xs text-gray-400">{{ __('booking.modify_note') }}</p>
        @else
        <p class="text-xs text-gray-400 border-t border-gray-100 pt-4">{{ __('booking.cannot_change_note') }}</p>
        @endif
    </div>
</div>
@endsection
