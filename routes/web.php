<?php

use App\Http\Controllers\EspacioFinancieroController;
use App\Http\Controllers\MovimientosController;
use App\Http\Controllers\PeriodoPresupuestoController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ProfileController;
use App\Models\EspacioFinanciero;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [EspacioFinancieroController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::post('/espacios-financieros', [EspacioFinancieroController::class, 'store'])
        ->name('dashboard.espacios-financieros.store');

    Route::patch('/espacios-financieros/{espacioFinanciero}', [EspacioFinancieroController::class, 'update'])
        ->name('dashboard.espacios-financieros.update');

    Route::get('/espacios-financieros/{espacio}', [EspacioFinancieroController::class, 'show'])->name('espacio.show');
    Route::get('/espacios-financieros/{espacio}/presupuestos', [PresupuestoController::class, 'index'])->name('presupuesto.index');
    Route::get('/espacios-financieros/{espacio}/presupuestos/crear', [PresupuestoController::class, 'create'])->name('presupuesto.create');
    Route::post('/espacios-financieros/{espacio}/presupuestos', [PresupuestoController::class, 'store'])->name('presupuesto.store');
    Route::get('/espacios-financieros/{espacio}/presupuestos/{presupuesto}/editar', [PresupuestoController::class, 'edit'])->name('presupuesto.edit');
    Route::put('/espacios-financieros/{espacio}/presupuestos/{presupuesto}', [PresupuestoController::class, 'update'])->name('presupuesto.update');
    Route::delete('/espacios-financieros/{espacio}/presupuestos/{presupuesto}', [PresupuestoController::class, 'destroy'])->name('presupuesto.destroy');
    Route::post('/espacios-financieros/{espacio}/presupuestos/{presupuesto}/partidas', [PresupuestoController::class, 'storePartida'])->name('presupuesto.partidas.store');
    Route::put('/espacios-financieros/{espacio}/presupuestos/{presupuesto}/partidas/{partida}', [PresupuestoController::class, 'updatePartida'])->name('presupuesto.partidas.update');
    Route::delete('/espacios-financieros/{espacio}/presupuestos/{presupuesto}/partidas/{partida}', [PresupuestoController::class, 'destroyPartida'])->name('presupuesto.partidas.destroy');
    Route::get('/espacios-financieros/{espacio}/periodos/{periodo}/editar', [PeriodoPresupuestoController::class, 'edit'])->name('periodo.edit');
    Route::put('/espacios-financieros/{espacio}/periodos/{periodo}', [PeriodoPresupuestoController::class, 'update'])->name('periodo.update');

    Route::get('/espacios-financieros/{espacio}/movimientos', [MovimientosController::class, 'index'])->name('movimiento.index');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
