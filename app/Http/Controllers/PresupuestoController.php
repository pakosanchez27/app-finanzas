<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresupuestoRequest;
use App\Http\Requests\PartidaPresupuestoRequest;
use App\Models\EspacioFinanciero;
use App\Models\PartidaPresupuesto;
use App\Models\PeriodoPresupuesto;
use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresupuestoController extends Controller
{
    public function index(Request $request, EspacioFinanciero $espacio)
    {
        abort_unless($espacio->user_id === Auth::id(), 403);

        $periodos = $espacio->periodosPresupuesto()
            ->orderByDesc('fecha_inicio')
            ->get();

        $periodoSeleccionado = $periodos->firstWhere('id', (int) $request->query('periodo'))
            ?? $periodos->firstWhere('estado', 'activo')
            ?? $periodos->first();

        $presupuestos = $espacio->presupuestos()
            ->with(['periodoPresupuesto', 'partidas.categoriaGasto'])
            ->when(
                $periodoSeleccionado,
                fn ($query) => $query->where('periodo_presupuesto_id', $periodoSeleccionado->id),
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->orderBy('nombre')
            ->get();

        $partidas = $presupuestos->flatMap->partidas;

        return view('presupuestos.index', [
            'espacioActual' => $espacio,
            'espacios' => EspacioFinanciero::where('user_id', Auth::id())->get(),
            'periodos' => $periodos,
            'periodoSeleccionado' => $periodoSeleccionado,
            'presupuestos' => $presupuestos,
            'presupuestoActual' => $presupuestos->first(),
            'partidas' => $partidas,
            'categorias' => $espacio->categoriasGasto()->orderBy('nombre')->get(),
            'totales' => [
                'ingreso' => $presupuestos->sum('ingreso_total'),
                'planeado' => $presupuestos->sum('gasto_planeado_total'),
                'real' => $presupuestos->sum('gasto_real_total'),
                'disponible_estimado' => $presupuestos->sum('disponible_estimado'),
                'disponible_real' => $presupuestos->sum('disponible_real'),
            ],
        ]);
    }

    public function create(Request $request, EspacioFinanciero $espacio)
    {
        abort_unless($espacio->user_id === Auth::id(), 403);

        $periodos = $espacio->periodosPresupuesto()
            ->whereIn('estado', ['borrador', 'activo'])
            ->whereDoesntHave('presupuesto')
            ->orderByDesc('fecha_inicio')
            ->get();

        return view('presupuestos.create', [
            'espacioActual' => $espacio,
            'espacios' => EspacioFinanciero::where('user_id', Auth::id())->get(),
            'categorias' => $espacio->categoriasGasto()->orderBy('nombre')->get(),
            'periodos' => $periodos,
            'periodoSugerido' => $periodos->firstWhere('id', (int) $request->query('periodo')),
        ]);
    }

    public function edit(EspacioFinanciero $espacio, Presupuesto $presupuesto)
    {
        $this->autorizarPresupuesto($espacio, $presupuesto);
        $presupuesto->load('partidas');

        return view('presupuestos.create', [
            'espacioActual' => $espacio,
            'espacios' => EspacioFinanciero::where('user_id', Auth::id())->get(),
            'categorias' => $espacio->categoriasGasto()->orderBy('nombre')->get(),
            'periodos' => $espacio->periodosPresupuesto()
                ->where(function ($query) use ($presupuesto): void {
                    $query->where(function ($query): void {
                        $query->whereIn('estado', ['borrador', 'activo'])
                            ->whereDoesntHave('presupuesto');
                    })->orWhere('id', $presupuesto->periodo_presupuesto_id);
                })
                ->orderByDesc('fecha_inicio')
                ->get(),
            'presupuesto' => $presupuesto,
        ]);
    }

    public function store(PresupuestoRequest $request, EspacioFinanciero $espacio)
    {
        $data = $request->validated();
        $gastoPlaneado = collect($data['partidas'])->sum('monto_planeado');

        [$periodo, $presupuesto] = DB::transaction(function () use ($data, $espacio, $gastoPlaneado): array {
            $periodo = $data['periodo_modo'] === 'existente'
                ? $espacio->periodosPresupuesto()->findOrFail($data['periodo_presupuesto_id'])
                : PeriodoPresupuesto::create([
                    'espacio_financiero_id' => $espacio->id,
                    'nombre' => $data['periodo_nombre'],
                    'tipo' => $data['periodo_tipo'],
                    'fecha_inicio' => $data['fecha_inicio'],
                    'fecha_fin' => $data['fecha_fin'],
                    'ingreso_estimado' => $data['ingreso_estimado'],
                    'estado' => 'activo',
                ]);

            $ingreso = (float) $periodo->ingreso_estimado;
            $proporcionGasto = $ingreso > 0 ? $gastoPlaneado / $ingreso : 1;
            $riesgo = $proporcionGasto <= 0.7
                ? 'bajo'
                : ($proporcionGasto <= 0.9 ? 'medio' : 'alto');

            $presupuesto = Presupuesto::create([
                'espacio_financiero_id' => $espacio->id,
                'periodo_presupuesto_id' => $periodo->id,
                'nombre' => $data['nombre'],
                'ingreso_total' => $ingreso,
                'gasto_planeado_total' => $gastoPlaneado,
                'gasto_real_total' => 0,
                'disponible_estimado' => $ingreso - $gastoPlaneado,
                'disponible_real' => $ingreso,
                'riesgo' => $riesgo,
                'estado' => 'activo',
            ]);

            $presupuesto->partidas()->createMany(
                collect($data['partidas'])->map(fn (array $partida): array => [
                    'categorias_gasto_id' => $partida['categorias_gasto_id'],
                    'nombre' => $partida['nombre'],
                    'tipo' => 'gasto',
                    'monto_planeado' => $partida['monto_planeado'],
                    'monto_real' => 0,
                    'fecha_objetivo' => $partida['fecha_objetivo'] ?: null,
                    'estado' => 'pendiente',
                ])->all(),
            );

            return [$periodo, $presupuesto];
        });

        return redirect()
            ->route('presupuesto.index', [
                'espacio' => $espacio,
                'periodo' => $periodo->id,
            ])
            ->with('toast_success', 'El presupuesto se creó correctamente.');
    }

    public function update(
        PresupuestoRequest $request,
        EspacioFinanciero $espacio,
        Presupuesto $presupuesto,
    ) {
        $this->autorizarPresupuesto($espacio, $presupuesto);

        $data = $request->validated();
        $gastoPlaneado = collect($data['partidas'])->sum('monto_planeado');

        $periodo = DB::transaction(function () use ($data, $espacio, $presupuesto, $gastoPlaneado) {
            $periodo = $data['periodo_modo'] === 'existente'
                ? $espacio->periodosPresupuesto()->findOrFail($data['periodo_presupuesto_id'])
                : PeriodoPresupuesto::create([
                    'espacio_financiero_id' => $espacio->id,
                    'nombre' => $data['periodo_nombre'],
                    'tipo' => $data['periodo_tipo'],
                    'fecha_inicio' => $data['fecha_inicio'],
                    'fecha_fin' => $data['fecha_fin'],
                    'ingreso_estimado' => $data['ingreso_estimado'],
                    'estado' => 'activo',
                ]);

            $idsConservados = collect($data['partidas'])
                ->pluck('id')
                ->filter()
                ->map(fn ($id) => (int) $id);

            $presupuesto->partidas()
                ->whereNotIn('id', $idsConservados->all())
                ->delete();

            foreach ($data['partidas'] as $partidaData) {
                $valores = [
                    'categorias_gasto_id' => $partidaData['categorias_gasto_id'],
                    'nombre' => $partidaData['nombre'],
                    'tipo' => 'gasto',
                    'monto_planeado' => $partidaData['monto_planeado'],
                    'fecha_objetivo' => $partidaData['fecha_objetivo'] ?: null,
                ];

                if (! empty($partidaData['id'])) {
                    $presupuesto->partidas()->findOrFail($partidaData['id'])->update($valores);
                } else {
                    $presupuesto->partidas()->create([
                        ...$valores,
                        'monto_real' => 0,
                        'estado' => 'pendiente',
                    ]);
                }
            }

            $ingreso = (float) $periodo->ingreso_estimado;
            $gastoReal = (float) $presupuesto->partidas()->sum('monto_real');
            $proporcionGasto = $ingreso > 0 ? $gastoPlaneado / $ingreso : 1;
            $riesgo = $proporcionGasto <= 0.7
                ? 'bajo'
                : ($proporcionGasto <= 0.9 ? 'medio' : 'alto');

            $presupuesto->update([
                'periodo_presupuesto_id' => $periodo->id,
                'nombre' => $data['nombre'],
                'ingreso_total' => $ingreso,
                'gasto_planeado_total' => $gastoPlaneado,
                'gasto_real_total' => $gastoReal,
                'disponible_estimado' => $ingreso - $gastoPlaneado,
                'disponible_real' => $ingreso - $gastoReal,
                'riesgo' => $riesgo,
            ]);

            return $periodo;
        });

        return redirect()
            ->route('presupuesto.index', [
                'espacio' => $espacio,
                'periodo' => $periodo->id,
            ])
            ->with('toast_success', 'El presupuesto se actualizó correctamente.');
    }

    public function destroy(EspacioFinanciero $espacio, Presupuesto $presupuesto)
    {
        $this->autorizarPresupuesto($espacio, $presupuesto);
        $periodoId = $presupuesto->periodo_presupuesto_id;

        DB::transaction(function () use ($presupuesto): void {
            $presupuesto->partidas()->delete();
            $presupuesto->delete();
        });

        return redirect()
            ->route('presupuesto.index', [
                'espacio' => $espacio,
                'periodo' => $periodoId,
            ])
            ->with('toast_success', 'El presupuesto se eliminó correctamente.');
    }

    public function storePartida(
        PartidaPresupuestoRequest $request,
        EspacioFinanciero $espacio,
        Presupuesto $presupuesto,
    ) {
        $this->autorizarPresupuesto($espacio, $presupuesto);

        DB::transaction(function () use ($request, $presupuesto): void {
            $presupuesto->partidas()->create([
                ...$request->validated(),
                'tipo' => 'gasto',
                'monto_real' => 0,
                'estado' => 'pendiente',
            ]);

            $this->recalcularPresupuesto($presupuesto);
        });

        return redirect()
            ->route('presupuesto.index', [
                'espacio' => $espacio,
                'periodo' => $presupuesto->periodo_presupuesto_id,
            ])
            ->with('toast_success', 'La partida se agregó correctamente.');
    }

    public function updatePartida(
        PartidaPresupuestoRequest $request,
        EspacioFinanciero $espacio,
        Presupuesto $presupuesto,
        PartidaPresupuesto $partida,
    ) {
        $this->autorizarPartida($espacio, $presupuesto, $partida);

        DB::transaction(function () use ($request, $presupuesto, $partida): void {
            $partida->update($request->validated());
            $this->recalcularPresupuesto($presupuesto);
        });

        return redirect()
            ->route('presupuesto.index', [
                'espacio' => $espacio,
                'periodo' => $presupuesto->periodo_presupuesto_id,
            ])
            ->with('toast_success', 'La partida se actualizó correctamente.');
    }

    public function destroyPartida(
        EspacioFinanciero $espacio,
        Presupuesto $presupuesto,
        PartidaPresupuesto $partida,
    ) {
        $this->autorizarPartida($espacio, $presupuesto, $partida);

        DB::transaction(function () use ($presupuesto, $partida): void {
            $partida->delete();
            $this->recalcularPresupuesto($presupuesto);
        });

        return redirect()
            ->route('presupuesto.index', [
                'espacio' => $espacio,
                'periodo' => $presupuesto->periodo_presupuesto_id,
            ])
            ->with('toast_success', 'La partida se eliminó correctamente.');
    }

    private function recalcularPresupuesto(Presupuesto $presupuesto): void
    {
        $presupuesto->loadMissing('periodoPresupuesto');

        $ingreso = (float) $presupuesto->periodoPresupuesto->ingreso_estimado;
        $gastoPlaneado = (float) $presupuesto->partidas()->sum('monto_planeado');
        $gastoReal = (float) $presupuesto->partidas()->sum('monto_real');
        $proporcion = $ingreso > 0 ? $gastoPlaneado / $ingreso : 1;

        $presupuesto->update([
            'ingreso_total' => $ingreso,
            'gasto_planeado_total' => $gastoPlaneado,
            'gasto_real_total' => $gastoReal,
            'disponible_estimado' => $ingreso - $gastoPlaneado,
            'disponible_real' => $ingreso - $gastoReal,
            'riesgo' => $proporcion <= 0.7
                ? 'bajo'
                : ($proporcion <= 0.9 ? 'medio' : 'alto'),
        ]);
    }

    private function autorizarPresupuesto(EspacioFinanciero $espacio, Presupuesto $presupuesto): void
    {
        abort_unless(
            $espacio->user_id === Auth::id()
                && $presupuesto->espacio_financiero_id === $espacio->id,
            403,
        );
    }

    private function autorizarPartida(
        EspacioFinanciero $espacio,
        Presupuesto $presupuesto,
        PartidaPresupuesto $partida,
    ): void {
        $this->autorizarPresupuesto($espacio, $presupuesto);
        abort_unless($partida->presupuesto_id === $presupuesto->id, 403);
    }
}
