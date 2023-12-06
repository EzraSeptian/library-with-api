<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AnggotaAuthController;
use App\Http\Controllers\PetugasAuthController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('', [SesiController::class, 'index'])->name('login');
    Route::post('', [SesiController::class, 'login']);
});

Route::get('/home', function () {
    return redirect('/buku');
});

Route::middleware(['auth'])->group(function () {
    Route::get('buku', [BukuController::class, 'index']);
    Route::post('buku', [BukuController::class, 'store']);
    Route::get('buku/{id}', [BukuController::class, 'edit']);
    Route::put('buku/{id}', [BukuController::class, 'update']);
    Route::delete('buku/{id}', [BukuController::class, 'destroy']);

    // Pastikan rute anggota ditempatkan setelah rute buku
    Route::get('anggota', [AnggotaController::class, 'index']);
    Route::post('anggota', [AnggotaController::class, 'store']);
    Route::get('anggota/{id}', [AnggotaController::class, 'edit']);
    Route::put('anggota/{id}', [AnggotaController::class, 'update']);
    Route::delete('anggota/{id}', [AnggotaController::class, 'destroy']);


    // Rute untuk Petugas


    Route::get('petugas', [PetugasController::class, 'index']);
    Route::post('petugas', [PetugasController::class, 'store']);
    Route::get('petugas/{id}', [PetugasController::class, 'edit']);
    Route::put('petugas/{id}', [PetugasController::class, 'update']);
    Route::delete('petugas/{id}', [PetugasController::class, 'destroy']);


    Route::get('transaksi', [TransaksiController::class, 'index']);
    Route::post('transaksi', [TransaksiController::class, 'store']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'edit']);
    Route::put('transaksi/{id}', [TransaksiController::class, 'update']);
    Route::delete('transaksi/{id}', [TransaksiController::class, 'destroy']);

    Route::get('detail_transaksi/{idTransaksi}', [DetailTransaksiController::class, 'show']);
    Route::post('detail_transaksi/{idTransaksi}', [DetailTransaksiController::class, 'store']);

    Route::get('/logout', [SesiController::class, 'logout']);
});
