@extends('layouts.app')

@section('title', 'Admin — Subscribers')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <form method="GET" action="{{ route('admin.subscribers.index') }}"
          class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search email</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600 transition-colors">Search</button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.subscribers.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">Reset</a>
            @endif
            <a href="{{ route('admin.subscribers.export') }}" class="px-4 py-2 border border-brand-200 text-brand-600 text-sm font-medium rounded-xl hover:bg-brand-50 transition-colors">Export CSV</a>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Newsletter subscribers
                <span class="text-gray-400 font-normal text-sm">({{ $activeCount }} active, {{ $subscribers->total() }} listed)</span>
            </h2>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($subscribers as $subscriber)
            <div class="px-6 py-3 flex items-center gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $subscriber->email }}</p>
                    <p class="text-xs text-gray-500">
                        {{ strtoupper($subscriber->locale) }} · joined {{ $subscriber->created_at->format('d M Y') }}
                        @unless($subscriber->isActive())
                            · <span class="text-red-500">unsubscribed {{ $subscriber->unsubscribed_at->format('d M Y') }}</span>
                        @endunless
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber) }}"
                      onsubmit="return confirm('Delete {{ addslashes($subscriber->email) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" data-no-loading class="text-xs px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors">Delete</button>
                </form>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-400">No subscribers yet.</div>
            @endforelse
        </div>

        @if($subscribers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $subscribers->links() }}</div>
        @endif
    </div>

</div>
@endsection
