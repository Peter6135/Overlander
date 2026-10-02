<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Package;
use App\Models\PackageItinerary;
use App\Models\PackagePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with('category')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $packages = $query->paginate(15)->withQueryString();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = Category::where('type', 'package')->orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        return view('admin.packages.create', compact('categories', 'destinations'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('packages', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $data['created_by'] = auth()->id();

        $package = Package::create($data);

        $this->syncDestinations($package, $request);
        $this->syncPlans($package, $request);
        $this->syncItineraries($package, $request);

        return redirect()->route('admin.packages.index')
            ->with('success', "Package {$package->name} added successfully.");
    }

    public function edit(Package $package)
    {
        $package->load('destinations', 'plans', 'itineraries');
        $categories = Category::where('type', 'package')->orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        return view('admin.packages.edit', compact('package', 'categories', 'destinations'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_photo')) {
            if ($package->cover_photo && ! str_starts_with($package->cover_photo, 'http')) {
                Storage::disk('public')->delete($package->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('packages', 'public');
        }

        $package->update($data);

        $this->syncDestinations($package, $request);
        $this->syncPlans($package, $request);
        $this->syncItineraries($package, $request);

        return redirect()->route('admin.packages.index')
            ->with('success', "Package {$package->name} updated successfully.");
    }

    public function destroy(Package $package)
    {
        $name = $package->name;
        $package->delete();
        return redirect()->route('admin.packages.index')
            ->with('success', "Package {$name} deleted successfully.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_id' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:4096',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'capacity' => 'nullable|integer|min:1',
            'duration_days' => 'nullable|integer|min:1',
            'start_city' => 'nullable|string|max:100',
            'end_city' => 'nullable|string|max:100',
        ]);
    }

    private function syncDestinations(Package $package, Request $request): void
    {
        $orders = collect($request->input('destination_order', []))
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->map(fn ($v) => (int) $v)
            ->sort();

        $sync = [];
        foreach ($orders as $destinationId => $order) {
            $sync[$destinationId] = ['order' => $order];
        }
        $package->destinations()->sync($sync);
    }

    private function syncPlans(Package $package, Request $request): void
    {
        $package->plans()->delete();

        foreach ($request->input('plan_name', []) as $i => $name) {
            if (blank($name)) continue;

            $features = array_filter(array_map('trim', explode(',', $request->input('plan_features')[$i] ?? '')));

            PackagePlan::create([
                'package_id' => $package->id,
                'name' => $name,
                'price' => $request->input('plan_price')[$i] ?? 0,
                'features' => array_values($features),
                'is_recommended' => $request->input('plan_recommended') == $i,
            ]);
        }
    }

    private function syncItineraries(Package $package, Request $request): void
    {
        $existingPhotos = $request->input('day_photo_existing', []);
        $uploadedPhotos = $request->file('day_photo', []);

        $package->itineraries()->delete();

        foreach ($request->input('day_label_en', []) as $i => $label) {
            if (blank($label)) continue;

            $photo = $existingPhotos[$i] ?? null;
            if (isset($uploadedPhotos[$i]) && $uploadedPhotos[$i]->isValid()) {
                $photo = $uploadedPhotos[$i]->store('itineraries', 'public');
            }

            PackageItinerary::create([
                'package_id' => $package->id,
                'day_label_en' => $label,
                'day_label_id' => $request->input('day_label_id')[$i] ?? null,
                'description_en' => $request->input('day_description_en')[$i] ?? null,
                'description_id' => $request->input('day_description_id')[$i] ?? null,
                'photo' => $photo,
                'order' => $i,
            ]);
        }
    }
}
