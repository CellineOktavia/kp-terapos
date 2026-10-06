<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\FakturController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\BackupController;

Route::get('/', function () {
    return view('landing-page');
})->name('landing-page');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('kategori.destroy');

    // Product Routes
    Route::get(
        '/produk',
        [ProductController::class, 'index']
    )
        ->name('produk.index');

    Route::get(
        '/produk/create',
        [ProductController::class, 'create']
    )
        ->name('produk.create');

    Route::post(
        '/produk',
        [ProductController::class, 'store']
    )
        ->name('produk.store');

    Route::get(
        '/produk/{product}/edit',
        [ProductController::class, 'edit']
    )
        ->name('produk.edit');

    Route::put(
        '/produk/{product}',
        [ProductController::class, 'update']
    )
        ->name('produk.update');

    Route::delete(
        '/produk/{product}',
        [ProductController::class, 'destroy']
    )
        ->name('produk.destroy');

    Route::get(
        '/produk/{product}',
        [ProductController::class, 'show']
    )->name('produk.show');

    // Supplier Routes
    Route::get(
        '/supplier',
        [SupplierController::class, 'index']
    )
        ->name('supplier.index');

    Route::get(
        '/supplier/create',
        [SupplierController::class, 'create']
    )
        ->name('supplier.create');

    Route::post(
        '/supplier',
        [SupplierController::class, 'store']
    )
        ->name('supplier.store');

    Route::get(
        '/supplier/{supplier}/edit',
        [SupplierController::class, 'edit']
    )
        ->name('supplier.edit');

    Route::put(
        '/supplier/{supplier}',
        [SupplierController::class, 'update']
    )
        ->name('supplier.update');

    Route::delete(
        '/supplier/{supplier}',
        [SupplierController::class, 'destroy']
    )
        ->name('supplier.destroy');

    Route::get(
        '/supplier/{supplier}',
        [SupplierController::class, 'show']
    )->name('supplier.show');

    // Faktur Routes
    Route::get('/faktur', [FakturController::class, 'index'])
        ->name('faktur.index');

    Route::get(
        '/faktur/create',
        [FakturController::class, 'create']
    )
        ->name('faktur.create');

    Route::post(
        '/faktur',
        [FakturController::class, 'store']
    )
        ->name('faktur.store');

    Route::get('/faktur/{faktur}', [FakturController::class, 'show'])
        ->name('faktur.show');

    Route::delete('/faktur/{faktur}', [FakturController::class, 'destroy'])
        ->name('faktur.destroy');

    // Laporan Routes
    Route::get(
        '/laporan',
        [LaporanController::class, 'index']
    )->name('laporan.index');

    Route::get(
        '/laporan/produk',
        [LaporanController::class, 'produk']
    )->name('laporan.produk');

    Route::get(
        '/laporan/supplier',
        [LaporanController::class, 'supplier']
    )->name('laporan.supplier');

    Route::get(
        '/laporan/customer',
        [LaporanController::class, 'customer']
    )->name('laporan.customer');

    Route::get(
        '/laporan/pembelian',
        [LaporanController::class, 'pembelian']
    )->name('laporan.pembelian');

    Route::get(
        '/laporan/penjualan',
        [LaporanController::class, 'penjualan']
    )->name('laporan.penjualan');

    Route::get(
        '/laporan/stok',
        [LaporanController::class, 'stok']
    )->name('laporan.stok');

    // Laporan Export Routes
    Route::get(
        '/laporan/produk/pdf',
        [LaporanController::class, 'produkPdf']
    )->name('laporan.produk.pdf');

    Route::get(
        '/laporan/supplier/pdf',
        [LaporanController::class, 'supplierPdf']
    )->name('laporan.supplier.pdf');

    Route::get(
        '/laporan/customer/pdf',
        [LaporanController::class, 'customerPdf']
    )->name('laporan.customer.pdf');

    Route::get(
        '/laporan/pembelian/pdf',
        [LaporanController::class, 'pembelianPdf']
    )->name('laporan.pembelian.pdf');

    Route::get(
        '/laporan/penjualan/pdf',
        [LaporanController::class, 'penjualanPdf']
    )->name('laporan.penjualan.pdf');

    Route::get(
        '/laporan/stok/pdf',
        [LaporanController::class, 'stokPdf']
    )->name('laporan.stok.pdf');

    // Stock Routes
    Route::get('/stok', [StockController::class, 'index'])
        ->name('stok.index');

    Route::get(
        '/stok-kritis',
        [StockController::class, 'critical']
    )->name('stok.kritis');

    Route::get(
        '/riwayat-stok',
        [StockMovementController::class, 'index']
    )->name('stok.riwayat');

    Route::middleware('role:owner')->group(function () {
        Route::get('/stok/adjustment', [StockController::class, 'createAdjustment'])
            ->name('stok.adjustment.create');
        Route::post('/stok/adjustment', [StockController::class, 'storeAdjustment'])
            ->name('stok.adjustment.store');

        Route::get('/backup', [BackupController::class, 'index'])
            ->name('backup.index');
        Route::post('/backup', [BackupController::class, 'store'])
            ->name('backup.store');
        Route::get('/backup/{filename}/download', [BackupController::class, 'download'])
            ->where('filename', 'terapos-backup-[0-9]{8}-[0-9]{6}(-[0-9]+)?\\.sqlite')
            ->name('backup.download');
    });


    // Customer Routes
    Route::get(
        '/customer',
        [CustomerController::class, 'index']
    )->name('customer.index');

    Route::get(
        '/customer/create',
        [CustomerController::class, 'create']
    )->name('customer.create');

    Route::post(
        '/customer',
        [CustomerController::class, 'store']
    )->name('customer.store');

    Route::get(
        '/customer/{customer}/edit',
        [CustomerController::class, 'edit']
    )->name('customer.edit');

    Route::put(
        '/customer/{customer}',
        [CustomerController::class, 'update']
    )->name('customer.update');

    Route::delete(
        '/customer/{customer}',
        [CustomerController::class, 'destroy']
    )->name('customer.destroy');

    Route::get(
        '/customer/{customer}',
        [CustomerController::class, 'show']
    )->name('customer.show');

    // Penjualan Routes
    Route::get(
        '/penjualan',
        [PenjualanController::class, 'index']
    )->name('penjualan.index');

    Route::get(
        '/penjualan/create',
        [PenjualanController::class, 'create']
    )->name('penjualan.create');

    Route::post(
        '/penjualan',
        [PenjualanController::class, 'store']
    )->name('penjualan.store');

    Route::get(
        '/penjualan/{penjualan}',
        [PenjualanController::class, 'show']
    )->name('penjualan.show');

    Route::delete(
        '/penjualan/{penjualan}',
        [PenjualanController::class, 'destroy']
    )->name('penjualan.destroy');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

