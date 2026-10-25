<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomizerController;
use App\Http\Controllers\GarageController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home', ['featured' => Vehicle::where('is_active', true)->take(3)->get()]))->name('home');
Route::get('/kendaraan', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/custom/{vehicle:slug}', [CustomizerController::class, 'show'])->name('customizer.show');

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.store');
    Route::get('/daftar', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');
});
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');
    Route::post('/custom/{vehicle:slug}', [CustomizerController::class, 'store'])->name('customizer.store');
    Route::get('/garasi', [GarageController::class, 'index'])->name('garage.index');
    Route::get('/garasi/{build}', [GarageController::class, 'show'])->name('garage.show');
    Route::delete('/garasi/{build}', [GarageController::class, 'destroy'])->name('garage.destroy');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/kendaraan', [AdminController::class, 'vehicle'])->name('admin.vehicles.store');
    Route::delete('/admin/kendaraan/{vehicle}', [AdminController::class, 'deleteVehicle'])->name('admin.vehicles.destroy');
    Route::post('/admin/komponen', [AdminController::class, 'item'])->name('admin.items.store');
    Route::delete('/admin/komponen/{item}', [AdminController::class, 'deleteItem'])->name('admin.items.destroy');
});
