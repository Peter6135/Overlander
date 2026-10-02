@php
    $saved = auth()->check() && auth()->user()->savedPackages->contains('id', $package->id);
    $label = auth()->check() ? ($saved ? __('wishlist.unsave') : __('wishlist.save')) : __('wishlist.login_to_save');
    $btnClass = 'w-9 h-9 rounded-full flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 active:scale-95 '
        . ($saved ? 'bg-brand-500 text-white' : 'bg-white/90 text-gray-500 hover:text-brand-500');
    $icon = '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="' . ($saved ? 'currentColor' : 'none') . '" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>';
@endphp
@auth
<form method="POST" action="{{ route('wishlist.toggle', $package) }}" class="{{ $position ?? '' }}">
    @csrf
    <button type="submit" data-no-loading aria-label="{{ $label }}" title="{{ $label }}" class="{{ $btnClass }}">{!! $icon !!}</button>
</form>
@else
<a href="{{ route('login') }}" aria-label="{{ $label }}" title="{{ $label }}" class="{{ $position ?? '' }} {{ $btnClass }}">{!! $icon !!}</a>
@endauth
