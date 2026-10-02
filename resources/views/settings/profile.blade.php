@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('profile.title') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('profile.subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Avatar --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-800 mb-4">{{ __('profile.photo_title') }}</h2>
            <div class="flex items-center gap-5">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt=""
                         class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 shrink-0">
                @else
                    <div class="w-16 h-16 rounded-full bg-blue-100 text-brand-500 flex items-center justify-center text-2xl font-bold shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('profile.change_photo') }}
                        <input type="file" name="avatar" accept="image/*" class="hidden"
                               onchange="document.getElementById('avatar-preview').src = URL.createObjectURL(this.files[0])">
                    </label>
                    <p class="text-xs text-gray-400 mt-1">{{ __('profile.photo_hint') }}</p>
                    @error('avatar') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Account Information --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">{{ __('profile.account_info_title') }}</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.full_name') }}</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                              @error('name') border-red-400 @enderror" required>
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.email') }}</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                              @error('email') border-red-400 @enderror" required>
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.whatsapp_number') }}</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                              @error('phone') border-red-400 @enderror"
                       placeholder="08xxxxxxxxxx">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.bio') }} <span class="text-gray-400 font-normal">{{ __('profile.optional') }}</span></label>
                <textarea name="bio" rows="3" maxlength="500"
                          class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                                 @error('bio') border-red-400 @enderror"
                          placeholder="{{ __('profile.bio_placeholder') }}">{{ old('bio', $user->bio) }}</textarea>
                @error('bio') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <div>
                <h2 class="font-semibold text-gray-800">{{ __('profile.change_password_title') }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('profile.change_password_hint') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.new_password') }}</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500
                              @error('password') border-red-400 @enderror"
                       placeholder="{{ __('profile.min_chars_placeholder') }}">
                @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.confirm_new_password') }}</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                       placeholder="{{ __('profile.repeat_new_password_placeholder') }}">
            </div>

            @if($user->google_id)
            <p class="text-xs text-gray-400">{{ __('profile.google_linked') }}</p>
            @endif
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="btn-pop px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600 transition-colors">
                {{ __('profile.save_changes') }}
            </button>
            <a href="{{ url()->previous() }}"
               class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                {{ __('profile.cancel') }}
            </a>
        </div>
    </form>

</div>
@endsection
