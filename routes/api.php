<?php

use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\DetailTransaksiController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('books/getAllData', [BukuController::class, 'getAllData']);
Route::get('anggotas/getAllData', [AnggotaController::class, 'getAllData']);
Route::get('petugass/getAllData', [PetugasController::class, 'getAllData']);
Route::apiResource('buku', BukuController::class);
Route::apiResource('anggota', AnggotaController::class);
Route::apiResource('petugas', PetugasController::class);
Route::apiResource('transaksi', TransaksiController::class);
Route::post('detail_transaksi/{idtransaksi}', [DetailTransaksiController::class, 'store']);
Route::prefix('detail_transaksi')->group(function () {
    Route::get('/', [DetailTransaksiController::class, 'index']);
    Route::get('/show', [DetailTransaksiController::class, 'show']);
    Route::post('/store', [DetailTransaksiController::class, 'store']);
    Route::put('/update/{idtransaksi}/{idbuku}', [DetailTransaksiController::class, 'update']);
});
