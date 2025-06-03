<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\AdminPetugasController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminKonfigurasiController;
use Illuminate\Routing\Events\Routing;

// Routing  untuk tampilkan halaman login Website
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/' . auth()->user()->role . '/dashboard');
    }
    return view('auth.login');
})->name('login');

// Routing untuk login & logout website
Route::post('/login', [WebAuthController::class, 'login']);
Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Routing Dashboard admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});

// Routing Menu pada Dashboard admin
Route::get('/admin/admin-daftarpoli', [AdminPoliController::class, 'index'])->name('admin.poli');
Route::get('/admin/admin-petugas', [AdminPetugasController::class, 'index'])->name('admin.petugas');
Route::get('/admin/admin-laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan');
Route::get('/admin/admin-konfigurasi', [AdminKonfigurasiController::class, 'index'])->name('admin.konfigurasi');




// Routing Dashboard petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    });
});


// ✅ API untuk Mobile (gunakan prefix agar tidak tabrakan dengan web)
Route::prefix('api')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiAuthController::class, 'logout']);
    });
});

