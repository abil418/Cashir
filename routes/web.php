<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Models\Setting;

use Illuminate\Routing\Route as RoutingRoute;

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

Route::get('/home', function () {
    $setting = Setting::first();
    return view('home', compact('setting'));
});

Route::get('/', function () {
    return redirect('home');
});
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::middleware(['auth', 'user-access:2'])->group(function () {
        //Kategori
    Route::prefix('kategori')->group(function () {
        Route::get('data', [KategoriController::class, 'data'])->name('kategori.data');
    });
    Route::resource('kategori', KategoriController::class);

    //Produk
    Route::prefix('produk')->group(function () {
        Route::get('data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('delete-selected', [ProdukController::class, 'deleteSelected'])->name('delete-selected');
        Route::post('cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('cetak-barcode');
    });
    Route::resource('produk', ProdukController::class);

    //member
    Route::prefix('member')->group(function () {
        Route::get('data', [MemberController::class, 'data'])->name('member.data');
        Route::get('cetak-member', [MemberController::class, 'cetakMember'])->name('cetak-member');
        Route::post('cetak-member', [MemberController::class, 'cetakMember'])->name('cetak-member');
    });
    Route::resource('member', MemberController::class);

    //supplier
    Route::prefix('supplier')->group(function () {
        Route::get('data', [SupplierController::class, 'data'])->name('supplier.data');
    });
    Route::resource('supplier', SupplierController::class);

    //pengeluaran
    Route::prefix('pengeluaran')->group(function () {
        Route::get('data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    });
    Route::resource('pengeluaran', PengeluaranController::class);

    //pembelian
    Route::prefix('pembelian')->group(function () {
        Route::get('data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('create/{id}', [PembelianController::class, 'create'])->name('pembelian.create');
    });
    Route::resource('pembelian', PembelianController::class)->except('create');

    //pembelian detail
    Route::get('pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    Route::get('pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadform'])->name('detail.loadform');
    Route::resource('pembelian_detail', PembelianDetailController::class)->except('create', 'show', 'edit');

    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });

    //penjualan
    Route::prefix('transaksi')->group(function () {
        Route::get('baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('done', [PenjualanController::class, 'done'])->name('transaksi.done');
        Route::get('nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
    });
    Route::resource('transaksi', PenjualanDetailController::class)->except('create', 'show', 'edit');

    //report
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    // Route::post('/laporan/refresh', [LaporanController::class, 'refresh'])->name('laporan.refresh');
    Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
    Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('/user', UserController::class);

    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
    Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

});
