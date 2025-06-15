<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Admin\AdminController;


// Routing halaman login Website
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/' . auth()->user()->role . '/dashboard');
    }
    return view('auth.login');
})->name('login');

// Routing login & logout
Route::post('/login', [WebAuthController::class, 'login']);
Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');

// -------------------- ADMIN AREA --------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    // Menu navigasi
    Route::get('/admin-daftarpoli', [AdminController::class, 'index'])->name('admin.poli');
    // Route::get('/admin-petugas', [AdminPetugasController::class, 'index'])->name('admin.petugas');
    // Route::get('/admin-laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan');
    // Route::get('/admin-konfigurasi', [AdminKonfigurasiController::class, 'index'])->name('admin.konfigurasi');

    // ✅ Routing khusus untuk Jadwal Poli (dengan modal)
    Route::post('/jadwal-poli', [AdminController::class, 'store'])->name('jadwalpoli.store');
    Route::put('/jadwal-poli/{id}', [AdminController::class, 'update'])->name('jadwalpoli.update');
    Route::delete('/jadwal-poli/{id}', [AdminController::class, 'destroy'])->name('jadwalpoli.destroy');
    Route::patch('/jadwal-poli/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('jadwalpoli.toggleStatus');
});

// -------------------- PETUGAS AREA --------------------
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    });
});

// -------------------- API untuk Mobile --------------------
Route::prefix('api')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiAuthController::class, 'logout']);
    });
});
