<?php

namespace App\Http\Controllers;

use App\Models\EspacioFinanciero;
use App\Models\Movimientos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EspacioFinanciero $espacio)
    {
        abort_unless($espacio->user_id === Auth::id(), 403);
        return view('movimientos.index', [
            'espacioActual' => $espacio,
            'espacios' => EspacioFinanciero::where('user_id', Auth::id())->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimientos $movimientos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimientos $movimientos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movimientos $movimientos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimientos $movimientos)
    {
        //
    }
}
