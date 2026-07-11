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
            <button command="show-modal" commandfor="dialog" type="button"
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
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-emerald-50 px-2 py-2 text-center">
                            <div class="text-[10px] font-medium text-emerald-700">● &nbsp;Disponible</div>
                            <strong class="mt-1 block text-base text-emerald-600">{{ $style['amount'] }}</strong>
                        </div>
                        <div class="rounded-lg bg-amber-50 px-2 py-2 text-center">
                            <div class="truncate text-[10px] font-medium text-amber-700">● &nbsp;{{ $style['metric'] }}
                            </div>
                            <strong class="mt-1 block text-base text-amber-600">{{ $style['metricValue'] }}</strong>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <button
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-md bg-violet-600 text-xs font-semibold text-white hover:bg-violet-700">Entrar
                            <span>→</span></button>
                        <button
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-md border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">✎
                            <span>Editar</span></button>
                    </div>
                </article>
            @endforeach
        </section>
    </div>



    {{-- Modales --}}

    <el-dialog>
        <dialog id="dialog" aria-labelledby="dialog-title"
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
            <el-dialog-backdrop
                class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

            <div tabindex="0"
                class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                <el-dialog-panel
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                    <form method="POST" action="{{ route('dashboard.espacios-financieros.store') }}">
                        @csrf
                        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 id="dialog-title" class="text-lg font-bold text-slate-900">Crear Espacio Financiero</h3>
                                    <p class="mt-1 text-sm text-slate-500">Configura la información básica de tu nuevo espacio.</p>
                                </div>
                                <button type="button" command="close" commandfor="dialog"
                                    class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                    aria-label="Cerrar formulario">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M6 6l12 12M18 6 6 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4 px-5 py-5 sm:px-6">
                            <div>
                                <label for="nombre" class="mb-1.5 block text-sm font-semibold text-slate-700">Nombre</label>
                                <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required
                                    placeholder="Ej. Presupuesto familiar"
                                    class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                @error('nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tipo" class="mb-1.5 block text-sm font-semibold text-slate-700">Tipo</label>
                                <select id="tipo" name="tipo" required
                                    class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                    <option value="" disabled @selected(!old('tipo'))>Selecciona un tipo</option>
                                    @foreach ($tiposEspacios as $tipo)
                                        <option value="{{ $tipo->value }}" @selected(old('tipo') === $tipo->value)>
                                            {{ ucfirst($tipo->value) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div x-data="{ count: {{ strlen(old('descripcion', '')) }} }">
                                <div class="mb-1.5 flex items-center justify-between gap-4">
                                    <label for="descripcion" class="text-sm font-semibold text-slate-700">Descripción</label>
                                    <span class="text-xs text-slate-400" :class="count >= 250 && 'text-red-500'" x-text="`${count}/250`"></span>
                                </div>
                                <textarea id="descripcion" name="descripcion" rows="4" maxlength="250" required
                                    @input="count = $event.target.value.length" placeholder="Describe brevemente el propósito de este espacio"
                                    class="block w-full resize-none rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('descripcion') }}</textarea>
                                @error('descripcion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="moneda" class="mb-1.5 block text-sm font-semibold text-slate-700">Moneda</label>
                                <select id="moneda" name="moneda" required
                                    class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                    <option value="" disabled @selected(!old('moneda'))>Selecciona una moneda</option>
                                    <option value="mxn" @selected(old('moneda') === 'mxn')>MXN - Peso mexicano</option>
                                    <option value="usd" @selected(old('moneda') === 'usd')>USD - Dólar estadounidense</option>
                                    <option value="euro" @selected(old('moneda') === 'euro')>EUR - Euro</option>
                                </select>
                                @error('moneda') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                            <button type="button" command="close" commandfor="dialog"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#087fe5] via-[#16bfc0] to-[#70d95c] px-5 text-sm font-semibold text-white shadow-md shadow-cyan-200/60 transition hover:brightness-105">
                                Crear espacio
                            </button>
                        </div>
                    </form>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
@endsection
