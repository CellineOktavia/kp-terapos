<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountSettingController;
use App\Http\Controllers\CrossSubsidyController;
use App\Http\Controllers\PenjualanReturnController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\UserManagementController;

Route::get('/', function () {
    return view('landing-page');
})->name('landing-page');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth', \App\Http\Middleware\EnsureActiveUser::class])->group(function () {
    // Kategori Routes
    Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('kategori.destroy');

    // Product Routes
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{product}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('produk.update');
    Route::patch('/produk/{product}/status', [ProductController::class, 'toggleStatus'])->name('produk.status');
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');
    Route::get('/produk/{product}', [ProductController::class, 'show'])->name('produk.show');

    // Penerimaan Barang Routes
    Route::get('/penerimaan', [PenerimaanBarangController::class, 'index'])->name('penerimaan.index');
    Route::get('/penerimaan/create', [PenerimaanBarangController::class, 'create'])
        ->middleware('role:owner')
        ->name('penerimaan.create');
    Route::post('/penerimaan', [PenerimaanBarangController::class, 'store'])
        ->middleware('role:owner')
        ->name('penerimaan.store');
    Route::get('/penerimaan/{penerimaanBarang}', [PenerimaanBarangController::class, 'show'])->name('penerimaan.show');

    // Laporan Routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/penjualan/pdf', [LaporanController::class, 'penjualanPdf'])->name('laporan.penjualan.pdf');
    Route::get('/laporan/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('laporan.barang-masuk');
    Route::get('/laporan/barang-masuk/pdf', [LaporanController::class, 'barangMasukPdf'])->name('laporan.barang-masuk.pdf');
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/laporan/stok/pdf', [LaporanController::class, 'stokPdf'])->name('laporan.stok.pdf');
    Route::get('/laporan/stok-kritis', [LaporanController::class, 'stokKritis'])->name('laporan.stok-kritis');
    Route::get('/laporan/stok-kritis/pdf', [LaporanController::class, 'stokKritisPdf'])->name('laporan.stok-kritis.pdf');

    // Stock Routes
    Route::get('/stok', [StockController::class, 'index'])->name('stok.index');
    Route::get('/stok-kritis', [StockController::class, 'critical'])->name('stok.kritis');
    Route::get('/riwayat-stok', [StockMovementController::class, 'index'])->name('stok.riwayat');

    // Owner Only Routes
    Route::middleware('role:owner')->group(function () {
        Route::get('/diskon', [DiscountSettingController::class, 'edit'])->name('diskon.edit');
        Route::put('/diskon', [DiscountSettingController::class, 'update'])->name('diskon.update');
        Route::get('/subsidi-silang', [CrossSubsidyController::class, 'edit'])->name('subsidi-silang.edit');
        Route::put('/subsidi-silang', [CrossSubsidyController::class, 'update'])->name('subsidi-silang.update');
        Route::post('/penjualan/{penjualan}/returns', [PenjualanReturnController::class, 'store'])
            ->name('penjualan.returns.store');
        Route::post('/penjualan/{penjualan}/cancel', [PenjualanReturnController::class, 'cancel'])
            ->name('penjualan.cancel');

        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/status', [UserManagementController::class, 'toggleStatus'])->name('users.status');

        Route::get('/stok/adjustment', [StockController::class, 'createAdjustment'])->name('stok.adjustment.create');
        Route::post('/stok/adjustment', [StockController::class, 'storeAdjustment'])->name('stok.adjustment.store');

        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup', [BackupController::class, 'store'])->name('backup.store');
        Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    });

    // Penjualan Routes
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/penjualan/{penjualan}/nota', [PenjualanController::class, 'receiptPdf'])->name('penjualan.nota');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Privasi Routes
Route::get('/kebijakan-privasi', function () {
    return view('privasi.kebijakan');
})->name('privasi.kebijakan');

Route::get('/syarat-dan-ketentuan', function () {
    return view('privasi.terms');
})->name('privasi.terms');

// Co-Owner Dashboard Routes
Route::middleware(['auth', 'role:co_owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Owner Dashboard Routes
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
});

// Realtime Dashboard Data Endpoint
    Route::get('/api/dashboard/realtime', [OwnerDashboardController::class, 'realtime'])
        ->name('dashboard.realtime');
