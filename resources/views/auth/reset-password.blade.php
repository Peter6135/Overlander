@extends('layouts.app')
@section('title', __('auth.reset_title'))

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('auth.reset_title') }}</h1>
        <p class="text-sm text-gray-500 mb-6">{{ __('auth.reset_subtitle') }}</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.new_password') }}</label>
                <input id="password" type="password" name="password" required minlength="8" autofocus
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 @error('password') border-red-400 @enderror"
                       placeholder="{{ __('auth.min_chars_placeholder') }}">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.confirm_password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                       placeholder="{{ __('auth.repeat_password_placeholder') }}">
            </div>

            <button type="submit"
                    class="btn-pop w-full bg-brand-500 text-white py-2.5 rounded-lg font-semibold hover:bg-brand-600 transition-colors text-sm">
                {{ __('auth.reset_submit') }}
            </button>
        </form>
    </div>
</div>
@endsection
