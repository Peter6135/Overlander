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
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-800" style="font-family: 'Inter', sans-serif;">

    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-12">
        <a href="{{ route('home') }}" class="group flex items-baseline gap-1 mb-8 transition-transform duration-300 hover:scale-105">
            <span class="text-2xl font-black tracking-tight text-brand-500 transition-colors duration-300 group-hover:text-neutral-900">THE</span>
            <span class="text-2xl font-black tracking-tight text-neutral-900 transition-colors duration-300 group-hover:text-brand-500">OVRLNDR</span>
        </a>

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
