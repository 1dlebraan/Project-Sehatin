<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/admin/dashboard', function () {
    return 'Admin Dashboard'; })->middleware('auth', 'role:admin');
Route::get('/petugas/dashboard', function () {
    return 'Petugas Dashboard'; })->middleware('auth', 'role:petugas');
