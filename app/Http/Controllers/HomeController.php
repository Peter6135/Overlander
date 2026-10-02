<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Package;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $packageCategories = Category::where('type', 'package')->orderBy('name')->get();

        $featuredPackages = Package::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $featuredDestinations = Destination::where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        $testimonials = Review::where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $latestArticles = Article::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $upcomingEvents = Event::where('is_active', true)
            ->orderBy('event_date')
            ->take(3)
            ->get();

        return view('home', compact(
            'packageCategories', 'featuredPackages', 'featuredDestinations', 'testimonials', 'latestArticles', 'upcomingEvents'
        ));
    }
}
