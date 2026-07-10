<?php

use App\Http\Controllers\AsetController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PengajuanPemusnahanController;
use App\Http\Controllers\SekolahController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', function() {
    return redirect()->route('login');
});

// Autentikasi (Guest)
Route::prefix('login')->middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/proses', [AuthController::class, 'loginproses'])->name('login-proses');
});

// Logout (Auth)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard Group (Auth)
Route::prefix("dashboard")->middleware('auth')->group(function () {

    // Main Dashboard
    Route::get("/", [DashboardController::class, "index"])->name("dashboard");

    Route::middleware('role:operator_sekolah')->group(function () {
        Route::get('/sekolah-saya', [SekolahController::class, 'mySekolah'])->name('sekolah.my');
    });

    // CRUD Kecamatan
    Route::get("/kecamatan", [KecamatanController::class, "index"])->name("kecamatan");
    Route::get("/kecamatan/create", [KecamatanController::class, 'create'])->name('kecamatan.create');
    Route::post('kecamatan/store', [KecamatanController::class, 'store'])->name('kecamatan.store');
    Route::delete("/kecamatan/destroy/{id}", [KecamatanController::class, "destroy"])->name("kecamatan.destroy");

    // CRUD Sekolah
    Route::get('/sekolah', [SekolahController::class, 'index'])->name('sekolah');
    Route::get('/sekolah/create', [SekolahController::class, 'create'])->name('sekolah.create');
    Route::post('/sekolah/store', [SekolahController::class, 'store'])->name('sekolah.store');
    Route::get('/sekolah/show/{id}', [SekolahController::class, 'show'])->name('sekolah.show');
    Route::get('/sekolah/edit/{id}', [SekolahController::class, 'edit'])->name('sekolah.edit');
    Route::put('/sekolah/{id}', [SekolahController::class, 'update'])->name('sekolah.update');
    Route::delete('/sekolah/destroy/{id}', [SekolahController::class, 'destroy'])->name('sekolah.destroy');

    // CRUD Aset
    Route::prefix('aset')->name('aset.')->group(function () {
        // Fitur Sampah (harus di atas route {aset})
        Route::get('/trash', [AsetController::class, 'trash'])->name('trash');
        Route::post('/empty-trash', [AsetController::class, 'emptyTrash'])->name('emptyTrash');
        Route::post('/{id}/restore', [AsetController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [AsetController::class, 'forceDelete'])->name('forceDelete');

        // CRUD Biasa
        Route::get('/', [AsetController::class, 'index'])->name('index');
        Route::get('/create', [AsetController::class, 'create'])->name('create');
        Route::post('/store', [AsetController::class, 'store'])->name('store');
        Route::get('/{aset}/edit', [AsetController::class, 'edit'])->name('edit');
        Route::put('/{aset}', [AsetController::class, 'update'])->name('update');
        Route::delete('/{aset}', [AsetController::class, 'destroy'])->name('destroy');
    });

    // ── Pengajuan Penghapusan Asset ──────────────────────────────────────

    // Index: semua role yang login
    Route::get('pengajuan', [PengajuanPemusnahanController::class, 'index'])
        ->name('pengajuan-penghapusan-asset.index');

    // FIX: Create & Store dipindah ke ATAS route show/{pengajuanPemusnahan}
    // agar 'create' tidak tertangkap sebagai nilai parameter {pengajuanPemusnahan}
    Route::middleware(['role:operator_sekolah|admin'])->group(function () {
        Route::get('pengajuan/create', [PengajuanPemusnahanController::class, 'create'])
            ->name('pengajuan-penghapusan-asset.create');

        Route::post('pengajuan', [PengajuanPemusnahanController::class, 'store'])
            ->name('pengajuan-penghapusan-asset.store');
    });

    // Export Excel: hanya admin & kepala_dinas (di atas route show agar tidak konflik)
    Route::get('pengajuan/export', [PengajuanPemusnahanController::class, 'export'])
        ->name('pengajuan-penghapusan-asset.export')
        ->middleware(['role:admin|kepala_dinas']);

    // Show: semua role yang login (setelah create agar tidak konflik)
    Route::get('pengajuan/{pengajuanPemusnahan}', [PengajuanPemusnahanController::class, 'show'])
        ->name('pengajuan-penghapusan-asset.show');

    // Edit, Update, Destroy: hanya operator_sekolah & admin
    Route::middleware(['role:operator_sekolah|admin'])->group(function () {
        Route::get('pengajuan/{pengajuanPemusnahan}/edit', [PengajuanPemusnahanController::class, 'edit'])
            ->name('pengajuan-penghapusan-asset.edit');

        Route::put('pengajuan/{pengajuanPemusnahan}', [PengajuanPemusnahanController::class, 'update'])
            ->name('pengajuan-penghapusan-asset.update');

        Route::delete('pengajuan/{pengajuanPemusnahan}', [PengajuanPemusnahanController::class, 'destroy'])
            ->name('pengajuan-penghapusan-asset.destroy');
    });

    // Validasi: hanya admin (Kepala Dinas hanya bisa melihat, tidak input apa pun)
    Route::patch(
        'pengajuan/{pengajuanPemusnahan}/validasi',
        [PengajuanPemusnahanController::class, 'validasi']
    )->name('pengajuan-penghapusan-asset.validasi')
     ->middleware(['role:admin']);

    Route::get('operator', [OperatorController::class, 'index'])->middleware(['auth', 'role:admin'])->name('operator');
    Route::get('operator/create', [OperatorController::class, 'create'])->name('operator.create');
    Route::post('operator', [OperatorController::class, 'store'])->name('operator.store');
    Route::get('operator/edit/{id}', [OperatorController::class, 'edit'])->name('operator.edit');
    Route::put('operator/{id}', [OperatorController::class, 'update'])->name('operator.update');
    Route::delete('operator/destroy/{id}', [OperatorController::class, 'destroy'])->name('operator.destroy');

});
