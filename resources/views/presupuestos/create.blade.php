@extends('layouts.app-espacio')

@section('titulo')
    {{ isset($presupuesto) ? 'Editar presupuesto' : 'Crear presupuesto' }}
@endsection

@section('content')
    @php
        $editando = isset($presupuesto);
        $periodoSugerido = $periodoSugerido ?? null;
        $periodoFijo = $editando ? $presupuesto->periodoPresupuesto : $periodoSugerido;
        $partidasIniciales = old('partidas', $editando
            ? $presupuesto->partidas->map(fn ($partida) => [
                'id' => $partida->id,
                'nombre' => $partida->nombre,
                'categorias_gasto_id' => (string) $partida->categorias_gasto_id,
                'monto_planeado' => $partida->monto_planeado,
                'fecha_objetivo' => $partida->fecha_objetivo?->format('Y-m-d') ?? '',
            ])->values()->all()
            : [[
                'id' => '',
                'nombre' => '',
                'categorias_gasto_id' => '',
                'monto_planeado' => '',
                'fecha_objetivo' => '',
            ]]);
    @endphp

    <div class="mx-auto max-w-7xl pb-8"
        x-data="{
            periodoModo: @js(old('periodo_modo', $periodoFijo ? 'existente' : 'nuevo')),
            periodoId: @js((string) old('periodo_presupuesto_id', $editando
                ? $presupuesto->periodo_presupuesto_id
                : ($periodoSugerido?->id ?? ''))),
            ingresoEstimado: @js(old('ingreso_estimado', '')),
            periodos: @js($periodos->map(fn ($periodo) => [
                'id' => (string) $periodo->id,
                'nombre' => $periodo->nombre,
                'ingreso' => (float) $periodo->ingreso_estimado,
            ])->values()),
            partidas: @js($partidasIniciales),
            agregarPartida() {
                this.partidas.push({ id: '', nombre: '', categorias_gasto_id: '', monto_planeado: '', fecha_objetivo: '' });
            },
            quitarPartida(indice) {
                if (this.partidas.length > 1) this.partidas.splice(indice, 1);
            },
            ingresoActual() {
                if (this.periodoModo === 'nuevo') return Number(this.ingresoEstimado) || 0;
                const periodo = this.periodos.find(item => item.id === String(this.periodoId));
                return periodo ? Number(periodo.ingreso) : 0;
            },
            gastoPlaneado() {
                return this.partidas.reduce((total, partida) => total + (Number(partida.monto_planeado) || 0), 0);
            },
            disponible() {
                return this.ingresoActual() - this.gastoPlaneado();
            },
            riesgo() {
                const ingreso = this.ingresoActual();
                if (!ingreso) return 'Sin calcular';
                const proporcion = this.gastoPlaneado() / ingreso;
                return proporcion <= .7 ? 'Bajo' : (proporcion <= .9 ? 'Medio' : 'Alto');
            },
            formatoMoneda(valor) {
                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: @js($espacioActual->moneda ?? 'MXN'),
                    maximumFractionDigits: 2
                }).format(Number(valor) || 0);
            }
        }">
        <nav class="mb-3 flex items-center gap-2 text-xs font-medium text-slate-500" aria-label="Navegación secundaria">
            <a href="{{ route('dashboard') }}" class="transition hover:text-violet-700">Espacio financiero</a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <a href="{{ route('presupuesto.index', $espacioActual) }}" class="transition hover:text-violet-700">Presupuestos</a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <span class="text-slate-700" aria-current="page">{{ $editando ? 'Editar' : 'Crear' }}</span>
        </nav>

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 lg:text-3xl">
                    {{ $editando ? 'Editar presupuesto' : 'Crear presupuesto' }}
                </h1>
                <p class="mt-1.5 text-sm text-slate-500">
                    {{ $editando ? 'Actualiza el periodo, nombre y distribución de las partidas.' : 'Define el periodo y distribuye el ingreso entre tus categorías de gasto.' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('presupuesto.index', $espacioActual) }}"
                    class="inline-flex h-11 flex-1 items-center justify-center rounded-lg border border-violet-300 bg-white px-5 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 sm:flex-none">
                    Cancelar
                </a>
                <button type="submit" form="presupuesto-form"
                    class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 px-5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:-translate-y-0.5 hover:brightness-105 sm:flex-none">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
                        <path d="M17 21v-8H7v8M7 3v5h8" />
                    </svg>
                    {{ $editando ? 'Guardar cambios' : 'Guardar presupuesto' }}
                </button>
            </div>
        </div>

        <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,2fr)_minmax(280px,0.8fr)]">
            <form id="presupuesto-form" method="POST"
                action="{{ $editando ? route('presupuesto.update', [$espacioActual, $presupuesto]) : route('presupuesto.store', $espacioActual) }}"
                class="space-y-5">
                @csrf
                @if ($editando)
                    @method('PUT')
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700" role="alert">
                        <p class="font-bold">Revisa la información antes de continuar:</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="periodo-titulo">
                    <div class="flex items-center gap-3">
                        <span class="grid size-9 place-items-center rounded-lg bg-violet-50 text-sm font-extrabold text-violet-700">1</span>
                        <div>
                            <h2 id="periodo-titulo" class="text-lg font-bold text-slate-950">Periodo presupuestario</h2>
                            <p class="mt-0.5 text-xs text-slate-400">
                                {{ $periodoFijo ? 'Periodo asociado a este presupuesto.' : 'Define las fechas y el ingreso del nuevo periodo.' }}
                            </p>
                        </div>
                    </div>

                    <input type="hidden" name="periodo_modo" value="{{ $periodoFijo ? 'existente' : 'nuevo' }}">

                    @if ($periodoFijo)
                        <input type="hidden" name="periodo_presupuesto_id" value="{{ $periodoFijo->id }}">
                        <div class="mt-5 flex flex-col gap-4 rounded-xl border border-violet-100 bg-violet-50/60 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-bold text-slate-900">{{ $periodoFijo->nombre }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ ucfirst($periodoFijo->tipo) }} ·
                                    {{ $periodoFijo->fecha_inicio->format('d/m/Y') }} al
                                    {{ $periodoFijo->fecha_fin->format('d/m/Y') }} ·
                                    ${{ number_format($periodoFijo->ingreso_estimado, 2) }}
                                </p>
                            </div>
                            <a href="{{ route('periodo.edit', [$espacioActual, $periodoFijo]) }}"
                                class="inline-flex h-9 items-center justify-center rounded-lg border border-violet-200 bg-white px-3 text-xs font-bold text-violet-700 hover:bg-violet-50">
                                Editar periodo
                            </a>
                        </div>
                    @else
                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="periodo_nombre" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del periodo <span class="text-rose-500">*</span></label>
                            <input id="periodo_nombre" name="periodo_nombre" value="{{ old('periodo_nombre') }}" type="text"
                                required maxlength="120"
                                class="h-12 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500"
                                placeholder="Ej. Agosto 2026">
                        </div>
                        <div>
                            <label for="periodo_tipo" class="mb-2 block text-sm font-semibold text-slate-700">Frecuencia <span class="text-rose-500">*</span></label>
                            <select id="periodo_tipo" name="periodo_tipo" required
                                class="h-12 w-full rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                <option value="" disabled @selected(!old('periodo_tipo'))>Selecciona una frecuencia</option>
                                @foreach (['semanal' => 'Semanal', 'quincenal' => 'Quincenal', 'mensual' => 'Mensual', 'anual' => 'Anual'] as $valor => $texto)
                                    <option value="{{ $valor }}" @selected(old('periodo_tipo') === $valor)>{{ $texto }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="fecha_inicio" class="mb-2 block text-sm font-semibold text-slate-700">Fecha de inicio <span class="text-rose-500">*</span></label>
                            <input id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" type="date"
                                required
                                class="h-12 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div>
                            <label for="fecha_fin" class="mb-2 block text-sm font-semibold text-slate-700">Fecha de finalización <span class="text-rose-500">*</span></label>
                            <input id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" type="date"
                                required
                                class="h-12 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="ingreso_estimado" class="mb-2 block text-sm font-semibold text-slate-700">Ingreso estimado <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-sm font-semibold text-slate-500">$</span>
                                <input id="ingreso_estimado" name="ingreso_estimado" x-model.number="ingresoEstimado" type="number"
                                    min="0.01" step="0.01" required
                                    class="h-12 w-full rounded-lg border-slate-300 pl-8 text-sm font-semibold shadow-sm focus:border-violet-500 focus:ring-violet-500">
                            </div>
                        </div>
                    </div>
                    @endif
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="presupuesto-titulo">
                    <div class="flex items-center gap-3">
                        <span class="grid size-9 place-items-center rounded-lg bg-violet-50 text-sm font-extrabold text-violet-700">2</span>
                        <div>
                            <h2 id="presupuesto-titulo" class="text-lg font-bold text-slate-950">Datos del presupuesto</h2>
                            <p class="mt-0.5 text-xs text-slate-400">Identifica el presupuesto dentro del periodo.</p>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label for="nombre" class="mb-2 block text-sm font-semibold text-slate-700">Nombre del presupuesto <span class="text-rose-500">*</span></label>
                        <input id="nombre" name="nombre" value="{{ old('nombre', $editando ? $presupuesto->nombre : '') }}" type="text" required maxlength="120"
                            class="h-12 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500"
                            placeholder="Ej. Gastos del hogar">
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="partidas-titulo">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <span class="grid size-9 place-items-center rounded-lg bg-violet-50 text-sm font-extrabold text-violet-700">3</span>
                            <div>
                                <h2 id="partidas-titulo" class="text-lg font-bold text-slate-950">Partidas de gasto</h2>
                                <p class="mt-0.5 text-xs text-slate-400">Distribuye el monto planeado por categoría.</p>
                            </div>
                        </div>
                        <button type="button" @click="agregarPartida()"
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-violet-200 px-3 text-xs font-bold text-violet-700 transition hover:bg-violet-50">
                            <span class="text-base leading-none">+</span> Agregar partida
                        </button>
                    </div>

                    <div class="mt-5 space-y-4">
                        <template x-for="(partida, indice) in partidas" :key="indice">
                            <div class="relative grid gap-4 rounded-xl border border-slate-200 bg-slate-50/60 p-4 md:grid-cols-2 xl:grid-cols-4">
                                <input type="hidden" :name="`partidas[${indice}][id]`" :value="partida.id || ''">
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Nombre</label>
                                    <input type="text" x-model="partida.nombre" :name="`partidas[${indice}][nombre]`" required maxlength="120"
                                        class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500"
                                        placeholder="Ej. Supermercado">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Categoría</label>
                                    <select x-model="partida.categorias_gasto_id" :name="`partidas[${indice}][categorias_gasto_id]`" required
                                        class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="" disabled>Selecciona</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }} · {{ ucfirst($categoria->tipo) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Monto planeado</label>
                                    <input type="number" x-model.number="partida.monto_planeado" :name="`partidas[${indice}][monto_planeado]`"
                                        min="0.01" step="0.01" required
                                        class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Fecha objetivo <span class="font-normal text-slate-400">(opcional)</span></label>
                                    <input type="date" x-model="partida.fecha_objetivo" :name="`partidas[${indice}][fecha_objetivo]`"
                                        class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                <button type="button" @click="quitarPartida(indice)" x-show="partidas.length > 1"
                                    class="absolute -right-2 -top-2 grid size-7 place-items-center rounded-full border border-rose-200 bg-white text-rose-500 shadow-sm hover:bg-rose-50"
                                    aria-label="Eliminar partida">×</button>
                            </div>
                        </template>
                    </div>
                </section>
            </form>

            <aside class="space-y-5 xl:sticky xl:top-0">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="resumen-titulo">
                    <h2 id="resumen-titulo" class="text-base font-bold text-slate-950">Resumen del presupuesto</h2>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Ingreso estimado</dt>
                            <dd class="font-bold text-slate-900" x-text="formatoMoneda(ingresoActual())">$0.00</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Gasto planeado</dt>
                            <dd class="font-bold text-violet-700" x-text="formatoMoneda(gastoPlaneado())">$0.00</dd>
                        </div>
                        <div class="border-t border-slate-100 pt-4">
                            <div class="flex items-center justify-between gap-4">
                                <dt class="font-semibold text-slate-700">Disponible estimado</dt>
                                <dd class="text-lg font-extrabold" :class="disponible() < 0 ? 'text-rose-600' : 'text-emerald-600'"
                                    x-text="formatoMoneda(disponible())">$0.00</dd>
                            </div>
                        </div>
                    </dl>
                    <div class="mt-5 rounded-xl bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-3 text-xs">
                            <span class="font-medium text-slate-500">Nivel de riesgo</span>
                            <span class="rounded-full px-2.5 py-1 font-bold"
                                :class="{
                                    'bg-emerald-100 text-emerald-700': riesgo() === 'Bajo',
                                    'bg-amber-100 text-amber-700': riesgo() === 'Medio',
                                    'bg-rose-100 text-rose-700': riesgo() === 'Alto',
                                    'bg-slate-200 text-slate-600': riesgo() === 'Sin calcular'
                                }"
                                x-text="riesgo()">Sin calcular</span>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-violet-50 p-5">
                    <h2 class="font-bold text-slate-900">¿Cómo se calcula?</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        El disponible estimado es el ingreso del periodo menos la suma de las partidas. El riesgo aumenta cuando planeas usar una proporción mayor de tus ingresos.
                    </p>
                </section>
            </aside>
        </div>
    </div>
@endsection
