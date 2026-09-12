@extends('layouts.app')

@section('title', 'Admin — Add Destination')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <a href="{{ route('admin.destinations.index') }}" class="text-sm text-gray-500 hover:text-brand-500">← Back to Destination List</a>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 max-w-3xl">
        <h2 class="font-semibold text-gray-800 mb-4">Add New Destination</h2>
        <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data">
            @include('admin.destinations._form', ['destination' => null])
        </form>
    </div>
</div>
@endsection
