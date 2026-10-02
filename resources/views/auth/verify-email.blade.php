@extends('layouts.minimal')
@section('title', 'Verify Email')

@section('content')
@php
    $email  = auth()->user()->email;
    $domain = strtolower(substr(strrchr($email, '@'), 1));
    $mailLinks = [
        'gmail.com'      => ['url' => 'https://mail.google.com', 'label' => __('auth.open') . ' Gmail'],
        'yahoo.com'      => ['url' => 'https://mail.yahoo.com',  'label' => __('auth.open') . ' Yahoo Mail'],
        'yahoo.co.id'    => ['url' => 'https://mail.yahoo.com',  'label' => __('auth.open') . ' Yahoo Mail'],
        'outlook.com'    => ['url' => 'https://outlook.live.com','label' => __('auth.open') . ' Outlook'],
        'hotmail.com'    => ['url' => 'https://outlook.live.com','label' => __('auth.open') . ' Outlook'],
        'live.com'       => ['url' => 'https://outlook.live.com','label' => __('auth.open') . ' Outlook'],
    ];
    $mailLink = $mailLinks[$domain] ?? null;
@endphp

<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1 class="text-xl font-bold text-gray-900 mb-2">{{ __('auth.verify_title') }}</h1>
        <p class="text-sm text-gray-500 mb-1">
            {{ __('auth.verify_sent') }}
        </p>
        <p class="text-sm font-semibold text-gray-800 mb-1">{{ $email }}</p>
        <p class="text-xs text-gray-400 mb-6">{{ __('auth.check_spam') }}</p>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($mailLink)
            <a href="{{ $mailLink['url'] }}" target="_blank"
               class="btn-pop flex items-center justify-center gap-2 w-full bg-brand-500 text-white py-2.5 rounded-xl font-semibold hover:bg-brand-600 transition-colors text-sm mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                {{ $mailLink['label'] }}
            </a>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full border border-gray-300 text-gray-600 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition-colors text-sm">
                {{ __('auth.resend') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600">
                {{ __('auth.logout') }}
            </button>
        </form>
    </div>
</div>
@endsection
