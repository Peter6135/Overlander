<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::with('categories')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $destinations = $query->paginate(15)->withQueryString();

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        $categories = Category::where('type', 'destination')->orderBy('name')->get();
        return view('admin.destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('destinations', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $data['created_by'] = auth()->id();

        $destination = Destination::create($data);
        $destination->categories()->sync($request->input('categories', []));

        $this->syncActivities($destination, $request);

        return redirect()->route('admin.destinations.index')
            ->with('success', "Destination {$destination->name} added successfully.");
    }

    public function edit(Destination $destination)
    {
        $destination->load('categories', 'activities');
        $categories = Category::where('type', 'destination')->orderBy('name')->get();
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
    {
        $data = $this->validated($request, $destination->id);

        if ($request->hasFile('cover_photo')) {
            if ($destination->cover_photo && ! str_starts_with($destination->cover_photo, 'http')) {
                Storage::disk('public')->delete($destination->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('destinations', 'public');
        }

        $destination->update($data);
        $destination->categories()->sync($request->input('categories', []));

        $this->syncActivities($destination, $request);

        return redirect()->route('admin.destinations.index')
            ->with('success', "Destination {$destination->name} updated successfully.");
    }

    public function destroy(Destination $destination)
    {
        $name = $destination->name;
        $destination->delete();
        return redirect()->route('admin.destinations.index')
            ->with('success', "Destination {$name} deleted successfully.");
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description_en' => 'nullable|string',
            'description_id' => 'nullable|string',
            'what_to_do_en' => 'nullable|string',
            'what_to_do_id' => 'nullable|string',
            'point_of_interest_en' => 'nullable|string',
            'point_of_interest_id' => 'nullable|string',
            'nature_level' => 'nullable|integer|min:1|max:5',
            'culture_level' => 'nullable|integer|min:1|max:5',
            'heritage_level' => 'nullable|integer|min:1|max:5',
            'cover_photo' => 'nullable|image|max:4096',
            'is_active' => 'nullable|boolean',
        ]);
    }

    private function syncActivities(Destination $destination, Request $request): void
    {
        $destination->activities()->delete();

        foreach ($request->input('activity_title', []) as $i => $title) {
            if (blank($title)) continue;

            Activity::create([
                'destination_id' => $destination->id,
                'title' => $title,
                'type' => $request->input('activity_type')[$i] ?? 'adventure',
                'description_en' => $request->input('activity_description_en')[$i] ?? null,
                'description_id' => $request->input('activity_description_id')[$i] ?? null,
            ]);
        }
    }
}
