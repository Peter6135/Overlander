@csrf
@if($event ?? null) @method('PUT') @endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-xs text-gray-400">(English)</span> <span class="text-red-500">*</span></label>
        <input type="text" name="title_en" value="{{ old('title_en', $event->title_en ?? '') }}" required
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        @error('title_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-xs text-gray-400">(Indonesia)</span></label>
        <input type="text" name="title_id" value="{{ old('title_id', $event->title_id ?? '') }}"
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
        <input type="date" name="event_date" value="{{ old('event_date', optional($event->event_date ?? null)->toDateString()) }}"
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Photo</label>
        <input type="file" name="cover_photo" accept="image/*" class="w-full text-sm">
        @if(($event->cover_photo ?? null))
            <img src="{{ str_starts_with($event->cover_photo, 'http') ? $event->cover_photo : asset('storage/' . $event->cover_photo) }}" class="mt-2 h-16 rounded-lg object-cover">
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-xs text-gray-400">(English)</span></label>
        <textarea name="description_en" rows="4" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('description_en', $event->description_en ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-xs text-gray-400">(Indonesia)</span></label>
        <textarea name="description_id" rows="4" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('description_id', $event->description_id ?? '') }}</textarea>
    </div>

    <div class="sm:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $event->is_active ?? true))>
        <label for="is_active" class="text-sm text-gray-700">Active (show on homepage carousel)</label>
    </div>
</div>

<div class="flex gap-3 pt-6">
    <button type="submit" class="px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">Save</button>
    <a href="{{ route('admin.events.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50">Cancel</a>
</div>
