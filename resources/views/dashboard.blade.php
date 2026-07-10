@extends('layouts.app')


@section('title')
    Dashboard
@endsection



@section('content')

<section class="mb-6 grid gap-5 sm:flex sm:items-start sm:justify-between">
    <div>
        <h1 class="mb-2 text-3xl font-extrabold leading-tight tracking-normal text-[#09122f] lg:text-[34px]">Mis espacios
            financieros</h1>
        <p class="text-sm text-slate-500">Selecciona un espacio para ver tu presupuesto, metas y movimientos.</p>
    </div>
    <button
        class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-[#714bff] to-[#4e35e8] px-6 text-sm font-extrabold text-white shadow-[0_16px_30px_rgba(101,60,244,.22)] sm:w-auto"
        type="button"
        x-data
        x-on:click="$dispatch('open-modal', 'crear-espacio-financiero')">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 5v14"></path>
            <path d="M5 12h14"></path>
        </svg>
        Crear espacio financiero
    </button>
</section>

<x-modal name="crear-espacio-financiero" :show="$errors->any()" maxWidth="lg" focusable>
    <form method="POST" action="{{ route('dashboard.espacios-financieros.store') }}" class="p-6">
        @csrf

        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold leading-tight text-[#09122f]">Crear espacio financiero</h2>
                <p class="mt-1 text-sm text-slate-500">Agrega los datos base para organizar tus finanzas.</p>
            </div>
            <button
                class="grid h-9 w-9 shrink-0 place-items-center rounded-md border border-slate-200 text-slate-500 hover:bg-slate-50"
                type="button"
                x-on:click="$dispatch('close-modal', 'crear-espacio-financiero')"
                aria-label="Cerrar modal">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <div class="grid gap-4">
            <div>
                <x-input-label for="nombre" value="Nombre del espacio" />
                <input
                    id="nombre"
                    name="nombre"
                    type="text"
                    value="{{ old('nombre') }}"
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#653cf4] focus:ring-[#653cf4]"
                    required
                    autofocus>
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tipo" value="Tipo" />
                <select
                    id="tipo"
                    name="tipo"
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#653cf4] focus:ring-[#653cf4]"
                    required>
                    <option value="">Selecciona un tipo</option>
                    @foreach ($tiposEspacios as $tipoEspacio)
                        <option value="{{ $tipoEspacio->value }}" @selected(old('tipo') === $tipoEspacio->value)>
                            {{ $tipoEspacio->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="descripcion" value="Descripcion" />
                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="3"
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#653cf4] focus:ring-[#653cf4]"
                    required>{{ old('descripcion') }}</textarea>
                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="moneda" value="Moneda" />
                <select
                    id="moneda"
                    name="moneda"
                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#653cf4] focus:ring-[#653cf4]"
                    required>
                    @foreach (['mxn' => 'MXN', 'usd' => 'USD', 'euro' => 'Euro'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('moneda', 'mxn') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('moneda')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <button
                class="inline-flex h-10 items-center justify-center rounded-md border border-slate-200 bg-white px-4 text-sm font-extrabold text-slate-700 hover:bg-slate-50"
                type="button"
                x-on:click="$dispatch('close-modal', 'crear-espacio-financiero')">
                Cancelar
            </button>
            <button
                class="inline-flex h-10 items-center justify-center rounded-md bg-gradient-to-br from-[#714bff] to-[#4e35e8] px-5 text-sm font-extrabold text-white shadow-[0_12px_24px_rgba(101,60,244,.18)]"
                type="submit">
                Crear espacio
            </button>
        </div>
    </form>
</x-modal>

@php
    $spaces = [
        [
            'title' => 'Presupuesto personal',
            'badge' => 'Personal',
            'tone' => 'purple',
            'icon' => 'person',
            'stats' => [
                ['label' => 'Disponible', 'value' => '$5,400', 'tone' => 'green'],
                ['label' => 'Pagos pendientes', 'value' => '3', 'tone' => 'orange'],
            ],
        ],
        [
            'title' => 'Familia Sanchez',
            'badge' => 'Familia',
            'tone' => 'green',
            'icon' => 'family',
            'stats' => [
                ['label' => 'Disponible', 'value' => '$2,850', 'tone' => 'green'],
                ['label' => 'Metas activas', 'value' => '2', 'tone' => 'blue'],
            ],
        ],
        [
            'title' => 'Casa con mi pareja',
            'badge' => 'Pareja',
            'tone' => 'pink',
            'icon' => 'heart',
            'stats' => [
                ['label' => 'Disponible', 'value' => '$4,100', 'tone' => 'green'],
                ['label' => 'Deudas activas', 'value' => '1', 'tone' => 'orange'],
            ],
        ],
        [
            'title' => 'Órale Web',
            'badge' => 'Negocio',
            'tone' => 'blue',
            'icon' => 'briefcase',
            'stats' => [
                ['label' => 'Disponible', 'value' => '$12,300', 'tone' => 'green'],
                ['label' => 'Ingresos este mes', 'value' => '$22,000', 'tone' => 'blue'],
            ],
        ],
    ];

    $tones = [
        'purple' => [
            'icon' => 'bg-[#efeaff] text-[#653cf4]',
            'badge' => 'bg-[#efeaff] text-[#653cf4]',
            'stat' => 'bg-[#efeaff] text-[#653cf4]',
            'dot' => 'bg-[#653cf4]',
        ],
        'green' => [
            'icon' => 'bg-[#eaf8ef] text-[#109447]',
            'badge' => 'bg-[#eaf8ef] text-[#109447]',
            'stat' => 'bg-[#eaf8ef] text-[#109447]',
            'dot' => 'bg-[#109447]',
        ],
        'pink' => [
            'icon' => 'bg-[#ffeaf4] text-[#e91e75]',
            'badge' => 'bg-[#ffeaf4] text-[#e91e75]',
            'stat' => 'bg-[#ffeaf4] text-[#e91e75]',
            'dot' => 'bg-[#e91e75]',
        ],
        'blue' => [
            'icon' => 'bg-[#eef4ff] text-[#2563eb]',
            'badge' => 'bg-[#eef4ff] text-[#2563eb]',
            'stat' => 'bg-[#eef4ff] text-[#2563eb]',
            'dot' => 'bg-[#2563eb]',
        ],
        'orange' => [
            'icon' => 'bg-[#fff6e9] text-[#f28a16]',
            'badge' => 'bg-[#fff6e9] text-[#f28a16]',
            'stat' => 'bg-[#fff6e9] text-[#f28a16]',
            'dot' => 'bg-[#f28a16]',
        ],
    ];
@endphp

<section class="mb-5 grid gap-5 md:grid-cols-2 xl:grid-cols-4" aria-label="Espacios financieros">
    @foreach ($spaces as $space)
        <article
            class="relative min-h-72 rounded-lg border border-slate-200 bg-white/95 p-4 shadow-[0_16px_38px_rgba(22,32,63,.08)]">
            <svg class="absolute right-4 top-4 h-5 w-5 text-slate-900" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                <path d="M12 5h.01M12 12h.01M12 19h.01"></path>
            </svg>

            <div
                class="{{ $tones[$space['tone']]['icon'] }} mx-auto mb-3 grid h-14 w-14 place-items-center rounded-full">
                @switch($space['icon'])
                    @case('person')
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    @break

                    @case('family')
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-8 0v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            <path d="M2 21v-2a4 4 0 0 1 3-3.87"></path>
                            <path d="M8 3.13a4 4 0 0 0 0 7.75"></path>
                        </svg>
                    @break

                    @case('heart')
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path
                                d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z">
                            </path>
                        </svg>
                    @break

                    @case('briefcase')
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            <rect width="20" height="14" x="2" y="6" rx="2"></rect>
                            <path d="M12 12h.01"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                    @break
                @endswitch
            </div>

            <h2 class="text-center text-[17px] font-extrabold leading-tight text-[#09122f]">{{ $space['title'] }}</h2>
            <div
                class="{{ $tones[$space['tone']]['badge'] }} mx-auto mb-4 mt-2 w-fit rounded-full px-2.5 py-1 text-[10px] font-extrabold">
                {{ $space['badge'] }}</div>
            <div class="mb-4 h-px bg-slate-200"></div>

            <div class="mb-3 grid grid-cols-2 gap-2">
                @foreach ($space['stats'] as $stat)
                    <div
                        class="{{ $tones[$stat['tone']]['stat'] }} flex min-h-12 flex-col justify-center gap-1 rounded-lg px-2.5 py-2">
                        <small class="flex items-center gap-1 text-[10px] font-semibold text-slate-600">
                            <span
                                class="{{ $tones[$stat['tone']]['dot'] }} h-1.5 w-1.5 shrink-0 rounded-full"></span>{{ $stat['label'] }}
                        </small>
                        <strong class="text-[17px] leading-none">{{ $stat['value'] }}</strong>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-[1.15fr_.85fr] gap-2">
                <button
                    class="inline-flex h-8 items-center justify-center gap-3 rounded-md bg-gradient-to-br from-[#714bff] to-[#4e35e8] text-xs font-extrabold text-white"
                    type="button">Entrar <span>→</span></button>
                <button
                    class="inline-flex h-8 items-center justify-center rounded-md border border-slate-200 bg-white text-xs font-extrabold text-slate-800"
                    type="button">Editar</button>
            </div>
        </article>
    @endforeach
</section>

<section class="grid gap-5 xl:grid-cols-[minmax(300px,.85fr)_minmax(420px,1.25fr)]">
    <article class="rounded-lg border border-slate-200 bg-white/95 p-4 shadow-[0_16px_38px_rgba(22,32,63,.08)]">
        <h2 class="mb-4 text-lg font-extrabold leading-tight text-[#09122f]">Acceso rapido</h2>
        <div class="mb-4 grid gap-3 sm:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-white px-3 py-4 text-center">
                <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-full bg-[#efeaff] text-[#653cf4]">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                </div>
                <strong class="mb-1.5 block text-xs">Crear espacio</strong>
                <p class="m-0 text-[10px] leading-snug text-slate-500">Crea un nuevo espacio financiero en segundos.</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white px-3 py-4 text-center">
                <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-full bg-[#eef4ff] text-[#2563eb]">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                </div>
                <strong class="mb-1.5 block text-xs">Ver ultimo espacio</strong>
                <p class="m-0 text-[10px] leading-snug text-slate-500">Accede rapidamente al ultimo espacio que
                    abriste.</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white px-3 py-4 text-center">
                <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-full bg-[#eaf8ef] text-[#109447]">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20.6 13.5 12 22l-9-9V4h9l8.6 8.5a1 1 0 0 1 0 1z"></path>
                        <path d="M7.5 7.5h.01"></path>
                    </svg>
                </div>
                <strong class="mb-1.5 block text-xs">Configurar categorias</strong>
                <p class="m-0 text-[10px] leading-snug text-slate-500">Organiza tus gastos con categorias
                    personalizadas.</p>
            </div>
        </div>
        <div
            class="flex min-h-16 items-center gap-3 rounded-lg border border-dashed border-[#a987ff] bg-[#fbf9ff] px-4 py-3 text-xs font-bold text-slate-700">
            <svg class="h-6 w-6 shrink-0 text-[#8c65ff]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8Z"></path>
                <path d="M19 17l.8 2.2L22 20l-2.2.8L19 23l-.8-2.2L16 20l2.2-.8Z"></path>
            </svg>
            Si aun no tienes espacios financieros, crea el primero para comenzar a organizar tus quincenas.
        </div>
    </article>

    <article class="rounded-lg border border-slate-200 bg-white/95 p-4 shadow-[0_16px_38px_rgba(22,32,63,.08)]">
        <div class="mb-1 flex flex-wrap items-center gap-2">
            <h2 class="text-lg font-extrabold leading-tight text-[#09122f]">Asi se ve dentro de un espacio</h2>
            <span class="rounded-full bg-[#efeaff] px-2.5 py-1 text-[11px] font-extrabold text-[#653cf4]">Vista
                previa</span>
        </div>
        <p class="mb-4 text-xs text-slate-500">Al entrar a un espacio, tendras una vista clara de lo mas importante.
        </p>

        <div class="grid gap-3 lg:grid-cols-[92px_repeat(3,minmax(0,1fr))]">
            <div class="grid gap-2 rounded-lg border border-slate-200 bg-white p-3" aria-hidden="true">
                @foreach ([false, true, false, false, false, false] as $active)
                    <div class="grid grid-cols-[14px_1fr] items-center gap-2">
                        <span class="{{ $active ? 'text-[#653cf4]' : 'text-slate-500' }}">●</span>
                        <div class="{{ $active ? 'bg-[#d9caff]' : 'bg-slate-200' }} h-2 rounded-full"></div>
                    </div>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white p-4">
                <div class="mb-2 flex items-center justify-between text-[11px] font-extrabold text-slate-700">
                    Disponible real
                    <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <div class="text-[27px] font-extrabold leading-none text-[#109447]">$5,400</div>
                <small class="mt-2 block text-[11px] text-slate-500">Actualizado hoy</small>
                <svg class="mt-3 h-11 w-full" viewBox="0 0 170 58" fill="none" aria-hidden="true">
                    <path
                        d="M0 48C16 42 21 36 36 42C51 48 59 54 75 39C91 24 98 20 112 32C126 44 136 50 151 31C157 24 163 20 170 18V58H0V48Z"
                        fill="#dff7e8"></path>
                    <path
                        d="M0 48C16 42 21 36 36 42C51 48 59 54 75 39C91 24 98 20 112 32C126 44 136 50 151 31C157 24 163 20 170 18"
                        stroke="#41c882" stroke-width="3" stroke-linecap="round"></path>
                </svg>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <div class="mb-2 flex items-center justify-between text-[11px] font-extrabold text-slate-700">
                    Proximo pago
                    <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>
                </div>
                <div class="text-[28px] font-extrabold leading-none">15</div>
                <small class="mt-2 block text-[11px] text-slate-500">Mayo 2024</small>
                <div class="mt-5 w-fit rounded-lg bg-[#eef4ff] px-2.5 py-2 text-[11px] font-extrabold text-[#2563eb]">
                    Servicio de internet - $650</div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <div class="mb-2 flex items-center justify-between text-[11px] font-extrabold text-slate-700">
                    Riesgo
                    <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                </div>
                <div class="text-[25px] font-extrabold leading-none text-[#f28a16]">Bajo</div>
                <small class="mt-2 block text-[11px] text-slate-500">Tu salud financiera se ve bien</small>
                <div class="relative mx-auto mt-3 h-[58px] w-28 overflow-hidden">
                    <svg class="h-[58px] w-28" viewBox="0 0 120 64" fill="none" aria-hidden="true">
                        <path d="M12 58a48 48 0 0 1 96 0" stroke="#e9ecf3" stroke-width="13" stroke-linecap="round">
                        </path>
                        <path d="M12 58a48 48 0 0 1 28-43" stroke="#41bd72" stroke-width="13" stroke-linecap="round">
                        </path>
                        <path d="M40 15a48 48 0 0 1 40 0" stroke="#c5d94a" stroke-width="13" stroke-linecap="round">
                        </path>
                        <path d="M80 15a48 48 0 0 1 28 43" stroke="#ff8d2b" stroke-width="13" stroke-linecap="round">
                        </path>
                    </svg>
                    <span class="gauge-needle absolute bottom-1 left-[54px] h-11 w-1 rounded-full bg-slate-800"></span>
                </div>
            </div>
        </div>

        <div
            class="mt-4 flex min-h-8 items-center justify-center gap-2 rounded-lg bg-slate-100 text-xs font-bold text-slate-500">
            <svg class="h-4.5 w-4.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3l1.7 5.3L19 10l-5.3 1.7L12 17l-1.7-5.3L5 10l5.3-1.7Z"></path>
            </svg>
            KPI y widgets personalizados segun tu espacio
        </div>
    </article>
</section>

@endsection
