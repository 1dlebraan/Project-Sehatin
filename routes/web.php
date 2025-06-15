<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\JadwalPoliController; // Tidak perlu jika Jadwal Poli tetap di AdminController
use App\Models\Poli; // Pastikan model Poli di-import jika Anda membuat controller terpisah


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
    Route::get('/admin-petugas', [AdminController::class, 'index'])->name('admin.petugas');
    Route::get('/admin-laporan', [AdminController::class, 'index'])->name('admin.laporan');
    Route::get('/admin-konfigurasi', [AdminController::class, 'index'])->name('admin.konfigurasi');

    // Routing khusus untuk Jadwal Poli (dengan modal)
    Route::get('/jadwal-poli', [AdminController::class, 'index'])->name('jadwalpoli.index');
    Route::post('/jadwal-poli', [AdminController::class, 'store'])->name('jadwalpoli.store');
    Route::put('/jadwal-poli/{id}', [AdminController::class, 'update'])->name('jadwalpoli.update');
    Route::delete('/jadwal-poli/{id}', [AdminController::class, 'destroy'])->name('jadwalpoli.destroy');
    Route::patch('/jadwal-poli/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('jadwalpoli.toggleStatus');

    // ✅ Routing BARU untuk Menambah Poli
    Route::post('/poli', [AdminController::class, 'storePoli'])->name('poli.store'); // BARIS INI DITAMBAHKAN
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
