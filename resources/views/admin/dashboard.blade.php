@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.destinations.index') }}" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-green-300 transition-colors">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Destinations</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['destinations'] }}</p>
        </a>
        <a href="{{ route('admin.packages.index') }}" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-brand-300 transition-colors">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Tour Packages</p>
            <p class="text-3xl font-bold text-brand-500 mt-1">{{ $stats['packages'] }}</p>
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-amber-300 transition-colors">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Pending Bookings</p>
            <p class="text-3xl font-bold text-amber-500 mt-1">{{ $stats['bookings_pending'] }}</p>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-purple-300 transition-colors">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Reviews</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['reviews'] }}</p>
        </a>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Bookings</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['bookings_total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Registered Members</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['members'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Articles</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['articles'] }}</p>
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Recent Bookings</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-brand-500 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentBookings as $booking)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-medium text-gray-800 text-sm">{{ $booking->guest_name }}</p>
                    <p class="text-gray-500 text-xs truncate">{{ $booking->is_custom ? 'Custom Trip' : $booking->package?->name }} · {{ $booking->trip_date->format('d M Y') }}</p>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full shrink-0
                    {{ match($booking->status) {
                        'confirmed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                        'completed' => 'bg-blue-100 text-blue-700',
                        default => 'bg-amber-100 text-amber-700',
                    } }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No bookings yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Reviews --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Recent Reviews</h2>
            <a href="{{ route('admin.reviews.index') }}" class="text-sm text-brand-500 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentReviews as $review)
            <div class="px-6 py-4 flex items-start gap-4">
                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-medium text-gray-800 text-sm">{{ $review->reviewer_name }}</span>
                        @if($review->destination)
                            <span class="text-gray-400 text-xs">→</span>
                            <a href="{{ route('destinations.show', $review->destination) }}" class="text-brand-500 hover:underline text-sm font-medium">{{ $review->destination->name }}</a>
                        @endif
                        <span class="text-yellow-500 text-xs font-medium">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </span>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-500 text-sm mt-0.5 truncate">{{ $review->comment }}</p>
                    @endif
                </div>
                <span class="text-xs text-gray-400 shrink-0">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No reviews yet.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
