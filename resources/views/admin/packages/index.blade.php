@extends('layouts.app')

@section('title', 'Admin — Tour Packages')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <form method="GET" class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search package name</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
        </div>
        <button type="submit" class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600">Search</button>
        <a href="{{ route('admin.packages.create') }}" class="ml-auto px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600">+ Add Package</a>
    </form>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium">Package Name</th>
                        <th class="text-left px-6 py-3 font-medium">Category</th>
                        <th class="text-center px-6 py-3 font-medium">Featured</th>
                        <th class="text-center px-6 py-3 font-medium">Active</th>
                        <th class="text-center px-6 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($packages as $package)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $package->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $package->category?->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-center">{{ $package->is_featured ? '⭐' : '—' }}</td>
                        <td class="px-6 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $package->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $package->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('packages.show', $package) }}" target="_blank" class="text-xs px-3 py-1 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">View</a>
                                <a href="{{ route('admin.packages.edit', $package) }}" class="text-xs px-3 py-1 rounded-lg border border-brand-200 text-brand-500 hover:bg-brand-50">Edit</a>
                                <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" onsubmit="return confirm('Delete package {{ addslashes($package->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-no-loading class="text-xs px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No packages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($packages->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $packages->links() }}</div>
        @endif
    </div>
</div>
@endsection
