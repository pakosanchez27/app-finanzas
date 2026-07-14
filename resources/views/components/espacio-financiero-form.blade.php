@props([
    'tiposEspacios',
    'espacio' => null,
    'dialogId' => 'crear-espacio-financiero',
])

@php
    $isEditing = $espacio !== null;
    $action = $isEditing
        ? route('dashboard.espacios-financieros.update', $espacio)
        : route('dashboard.espacios-financieros.store');
    $title = $isEditing ? 'Editar espacio financiero' : 'Crear espacio financiero';
    $description = $isEditing
        ? 'Actualiza la información de tu espacio.'
        : 'Configura la información básica de tu nuevo espacio.';
    $submitLabel = $isEditing ? 'Guardar cambios' : 'Crear espacio';
    $nombre = old('nombre', $espacio?->nombre);
    $tipoSeleccionado = old('tipo', $espacio?->tipo);
    $descripcion = old('descripcion', $espacio?->descripcion);
    $monedaSeleccionada = old('moneda', $espacio?->moneda);
@endphp

<el-dialog>
    <dialog id="{{ $dialogId }}" aria-labelledby="{{ $dialogId }}-title"
        class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
        <el-dialog-backdrop
            class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

        <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
            <el-dialog-panel
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @if ($isEditing)
                        @method('PATCH')
                    @endif

                    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 id="{{ $dialogId }}-title" class="text-lg font-bold text-slate-900">{{ $title }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                            </div>
                            <button type="button" command="close" commandfor="{{ $dialogId }}"
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
                            <label for="{{ $dialogId }}-nombre" class="mb-1.5 block text-sm font-semibold text-slate-700">Nombre</label>
                            <input id="{{ $dialogId }}-nombre" name="nombre" type="text" value="{{ $nombre }}" required
                                placeholder="Ej. Presupuesto familiar"
                                class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                            @error('nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="{{ $dialogId }}-tipo" class="mb-1.5 block text-sm font-semibold text-slate-700">Tipo</label>
                            <select id="{{ $dialogId }}-tipo" name="tipo" required
                                class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="" disabled @selected(!$tipoSeleccionado)>Selecciona un tipo</option>
                                @foreach ($tiposEspacios as $tipo)
                                    <option value="{{ $tipo->value }}" @selected($tipoSeleccionado === $tipo->value)>
                                        {{ ucfirst($tipo->value) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div x-data="{ count: {{ strlen($descripcion ?? '') }} }">
                            <div class="mb-1.5 flex items-center justify-between gap-4">
                                <label for="{{ $dialogId }}-descripcion" class="text-sm font-semibold text-slate-700">Descripción</label>
                                <span class="text-xs text-slate-400" :class="count >= 250 && 'text-red-500'" x-text="`${count}/250`"></span>
                            </div>
                            <textarea id="{{ $dialogId }}-descripcion" name="descripcion" rows="4" maxlength="250" required
                                @input="count = $event.target.value.length" placeholder="Describe brevemente el propósito de este espacio"
                                class="block w-full resize-none rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ $descripcion }}</textarea>
                            @error('descripcion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="{{ $dialogId }}-moneda" class="mb-1.5 block text-sm font-semibold text-slate-700">Moneda</label>
                            <select id="{{ $dialogId }}-moneda" name="moneda" required
                                class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="" disabled @selected(!$monedaSeleccionada)>Selecciona una moneda</option>
                                <option value="mxn" @selected($monedaSeleccionada === 'mxn')>MXN - Peso mexicano</option>
                                <option value="usd" @selected($monedaSeleccionada === 'usd')>USD - Dólar estadounidense</option>
                                <option value="euro" @selected($monedaSeleccionada === 'euro')>EUR - Euro</option>
                            </select>
                            @error('moneda') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <button type="button" command="close" commandfor="{{ $dialogId }}"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#087fe5] via-[#16bfc0] to-[#70d95c] px-5 text-sm font-semibold text-white shadow-md shadow-cyan-200/60 transition hover:brightness-105">
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>
