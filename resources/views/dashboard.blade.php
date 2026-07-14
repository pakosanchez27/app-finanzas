@extends('layouts.app')

@section('titulo')
    Dashboard
@endsection

@php
    $demoSpaces = collect([
        (object) ['nombre' => 'Presupuesto personal', 'tipo' => 'personal'],
        (object) ['nombre' => 'Familia Sánchez', 'tipo' => 'familia'],
        (object) ['nombre' => 'Casa con mi pareja', 'tipo' => 'pareja'],
        (object) ['nombre' => 'Órale Web', 'tipo' => 'negocio'],
    ]);
    $dashboardSpaces = $espacios->isNotEmpty() ? $espacios->take(4) : $demoSpaces;
    $spaceStyles = [
        'personal' => [
            'label' => 'Personal',
            'icon' => 'user',
            'accent' => 'violet',
            'amount' => '$5,400',
            'metric' => 'Pagos pendientes',
            'metricValue' => '3',
        ],
        'familia' => [
            'label' => 'Familia',
            'icon' => 'users',
            'accent' => 'green',
            'amount' => '$2,850',
            'metric' => 'Metas activas',
            'metricValue' => '2',
        ],
        'pareja' => [
            'label' => 'Pareja',
            'icon' => 'heart',
            'accent' => 'pink',
            'amount' => '$4,100',
            'metric' => 'Deudas activas',
            'metricValue' => '1',
        ],
        'negocio' => [
            'label' => 'Negocio',
            'icon' => 'briefcase',
            'accent' => 'blue',
            'amount' => '$12,300',
            'metric' => 'Ingresos este mes',
            'metricValue' => '$22,000',
        ],
        'otro' => [
            'label' => 'Otro',
            'icon' => 'user',
            'accent' => 'slate',
            'amount' => '$3,200',
            'metric' => 'Movimientos',
            'metricValue' => '8',
        ],
    ];
    $accentClasses = [
        'violet' => ['circle' => 'bg-violet-100 text-violet-600', 'pill' => 'bg-violet-50 text-violet-600'],
        'green' => ['circle' => 'bg-emerald-100 text-emerald-600', 'pill' => 'bg-emerald-50 text-emerald-600'],
        'pink' => ['circle' => 'bg-pink-100 text-pink-600', 'pill' => 'bg-pink-50 text-pink-600'],
        'blue' => ['circle' => 'bg-blue-100 text-blue-600', 'pill' => 'bg-blue-50 text-blue-600'],
        'slate' => ['circle' => 'bg-slate-100 text-slate-500', 'pill' => 'bg-slate-100 text-slate-500'],
    ];
@endphp
@section('content')
    <div class="mx-auto max-w-[1500px]">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 lg:text-3xl">Mis espacios financieros</h1>
                <p class="mt-1.5 text-sm text-slate-500">Selecciona un espacio para ver tu presupuesto, metas y movimientos.
                </p>
            </div>
            <button command="show-modal" commandfor="crear-espacio-financiero" type="button"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#087fe5] via-[#16bfc0] to-[#70d95c] px-5 text-sm font-semibold text-white shadow-lg shadow-cyan-200/60 transition hover:-translate-y-0.5 hover:brightness-105">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Crear espacio financiero
            </button>
        </div>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Espacios financieros">
            @foreach ($espacios as $space)
                @php
                    $type = strtolower((string) ($space->tipo ?? 'otro'));
                    $style = $spaceStyles[$type] ?? $spaceStyles['otro'];
                    $colors = $accentClasses[$style['accent']];
                @endphp
                <article class="relative rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <button type="button" class="absolute right-3 top-3 rounded-md p-1 text-slate-500 hover:bg-slate-100"
                        aria-label="Opciones">
                        <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <circle cx="12" cy="5" r="1.5" />
                            <circle cx="12" cy="12" r="1.5" />
                            <circle cx="12" cy="19" r="1.5" />
                        </svg>
                    </button>
                    <div class="mx-auto grid size-14 place-items-center rounded-full {{ $colors['circle'] }}">
                        @if ($style['icon'] === 'heart')
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path
                                    d="M20.8 5.7a5.4 5.4 0 0 0-7.7 0L12 6.8l-1.1-1.1a5.4 5.4 0 1 0-7.7 7.7L12 22l8.8-8.6a5.4 5.4 0 0 0 0-7.7Z" />
                            </svg>
                        @elseif ($style['icon'] === 'briefcase')
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="7" width="18" height="13" rx="2" />
                                <path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 12h18M9 12v2h6v-2" />
                            </svg>
                        @elseif ($style['icon'] === 'users')
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="9" cy="8" r="3" />
                                <circle cx="17" cy="9" r="2.5" />
                                <path d="M3 20a6 6 0 0 1 12 0M14 17a5 5 0 0 1 7 3" />
                            </svg>
                        @else
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M5 21a7 7 0 0 1 14 0" />
                            </svg>
                        @endif
                    </div>
                    <h2 class="mt-3 truncate text-center text-base font-bold text-slate-950">{{ $space->nombre }}</h2>
                    <div class="mx-auto mt-2 w-fit rounded-full px-3 py-1 text-[10px] font-semibold {{ $colors['pill'] }}">
                        {{ $style['label'] }}</div>
                    <div class="my-3 border-t border-slate-200"></div>
                    <div class="text-center">
                        <p class=" text-slate-600 text-xs">
                            {{$space->descripcion}}
                        </p>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <a href="{{route('espacio.show', $space)}}"
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-md bg-violet-600 text-xs font-semibold text-white hover:bg-violet-700">Entrar
                            <span>→</span></a>
                        <button type="button" command="show-modal" commandfor="editar-espacio-{{ $space->getKey() }}"
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-md border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">✎
                            <span>Editar</span></button>
                    </div>
                </article>
            @endforeach
        </section>

        @foreach ($espacios as $space)
            <x-espacio-financiero-form
                :tipos-espacios="$tiposEspacios"
                :espacio="$space"
                dialog-id="editar-espacio-{{ $space->getKey() }}"
            />
        @endforeach
    </div>
    <x-espacio-financiero-form :tipos-espacios="$tiposEspacios" />
@endsection
