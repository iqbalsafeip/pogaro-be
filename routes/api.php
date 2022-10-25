<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\HydraController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//use the middleware 'hydra.log' with any request to get the detailed headers, request parameters and response logged in logs/laravel.log

Route::get('hydra', [HydraController::class, 'hydra']);
Route::get('hydra/version', [HydraController::class, 'version']);

Route::apiResource('users', UserController::class)->except(['edit', 'create', 'store', 'update']);
Route::post('users', [UserController::class, 'store']);
Route::post('ubahProfile/{barber:id}', [UserController::class, 'ubahProfile']);
Route::get('barber', [UserController::class, 'barber']);
Route::get('barber/{id:barber}', [UserController::class, 'barberid']);
Route::get('mahasiswa', [UserController::class, 'mahasiswa'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::post('approval', [ApprovalController::class, 'create'])->middleware(['auth:sanctum']);
Route::get('approval/{user:id}', [ApprovalController::class, 'getApprovalById']);
Route::put('users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::post('users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::patch('users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum');
Route::post('login', [UserController::class, 'login']);

Route::apiResource('roles', RoleController::class)->except(['create', 'edit'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::apiResource('users.roles', UserRoleController::class)->except(['create', 'edit', 'show', 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);


Route::get('service/{id:service}', [ServiceController::class, 'index']);
Route::post('service', [ServiceController::class, 'store']);
Route::post('transaksi', [ServiceController::class, 'transaksi']);
Route::get('service/{id:service}/delete', [ServiceController::class, 'hapus']);
Route::get('metode-pembayaran/{id:service}', [MetodePembayaranController::class, 'index']);
Route::post('metode-pembayaran', [MetodePembayaranController::class, 'store']);
Route::get('metode-pembayaran/{id:service}/delete', [MetodePembayaranController::class, 'hapus']);
Route::get('katalog/{id:service}', [KatalogController::class, 'index']);
Route::post('katalog', [KatalogController::class, 'store']);
Route::get('katalog/{id:service}/delete', [KatalogController::class, 'hapus']);
Route::get("transaksi/{user:id}", [ServiceController::class, "transaksid"]);
Route::get("transaksi-riwayat/{user:id}", [ServiceController::class, "riwayat"]);
Route::get("transaksi-riwayat-barber/{user:id}", [ServiceController::class, "riwayatBarber"]);
Route::post('upload', [ServiceController::class, 'uploadBukti']);
Route::post('verifikasi/{id:transaksi}', [ServiceController::class, 'verifikasi']);