@extends('layouts.app-espacio')

@section('titulo')
    Movimientos
@endsection

@section('content')
    <div class="mx-auto max-w-7xl pb-8" x-data="movimientosDemo()">
        <nav class="mb-3 flex items-center gap-2 text-xs font-medium text-slate-500" aria-label="Navegación secundaria">
            <a href="{{ route('dashboard') }}" class="transition hover:text-violet-700">Espacio financiero</a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <a href="{{ route('espacio.show', $espacioActual) }}" class="transition hover:text-violet-700">
                {{ $espacioActual->nombre }}
            </a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <span class="text-slate-700" aria-current="page">Movimientos</span>
        </nav>

        <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 lg:text-3xl">Movimientos</h1>
                <p class="mt-1.5 text-sm text-slate-500">Registra ingresos y gastos rápidamente y conoce cómo afectan tu saldo.</p>
            </div>
            <button type="button" @click="abrirNuevo()"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 px-5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:-translate-y-0.5 hover:brightness-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 focus-visible:ring-offset-2">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Nuevo movimiento
            </button>
        </header>

        <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Resumen de movimientos">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Saldo actual</p>
                        <p class="mt-2 text-2xl font-extrabold text-slate-950" x-text="moneda(saldoActual)"></p>
                        <p class="mt-1 text-xs text-slate-400">Después de todos los movimientos</p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-violet-50 text-violet-600">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="6" width="18" height="14" rx="2" />
                            <path d="M16 10h5v6h-5a3 3 0 0 1 0-6Z" />
                        </svg>
                    </span>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Ingresos</p>
                        <p class="mt-2 text-2xl font-extrabold text-emerald-600" x-text="moneda(totalIngresos)"></p>
                        <p class="mt-1 text-xs text-slate-400" x-text="`${cantidadIngresos} registros en el historial`"></p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 19V5m-6 6 6-6 6 6" />
                        </svg>
                    </span>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Gastos</p>
                        <p class="mt-2 text-2xl font-extrabold text-rose-600" x-text="moneda(totalGastos)"></p>
                        <p class="mt-1 text-xs text-slate-400" x-text="`${cantidadGastos} registros en el historial`"></p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-rose-50 text-rose-600">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14m6-6-6 6-6-6" />
                        </svg>
                    </span>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Balance neto</p>
                        <p class="mt-2 text-2xl font-extrabold" :class="balanceNeto >= 0 ? 'text-cyan-600' : 'text-rose-600'"
                            x-text="monedaConSigno(balanceNeto)"></p>
                        <p class="mt-1 text-xs text-slate-400">Ingresos menos gastos</p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-cyan-50 text-cyan-600">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 17 9 12l4 4 7-9" />
                            <path d="M15 7h5v5" />
                        </svg>
                    </span>
                </div>
            </article>
        </section>

        <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"
            aria-labelledby="filtros-titulo">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end">
                <div class="min-w-0 flex-1">
                    <h2 id="filtros-titulo" class="text-sm font-bold text-slate-900">Buscar y filtrar</h2>
                    <div class="relative mt-2">
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-slate-400"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3-3" />
                        </svg>
                        <input type="search" x-model.debounce.250ms="filtros.buscar"
                            placeholder="Buscar por nombre, categoría, partida o método..."
                            class="h-11 w-full rounded-lg border-slate-300 pl-10 text-sm placeholder:text-slate-400 focus:border-violet-500 focus:ring-violet-500">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-3 xl:w-[620px]">
                    <label class="block">
                        <span class="mb-2 block text-xs font-semibold text-slate-500">Tipo</span>
                        <select x-model="filtros.tipo"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="todos">Todos</option>
                            <option value="ingreso">Ingresos</option>
                            <option value="gasto">Gastos</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-xs font-semibold text-slate-500">Categoría</span>
                        <select x-model="filtros.categoria_gasto_id"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="todas">Todas</option>
                            <template x-for="categoria in categorias" :key="categoria.id">
                                <option :value="categoria.id" x-text="categoria.nombre"></option>
                            </template>
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-xs font-semibold text-slate-500">Presupuesto</span>
                        <select x-model="filtros.presupuesto_id"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="todos">Todos</option>
                            <template x-for="presupuesto in presupuestos" :key="presupuesto.id">
                                <option :value="presupuesto.id" x-text="presupuesto.nombre"></option>
                            </template>
                        </select>
                    </label>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">
                <p class="text-xs text-slate-400"><span class="font-bold text-slate-600" x-text="movimientosFiltrados.length"></span> movimientos encontrados</p>
                <button type="button" @click="limpiarFiltros()"
                    class="text-xs font-bold text-violet-700 transition hover:text-violet-900">Limpiar filtros</button>
            </div>
        </section>

        <div class="mt-5 grid items-start gap-5 xl:grid-cols-[minmax(0,2fr)_320px]">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                aria-labelledby="historial-titulo">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div>
                        <h2 id="historial-titulo" class="text-lg font-bold text-slate-950">Historial de movimientos</h2>
                        <p class="mt-1 text-xs text-slate-400">Consulta, edita o elimina tus registros.</p>
                    </div>
                    <button type="button" class="hidden h-9 items-center gap-2 rounded-lg border border-slate-200 px-3 text-xs font-semibold text-slate-600 hover:bg-slate-50 sm:inline-flex">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12m-4-4 4 4 4-4" />
                            <path d="M5 21h14" />
                        </svg>
                        Exportar
                    </button>
                </div>

                <div class="overflow-x-auto" x-show="movimientosFiltrados.length">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="px-5 py-3 sm:px-6">Movimiento</th>
                                <th class="px-5 py-3">Fecha</th>
                                <th class="px-5 py-3">Método de pago</th>
                                <th class="px-5 py-3 text-right">Monto</th>
                                <th class="px-5 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="movimiento in movimientosFiltrados" :key="movimiento.id">
                                <tr class="transition hover:bg-slate-50/70">
                                    <td class="px-5 py-4 sm:px-6">
                                        <div class="flex min-w-52 items-center gap-3">
                                            <span class="grid size-10 shrink-0 place-items-center rounded-full"
                                                :class="movimiento.tipo === 'ingreso' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'">
                                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path x-show="movimiento.tipo === 'ingreso'" d="M12 19V5m-6 6 6-6 6 6" />
                                                    <path x-show="movimiento.tipo === 'gasto'" d="M12 5v14m6-6-6 6-6-6" />
                                                </svg>
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate font-semibold text-slate-800" x-text="movimiento.nombre"></p>
                                                <p class="mt-0.5 text-xs text-slate-400">
                                                    <span x-text="categoriaNombre(movimiento.categoria_gasto_id)"></span>
                                                    <template x-if="movimiento.presupuesto_id">
                                                        <span>
                                                            · <span x-text="presupuestoNombre(movimiento.presupuesto_id)"></span>
                                                            <template x-if="movimiento.partida_presupuesto_id">
                                                                <span> / <span x-text="partidaNombre(movimiento.partida_presupuesto_id)"></span></span>
                                                            </template>
                                                        </span>
                                                    </template>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-500" x-text="fechaCorta(movimiento.fecha_movimiento)"></td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-500" x-text="movimiento.metodo_pago || 'Sin especificar'"></td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right font-bold"
                                        :class="movimiento.tipo === 'ingreso' ? 'text-emerald-600' : 'text-rose-600'"
                                        x-text="`${movimiento.tipo === 'ingreso' ? '+' : '-'}${moneda(movimiento.monto)}`"></td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <button type="button" @click="abrirEditar(movimiento)"
                                                class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-violet-700 hover:bg-violet-50">Editar</button>
                                            <button type="button" @click="eliminar(movimiento)"
                                                class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50">Eliminar</button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div x-cloak x-show="!movimientosFiltrados.length" class="px-6 py-16 text-center">
                    <span class="mx-auto grid size-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3-3M8 11h6" />
                        </svg>
                    </span>
                    <p class="mt-4 font-bold text-slate-700">No encontramos movimientos</p>
                    <p class="mt-1 text-sm text-slate-400">Prueba cambiando o limpiando los filtros.</p>
                </div>
            </section>

            <aside class="space-y-5 xl:sticky xl:top-0">
                <section class="rounded-2xl border border-violet-100 bg-gradient-to-br from-violet-50 to-cyan-50 p-5">
                    <div class="flex items-center gap-3">
                        <span class="grid size-9 place-items-center rounded-full bg-white text-violet-600 shadow-sm">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 3v18M5 8h10.5a3.5 3.5 0 0 1 0 7H8.5a3.5 3.5 0 0 0 0 7H19" />
                            </svg>
                        </span>
                        <h2 class="font-bold text-slate-900">Así cambia tu saldo</h2>
                    </div>
                    <div class="mt-5 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Saldo inicial</span>
                            <span class="font-bold text-slate-800" x-text="moneda(saldoInicial)"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="inline-flex items-center gap-2 text-slate-500"><i class="size-2 rounded-full bg-emerald-500"></i>Ingresos</span>
                            <span class="font-bold text-emerald-600" x-text="`+${moneda(totalIngresos)}`"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="inline-flex items-center gap-2 text-slate-500"><i class="size-2 rounded-full bg-rose-500"></i>Gastos</span>
                            <span class="font-bold text-rose-600" x-text="`-${moneda(totalGastos)}`"></span>
                        </div>
                        <div class="border-t border-violet-100 pt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-700">Saldo actual</span>
                                <span class="text-lg font-extrabold text-violet-700" x-text="moneda(saldoActual)"></span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 rounded-xl bg-white/70 p-3 text-xs leading-5 text-slate-500">
                        Los ingresos aumentan tu saldo disponible; los gastos lo reducen. Mantener cada registro al día te da una lectura real de tus finanzas.
                    </p>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-bold text-slate-900">Distribución de gastos</h2>
                    <p class="mt-1 text-xs text-slate-400">Principales categorías registradas.</p>
                    <div class="mt-5 space-y-4">
                        <template x-for="item in distribucionGastos" :key="item.nombre">
                            <div>
                                <div class="mb-1.5 flex items-center justify-between gap-3 text-xs">
                                    <span class="font-semibold text-slate-600" x-text="item.nombre"></span>
                                    <span class="font-bold text-slate-800" x-text="`${item.porcentaje}%`"></span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-cyan-500"
                                        :style="`width: ${item.porcentaje}%`"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </aside>
        </div>

        <dialog x-ref="modalMovimiento" @close="cerrarModal()"
            class="w-[calc(100%-2rem)] max-w-xl rounded-2xl border-0 bg-white p-0 shadow-2xl backdrop:bg-slate-950/40">
            <form @submit.prevent="guardar()" class="p-5 sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950" x-text="editando ? 'Editar movimiento' : 'Nuevo movimiento'"></h2>
                        <p class="mt-1 text-xs text-slate-400">Esta es una demostración visual; los cambios no se guardan en la base de datos.</p>
                    </div>
                    <button type="button" @click="$refs.modalMovimiento.close()"
                        class="grid size-8 place-items-center rounded-full text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Cerrar">×</button>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Tipo de movimiento</span>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" value="ingreso" x-model="form.tipo" class="peer sr-only">
                                <span class="flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 text-sm font-bold text-slate-500 transition peer-checked:border-emerald-300 peer-checked:bg-emerald-50 peer-checked:text-emerald-700">
                                    <span>↑</span> Ingreso
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" value="gasto" x-model="form.tipo" class="peer sr-only">
                                <span class="flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 text-sm font-bold text-slate-500 transition peer-checked:border-rose-300 peer-checked:bg-rose-50 peer-checked:text-rose-700">
                                    <span>↓</span> Gasto
                                </span>
                            </label>
                        </div>
                    </div>
                    <label class="block sm:col-span-2">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Nombre <span class="text-rose-500">*</span></span>
                        <input type="text" x-model.trim="form.nombre" required maxlength="255" placeholder="Ej. Compra del supermercado"
                            class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Monto <span class="text-rose-500">*</span></span>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center font-semibold text-slate-400">$</span>
                            <input type="number" x-model.number="form.monto" min="0.01" step="0.01" required placeholder="0.00"
                                class="h-11 w-full rounded-lg border-slate-300 pl-8 text-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Fecha <span class="text-rose-500">*</span></span>
                        <input type="date" x-model="form.fecha_movimiento" required
                            class="h-11 w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Categoría <span class="font-normal text-slate-400">(opcional)</span></span>
                        <select x-model="form.categoria_gasto_id"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="">Sin categoría</option>
                            <template x-for="categoria in categorias" :key="categoria.id">
                                <option :value="categoria.id" x-text="categoria.nombre"></option>
                            </template>
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Método de pago <span class="font-normal text-slate-400">(opcional)</span></span>
                        <select x-model="form.metodo_pago"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="">Sin especificar</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Tarjeta de débito">Tarjeta de débito</option>
                            <option value="Tarjeta de crédito">Tarjeta de crédito</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Presupuesto <span class="font-normal text-slate-400">(opcional)</span></span>
                        <select x-model="form.presupuesto_id" @change="sincronizarPartida()"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="">Sin presupuesto</option>
                            <template x-for="presupuesto in presupuestos" :key="presupuesto.id">
                                <option :value="presupuesto.id" x-text="presupuesto.nombre"></option>
                            </template>
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Partida <span class="font-normal text-slate-400">(opcional)</span></span>
                        <select x-model="form.partida_presupuesto_id" :disabled="!form.presupuesto_id"
                            class="h-11 w-full rounded-lg border-slate-300 bg-white text-sm disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 focus:border-violet-500 focus:ring-violet-500">
                            <option value="" x-text="form.presupuesto_id ? 'Sin partida' : 'Selecciona primero un presupuesto'"></option>
                            <template x-for="partida in partidasDelFormulario" :key="partida.id">
                                <option :value="partida.id" x-text="partida.nombre"></option>
                            </template>
                        </select>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Notas <span class="font-normal text-slate-400">(opcional)</span></span>
                        <textarea x-model.trim="form.notas" rows="3" placeholder="Agrega información para recordar este movimiento"
                            class="w-full resize-none rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                    </label>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" @click="$refs.modalMovimiento.close()"
                        class="h-10 rounded-lg border border-slate-200 px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancelar</button>
                    <button type="submit"
                        class="h-10 rounded-lg bg-violet-600 px-4 text-sm font-semibold text-white hover:bg-violet-700"
                        x-text="editando ? 'Guardar cambios' : 'Registrar movimiento'"></button>
                </div>
            </form>
        </dialog>
    </div>

    <script>
        function movimientosDemo() {
            return {
                saldoInicial: 12500,
                editando: null,
                siguienteId: 9,
                filtros: { buscar: '', tipo: 'todos', categoria_gasto_id: 'todas', presupuesto_id: 'todos' },
                categorias: [
                    { id: 1, nombre: 'Alimentación' },
                    { id: 2, nombre: 'Vivienda' },
                    { id: 3, nombre: 'Transporte' },
                    { id: 4, nombre: 'Servicios' },
                    { id: 5, nombre: 'Salud' },
                    { id: 6, nombre: 'Entretenimiento' },
                    { id: 7, nombre: 'Nómina' },
                    { id: 8, nombre: 'Otros' },
                ],
                presupuestos: [
                    { id: 1, nombre: 'Presupuesto Julio 2026' },
                    { id: 2, nombre: 'Presupuesto Junio 2026' },
                ],
                partidas: [
                    { id: 1, presupuesto_id: 1, nombre: 'Alimentación' },
                    { id: 2, presupuesto_id: 1, nombre: 'Renta y vivienda' },
                    { id: 3, presupuesto_id: 1, nombre: 'Servicios del hogar' },
                    { id: 4, presupuesto_id: 2, nombre: 'Transporte' },
                    { id: 5, presupuesto_id: 2, nombre: 'Salud' },
                    { id: 6, presupuesto_id: 2, nombre: 'Entretenimiento' },
                ],
                form: {},
                movimientos: [
                    { id: 1, tipo: 'ingreso', nombre: 'Pago de nómina', categoria_gasto_id: 7, presupuesto_id: null, partida_presupuesto_id: null, metodo_pago: 'Transferencia', monto: 18500, fecha_movimiento: '2026-07-14', notas: 'Pago quincenal' },
                    { id: 2, tipo: 'gasto', nombre: 'Supermercado semanal', categoria_gasto_id: 1, presupuesto_id: 1, partida_presupuesto_id: 1, metodo_pago: 'Tarjeta de crédito', monto: 1680.50, fecha_movimiento: '2026-07-13', notas: '' },
                    { id: 3, tipo: 'gasto', nombre: 'Renta del departamento', categoria_gasto_id: 2, presupuesto_id: 1, partida_presupuesto_id: 2, metodo_pago: 'Transferencia', monto: 6500, fecha_movimiento: '2026-07-05', notas: 'Renta de julio' },
                    { id: 4, tipo: 'gasto', nombre: 'Servicio de internet', categoria_gasto_id: 4, presupuesto_id: 1, partida_presupuesto_id: 3, metodo_pago: 'Tarjeta de crédito', monto: 599, fecha_movimiento: '2026-07-04', notas: '' },
                    { id: 5, tipo: 'ingreso', nombre: 'Proyecto independiente', categoria_gasto_id: 8, presupuesto_id: null, partida_presupuesto_id: null, metodo_pago: 'Transferencia', monto: 4200, fecha_movimiento: '2026-07-02', notas: 'Diseño de sitio web' },
                    { id: 6, tipo: 'gasto', nombre: 'Gasolina', categoria_gasto_id: 3, presupuesto_id: 2, partida_presupuesto_id: 4, metodo_pago: 'Efectivo', monto: 850, fecha_movimiento: '2026-06-28', notas: '' },
                    { id: 7, tipo: 'gasto', nombre: 'Consulta médica', categoria_gasto_id: 5, presupuesto_id: 2, partida_presupuesto_id: 5, metodo_pago: 'Tarjeta de crédito', monto: 900, fecha_movimiento: '2026-06-22', notas: '' },
                    { id: 8, tipo: 'gasto', nombre: 'Cena con amigos', categoria_gasto_id: 6, presupuesto_id: 2, partida_presupuesto_id: 6, metodo_pago: 'Tarjeta de crédito', monto: 780, fecha_movimiento: '2026-06-18', notas: '' },
                ],
                get movimientosFiltrados() {
                    const texto = this.filtros.buscar.toLocaleLowerCase('es');
                    return this.movimientos.filter((movimiento) => {
                        const coincideTexto = !texto || [
                            movimiento.nombre,
                            this.categoriaNombre(movimiento.categoria_gasto_id),
                            this.presupuestoNombre(movimiento.presupuesto_id),
                            this.partidaNombre(movimiento.partida_presupuesto_id),
                            movimiento.metodo_pago || '',
                        ].some((valor) => valor.toLocaleLowerCase('es').includes(texto));
                        const coincideTipo = this.filtros.tipo === 'todos' || movimiento.tipo === this.filtros.tipo;
                        const coincideCategoria = this.filtros.categoria_gasto_id === 'todas'
                            || Number(movimiento.categoria_gasto_id) === Number(this.filtros.categoria_gasto_id);
                        const coincidePresupuesto = this.filtros.presupuesto_id === 'todos'
                            || Number(movimiento.presupuesto_id) === Number(this.filtros.presupuesto_id);
                        return coincideTexto && coincideTipo && coincideCategoria && coincidePresupuesto;
                    }).sort((a, b) => b.fecha_movimiento.localeCompare(a.fecha_movimiento));
                },
                get totalIngresos() {
                    return this.movimientos.filter((item) => item.tipo === 'ingreso').reduce((total, item) => total + Number(item.monto), 0);
                },
                get totalGastos() {
                    return this.movimientos.filter((item) => item.tipo === 'gasto').reduce((total, item) => total + Number(item.monto), 0);
                },
                get cantidadIngresos() { return this.movimientos.filter((item) => item.tipo === 'ingreso').length; },
                get cantidadGastos() { return this.movimientos.filter((item) => item.tipo === 'gasto').length; },
                get balanceNeto() { return this.totalIngresos - this.totalGastos; },
                get saldoActual() { return this.saldoInicial + this.balanceNeto; },
                get distribucionGastos() {
                    if (!this.totalGastos) return [];
                    const totales = this.movimientos.filter((item) => item.tipo === 'gasto').reduce((resultado, item) => {
                        const categoria = this.categoriaNombre(item.categoria_gasto_id);
                        resultado[categoria] = (resultado[categoria] || 0) + Number(item.monto);
                        return resultado;
                    }, {});
                    return Object.entries(totales)
                        .map(([nombre, total]) => ({ nombre, porcentaje: Math.round((total / this.totalGastos) * 100) }))
                        .sort((a, b) => b.porcentaje - a.porcentaje)
                        .slice(0, 4);
                },
                get partidasDelFormulario() {
                    return this.partidas.filter((partida) => Number(partida.presupuesto_id) === Number(this.form.presupuesto_id));
                },
                formularioVacio() {
                    return {
                        tipo: 'gasto',
                        nombre: '',
                        categoria_gasto_id: '',
                        presupuesto_id: '',
                        partida_presupuesto_id: '',
                        monto: '',
                        fecha_movimiento: '2026-07-14',
                        metodo_pago: '',
                        notas: '',
                    };
                },
                abrirNuevo() {
                    this.editando = null;
                    this.form = this.formularioVacio();
                    this.$refs.modalMovimiento.showModal();
                },
                abrirEditar(movimiento) {
                    this.editando = movimiento.id;
                    this.form = { ...movimiento };
                    this.$refs.modalMovimiento.showModal();
                },
                sincronizarPartida() {
                    const pertenece = this.partidas.some((partida) =>
                        Number(partida.id) === Number(this.form.partida_presupuesto_id)
                        && Number(partida.presupuesto_id) === Number(this.form.presupuesto_id)
                    );
                    if (!pertenece) this.form.partida_presupuesto_id = '';
                },
                cerrarModal() {
                    this.editando = null;
                    this.form = this.formularioVacio();
                },
                guardar() {
                    const datos = { ...this.form, monto: Number(this.form.monto) };
                    const fueEdicion = Boolean(this.editando);
                    if (this.editando) {
                        const indice = this.movimientos.findIndex((item) => item.id === this.editando);
                        this.movimientos[indice] = { ...datos, id: this.editando };
                    } else {
                        this.movimientos.push({ ...datos, id: this.siguienteId++ });
                    }
                    this.$refs.modalMovimiento.close();
                    window.showSuccessToast?.(fueEdicion ? 'Movimiento actualizado en la demostración.' : 'Movimiento agregado a la demostración.');
                },
                eliminar(movimiento) {
                    if (!window.confirm(`¿Eliminar “${movimiento.nombre}” de esta demostración?`)) return;
                    this.movimientos = this.movimientos.filter((item) => item.id !== movimiento.id);
                    window.showSuccessToast?.('Movimiento eliminado de la demostración.');
                },
                limpiarFiltros() {
                    this.filtros = { buscar: '', tipo: 'todos', categoria_gasto_id: 'todas', presupuesto_id: 'todos' };
                },
                categoriaNombre(id) {
                    return this.categorias.find((categoria) => Number(categoria.id) === Number(id))?.nombre || 'Sin categoría';
                },
                partidaNombre(id) {
                    return this.partidas.find((partida) => Number(partida.id) === Number(id))?.nombre || 'Sin partida';
                },
                presupuestoNombre(id) {
                    return this.presupuestos.find((presupuesto) => Number(presupuesto.id) === Number(id))?.nombre || 'Sin presupuesto';
                },
                moneda(valor) {
                    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: @js($espacioActual->moneda ?? 'MXN') }).format(Number(valor) || 0);
                },
                monedaConSigno(valor) {
                    const numero = Number(valor) || 0;
                    return `${numero >= 0 ? '+' : '-'}${this.moneda(Math.abs(numero))}`;
                },
                fechaCorta(fecha) {
                    return new Intl.DateTimeFormat('es-MX', { day: '2-digit', month: 'short', year: 'numeric', timeZone: 'UTC' })
                        .format(new Date(`${fecha}T00:00:00Z`));
                },
                init() { this.form = this.formularioVacio(); },
            };
        }
    </script>
@endsection
