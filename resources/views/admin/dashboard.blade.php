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

    {{-- Booking analytics --}}
    @php $maxBookings = max(1, $monthly->max('bookings')); @endphp
    <div class="grid lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-semibold text-gray-800">Booking requests per month</h2>
                <span class="text-xs text-gray-400">Last 6 months, cancelled excluded</span>
            </div>
            <div class="flex items-end gap-3 h-40">
                @foreach($monthly as $m)
                <div class="flex-1 flex flex-col items-center justify-end h-full gap-1" title="{{ $m['bookings'] }} request(s), {{ $m['travelers'] }} traveler(s)">
                    <span class="text-xs font-semibold text-gray-700">{{ $m['bookings'] }}</span>
                    <div class="w-full rounded-t-lg bg-brand-500/80 hover:bg-brand-500 transition-colors" style="height: {{ max(4, round($m['bookings'] / $maxBookings * 100)) }}%"></div>
                </div>
                @endforeach
            </div>
            <div class="flex gap-3 mt-2">
                @foreach($monthly as $m)
                <div class="flex-1 text-center text-xs text-gray-400">{{ $m['label'] }}</div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Bookings by status</h2>
            @php
                $statusStyles = ['pending' => 'bg-amber-400', 'confirmed' => 'bg-green-500', 'completed' => 'bg-blue-500', 'cancelled' => 'bg-red-400'];
                $statusTotal = max(1, $statusCounts->sum());
            @endphp
            <div class="space-y-3">
                @foreach($statusStyles as $status => $color)
                @php $count = (int) ($statusCounts[$status] ?? 0); @endphp
                <div>
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>{{ ucfirst($status) }}</span>
                        <span class="font-semibold">{{ $count }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full {{ $color }}" style="width: {{ round($count / $statusTotal * 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Most booked packages</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topPackages as $row)
                <div class="px-6 py-3 flex items-center justify-between gap-4">
                    <p class="text-sm text-gray-800 truncate">{{ $packageNames[$row->package_id] ?? 'Deleted package' }}</p>
                    <p class="text-xs text-gray-500 shrink-0"><span class="font-semibold text-gray-800">{{ $row->bookings }}</span> booking(s) · {{ $row->travelers }} traveler(s)</p>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No bookings yet.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Upcoming trips</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($upcomingTrips as $trip)
                <div class="px-6 py-3 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm text-gray-800 truncate">{{ $trip->is_custom ? 'Custom trip' : $trip->package?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $trip->guest_name }} · {{ $trip->pax }} pax</p>
                    </div>
                    <p class="text-xs font-semibold text-brand-600 shrink-0">{{ $trip->trip_date->format('d M Y') }}</p>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No upcoming trips.</div>
                @endforelse
            </div>
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
