<div id="mobile-menu" class="hidden sm:hidden border-t border-neutral-800 py-3 space-y-1 text-sm">
    <a href="{{ route('home') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.home') }}</a>
    <a href="{{ route('destinations.index') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.destination') }}</a>
    <a href="{{ route('packages.index') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.package') }}</a>
    <a href="{{ route('articles.index') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.blog') }}</a>
    <a href="{{ route('about') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.about') }}</a>

    <form method="GET" action="{{ route('search') }}" class="px-2 py-2">
        <input type="text" name="q" placeholder="{{ __('nav.search_placeholder') }}"
               class="w-full bg-neutral-800 text-neutral-200 placeholder-neutral-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
    </form>

    <div class="flex items-center gap-2 px-2 py-2 text-xs">
        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'text-white font-semibold' : 'text-neutral-500' }}">EN</a>
        <span class="text-neutral-600">|</span>
        <a href="{{ route('locale.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'text-white font-semibold' : 'text-neutral-500' }}">ID</a>
    </div>

    @auth
        <a href="{{ route('cart.show') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">
            {{ __('nav.cart') }}
            @if(session('cart'))<span class="inline-block w-2 h-2 bg-brand-500 rounded-full ml-1"></span>@endif
        </a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.admin_panel') }}</a>
        @endif
        <a href="{{ route('bookings.index') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.my_bookings') }}</a>
        <a href="{{ route('settings.profile.edit') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.profile_settings') }}</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" data-no-loading class="w-full text-left px-2 py-2 rounded-lg text-red-400 hover:bg-neutral-800">{{ __('nav.log_out') }}</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="block px-2 py-2 rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white">{{ __('nav.login') }}</a>
        <a href="{{ route('register') }}" class="btn-pop block px-2 py-2 rounded-lg bg-brand-500 text-white text-center font-medium">{{ __('nav.sign_up') }}</a>
    @endauth
</div>
