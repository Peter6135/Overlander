@extends('layouts.app')

@section('title', __('terms.title') . ' — Overlander')
@section('meta_description', __('terms.intro'))

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div>
        <h1 class="text-3xl font-black text-gray-900">{{ __('terms.title') }}</h1>
        <p class="text-xs text-gray-400 mt-1">{{ __('terms.updated') }}</p>
        <p class="text-gray-600 mt-4 leading-relaxed">{{ __('terms.intro') }}</p>
    </div>

    @foreach(__('terms.sections') as $section)
    <section>
        <h2 class="text-lg font-bold text-gray-900 mb-2">{{ $section['h'] }}</h2>
        <ul class="space-y-2 text-sm text-gray-600 leading-relaxed">
            @foreach($section['p'] as $line)
            <li class="flex gap-2">
                <span class="text-brand-500 shrink-0">•</span>
                <span>{{ str_replace(':email', config('booking.email'), $line) }}</span>
            </li>
            @endforeach
        </ul>
    </section>
    @endforeach

</div>
@endsection
