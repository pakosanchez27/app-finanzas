<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EspacioFinancieroController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [LoginController::class, 'store']);
Route::post('/auth/register', [RegisterController::class, 'store']);

Route::get('/email/verify/{id}/{hash}', function (Request $request, string $id, string $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return response()->json([
        'message' => 'Email verificado correctamente.',
    ]);
})->middleware('signed')->name('api.verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [LogoutController::class, 'logout']);

    //espacio financiero
    Route::apiResource('espacios-financieros', EspacioFinancieroController::class);
});
