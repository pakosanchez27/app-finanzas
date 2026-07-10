<?php

use App\Http\Controllers\EspacioFinancieroController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [EspacioFinancieroController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/espacios-financieros', [EspacioFinancieroController::class, 'store'])->name('dashboard.espacios-financieros.store');
    Route::get('/espacios-financieros/{espacioFinanciero}/home', [EspacioFinancieroController::class, 'show'])->name('dashboard.espacios-financieros.show');
    Route::get('/espacios-financieros/{id}/edit', [EspacioFinancieroController::class, 'edit'])->name('espacios-financieros.edit');
    Route::patch('/espacios-financieros/{espacioFinanciero}', [EspacioFinancieroController::class, 'update'])->name('dashboard.espacios-financieros.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
