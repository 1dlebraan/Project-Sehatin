<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController; // ✅ Pastikan ini di-import
use App\Models\Poli;
use App\Models\UsersModel;

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

    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    // Menu navigasi umum Admin
    Route::get('/admin-daftarpoli', [AdminController::class, 'showDaftarPoli'])->name('admin.poli');
    Route::get('/admin-petugas', [AdminController::class, 'showPetugas'])->name('admin.petugas');
    Route::get('/admin-laporan', [AdminController::class, 'showLaporan'])->name('admin.laporan');
    Route::get('/admin-konfigurasi', [AdminController::class, 'showKonfigurasi'])->name('admin.konfigurasi');
    Route::post('/admin-konfigurasi', [AdminController::class, 'updateKonfigurasi'])->name('admin.konfigurasi.update');

    // Routing CRUD untuk Jadwal Poli
    Route::get('/jadwal-poli', [AdminController::class, 'showDaftarPoli'])->name('jadwalpoli.index');
    Route::post('/jadwal-poli', [AdminController::class, 'store'])->name('jadwalpoli.store');
    Route::put('/jadwal-poli/{id}', [AdminController::class, 'update'])->name('jadwalpoli.update');
    Route::delete('/jadwal-poli/{id}', [AdminController::class, 'destroy'])->name('jadwalpoli.destroy');
    Route::patch('/jadwal-poli/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('jadwalpoli.toggleStatus');

    // Routing untuk Menambah Poli (yang terpisah)
    Route::post('/poli', [AdminController::class, 'storePoli'])->name('poli.store');

    // Routing CRUD untuk Manajemen Petugas
    Route::post('/petugas', [AdminController::class, 'storePetugas'])->name('admin.petugas.store');
    Route::put('/petugas/{id}', [AdminController::class, 'updatePetugas'])->name('admin.petugas.update');
    Route::delete('/petugas/{id}', [AdminController::class, 'destroyPetugas'])->name('admin.petugas.destroy');
}); // ✅ Penutup untuk grup 'admin'


// -------------------- PETUGAS AREA --------------------
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    // Dashboard Petugas
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    });

    // Rute untuk Daftar Pasien Petugas, Panggil Antrean, dan Ubah Status
    Route::get('/daftar-pasien', [PetugasController::class, 'showDaftarPasien'])->name('petugas.daftar_pasien');
    Route::post('/daftar-pasien/{id}/call', [PetugasController::class, 'callAntrian'])->name('petugas.call_antrian');
    Route::post('/daftar-pasien/{id}/update-status', [PetugasController::class, 'updateAntrianStatus'])->name('petugas.update_antrian_status');

    Route::get('/monitor-efisiensi', [PetugasController::class, 'showMonitorEfisiensi'])->name('petugas.monitor_efisiensi');
}); // ✅ Penutup untuk grup 'petugas'


// -------------------- API untuk Mobile --------------------
// Route::prefix('api')->group(function () {
//     Route::post('/register', [ApiAuthController::class, 'register']);
//     Route::post('/login', [ApiAuthController::class, 'login']);

//     Route::middleware('auth:api')->group(function () { // Menggunakan 'auth:api' untuk JWT
//         Route::post('/logout', [ApiAuthController::class, 'logout']);
//         Route::get('/user', function (Request $request) {
//             return response()->json($request->user());
//         });
//     });
// }); // ✅ Penutup untuk grup 'api'
