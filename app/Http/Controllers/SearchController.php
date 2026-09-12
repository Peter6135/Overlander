<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $destinations = $q !== ''
            ? Destination::where('is_active', true)->where('name', 'like', "%{$q}%")->orderBy('name')->get()
            : collect();

        $packages = $q !== ''
            ? Package::where('is_active', true)->where('name', 'like', "%{$q}%")->orderBy('name')->get()
            : collect();

        return view('search.index', compact('q', 'destinations', 'packages'));
    }
}
