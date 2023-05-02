<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Rotte protette dal middleware JWT
Route::middleware(['jwt'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservation', [ReservationController::class, 'store']);
    Route::get('/reservation/{reservation:id}', [ReservationController::class, 'show']);
    Route::delete('/reservation/delete/{reservation:id}', [ReservationController::class, 'destroy']);
    Route::put('/reservation/update/{reservation:id}', [ReservationController::class, 'update']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