//Privasi Routes
Route::get('/kebijakan-privasi', function () {
    return view('privasi.kebijakan');
})->name('privasi.kebijakan');

Route::get('/syarat-dan-ketentuan', function () {
    return view('privasi.terms');
})->name('privasi.terms');

// Co-Owner Dashboard Routes
Route::middleware([
    'auth',
    'role:co_owner'
])->group(function () {

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');
});

// Owner Dashboard Routes
Route::middleware([
    'auth',
    'role:owner'
])->group(function () {

    Route::get(
        '/owner/dashboard',
        [OwnerDashboardController::class, 'index']
    )->name('owner.dashboard');
});

// routes/web.php
Route::get('/api/produk-by-barcode', function (Illuminate\Http\Request $request) {
    $produk = \App\Models\Product::where('barcode', $request->kode)->first()
        ?? \App\Models\Product::where('kode_produk', $request->kode)->first();

    if ($produk) {
        return response()->json([
            'success' => true,
            'produk'  => [
                'id'           => $produk->id,
                'kode_produk'  => $produk->kode_produk,
                'nama_produk'  => $produk->nama_produk,
                'harga_jual'   => $produk->harga_jual,
                'stok'         => $produk->stok,
            ],
        ]);
    }

    return response()->json(['success' => false]);
})->middleware('auth');
