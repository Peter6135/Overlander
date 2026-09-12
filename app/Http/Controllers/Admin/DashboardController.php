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

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentReviews'));
    }
}
