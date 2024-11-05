<?php

use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\DepartemenController;
use App\Http\Controllers\Api\GajiController;
use App\Http\Controllers\Api\JabatanController;
use App\Http\Controllers\Api\KaryawanController;
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

Route::apiResource('karyawan', KaryawanController::class);
Route::apiResource('departemen',DepartemenController::class);
Route::apiResource('gaji', GajiController::class);
Route::apiResource('jabatan', JabatanController::class);
Route::apiResource('absensi', AbsensiController::class);

Route::patch('/karyawan/{id}', [KaryawanController::class, 'update']);
Route::patch('/gaji/{id}', [GajiController::class, 'update']);
Route::patch('/departemen/{id}', [DepartemenController::class, 'update']);
Route::patch('/jabatan/{id}', [JabatanController::class, 'update']);
Route::patch('/absensi/{id}', [AbsensiController::class, 'update']);

Route::get('/karyawan/nama_lengkap/{name}', [KaryawanController::class, 'showByName']);
