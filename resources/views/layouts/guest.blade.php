<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-900 antialiased">
    <div
        class="min-h-screen flex flex-col justify-center items-center px-4 py-8 bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.18),_transparent_32%),linear-gradient(135deg,_#f8fafc_0%,_#eff6ff_52%,_#ecfdf5_100%)]">
        <div class="mb-5">
            <a href="/"
                class="inline-flex rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-4">
                <x-application-logo class="h-20 sm:h-24 w-auto drop-shadow-sm" />
            </a>
        </div>

        <div
            class="w-full sm:max-w-md overflow-hidden rounded-3xl border border-white/80 bg-white/90 px-6 py-7 shadow-2xl shadow-blue-950/10 backdrop-blur sm:px-8 sm:py-8">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
