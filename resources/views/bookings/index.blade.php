@extends('layouts.app')

@section('title', 'My Bookings — Overlander')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('booking.my_bookings_title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('booking.my_bookings_subtitle') }}</p>
    </div>

    <div class="space-y-4">
        @forelse($bookings as $booking)
        <a href="{{ route('bookings.show', $booking) }}" class="block bg-white rounded-2xl border border-gray-200 p-5 hover:border-brand-300 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-semibold text-gray-800">{{ $booking->is_custom ? __('booking.custom_trip_request') : $booking->package?->name }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $booking->trip_date->format('d M Y') }} @if($booking->packagePlan) · {{ $booking->packagePlan->name }} @endif</p>
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
        </a>
        @empty
        <div class="text-center py-16 text-gray-400">
            <p>{{ __('booking.empty') }}</p>
            <a href="{{ route('packages.index') }}" class="text-brand-500 font-medium hover:underline text-sm mt-2 inline-block">{{ __('booking.view_packages') }}</a>
        </div>
        @endforelse
    </div>

    {{ $bookings->links() }}
</div>
@endsection
