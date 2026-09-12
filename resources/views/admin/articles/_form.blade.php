@csrf
@if($article ?? null) @method('PUT') @endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-xs text-gray-400">(English)</span> <span class="text-red-500">*</span></label>
        <input type="text" name="title_en" value="{{ old('title_en', $article->title_en ?? '') }}" required
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        @error('title_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-xs text-gray-400">(Indonesia)</span></label>
        <input type="text" name="title_id" value="{{ old('title_id', $article->title_id ?? '') }}"
               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        @error('title_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <select name="category_id" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            <option value="">— Select category —</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Photo</label>
        <input type="file" name="cover_photo" accept="image/*" class="w-full text-sm">
        @if(($article->cover_photo ?? null))
            <img src="{{ str_starts_with($article->cover_photo, 'http') ? $article->cover_photo : asset('storage/' . $article->cover_photo) }}" class="mt-2 h-16 rounded-lg object-cover">
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Summary (excerpt) <span class="text-xs text-gray-400">(English)</span></label>
        <textarea name="excerpt_en" rows="2" maxlength="300" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('excerpt_en', $article->excerpt_en ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan <span class="text-xs text-gray-400">(Indonesia)</span></label>
        <textarea name="excerpt_id" rows="2" maxlength="300" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('excerpt_id', $article->excerpt_id ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Article Content <span class="text-xs text-gray-400">(English)</span> <span class="text-red-500">*</span></label>
        <textarea name="content_en" rows="8" required class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('content_en', $article->content_en ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Artikel <span class="text-xs text-gray-400">(Indonesia)</span></label>
        <textarea name="content_id" rows="8" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('content_id', $article->content_id ?? '') }}</textarea>
    </div>

    <div class="sm:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="is_published" value="1" id="is_published" @checked(old('is_published', $article->is_published ?? false))>
        <label for="is_published" class="text-sm text-gray-700">Publish now</label>
    </div>
</div>

<div class="flex gap-3 pt-6">
    <button type="submit" class="px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">Save</button>
    <a href="{{ route('admin.articles.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50">Cancel</a>
</div>
