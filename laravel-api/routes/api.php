<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArtistController;
use App\Http\Controllers\API\Auth\AuthenticateController;

Route::middleware(['throttle:api'])->as('api.')->group(function () {
    Route::get('/', function () {
        return [
            'version' => '1.0.0',
        ];
    })->name('root');

    Route::post('login', [AuthenticateController::class, 'login'])->name('user.login');
});

Route::middleware(['auth:sanctum', 'throttle:api'])->as('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('me');

    Route::middleware(['ability:ADMIN'])->as('admin.')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome Admin']);
        })->name('dashboard');
    });

    Route::apiResource('artists', ArtistController::class);
    Route::delete('revoke', [AuthenticateController::class, 'revoke'])->name('user.revoke');
});

Route::get('/orders', function () {
    // Token has both "check-status" and "place-orders" abilities...
})->middleware(['auth:sanctum', 'abilities:check-status,place-orders']);

Route::get('/orders', function () {
    // Token has the "check-status" or "place-orders" ability...
})->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
