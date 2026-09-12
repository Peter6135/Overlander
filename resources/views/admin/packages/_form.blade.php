@csrf
@if($package ?? null) @method('PUT') @endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Package Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $package->name ?? '') }}" required
               placeholder="e.g. Jogja/Borobudur > Bromo > Tumpak Sewu > Ijen Crater"
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Package Category</label>
        <select name="category_id" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            <option value="">— Select category —</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $package->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Photo</label>
        <input type="file" name="cover_photo" accept="image/*" class="w-full text-sm">
        @if(($package->cover_photo ?? null))
            <img src="{{ str_starts_with($package->cover_photo, 'http') ? $package->cover_photo : asset('storage/' . $package->cover_photo) }}" class="mt-2 h-16 rounded-lg object-cover">
        @endif
    </div>

    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-xs text-gray-400">(English)</span></label>
            <textarea name="description_en" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('description_en', $package->description_en ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-xs text-gray-400">(Indonesia)</span></label>
            <textarea name="description_id" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('description_id', $package->description_id ?? '') }}</textarea>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity (travelers per day)</label>
        <input type="number" min="1" name="capacity" value="{{ old('capacity', $package->capacity ?? '') }}"
               placeholder="Leave blank for unlimited"
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', $package->is_featured ?? false))>
        <label for="is_featured" class="text-sm text-gray-700">Show on "Our Packages" homepage</label>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $package->is_active ?? true))>
        <label for="is_active" class="text-sm text-gray-700">Active</label>
    </div>
</div>

<div class="border-t border-gray-100 mt-6 pt-6">
    <h3 class="font-semibold text-gray-800 mb-1">Destinations in This Package</h3>
    <p class="text-xs text-gray-500 mb-3">Fill in the stop order (1, 2, 3, ...) for destinations included in this route. Leave blank for destinations not included. For a single-destination package, just fill in 1 for that destination.</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @php $packageDestOrders = ($package->destinations ?? collect())->pluck('pivot.order', 'id'); @endphp
        @foreach($destinations as $destination)
        <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2">
            <span class="flex-1 text-sm text-gray-700">{{ $destination->name }}</span>
            <input type="number" min="0" name="destination_order[{{ $destination->id }}]"
                   value="{{ old('destination_order.' . $destination->id, $packageDestOrders[$destination->id] ?? '') }}"
                   placeholder="—" class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center">
        </div>
        @endforeach
    </div>
</div>

<div class="border-t border-gray-100 mt-6 pt-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-gray-800">Pricing Options (Package Plans)</h3>
        <button type="button" onclick="addPlanRow()" class="text-xs px-3 py-1 rounded-lg border border-brand-200 text-brand-500 hover:bg-brand-50">+ Add Plan</button>
    </div>
    <div id="plan-rows" class="space-y-3">
        @foreach(($package->plans ?? []) as $i => $plan)
        <div class="grid grid-cols-1 sm:grid-cols-[2fr_1fr_3fr_auto_auto] gap-2 items-center">
            <input type="text" name="plan_name[]" value="{{ $plan->name }}" placeholder="e.g. All In Package" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <input type="number" step="0.01" name="plan_price[]" value="{{ $plan->price }}" placeholder="Price" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <input type="text" name="plan_features[]" value="{{ implode(', ', $plan->features ?? []) }}" placeholder="Features, comma separated" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <label class="flex items-center gap-1 text-xs text-gray-600"><input type="radio" name="plan_recommended" value="{{ $i }}" @checked($plan->is_recommended)> Recommended</label>
            <button type="button" onclick="this.closest('div').remove()" class="text-red-500 text-xs px-2 py-2">Delete</button>
        </div>
        @endforeach
    </div>
</div>

<div class="border-t border-gray-100 mt-6 pt-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-gray-800">Daily Itinerary (for multi-day / Overland packages)</h3>
        <button type="button" onclick="addDayRow()" class="text-xs px-3 py-1 rounded-lg border border-brand-200 text-brand-500 hover:bg-brand-50">+ Add Day</button>
    </div>
    <div id="day-rows" class="space-y-3">
        @foreach(($package->itineraries ?? []) as $day)
        <div class="grid grid-cols-1 sm:grid-cols-[1fr_1fr_3fr_3fr_auto] gap-2 items-start">
            <input type="text" name="day_label_en[]" value="{{ $day->day_label_en }}" placeholder="e.g. Day 1-2" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <input type="text" name="day_label_id[]" value="{{ $day->day_label_id }}" placeholder="cth. Hari 1-2" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
            <textarea name="day_description_en[]" rows="1" placeholder="Description (English)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">{{ $day->description_en }}</textarea>
            <textarea name="day_description_id[]" rows="1" placeholder="Deskripsi (Indonesia)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">{{ $day->description_id }}</textarea>
            <button type="button" onclick="this.closest('div').remove()" class="text-red-500 text-xs px-2 py-2">Delete</button>
        </div>
        @endforeach
    </div>
</div>

<script>
let planIndex = {{ count($package->plans ?? []) }};
function addPlanRow() {
    const wrap = document.getElementById('plan-rows');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 sm:grid-cols-[2fr_1fr_3fr_auto_auto] gap-2 items-center';
    row.innerHTML = `
        <input type="text" name="plan_name[]" placeholder="e.g. All In Package" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
        <input type="number" step="0.01" name="plan_price[]" placeholder="Price" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
        <input type="text" name="plan_features[]" placeholder="Features, comma separated" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
        <label class="flex items-center gap-1 text-xs text-gray-600"><input type="radio" name="plan_recommended" value="${planIndex}"> Recommended</label>
        <button type="button" onclick="this.closest('div').remove()" class="text-red-500 text-xs px-2 py-2">Delete</button>
    `;
    wrap.appendChild(row);
    planIndex++;
}
function addDayRow() {
    const wrap = document.getElementById('day-rows');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 sm:grid-cols-[1fr_1fr_3fr_3fr_auto] gap-2 items-start';
    row.innerHTML = `
        <input type="text" name="day_label_en[]" placeholder="e.g. Day 1-2" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
        <input type="text" name="day_label_id[]" placeholder="cth. Hari 1-2" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
        <textarea name="day_description_en[]" rows="1" placeholder="Description (English)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm"></textarea>
        <textarea name="day_description_id[]" rows="1" placeholder="Deskripsi (Indonesia)" class="border border-gray-300 rounded-xl px-3 py-2 text-sm"></textarea>
        <button type="button" onclick="this.closest('div').remove()" class="text-red-500 text-xs px-2 py-2">Delete</button>
    `;
    wrap.appendChild(row);
}
</script>

<div class="flex gap-3 pt-6">
    <button type="submit" class="px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">Save</button>
    <a href="{{ route('admin.packages.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50">Cancel</a>
</div>
