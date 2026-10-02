<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::latest('event_date');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title_en', 'like', "%{$request->search}%")
                    ->orWhere('title_id', 'like', "%{$request->search}%");
            });
        }

        $events = $query->paginate(15)->withQueryString();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('events', 'public');
        }

        $data['created_by'] = auth()->id();

        $event = Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', "Event {$event->title} added successfully.");
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_photo')) {
            if ($event->cover_photo && ! str_starts_with($event->cover_photo, 'http')) {
                Storage::disk('public')->delete($event->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', "Event {$event->title} updated successfully.");
    }

    public function destroy(Event $event)
    {
        $title = $event->title;
        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', "Event {$title} deleted successfully.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title_en' => 'required|string|max:255',
            'title_id' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_id' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:4096',
            'event_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
