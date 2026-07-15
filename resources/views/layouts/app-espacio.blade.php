@extends('layouts.app')

@section('aside')
    <aside class="h-full w-[250px] shrink-0 overflow-y-auto border border-gray-200 bg-white px-5 py-8">
        <nav class="flex h-full flex-col" aria-label="Navegación principal">
            <ul class="space-y-1 text-sm font-medium text-slate-600">
                <li>
                    <div class="relative text-violet-700">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center" aria-hidden="true">
                            <span
                                class="grid size-7 place-items-center rounded-full bg-violet-600 text-white shadow-sm shadow-violet-200">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 11 9-8 9 8" />
                                    <path d="M5 10v10h5v-6h4v6h5V10" />
                                </svg>
                            </span>
                        </span>

                        <select id="financialSpace" name="espacio_financiero"
                            onchange="if (this.value) window.location.href = this.value"
                            aria-label="Seleccionar espacio financiero"
                            class="h-12 w-full cursor-pointer appearance-none rounded-xl border-0 bg-violet-50 py-0 pl-12 pr-10 text-sm font-semibold text-slate-700 outline-none ring-1 ring-inset ring-violet-100 transition hover:bg-violet-100 focus:ring-2 focus:ring-violet-400">
                            @forelse ($espacios as $espacio)
                                <option value="{{ route('espacio.show', $espacio) }}" @selected($espacio->is($espacioActual))>
                                    {{ $espacio->nombre }}
                                </option>
                            @empty
                                <option value="">Sin espacios financieros</option>
                            @endforelse
                        </select>

                        <svg class="pointer-events-none absolute right-3 top-1/2 size-4 -translate-y-1/2 text-slate-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                </li>
                <li>
                    <a href="{{ route('movimiento.index', $espacioActual) }}" @class([
                        'active' => request()->routeIs('movimiento.*'),
                        'flex h-12 items-center gap-4 rounded-xl px-4 transition-colors hover:bg-slate-50 hover:text-slate-900',
                    ])
                        @if (request()->routeIs('movimiento.*')) aria-current="page" @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-pie-3">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12l-6.5 5.5" />
                            <path d="M12 3v9h9" />
                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        </svg>
                        <span>Resumen</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('movimiento.index', $espacioActual) }}" @class([
                        'active' => request()->routeIs('movimientos.*'),
                        'flex h-12 items-center gap-4 rounded-xl px-4 transition-colors hover:bg-slate-50 hover:text-slate-900',
                    ])
                        @if (request()->routeIs('movimientos.*')) aria-current="page" @endif>
                        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 7h14l-4-4" />
                            <path d="m21 7-4 4" />
                            <path d="M17 17H3l4 4" />
                            <path d="m3 17 4-4" />
                        </svg>
                        <span>Movimientos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('presupuesto.index', $espacioActual) }}" @class([
                        'active' => request()->routeIs('presupuesto.*'),
                        'flex h-12 items-center gap-4 rounded-xl px-4 transition-colors hover:bg-slate-50 hover:text-slate-900',
                    ])
                        @if (request()->routeIs('presupuesto.*')) aria-current="page" @endif>
                        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 12a9 9 0 1 1-9-9v9z" />
                            <path d="M12 3a9 9 0 0 1 9 9h-9z" />
                        </svg>
                        <span>Presupuestos</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex h-12 items-center gap-4 rounded-xl px-4 transition-colors hover:bg-slate-50 hover:text-slate-900">
                        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" />
                            <circle cx="12" cy="12" r="5" />
                            <circle cx="12" cy="12" r="1" />
                        </svg>
                        <span>Metas</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex h-12 items-center gap-4 rounded-xl px-4 transition-colors hover:bg-slate-50 hover:text-slate-900">
                        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 20V10" />
                            <path d="M10 20V4" />
                            <path d="M15 20v-7" />
                            <path d="M20 20V7" />
                            <path d="M3 20h19" />
                        </svg>
                        <span>Reportes</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex h-12 items-center gap-4 rounded-xl px-4 transition-colors hover:bg-slate-50 hover:text-slate-900">
                        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 13 13 20 4 11V4h7l9 9z" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                        </svg>
                        <span>Categorías</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex h-12 items-center gap-4 rounded-xl px-4 transition-colors hover:bg-slate-50 hover:text-slate-900">
                        <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1A2 2 0 1 1 4.2 17l.1-.1A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.6-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.3 7A2 2 0 1 1 7.1 4.2l.1.1A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.6V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1A2 2 0 1 1 19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1a2 2 0 1 1 0 4H21a1.7 1.7 0 0 0-1.6 1z" />
                        </svg>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>

            <a href="#"
                class="mt-auto flex min-h-16 items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 shadow-sm transition hover:border-violet-200 hover:bg-violet-50/50"
                aria-label="Visitar nuestro centro de ayuda">
                <span class="grid size-8 shrink-0 place-items-center rounded-full bg-violet-50 text-violet-600">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9.5 18H8a5 5 0 0 1-5-5V8a5 5 0 0 1 5-5h5a5 5 0 0 1 5 5v1.5" />
                        <path d="M8 21v-3" />
                        <path d="M8 7.8a2.2 2.2 0 0 1 4.2 1c0 1.5-2.2 1.7-2.2 3.2" />
                        <path d="M10 15h.01" />
                        <path d="m18 14 .7 1.8L21 16.5l-2.3.7L18 19l-.7-1.8-2.3-.7 2.3-.7L18 14Z" />
                    </svg>
                </span>
                <span class="min-w-0 flex-1 leading-tight">
                    <span class="block text-xs font-semibold text-slate-700">¿Necesitas ayuda?</span>
                    <span class="mt-1 block text-[10px] font-medium text-violet-600">Visita nuestro centro de
                        ayuda</span>
                </span>
                <svg class="size-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </nav>
    </aside>
@endsection
