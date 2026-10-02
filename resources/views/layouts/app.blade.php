<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Overlander')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-white font-sans text-gray-800 overflow-x-hidden" style="font-family: 'Inter', sans-serif;">

    {{-- Navbar --}}
    <nav class="bg-neutral-900 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">

                <a href="{{ route('home') }}" class="group flex items-baseline gap-1 shrink-0 transition-transform duration-300 hover:scale-105">
                    <span class="text-xl font-black tracking-tight text-brand-500 transition-colors duration-300 group-hover:text-white">THE</span>
                    <span class="text-xl font-black tracking-tight text-white transition-colors duration-300 group-hover:text-brand-500">OVRLNDR</span>
                </a>

                @include('layouts._nav-desktop')

                <button id="nav-toggle" class="sm:hidden p-2 rounded-lg text-neutral-300 hover:bg-neutral-800 transition-colors">
                    <svg id="icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @include('layouts._nav-mobile')
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success') || session('info') || session('error'))
    <div class="max-w-6xl mx-auto px-4 mt-4 space-y-2">
        @if(session('success'))
            <div class="flash-msg bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 ml-4 font-bold">×</button>
            </div>
        @endif
        @if(session('info'))
            <div class="flash-msg bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 text-sm flex items-center justify-between">
                <span>ℹ {{ session('info') }}</span>
                <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700 ml-4 font-bold">×</button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-msg bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm flex items-center justify-between">
                <span>✕ {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 ml-4 font-bold">×</button>
            </div>
        @endif
    </div>
    @endif

    {{-- Main content --}}
    <main class="@yield('main-class', 'max-w-6xl mx-auto px-4 py-8')">
        @yield('content')
    </main>

    <footer class="mt-16 bg-neutral-900 text-neutral-300">
        <div class="max-w-6xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
                <div>
                    <p class="text-xl font-black mb-2"><span class="text-brand-500">THE</span> <span class="text-white">OVRLNDR</span></p>
                    <p class="text-sm text-neutral-400 leading-relaxed">{{ __('nav.footer_tagline') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-3">{{ __('nav.footer_explore') }}</p>
                    <div class="space-y-2">
                        <a href="{{ route('destinations.index') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.footer_destinations') }}</a>
                        <a href="{{ route('packages.index') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.footer_tour_packages') }}</a>
                        <a href="{{ route('articles.index') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.footer_blog') }}</a>
                        <a href="{{ route('about') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.about') }}</a>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-3">{{ __('nav.footer_get_in_touch') }}</p>
                    <div class="space-y-2 text-sm text-neutral-400">
                        <p><a href="https://wa.me/{{ config('booking.whatsapp_number') }}" target="_blank" rel="noopener" class="hover:text-brand-400 transition-colors">WhatsApp: +62 812-0000-0000</a></p>
                        <p>Email: hello@overlander.id</p>
                        <p>Instagram: @theovrlndr</p>
                        <p>TikTok: @theovrlndr</p>
                        <p>Facebook: The Overlander Indonesia</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-neutral-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-neutral-500">
                <p>&copy; {{ __('nav.footer_copyright', ['year' => date('Y')]) }}</p>
            </div>
        </div>
    </footer>

    <script>
    const userMenuBtn      = document.getElementById('user-menu-btn');
    const userMenuDropdown = document.getElementById('user-menu-dropdown');

    userMenuBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        userMenuDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function() {
        userMenuDropdown?.classList.add('hidden');
    });

    const navToggle = document.getElementById('nav-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen   = document.getElementById('icon-open');
    const iconClose  = document.getElementById('icon-close');

    navToggle?.addEventListener('click', function() {
        const isHidden = mobileMenu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden', !isHidden);
        iconClose.classList.toggle('hidden', isHidden);
    });

    document.addEventListener('submit', function(e) {
        const btn = e.target.querySelector('button[type="submit"]:not([data-no-loading])');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalText = btn.innerText;
            btn.innerText = btn.dataset.loadingText || 'Saving...';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        }
    });

    document.querySelectorAll('.flash-msg').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
    </script>

    @stack('scripts')
</body>
</html>
