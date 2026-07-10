<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        //  Alamcenar en la base de datos
        $user = User::create($data);

        $user->notify(new VerifyEmail('api.verification.verify'));

        return response()->json([
            'message' => 'Usuario registrado correctamente. Revisa tu correo para verificar tu cuenta.',
            'user' => $user,
        ], 201);
    }
}
