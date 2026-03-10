<?php

use App\Http\Controllers\API\DinnerPollController;
use App\Http\Controllers\API\PointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArtistController;
use App\Http\Controllers\API\Auth\AuthenticateController;

Route::as('api.')->group(function () {
    Route::get('/', function () {
        return [
            'version' => '1.0.0',
        ];
    })->name('root');

    Route::post('login', [AuthenticateController::class, 'login'])->name('user.login');

    Route::get('artists/recommended', [ArtistController::class, 'recommended'])->name('artists.recommended');
});

Route::middleware(['auth:sanctum'])->as('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('me');

    Route::middleware(['ability:ADMIN'])->as('admin.')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome Admin']);
        })->name('dashboard');
    });
    Route::put('artists/recommended', [ArtistController::class, 'updateRecommended'])->name('artists.recommended.update');
    Route::apiResource('artists', ArtistController::class);
    Route::delete('revoke', [AuthenticateController::class, 'revoke'])->name('user.revoke');

    // Point
    Route::get('/point', [PointController::class, 'show'])->name('point.index');
    Route::post('/point', [PointController::class, 'earn'])->name('point.earn');
    Route::put('/point', [PointController::class, 'redeem'])->name('point.redeem');
    Route::get('/points', [PointController::class, 'index'])->name('point.index');
});

Route::get('/orders', function () {
    // Token has both "check-status" and "place-orders" abilities...
})->middleware(['auth:sanctum', 'abilities:check-status,place-orders']);

Route::get('/orders', function () {
    // Token has the "check-status" or "place-orders" ability...
})->middleware(['auth:sanctum', 'ability:check-status,place-orders']);

Route::get('dinner-poll', [DinnerPollController::class, 'results'])->name('dinner-poll.results');
Route::post('dinner-poll', [DinnerPollController::class, 'vote'])->name('dinner-poll.vote');
