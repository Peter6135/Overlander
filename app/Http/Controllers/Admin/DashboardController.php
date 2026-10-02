<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Review;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'destinations'      => Destination::count(),
            'packages'          => Package::count(),
            'members'           => User::where('is_admin', false)->count(),
            'reviews'           => Review::count(),
            'bookings_pending'  => Booking::where('status', 'pending')->count(),
            'bookings_total'    => Booking::count(),
            'articles'          => Article::count(),
        ];

        $recentBookings = Booking::with(['user', 'package'])->latest()->take(10)->get();
        $recentReviews = Review::with(['destination', 'user'])->latest()->take(10)->get();

        $monthStarts = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->startOfMonth());
        $perMonth = Booking::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $monthStarts->first())
            ->get(['created_at', 'pax'])
            ->groupBy(fn ($b) => $b->created_at->format('Y-m'));

        $monthly = $monthStarts->map(fn ($m) => [
            'label' => $m->format('M'),
            'bookings' => $perMonth->get($m->format('Y-m'), collect())->count(),
            'travelers' => (int) $perMonth->get($m->format('Y-m'), collect())->sum('pax'),
        ]);

        $statusCounts = Booking::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $topPackages = Booking::whereNotNull('package_id')
            ->where('status', '!=', 'cancelled')
            ->selectRaw('package_id, COUNT(*) as bookings, SUM(pax) as travelers')
            ->groupBy('package_id')
            ->orderByDesc('bookings')
            ->take(5)
            ->get();
        $packageNames = Package::whereIn('id', $topPackages->pluck('package_id'))->pluck('name', 'id');

        $upcomingTrips = Booking::with('package')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('trip_date', '>=', today())
            ->orderBy('trip_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'recentReviews',
            'monthly', 'statusCounts', 'topPackages', 'packageNames', 'upcomingTrips'
        ));
    }
}
