<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KecamatanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix("dashboard")->group(function() {
    Route::get("/", [DashboardController::class,"index"])->name("dashboard");
    Route::get("/kecamatan", [KecamatanController::class,"index"])->name("kecamatan");
    Route::get("/kecamatan/create", [KecamatanController::class, 'create'])->name('kecamatan.create');
    Route::delete("/kecamatan/destroy/{id}", [KecamatanController::class,"destroy"])->name("kecamatan.destroy");
    Route::post('kecamatan/store', [KecamatanController::class, 'store'])->name('kecamatan.store');

});
// Route::get('/dashboard', function() {
//     return view ('dashboard.index');
// })->name('dashboard');


