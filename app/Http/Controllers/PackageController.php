<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['category', 'plans', 'destinations'])->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $packages = $query->orderByDesc('is_featured')->latest()->paginate(9)->withQueryString();
        $categories = Category::where('type', 'package')->orderBy('name')->get();

        return view('packages.index', compact('packages', 'categories'));
    }

    public function show(Package $package)
    {
        $package->load(['category', 'plans', 'destinations', 'itineraries']);

        return view('packages.show', compact('package'));
    }

    public function availability(Request $request, Package $package)
    {
        $request->validate(['date' => 'required|date_format:Y-m-d']);

        return response()->json([
            'capacity' => $package->capacity,
            'remaining' => $package->remainingCapacity($request->date),
        ]);
    }
}
