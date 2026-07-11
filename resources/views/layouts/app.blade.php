<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('titulo') | Aniafi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="bg-gray-200 h-dvh overflow-hidden">
    <header class="w-full h-20 bg-white shadow px-20 flex justify-between items-center">
        <div id="logo">
            <img src="{{ asset('src/img/logo.png') }}" alt="Logo de Aniafi" class="h-16 object-cover">
        </div>
        <div id="control" class="flex items-center gap-6">
            <button class="text-gray-500 hover:text-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                    <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                </svg>
            </button>

            <div class="relative" x-data="{ open: false }" @click.outside="open = false"
                @keydown.escape.window="open = false">
                <button type="button" @click="open = !open" :aria-expanded="open"
                    class="flex h-12 items-center gap-2.5 rounded-lg px-2 text-left transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-cyan-500">
                    <img class="size-9 rounded-full object-cover ring-1 ring-slate-200"
                        src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80"
                        alt="Foto de {{ Auth::user()->name }}">
                    <span class="hidden max-w-36 truncate text-sm font-semibold text-slate-800 sm:block">
                        {{ Auth::user()->name }}
                    </span>
                    <svg class="size-4 shrink-0 text-slate-500 transition-transform duration-200"
                        :class="open && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <div x-cloak x-show="open" x-transition.origin.top.right
                    class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-lg border border-slate-200 bg-white p-1.5 shadow-lg shadow-slate-200/70">
                    <div class="border-b border-slate-100 px-3 py-2.5">
                        <p class="truncate text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="mt-0.5 truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="mt-1 flex h-10 items-center gap-3 rounded-md px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">
                        <svg width="18" height="18" class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M5 21a7 7 0 0 1 14 0" />
                        </svg>
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex h-10 w-full items-center gap-3 rounded-md px-3 text-sm font-medium text-red-600 transition hover:bg-red-50">
                            <svg width="18" height="18" class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M10 17l5-5-5-5" />
                                <path d="M15 12H3" />
                                <path d="M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5" />
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <div class="flex h-[calc(100dvh-5rem)] min-w-0 overflow-hidden">

        @yield('aside')

        <main class="min-w-0 flex-1 overflow-y-auto bg-slate-50/70 p-5 lg:p-7 xl:p-8">

            @yield('content')

        </main>
    </div>
</body>

</html>
