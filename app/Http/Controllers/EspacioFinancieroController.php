<?php

namespace App\Http\Controllers;

use App\Http\Requests\EspacioRequest;
use App\Models\EspacioFinanciero;
use App\TipoEspacios;
use Illuminate\Support\Facades\Auth;

class EspacioFinancieroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $id_user = Auth::user()->id;

        $espacio = EspacioFinanciero::where('user_id', $id_user)->get();

        return view('dashboard', [
            'tiposEspacios' => TipoEspacios::cases(),
            'espacios' => $espacio,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EspacioRequest $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(EspacioRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        EspacioFinanciero::create($data);

        return redirect()
            ->route('dashboard')
            ->with('toast_success', 'El espacio financiero se creo correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EspacioFinanciero $espacio)
    {
        abort_unless($espacio->user_id === Auth::id(), 403);

        return view('espacios.index', [
            'espacioActual' => $espacio,
            'espacios' => EspacioFinanciero::where('user_id', Auth::id())->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EspacioFinanciero $espacioFinanciero)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EspacioRequest $request, EspacioFinanciero $espacioFinanciero)
    {
        abort_unless($espacioFinanciero->user_id === $request->user()->id, 403);

        $espacioFinanciero->update($request->validated());

        return redirect()
            ->route('dashboard')
            ->with('toast_success', 'El espacio financiero se actualizo correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EspacioFinanciero $espacioFinanciero)
    {
        //
    }
}
