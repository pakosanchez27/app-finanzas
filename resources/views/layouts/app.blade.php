<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | ANIAFI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gauge-needle {
            transform: rotate(32deg);
            transform-origin: bottom center;
        }
    </style>
</head>

@php
    $showSpaceAside = request()->routeIs('dashboard.espacios-financieros.show') && isset($espacioFinanciero);
@endphp

<body class="min-h-screen bg-slate-50 font-sans text-[#09122f] antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_42%_20%,rgba(101,60,244,.08),transparent_32%),linear-gradient(180deg,#fff_0%,#f7f9fd_100%)] {{ $showSpaceAside ? 'lg:grid lg:grid-cols-[236px_minmax(0,1fr)] lg:grid-rows-[68px_minmax(0,1fr)] xl:grid-cols-[236px_minmax(0,1fr)]' : '' }}">
        <header class="{{ $showSpaceAside ? 'lg:col-span-2' : '' }} z-10 grid items-center gap-4 border-b border-slate-200/80 bg-white/90 px-4 py-3 backdrop-blur lg:grid-cols-[236px_minmax(260px,440px)_1fr] lg:px-6 lg:py-0">
            <a class="flex min-w-0 items-center" href="{{ route('dashboard') }}" aria-label="ANIAFI">
                <img class="block h-auto w-36 sm:w-44" src="{{ asset('src/img/logo.png') }}" alt="ANIAFI">
            </a>

            <label class="order-3 flex h-10 items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 shadow-[0_8px_22px_rgba(18,28,58,.04)] lg:order-none" aria-label="Buscar">
                <svg class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input class="w-full border-0 bg-transparent p-0 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0" type="search" placeholder="Buscar...">
                <span class="rounded-md bg-slate-50 px-2 py-1 text-xs text-slate-400">⌘ K</span>
            </label>

            <div class="flex items-center justify-end gap-3 sm:gap-5">
                <button class="grid h-7 w-7 place-items-center text-slate-500" type="button" aria-label="Ayuda">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.1 9a3 3 0 1 1 5.8 1c-.8 1.4-2.9 1.6-2.9 3.4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </button>
                <button class="relative grid h-7 w-7 place-items-center text-slate-500" type="button" aria-label="Notificaciones">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10 21h4"></path>
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 8-3 8h18s-3-1-3-8"></path>
                    </svg>
                    <span class="absolute right-0.5 top-0.5 h-2 w-2 rounded-full border-2 border-white bg-[#653cf4]"></span>
                </button>
                <div class="flex items-center gap-2 whitespace-nowrap text-xs font-bold text-slate-700">
                    <div class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-emerald-600 to-blue-600 font-extrabold text-white">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <strong class="hidden sm:block">{{ Auth::user()->name ?? 'Carlos Hernandez' }}</strong>
                    <svg class="hidden h-3.5 w-3.5 text-slate-700 sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </div>
            </div>
        </header>

        @if ($showSpaceAside)
            <aside class="hidden border-r border-slate-200/80 bg-white px-3 py-5 lg:flex lg:flex-col lg:gap-5 xl:px-4">
                <section class="rounded-lg border border-slate-200 bg-white p-3 shadow-[0_10px_28px_rgba(23,34,69,.05)]">
                    <h2 class="mb-3 text-xs font-extrabold text-slate-700">Espacios financieros</h2>
                    <a class="flex min-h-16 items-center gap-3 rounded-lg bg-emerald-50 px-3 py-2 text-[#09122f]" href="{{ route('dashboard.espacios-financieros.show', $espacioFinanciero) }}">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-lg bg-emerald-100 text-emerald-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 21v-2a4 4 0 0 0-8 0v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <strong class="block truncate text-sm font-extrabold">{{ $espacioFinanciero->nombre }}</strong>
                            <small class="text-[11px] font-extrabold text-emerald-600">Espacio activo</small>
                        </span>
                        <svg class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </a>
                </section>

                <nav class="rounded-lg border border-slate-200 bg-white p-3 shadow-[0_10px_28px_rgba(23,34,69,.05)]" aria-label="Secciones del espacio">
                    <h2 class="mb-3 text-xs font-extrabold text-slate-700">Secciones del espacio</h2>
                    <div class="grid gap-1">
                        <a class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="#">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 11 9-8 9 8"></path><path d="M5 10v10h14V10"></path><path d="M9 20v-6h6v6"></path></svg>
                            Resumen
                        </a>
                        <a class="flex h-11 items-center gap-3 rounded-lg bg-[#efeaff] px-3 text-sm font-extrabold text-[#653cf4]" href="#">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h14l-4-4"></path><path d="M17 17H3l4 4"></path></svg>
                            Movimientos
                        </a>
                        <a class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="#">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-9-9v9z"></path><path d="M12 3a9 9 0 0 1 9 9h-9z"></path></svg>
                            Presupuestos
                        </a>
                        <a class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="#">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="4"></circle><circle cx="12" cy="12" r="1"></circle></svg>
                            Metas
                        </a>
                        <a class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="#">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="14" x="2" y="5" rx="2"></rect><path d="M2 10h20"></path></svg>
                            Deudas
                        </a>
                        <a class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="#">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.6 13.5 12 22l-9-9V4h9l8.6 8.5a1 1 0 0 1 0 1z"></path><path d="M7.5 7.5h.01"></path></svg>
                            Categorias
                        </a>
                    </div>
                </nav>

                <nav class="rounded-lg border border-slate-200 bg-white p-3 shadow-[0_10px_28px_rgba(23,34,69,.05)]" aria-label="Navegacion general">
                    <a class="mb-1 flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="{{ route('dashboard') }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 11 9-8 9 8"></path><path d="M5 10v10h14V10"></path><path d="M9 20v-6h6v6"></path></svg>
                        Dashboard general
                    </a>
                    <a class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="{{ route('dashboard') }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="7" height="7" x="3" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="3" rx="1"></rect><rect width="7" height="7" x="14" y="14" rx="1"></rect><rect width="7" height="7" x="3" y="14" rx="1"></rect></svg>
                        Espacios financieros
                    </a>
                </nav>

                <section class="rounded-lg border border-violet-100 bg-[#f5f1ff] p-4 shadow-[0_10px_28px_rgba(101,60,244,.08)]">
                    <span class="mb-4 grid h-10 w-10 place-items-center rounded-full bg-white text-[#653cf4]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18h6"></path>
                            <path d="M10 22h4"></path>
                            <path d="M12 2a7 7 0 0 0-4 12.75V17h8v-2.25A7 7 0 0 0 12 2Z"></path>
                        </svg>
                    </span>
                    <h2 class="mb-3 text-sm font-extrabold">Consejo del dia</h2>
                    <p class="text-xs font-semibold leading-relaxed text-slate-600">Revisa tus gastos variables esta quincena y asegurate de mantener tu presupuesto bajo control.</p>
                    <a class="mt-5 flex items-center gap-2 text-xs font-extrabold text-[#653cf4]" href="#">Ver mas consejos <span>&rarr;</span></a>
                </section>

                <a class="mt-auto flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-extrabold text-slate-600 hover:bg-slate-50" href="#">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5Z"></path>
                        <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1A2 2 0 1 1 4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.3 7A2 2 0 1 1 7.1 4.2l.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1A2 2 0 1 1 19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1a2 2 0 1 1 0 4H21a1.7 1.7 0 0 0-1.6 1Z"></path>
                    </svg>
                    Configuracion
                </a>
            </aside>
        @endif

        <aside class="hidden">
            <nav class="grid gap-2" aria-label="Navegacion principal">
                @php
                    $navItems = [
                        ['label' => 'Mis espacios', 'active' => true, 'icon' => 'home'],
                        ['label' => 'Movimientos', 'active' => false, 'icon' => 'arrows'],
                        ['label' => 'Presupuestos', 'active' => false, 'icon' => 'pie'],
                        ['label' => 'Metas', 'active' => false, 'icon' => 'target'],
                        ['label' => 'Reportes', 'active' => false, 'icon' => 'bars'],
                        ['label' => 'Categorias', 'active' => false, 'icon' => 'tag'],
                        ['label' => 'Configuracion', 'active' => false, 'icon' => 'gear'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a class="{{ $item['active'] ? 'bg-[#efeaff] text-[#653cf4]' : 'text-slate-600 hover:bg-slate-50' }} flex h-12 items-center justify-center gap-3 rounded-lg px-3 text-sm font-semibold xl:justify-start" href="#">
                        @switch($item['icon'])
                            @case('home')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 11 9-8 9 8"></path><path d="M5 10v10h14V10"></path><path d="M9 20v-6h6v6"></path></svg>
                                @break
                            @case('arrows')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h14l-4-4"></path><path d="M17 17H3l4 4"></path></svg>
                                @break
                            @case('pie')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-9-9v9z"></path><path d="M12 3a9 9 0 0 1 9 9h-9z"></path></svg>
                                @break
                            @case('target')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="4"></circle><circle cx="12" cy="12" r="1"></circle></svg>
                                @break
                            @case('bars')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20V10"></path><path d="M10 20V4"></path><path d="M16 20v-7"></path><path d="M22 20V8"></path></svg>
                                @break
                            @case('tag')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.6 13.5 12 22l-9-9V4h9l8.6 8.5a1 1 0 0 1 0 1z"></path><path d="M7.5 7.5h.01"></path></svg>
                                @break
                            @case('gear')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5Z"></path><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1A2 2 0 1 1 4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.3 7A2 2 0 1 1 7.1 4.2l.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1A2 2 0 1 1 19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1a2 2 0 1 1 0 4H21a1.7 1.7 0 0 0-1.6 1Z"></path></svg>
                                @break
                        @endswitch
                        <span class="hidden xl:inline">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <a class="flex min-h-16 items-center justify-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-[#09122f] shadow-[0_10px_28px_rgba(23,34,69,.04)] xl:justify-start" href="#">
                <svg class="h-8 w-8 shrink-0 text-[#653cf4]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                    <path d="M12 8v8"></path>
                    <path d="M8 12h8"></path>
                </svg>
                <div class="hidden xl:block">
                    <strong class="mb-1 block text-[11px]">¿Necesitas ayuda?</strong>
                    <span class="text-[10px] font-bold text-[#653cf4]">Visita nuestro centro de ayuda</span>
                </div>
                <svg class="hidden h-4 w-4 text-slate-800 xl:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </a>
        </aside>

        <main class="overflow-y-auto px-4 py-6 sm:px-6 lg:px-7 lg:py-8">

        @yield('content')

        </main>
    </div>

    @if (session('toast_success'))
        <script>
            window.addEventListener('load', () => {
                window.showSuccessToast(@js(session('toast_success')));
            });
        </script>
    @endif
</body>

</html>
