<div class="hidden sm:flex items-center gap-6 text-sm">
    <a href="{{ route('home') }}" class="text-neutral-300 hover:text-white transition-colors">{{ __('nav.home') }}</a>
    <a href="{{ route('destinations.index') }}" class="text-neutral-300 hover:text-white transition-colors">{{ __('nav.destination') }}</a>
    <a href="{{ route('packages.index') }}" class="text-neutral-300 hover:text-white transition-colors">{{ __('nav.package') }}</a>
    <a href="{{ route('articles.index') }}" class="text-neutral-300 hover:text-white transition-colors">{{ __('nav.blog') }}</a>

    <form method="GET" action="{{ route('search') }}">
        <input type="text" name="q" placeholder="{{ __('nav.search_placeholder') }}"
               class="w-32 lg:w-40 bg-neutral-800 text-neutral-200 placeholder-neutral-500 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-brand-400">
    </form>

    <div class="flex items-center gap-1 text-xs">
        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'text-white font-semibold' : 'text-neutral-500 hover:text-neutral-300' }}">EN</a>
        <span class="text-neutral-600">|</span>
        <a href="{{ route('locale.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'text-white font-semibold' : 'text-neutral-500 hover:text-neutral-300' }}">ID</a>
    </div>

    @auth
        <a href="{{ route('cart.show') }}" class="relative text-neutral-300 hover:text-white transition-colors" title="{{ __('nav.cart') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            @if(session('cart'))
                <span class="absolute -top-1 -right-1 w-2 h-2 bg-brand-500 rounded-full"></span>
            @endif
        </a>
        <div class="relative" id="user-menu-wrapper">
            <button id="user-menu-btn" class="flex items-center gap-1.5 text-white font-medium">
                {{ auth()->user()->name }}
                <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600">{{ __('nav.admin_panel') }}</a>
                @endif
                <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600">{{ __('nav.my_bookings') }}</a>
                <a href="{{ route('settings.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600">{{ __('nav.profile_settings') }}</a>
                <div class="border-t border-gray-100 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" data-no-loading class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">{{ __('nav.log_out') }}</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="text-neutral-300 hover:text-white transition-colors">{{ __('nav.login') }}</a>
        <a href="{{ route('register') }}" class="bg-brand-500 text-white px-4 py-1.5 rounded-lg hover:bg-brand-600 transition-colors text-sm font-medium">{{ __('nav.sign_up') }}</a>
    @endauth
</div>
