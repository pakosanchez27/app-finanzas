@extends('layouts.app-espacio')

@section('titulo')
    Presupuestos
@endsection

@section('content')
    @php
        $moneda = $espacioActual->moneda ?? 'MXN';
        $formatearDinero = fn($valor) => '$' . number_format((float) $valor, 2);
        $porcentajeUsado =
            $totales['planeado'] > 0 ? min(100, round(($totales['real'] / $totales['planeado']) * 100)) : 0;
        $riesgoGeneral = $presupuestos->contains('riesgo', 'alto')
            ? 'alto'
            : ($presupuestos->contains('riesgo', 'medio')
                ? 'medio'
                : 'bajo');
        $riesgoClases = [
            'bajo' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'medio' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'alto' => 'bg-rose-50 text-rose-700 ring-rose-200',
        ];
        $estadoClases = [
            'borrador' => 'bg-slate-100 text-slate-600',
            'activo' => 'bg-emerald-50 text-emerald-700',
            'cerrado' => 'bg-violet-50 text-violet-700',
            'pendiente' => 'bg-amber-50 text-amber-700',
            'parcial' => 'bg-blue-50 text-blue-700',
            'completado' => 'bg-emerald-50 text-emerald-700',
            'cancelado' => 'bg-rose-50 text-rose-700',
        ];
    @endphp


    <div class="mx-auto max-w-7xl pb-8">
        <nav class="mb-3 flex items-center gap-2 text-xs font-medium text-slate-500" aria-label="Navegación secundaria">
            <a href="{{ route('dashboard') }}" class="transition hover:text-violet-700">Espacio financiero</a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <a href="{{ route('espacio.show', $espacioActual) }}" class="transition hover:text-violet-700">
                {{ $espacioActual->nombre }}
            </a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <span class="text-slate-700" aria-current="page">Presupuestos</span>
        </nav>

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 lg:text-3xl">Presupuestos</h1>
                <p class="mt-1.5 text-sm text-slate-500">Controla lo planeado, registra lo real y conoce cuánto tienes
                    disponible.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @if ($periodos->isNotEmpty())
                    <form method="GET" action="{{ route('presupuesto.index', $espacioActual) }}">
                        <label for="periodo" class="sr-only">Periodo del presupuesto</label>
                        <select id="periodo" name="periodo" onchange="this.form.submit()"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white pr-10 text-sm font-semibold text-slate-700 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:w-56">
                            @foreach ($periodos as $periodo)
                                <option value="{{ $periodo->id }}" @selected($periodoSeleccionado?->is($periodo))>
                                    {{ $periodo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
                @if ($presupuestoActual)
                    <a href="{{ route('presupuesto.edit', [$espacioActual, $presupuestoActual]) }}"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-violet-200 bg-white px-4 text-sm font-semibold text-violet-700 shadow-sm transition hover:bg-violet-50">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />
                        </svg>
                        Editar presupuesto
                    </a>
                @elseif ($periodoSeleccionado)
                    <a href="{{ route('presupuesto.create', ['espacio' => $espacioActual, 'periodo' => $periodoSeleccionado->id]) }}"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-violet-200 bg-white px-4 text-sm font-semibold text-violet-700 shadow-sm transition hover:bg-violet-50">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Crear en este periodo
                    </a>
                @endif
                <a href="{{ route('presupuesto.create', $espacioActual) }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 px-5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:-translate-y-0.5 hover:brightness-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 focus-visible:ring-offset-2">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" aria-hidden="true">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Nuevo periodo y presupuesto
                </a>
            </div>
        </div>

        @if ($periodoSeleccionado)
            <section
                class="mb-5 flex flex-col gap-4 rounded-2xl border border-violet-100 bg-gradient-to-r from-violet-50 via-white to-cyan-50 p-5 sm:flex-row sm:items-center sm:justify-between"
                aria-label="Periodo seleccionado">
                <div class="flex items-center gap-4">
                    <span
                        class="grid size-11 shrink-0 place-items-center rounded-xl bg-white text-violet-600 shadow-sm ring-1 ring-violet-100">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="16" rx="2" />
                            <path d="M16 3v4M8 3v4M3 11h18" />
                        </svg>
                    </span>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="font-bold text-slate-900">{{ $periodoSeleccionado->nombre }}</h2>
                            <span
                                class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $estadoClases[$periodoSeleccionado->estado] ?? $estadoClases['borrador'] }}">
                                {{ $periodoSeleccionado->estado }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ ucfirst($periodoSeleccionado->tipo) }} ·
                            {{ $periodoSeleccionado->fecha_inicio->format('d/m/Y') }} al
                            {{ $periodoSeleccionado->fecha_fin->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Ingreso estimado</p>
                    <p class="mt-1 text-xl font-extrabold text-slate-900">
                        {{ $formatearDinero($periodoSeleccionado->ingreso_estimado) }}</p>
                    <a href="{{ route('periodo.edit', [$espacioActual, $periodoSeleccionado]) }}"
                        class="mt-2 inline-flex text-xs font-bold text-violet-700 hover:text-violet-900">
                        Editar periodo
                    </a>
                </div>
            </section>
        @endif

        @if ($presupuestos->isNotEmpty())
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Resumen financiero">
                @php
                    $resumen = [
                        [
                            'titulo' => 'Ingresos totales',
                            'valor' => $totales['ingreso'],
                            'color' => 'emerald',
                            'icono' => 'ingreso',
                        ],
                        [
                            'titulo' => 'Gasto planeado',
                            'valor' => $totales['planeado'],
                            'color' => 'violet',
                            'icono' => 'planeado',
                        ],
                        ['titulo' => 'Gasto real', 'valor' => $totales['real'], 'color' => 'amber', 'icono' => 'real'],
                        [
                            'titulo' => 'Disponible real',
                            'valor' => $totales['disponible_real'],
                            'color' => 'blue',
                            'icono' => 'disponible',
                        ],
                    ];
                    $colorTarjeta = [
                        'emerald' => 'bg-emerald-50 text-emerald-600',
                        'violet' => 'bg-violet-50 text-violet-600',
                        'amber' => 'bg-amber-50 text-amber-600',
                        'blue' => 'bg-blue-50 text-blue-600',
                    ];
                @endphp
                @foreach ($resumen as $dato)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    {{ $dato['titulo'] }}</p>
                                <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">
                                    {{ $formatearDinero($dato['valor']) }}</p>
                                <p class="mt-1 text-xs font-medium text-slate-400">{{ $moneda }}</p>
                            </div>
                            <span class="grid size-10 place-items-center rounded-xl {{ $colorTarjeta[$dato['color']] }}">
                                @if ($dato['icono'] === 'ingreso')
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M12 19V5m-6 6 6-6 6 6" />
                                    </svg>
                                @elseif ($dato['icono'] === 'planeado')
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M4 19V9m6 10V5m6 14v-7m4 7H2" />
                                    </svg>
                                @elseif ($dato['icono'] === 'real')
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M12 5v14m6-6-6 6-6-6" />
                                    </svg>
                                @else
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="3" y="6" width="18" height="14" rx="2" />
                                        <path d="M16 10h5v6h-5a3 3 0 0 1 0-6Z" />
                                    </svg>
                                @endif
                            </span>
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="mt-5 grid items-start gap-5 xl:grid-cols-[minmax(0,2fr)_minmax(280px,0.8fr)]">
                <div class="space-y-5">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
                        aria-labelledby="avance-titulo">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 id="avance-titulo" class="text-lg font-bold text-slate-950">Avance del periodo</h2>
                                <p class="mt-1 text-sm text-slate-500">Gasto real frente al total planeado.</p>
                            </div>
                            <span
                                class="w-fit rounded-full px-3 py-1.5 text-xs font-bold capitalize ring-1 ring-inset {{ $riesgoClases[$riesgoGeneral] }}">
                                Riesgo {{ $riesgoGeneral }}
                            </span>
                        </div>
                        <div class="mt-6 flex items-end justify-between gap-4">
                            <div>
                                <p class="text-3xl font-extrabold text-slate-950">{{ $porcentajeUsado }}%</p>
                                <p class="mt-1 text-xs text-slate-400">del presupuesto utilizado</p>
                            </div>
                            <p class="text-right text-sm font-semibold text-slate-600">
                                {{ $formatearDinero($totales['real']) }} <span class="font-normal text-slate-400">de
                                    {{ $formatearDinero($totales['planeado']) }}</span>
                            </p>
                        </div>
                        <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100" role="progressbar"
                            aria-valuenow="{{ $porcentajeUsado }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="h-full rounded-full bg-gradient-to-r from-violet-600 to-cyan-500 transition-all"
                                style="width: {{ $porcentajeUsado }}%"></div>
                        </div>
                    </section>

                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                        aria-labelledby="partidas-titulo">
                        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
                            <div>
                                <h2 id="partidas-titulo" class="text-lg font-bold text-slate-950">Partidas del presupuesto
                                </h2>
                                <p class="mt-1 text-xs text-slate-400">{{ $partidas->count() }} partidas registradas</p>
                            </div>
                            <button type="button" onclick="document.getElementById('agregar-partida').showModal()"
                                class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-violet-200 px-3 text-xs font-bold text-violet-700 transition hover:bg-violet-50">
                                <span class="text-base leading-none">+</span>
                                Agregar partida
                            </button>
                        </div>
                        @if ($partidas->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                                    <thead
                                        class="bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                        <tr>
                                            <th class="px-5 py-3 sm:px-6">Partida</th>
                                            <th class="px-5 py-3">Planeado</th>
                                            <th class="px-5 py-3">Real</th>
                                            <th class="px-5 py-3">Avance</th>
                                            <th class="px-5 py-3">Estado</th>
                                            <th class="px-5 py-3 text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($partidas as $partida)
                                            @php
                                                $avancePartida =
                                                    $partida->monto_planeado > 0
                                                        ? min(
                                                            100,
                                                            round(
                                                                ($partida->monto_real / $partida->monto_planeado) * 100,
                                                            ),
                                                        )
                                                        : 0;
                                                $colorCategoria = $partida->categoriaGasto?->color ?: '#7c3aed';
                                            @endphp
                                            <tr class="transition hover:bg-slate-50/70">
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center gap-3">
                                                        <span class="size-2.5 shrink-0 rounded-full"
                                                            style="background-color: {{ $colorCategoria }}"></span>
                                                        <div>
                                                            <p class="font-semibold text-slate-800">{{ $partida->nombre }}
                                                            </p>
                                                            <p class="mt-0.5 text-xs text-slate-400">
                                                                {{ $partida->categoriaGasto?->nombre ?? ucfirst($partida->tipo) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-600">
                                                    {{ $formatearDinero($partida->monto_planeado) }}</td>
                                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">
                                                    {{ $formatearDinero($partida->monto_real) }}</td>
                                                <td class="min-w-32 px-5 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                                                            <div class="h-full rounded-full bg-violet-500"
                                                                style="width: {{ $avancePartida }}%"></div>
                                                        </div>
                                                        <span
                                                            class="w-9 text-right text-xs font-semibold text-slate-500">{{ $avancePartida }}%</span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4">
                                                    <span
                                                        class="rounded-full px-2.5 py-1 text-[10px] font-bold capitalize {{ $estadoClases[$partida->estado] ?? $estadoClases['pendiente'] }}">
                                                        {{ $partida->estado }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                                    <div class="inline-flex items-center gap-1">
                                                        <button type="button"
                                                            onclick="document.getElementById('editar-partida-{{ $partida->id }}').showModal()"
                                                            class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-violet-700 transition hover:bg-violet-50">
                                                            Editar
                                                        </button>
                                                        <form method="POST"
                                                            action="{{ route('presupuesto.partidas.destroy', [$espacioActual, $presupuestoActual, $partida]) }}"
                                                            onsubmit="return confirm('¿Seguro que deseas eliminar esta partida? Esta acción no se puede deshacer.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-rose-600 transition hover:bg-rose-50">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="px-6 py-10 text-center">
                                <p class="text-sm font-semibold text-slate-700">Todavía no hay partidas</p>
                                <p class="mt-1 text-xs text-slate-400">Agrega ingresos y gastos para detallar este
                                    presupuesto.</p>
                            </div>
                        @endif
                    </section>

                    @foreach ($partidas as $partida)
                        @php
                            $contextoEdicion = 'editar-partida-'.$partida->id;
                        @endphp
                        <dialog id="editar-partida-{{ $partida->id }}"
                            class="w-[calc(100%-2rem)] max-w-lg rounded-2xl border-0 bg-white p-0 shadow-2xl backdrop:bg-slate-950/40">
                            <form method="POST"
                                action="{{ route('presupuesto.partidas.update', [$espacioActual, $presupuestoActual, $partida]) }}"
                                class="p-5 sm:p-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_form_context" value="{{ $contextoEdicion }}">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 class="text-lg font-bold text-slate-950">Editar partida</h2>
                                        <p class="mt-1 text-xs text-slate-400">Modifica la información y actualiza los totales del presupuesto.</p>
                                    </div>
                                    <button type="button" onclick="document.getElementById('editar-partida-{{ $partida->id }}').close()"
                                        class="grid size-8 place-items-center rounded-full text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                                        aria-label="Cerrar">×</button>
                                </div>

                                @if ($errors->any() && old('_form_context') === $contextoEdicion)
                                    <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <div class="mt-5 space-y-4">
                                    <div>
                                        <label for="editar_nombre_{{ $partida->id }}" class="mb-1.5 block text-sm font-semibold text-slate-700">Nombre <span class="text-rose-500">*</span></label>
                                        <input id="editar_nombre_{{ $partida->id }}" name="nombre" type="text"
                                            value="{{ old('_form_context') === $contextoEdicion ? old('nombre') : $partida->nombre }}"
                                            required maxlength="120"
                                            class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                    </div>
                                    <div>
                                        <label for="editar_categoria_{{ $partida->id }}" class="mb-1.5 block text-sm font-semibold text-slate-700">Categoría <span class="text-rose-500">*</span></label>
                                        @php
                                            $categoriaSeleccionada = old('_form_context') === $contextoEdicion
                                                ? old('categorias_gasto_id')
                                                : $partida->categorias_gasto_id;
                                        @endphp
                                        <select id="editar_categoria_{{ $partida->id }}" name="categorias_gasto_id" required
                                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" @selected((string) $categoriaSeleccionada === (string) $categoria->id)>
                                                    {{ $categoria->nombre }} · {{ ucfirst($categoria->tipo) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="editar_monto_{{ $partida->id }}" class="mb-1.5 block text-sm font-semibold text-slate-700">Monto planeado <span class="text-rose-500">*</span></label>
                                            <input id="editar_monto_{{ $partida->id }}" name="monto_planeado" type="number" min="0.01" step="0.01"
                                                value="{{ old('_form_context') === $contextoEdicion ? old('monto_planeado') : $partida->monto_planeado }}"
                                                required class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                        </div>
                                        <div>
                                            <label for="editar_fecha_{{ $partida->id }}" class="mb-1.5 block text-sm font-semibold text-slate-700">Fecha objetivo</label>
                                            <input id="editar_fecha_{{ $partida->id }}" name="fecha_objetivo" type="date"
                                                value="{{ old('_form_context') === $contextoEdicion ? old('fecha_objetivo') : $partida->fecha_objetivo?->format('Y-m-d') }}"
                                                class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                                    <button type="button" onclick="document.getElementById('editar-partida-{{ $partida->id }}').close()"
                                        class="h-10 rounded-lg border border-slate-200 px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</button>
                                    <button type="submit"
                                        class="h-10 rounded-lg bg-violet-600 px-4 text-sm font-semibold text-white hover:bg-violet-700">Guardar cambios</button>
                                </div>
                            </form>
                        </dialog>
                    @endforeach

                    <dialog id="agregar-partida"
                        class="w-[calc(100%-2rem)] max-w-lg rounded-2xl border-0 bg-white p-0 shadow-2xl backdrop:bg-slate-950/40">
                        <form method="POST"
                            action="{{ route('presupuesto.partidas.store', [$espacioActual, $presupuestoActual]) }}"
                            class="p-5 sm:p-6">
                            @csrf
                            <input type="hidden" name="_form_context" value="agregar-partida">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-950">Agregar partida</h2>
                                    <p class="mt-1 text-xs text-slate-400">Se actualizarán automáticamente los totales del presupuesto.</p>
                                </div>
                                <button type="button" onclick="document.getElementById('agregar-partida').close()"
                                    class="grid size-8 place-items-center rounded-full text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                                    aria-label="Cerrar">×</button>
                            </div>

                            @if ($errors->any() && old('_form_context') === 'agregar-partida')
                                <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="mt-5 space-y-4">
                                <div>
                                    <label for="partida_nombre" class="mb-1.5 block text-sm font-semibold text-slate-700">Nombre <span class="text-rose-500">*</span></label>
                                    <input id="partida_nombre" name="nombre" type="text" value="{{ old('nombre') }}" required maxlength="120"
                                        class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                <div>
                                    <label for="partida_categoria" class="mb-1.5 block text-sm font-semibold text-slate-700">Categoría <span class="text-rose-500">*</span></label>
                                    <select id="partida_categoria" name="categorias_gasto_id" required
                                        class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="" disabled @selected(!old('categorias_gasto_id'))>Selecciona una categoría</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" @selected((string) old('categorias_gasto_id') === (string) $categoria->id)>
                                                {{ $categoria->nombre }} · {{ ucfirst($categoria->tipo) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="partida_monto" class="mb-1.5 block text-sm font-semibold text-slate-700">Monto planeado <span class="text-rose-500">*</span></label>
                                        <input id="partida_monto" name="monto_planeado" type="number" min="0.01" step="0.01"
                                            value="{{ old('monto_planeado') }}" required
                                            class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                    </div>
                                    <div>
                                        <label for="partida_fecha" class="mb-1.5 block text-sm font-semibold text-slate-700">Fecha objetivo</label>
                                        <input id="partida_fecha" name="fecha_objetivo" type="date" value="{{ old('fecha_objetivo') }}"
                                            class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                                <button type="button" onclick="document.getElementById('agregar-partida').close()"
                                    class="h-10 rounded-lg border border-slate-200 px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="h-10 rounded-lg bg-violet-600 px-4 text-sm font-semibold text-white hover:bg-violet-700">
                                    Agregar partida
                                </button>
                            </div>
                        </form>
                    </dialog>

                    @if ($errors->any() && old('_form_context'))
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                document.getElementById(@js(old('_form_context')))?.showModal();
                            });
                        </script>
                    @endif
                </div>

                <aside class="space-y-5 xl:sticky xl:top-0">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                        aria-labelledby="presupuestos-titulo">
                        <div class="flex items-center justify-between gap-3">
                            <h2 id="presupuestos-titulo" class="font-bold text-slate-950">Presupuesto del periodo</h2>
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-700">Único</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            @foreach ($presupuestos as $presupuesto)
                                @php
                                    $uso =
                                        $presupuesto->gasto_planeado_total > 0
                                            ? min(
                                                100,
                                                round(
                                                    ($presupuesto->gasto_real_total /
                                                        $presupuesto->gasto_planeado_total) *
                                                        100,
                                                ),
                                            )
                                            : 0;
                                @endphp
                                <article class="rounded-xl border border-slate-200 p-3.5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h3 class="truncate text-sm font-bold text-slate-800">
                                                {{ $presupuesto->nombre }}</h3>
                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $formatearDinero($presupuesto->gasto_real_total) }} gastado</p>
                                        </div>
                                        <span
                                            class="rounded-full px-2 py-1 text-[9px] font-bold capitalize ring-1 ring-inset {{ $riesgoClases[$presupuesto->riesgo] }}">
                                            {{ $presupuesto->riesgo }}
                                        </span>
                                    </div>
                                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-violet-500"
                                            style="width: {{ $uso }}%"></div>
                                    </div>
                                    <div class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-3">
                                        <a href="{{ route('presupuesto.edit', [$espacioActual, $presupuesto]) }}"
                                            class="inline-flex h-8 flex-1 items-center justify-center gap-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 transition hover:border-violet-200 hover:bg-violet-50 hover:text-violet-700">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                aria-hidden="true">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />
                                            </svg>
                                            Editar
                                        </a>
                                        <form method="POST"
                                            action="{{ route('presupuesto.destroy', [$espacioActual, $presupuesto]) }}"
                                            class="flex-1"
                                            onsubmit="return confirm('¿Eliminar este presupuesto y todas sus partidas? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-8 w-full items-center justify-center gap-1.5 rounded-lg border border-rose-200 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-violet-50 p-5">
                        <div class="flex items-center gap-3">
                            <span class="grid size-9 place-items-center rounded-full bg-white text-cyan-600 shadow-sm">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M9 18h6M10 22h4" />
                                    <path d="M8.5 14.5A7 7 0 1 1 15.5 14.5c-.9.7-1.5 1.5-1.5 2.5h-4c0-1-.6-1.8-1.5-2.5Z" />
                                </svg>
                            </span>
                            <h2 class="font-bold text-slate-900">Lectura rápida</h2>
                        </div>
                        <p class="mt-4 text-sm leading-6 text-slate-600">
                            @if ($porcentajeUsado < 80)
                                Tus gastos se mantienen dentro de lo planeado. Conserva este ritmo durante el periodo.
                            @elseif ($porcentajeUsado <= 100)
                                Estás cerca del límite planeado. Revisa las partidas con mayor avance antes de gastar.
                            @else
                                El gasto real superó lo planeado. Conviene ajustar las partidas y el disponible del periodo.
                            @endif
                        </p>
                    </section>
                </aside>
            </div>
        @else
            <section
                class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm sm:py-16">
                <span
                    class="mx-auto grid size-20 place-items-center rounded-2xl bg-gradient-to-br from-violet-50 to-cyan-50 text-violet-600 ring-1 ring-violet-100">
                    <svg class="size-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19V9m5 10V5m5 14v-7m5 7V8M2 19h20" />
                    </svg>
                </span>
                <h2 class="mt-6 text-xl font-bold text-slate-950">
                    {{ $periodoSeleccionado ? 'Crea el primer presupuesto de este periodo' : 'Comienza creando un periodo presupuestario' }}
                </h2>
                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                    Organiza tus ingresos y gastos planeados para comparar después los resultados reales y conocer tu
                    disponibilidad.
                </p>
                <a href="{{ route('presupuesto.create', ['espacio' => $espacioActual, 'periodo' => $periodoSeleccionado?->id]) }}"
                    class="mt-7 inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 px-5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:-translate-y-0.5 hover:brightness-105">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Crear presupuesto
                </a>
            </section>
        @endif
    </div>


>
@endsection
