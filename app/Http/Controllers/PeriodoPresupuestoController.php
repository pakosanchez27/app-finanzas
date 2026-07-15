<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeriodoPresupuestoRequest;
use App\Models\EspacioFinanciero;
use App\Models\PeriodoPresupuesto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriodoPresupuestoController extends Controller
{
    public function edit(EspacioFinanciero $espacio, PeriodoPresupuesto $periodo)
    {
        $this->autorizarPeriodo($espacio, $periodo);

        return view('presupuestos.periodos.edit', [
            'espacioActual' => $espacio,
            'espacios' => EspacioFinanciero::where('user_id', Auth::id())->get(),
            'periodo' => $periodo,
        ]);
    }

    public function update(
        PeriodoPresupuestoRequest $request,
        EspacioFinanciero $espacio,
        PeriodoPresupuesto $periodo,
    ) {
        DB::transaction(function () use ($request, $periodo): void {
            $periodo->update($request->validated());

            if ($presupuesto = $periodo->presupuesto) {
                $ingreso = (float) $periodo->ingreso_estimado;
                $gastoPlaneado = (float) $presupuesto->gasto_planeado_total;
                $gastoReal = (float) $presupuesto->gasto_real_total;
                $proporcion = $ingreso > 0 ? $gastoPlaneado / $ingreso : 1;

                $presupuesto->update([
                    'ingreso_total' => $ingreso,
                    'disponible_estimado' => $ingreso - $gastoPlaneado,
                    'disponible_real' => $ingreso - $gastoReal,
                    'riesgo' => $proporcion <= 0.7
                        ? 'bajo'
                        : ($proporcion <= 0.9 ? 'medio' : 'alto'),
                ]);
            }
        });

        return redirect()
            ->route('presupuesto.index', [
                'espacio' => $espacio,
                'periodo' => $periodo->id,
            ])
            ->with('toast_success', 'El periodo se actualizó correctamente.');
    }

    private function autorizarPeriodo(EspacioFinanciero $espacio, PeriodoPresupuesto $periodo): void
    {
        abort_unless(
            $espacio->user_id === Auth::id()
                && $periodo->espacio_financiero_id === $espacio->id,
            403,
        );
    }
}
