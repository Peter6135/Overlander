<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $destination->reviews()->create([
            ...$data,
            'user_id' => auth()->id(),
            'reviewer_name' => auth()->user()->name,
            'reviewer_avatar' => auth()->user()->avatar_url,
        ]);

        return back()->with('success', __('flash.review_thanks'));
    }
}
