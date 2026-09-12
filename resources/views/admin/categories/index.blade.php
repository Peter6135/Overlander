@extends('layouts.app')

@section('title', 'Admin — Categories')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.categories.index') }}"
          class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search category name</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="e.g. Beach"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors">
        </div>
        <div class="min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
            <select name="type"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors">
                <option value="">All types</option>
                <option value="destination" @selected(request('type') === 'destination')>Destination</option>
                <option value="activity" @selected(request('type') === 'activity')>Activity</option>
                <option value="package" @selected(request('type') === 'package')>Package</option>
                <option value="article" @selected(request('type') === 'article')>Article</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600 transition-colors">
                Search
            </button>
            @if(request()->hasAny(['search', 'type']))
                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            @endif
        </div>
        <div class="ml-auto">
            <a href="{{ route('admin.categories.create') }}"
               class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600 transition-colors block">
                + Add Category
            </a>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Categories
                <span class="text-gray-400 font-normal text-sm">({{ $categories->total() }} total)</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium">Name</th>
                        <th class="text-left px-6 py-3 font-medium">Type</th>
                        <th class="text-center px-6 py-3 font-medium">Icon</th>
                        <th class="text-left px-6 py-3 font-medium">Slug</th>
                        <th class="text-center px-6 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ match($category->type) {
                                    'destination' => 'bg-green-100 text-green-700',
                                    'activity' => 'bg-brand-100 text-brand-600',
                                    'package' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-purple-100 text-purple-700',
                                } }}">
                                {{ ucfirst($category->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center text-lg">{{ $category->icon ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-xs px-3 py-1 rounded-lg border border-blue-200 text-brand-500 hover:bg-blue-50 transition-colors">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Delete category {{ addslashes($category->name) }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-no-loading
                                            class="text-xs px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
