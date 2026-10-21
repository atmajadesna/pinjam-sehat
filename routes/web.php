<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SimulasiController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Kalkulator angsuran — bisa dibuka tanpa login, biar calon peminjam bisa hitung dulu.
Route::get('/simulasi', [SimulasiController::class, 'index']);

Route::middleware('cek')->group(function () {
    Route::get('/', [BerandaController::class, 'index']);

    Route::get('/anggota', [AnggotaController::class, 'index']);
    Route::get('/anggota/tambah', [AnggotaController::class, 'create']);
    Route::post('/anggota', [AnggotaController::class, 'store']);
    Route::get('/anggota/{anggota}/ubah', [AnggotaController::class, 'edit']);
    Route::put('/anggota/{anggota}', [AnggotaController::class, 'update']);
    Route::delete('/anggota/{anggota}', [AnggotaController::class, 'destroy']);

    Route::get('/pinjaman', [PinjamanController::class, 'index']);
    Route::get('/pinjaman/baru', [PinjamanController::class, 'create']);
    Route::post('/pinjaman', [PinjamanController::class, 'store']);
    Route::get('/pinjaman/{pinjaman}', [PinjamanController::class, 'show']);
    Route::post('/pinjaman/{pinjaman}/bayar', [PinjamanController::class, 'bayar']);
    Route::delete('/pinjaman/{pinjaman}', [PinjamanController::class, 'destroy']);

    Route::get('/laporan', [LaporanController::class, 'index']);
});
