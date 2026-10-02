@extends('layouts.app')
@section('title', __('auth.forgot_title'))

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('auth.forgot_title') }}</h1>
        <p class="text-sm text-gray-500 mb-6">{{ __('auth.forgot_subtitle') }}</p>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 @error('email') border-red-400 @enderror"
                       placeholder="email@example.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="btn-pop w-full bg-brand-500 text-white py-2.5 rounded-lg font-semibold hover:bg-brand-600 transition-colors text-sm">
                {{ __('auth.send_reset_link') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-brand-500 font-medium hover:underline">{{ __('auth.back_to_login') }}</a>
        </p>
    </div>
</div>
@endsection
