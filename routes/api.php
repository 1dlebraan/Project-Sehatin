<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiAuthController; // Pastikan ini di-import

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rute untuk pendaftaran dan login (tanpa autentikasi)
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

// ✅ GANTI: Rute yang memerlukan autentikasi API token (menggunakan JWT)
Route::middleware('auth:api')->group(function () { // <-- Gunakan 'auth:api'
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    // Contoh: Route::get('/user', function (Request $request) { return $request->user(); });
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
});
