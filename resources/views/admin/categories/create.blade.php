@extends('layouts.app')

@section('title', 'Admin — Add Category')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.categories.index') }}"
           class="text-sm text-gray-500 hover:text-brand-500 transition-colors">← Back to Category List</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 max-w-lg">
        <h2 class="font-semibold text-gray-800 mb-4">Add New Category</h2>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors"
                       placeholder="e.g. Beach" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors"
                        required>
                    <option value="">— Select type —</option>
                    <option value="destination" @selected(old('type') === 'destination')>Destination (destination tag)</option>
                    <option value="activity" @selected(old('type') === 'activity')>Activity (activity tag)</option>
                    <option value="package" @selected(old('type') === 'package')>Package (package group)</option>
                    <option value="article" @selected(old('type') === 'article')>Article (article topic)</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon') }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors"
                       placeholder="e.g. 🔧">
                <p class="text-xs text-gray-400 mt-1">Use a single-character emoji</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600 transition-colors">
                    Save
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
