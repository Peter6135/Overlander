@extends('layouts.app')

@section('title', 'Admin — Users')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <form method="GET" action="{{ route('admin.users.index') }}"
          class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search name or email</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-colors">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl hover:bg-brand-600 transition-colors">Search</button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">Reset</a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Users
                <span class="text-gray-400 font-normal text-sm">({{ $users->total() }} total)</span>
            </h2>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($users as $user)
            <div class="px-6 py-4 flex items-center gap-4">
                <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-medium text-gray-800 text-sm">{{ $user->name }}</span>
                        @if($user->isAdmin())
                            <span class="text-xs px-1.5 py-0.5 rounded bg-brand-100 text-brand-700">Admin</span>
                        @endif
                        @if($user->email_verified_at)
                            <span class="text-xs px-1.5 py-0.5 rounded bg-green-100 text-green-700">Verified</span>
                        @else
                            <span class="text-xs px-1.5 py-0.5 rounded bg-amber-100 text-amber-700">Unverified</span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-xs mt-0.5">
                        {{ $user->email }} · {{ $user->bookings_count }} booking(s) · joined {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>
                @if(! $user->isAdmin())
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Delete {{ addslashes($user->email) }}? Their bookings will be deleted too.')">
                    @csrf @method('DELETE')
                    <button type="submit" data-no-loading
                            class="text-xs px-3 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                        Delete
                    </button>
                </form>
                @endif
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-400">No users found.</div>
            @endforelse
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
