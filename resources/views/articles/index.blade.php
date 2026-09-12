@extends('layouts.app')

@section('title', 'Blog — Overlander')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('articles.title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('articles.subtitle') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <a href="{{ route('articles.show', $article) }}" class="block rounded-2xl overflow-hidden border border-gray-100 provider-card">
            <img src="{{ $article->cover_photo }}" class="w-full h-44 object-cover" alt="{{ $article->title }}">
            <div class="p-4">
                @if($article->category)
                    <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-600">{{ $article->category->name }}</span>
                @endif
                <p class="font-semibold text-gray-800 mt-2 line-clamp-2">{{ $article->title }}</p>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $article->excerpt }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $article->published_at?->format('d M Y') }}</p>
            </div>
        </a>
        @empty
        <p class="col-span-3 text-center text-gray-400 py-12">{{ __('articles.empty') }}</p>
        @endforelse
    </div>

    {{ $articles->links() }}
</div>
@endsection
