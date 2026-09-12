@extends('layouts.app')

@section('title', 'Admin — Edit Article')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">Manage Overlander data — Travel Agency</p>
    </div>

    @include('admin._nav')

    <a href="{{ route('admin.articles.index') }}" class="text-sm text-gray-500 hover:text-brand-500">← Back to Article List</a>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 max-w-2xl">
        <h2 class="font-semibold text-gray-800 mb-4">Edit Article: {{ $article->title }}</h2>
        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
            @include('admin.articles._form')
        </form>
    </div>
</div>
@endsection
