<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Event;
use App\Models\Package;
use App\Models\PackageItinerary;

class GalleryController extends Controller
{
    public function index()
    {
        $items = collect();
        $seen = [];

        // Only photos stored on this site (our own trips), never stock images from external URLs.
        $add = function (?string $path, string $caption, string $group) use (&$items, &$seen) {
            if (! $path || str_starts_with($path, 'http') || isset($seen[$path])) {
                return;
            }

            $seen[$path] = true;
            $items->push(['url' => asset('storage/' . $path), 'caption' => $caption, 'group' => $group]);
        };

        $tripsGroup = __('gallery.group_trips');

        Destination::where('is_active', true)->with('activities')->orderBy('name')->get()->each(function ($d) use ($add) {
            $add($d->cover_photo, $d->name, $d->name);

            foreach ($d->activities as $activity) {
                $add($activity->photo, $activity->title . ' · ' . $d->name, $d->name);
            }
        });

        Package::where('is_active', true)->get()->each(fn ($p) => $add($p->cover_photo, $p->name, $tripsGroup));
        PackageItinerary::with('package')->orderBy('package_id')->orderBy('order')->get()
            ->each(fn ($i) => $add($i->photo, ($i->package?->name ?? '') . ' · ' . $i->day_label, $tripsGroup));
        Event::where('is_active', true)->get()->each(fn ($e) => $add($e->cover_photo, $e->title, $tripsGroup));

        $groups = $items->pluck('group')->unique()->values();

        return view('gallery', compact('items', 'groups'));
    }
}
