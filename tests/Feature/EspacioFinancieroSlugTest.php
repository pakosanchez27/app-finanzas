<?php

use App\Models\EspacioFinanciero;
use App\Models\User;

function createEspacioFinanciero(User $user, string $nombre): EspacioFinanciero
{
    return EspacioFinanciero::create([
        'user_id' => $user->id,
        'nombre' => $nombre,
        'tipo' => 'personal',
        'descripcion' => 'Espacio para probar la generación de slugs.',
        'moneda' => 'mxn',
    ]);
}

it('genera un slug único al crear un espacio financiero', function () {
    $user = User::factory()->create();

    $first = createEspacioFinanciero($user, 'Presupuesto Familiar');
    $second = createEspacioFinanciero($user, 'Presupuesto Familiar');

    expect($first->slug)->toBe('presupuesto-familiar')
        ->and($second->slug)->toBe('presupuesto-familiar-2')
        ->and(route('espacio.show', $first))->toEndWith('/espacios-financieros/presupuesto-familiar');
});

it('actualiza el slug cuando cambia el nombre del espacio', function () {
    $espacio = createEspacioFinanciero(User::factory()->create(), 'Presupuesto Familiar');

    $espacio->update(['nombre' => 'Viaje a Cancún']);

    expect($espacio->fresh()->slug)->toBe('viaje-a-cancun');
});
