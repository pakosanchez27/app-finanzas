@extends('layouts.app-espacio')

@section('titulo')
    Editar periodo
@endsection

@section('content')
    <div class="mx-auto max-w-3xl pb-8">
        <nav class="mb-3 flex items-center gap-2 text-xs font-medium text-slate-500" aria-label="Navegación secundaria">
            <a href="{{ route('presupuesto.index', $espacioActual) }}" class="transition hover:text-violet-700">Presupuestos</a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <span class="text-slate-700" aria-current="page">Editar periodo</span>
        </nav>

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 lg:text-3xl">Editar periodo</h1>
            <p class="mt-1.5 text-sm text-slate-500">Los cambios en el ingreso recalcularán el disponible y riesgo del presupuesto.</p>
        </div>

        <form method="POST" action="{{ route('periodo.update', [$espacioActual, $periodo]) }}"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="nombre" class="mb-2 block text-sm font-semibold text-slate-700">Nombre <span class="text-rose-500">*</span></label>
                    <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $periodo->nombre) }}" required maxlength="120"
                        class="h-12 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                </div>
                <div>
                    <label for="tipo" class="mb-2 block text-sm font-semibold text-slate-700">Frecuencia <span class="text-rose-500">*</span></label>
                    <select id="tipo" name="tipo" required
                        class="h-12 w-full rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        @foreach (['semanal' => 'Semanal', 'quincenal' => 'Quincenal', 'mensual' => 'Mensual', 'anual' => 'Anual'] as $valor => $texto)
                            <option value="{{ $valor }}" @selected(old('tipo', $periodo->tipo) === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="estado" class="mb-2 block text-sm font-semibold text-slate-700">Estado <span class="text-rose-500">*</span></label>
                    <select id="estado" name="estado" required
                        class="h-12 w-full rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        @foreach (['borrador' => 'Borrador', 'activo' => 'Activo', 'cerrado' => 'Cerrado'] as $valor => $texto)
                            <option value="{{ $valor }}" @selected(old('estado', $periodo->estado) === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="fecha_inicio" class="mb-2 block text-sm font-semibold text-slate-700">Fecha de inicio <span class="text-rose-500">*</span></label>
                    <input id="fecha_inicio" name="fecha_inicio" type="date"
                        value="{{ old('fecha_inicio', $periodo->fecha_inicio->format('Y-m-d')) }}" required
                        class="h-12 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                </div>
                <div>
                    <label for="fecha_fin" class="mb-2 block text-sm font-semibold text-slate-700">Fecha final <span class="text-rose-500">*</span></label>
                    <input id="fecha_fin" name="fecha_fin" type="date"
                        value="{{ old('fecha_fin', $periodo->fecha_fin->format('Y-m-d')) }}" required
                        class="h-12 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                </div>
                <div class="md:col-span-2">
                    <label for="ingreso_estimado" class="mb-2 block text-sm font-semibold text-slate-700">Ingreso estimado <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-sm font-semibold text-slate-500">$</span>
                        <input id="ingreso_estimado" name="ingreso_estimado" type="number" min="0.01" step="0.01"
                            value="{{ old('ingreso_estimado', $periodo->ingreso_estimado) }}" required
                            class="h-12 w-full rounded-lg border-slate-300 pl-8 text-sm font-semibold shadow-sm focus:border-violet-500 focus:ring-violet-500">
                    </div>
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('presupuesto.index', ['espacio' => $espacioActual, 'periodo' => $periodo->id]) }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 px-5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 px-5 text-sm font-semibold text-white shadow-lg shadow-violet-200">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection
