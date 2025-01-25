<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/data-barang', [AdminController::class, 'dataBarang'])->name('admin.data-barang');
    Route::get('/tambah-barang', [AdminController::class, 'tambahBarang'])->name('admin.tambah-barang');
    Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('admin.pesanan');
    Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('admin.transaksi');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('/laporan/export', [AdminController::class, 'exportLaporan'])->name('admin.laporan.export');
    Route::post('/store-barang', [AdminController::class, 'storeBarang'])->name('admin.store-barang');
    Route::get('/edit-barang/{id}', [AdminController::class, 'editBarang'])->name('admin.edit-barang');
    Route::delete('/delete-barang/{id}', [AdminController::class, 'deleteBarang'])->name('admin.delete-barang');
    Route::put('/update-barang/{id}', [AdminController::class, 'updateBarang'])->name('admin.update-barang');
    Route::put('/update-status-pesanan/{id}', [AdminController::class, 'updateStatusPesanan'])
        ->name('admin.update-status-pesanan');
    Route::get('/export-transaksi', [AdminController::class, 'exportTransaksi'])->name('admin.export-transaksi');
    Route::get('/export-laporan', [AdminController::class, 'exportLaporan'])->name('admin.export-laporan');

    Route::get('/input', [NilaiController::class, 'index'])->name('input');

    // Route untuk menyimpan data nilai
    Route::post('/input/store', [NilaiController::class, 'storeOrUpdate'])->name('input.store');

    // Route untuk menampilkan hasil nilai
    Route::get('/hasil', [NilaiController::class, 'index2'])->name('hasil');
});

// User Routes
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/catalog', [UserController::class, 'catalog'])->name('user.catalog');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/riwayat-transaksi', [UserController::class, 'riwayatTransaksi'])->name('user.riwayat-transaksi');
    Route::post('/pesanan/{id}', [UserController::class, 'createPesanan'])->name('user.create-pesanan');
    Route::post('/upload-bukti/{id}', [UserController::class, 'uploadBuktiTransfer'])->name('user.upload-bukti');
    Route::post('/cart/add/{barangId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::delete('/cart/item/{itemId}', [CartController::class, 'removeItem'])->name('cart.remove');
});
