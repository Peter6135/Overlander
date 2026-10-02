<?php

namespace App\Http\Controllers;

use App\Models\Package;

class WishlistController extends Controller
{
    public function index()
    {
        $packages = auth()->user()->savedPackages()
            ->where('is_active', true)
            ->with('category')
            ->latest('wishlists.created_at')
            ->get();

        return view('wishlist.index', compact('packages'));
    }

    public function toggle(Package $package)
    {
        $result = auth()->user()->savedPackages()->toggle($package->id);

        $message = count($result['attached']) ? __('wishlist.saved') : __('wishlist.removed');

        return back()->with('success', $message);
    }
}
