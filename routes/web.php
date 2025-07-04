<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\TaksiController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\UserController;
use App\Models\Pemesanan;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/rute-list', [RuteController::class, 'list'])->name('rute-list');
Auth::routes();
Route::middleware(['auth:web'])->group(function () {
    Route::get('/tracking/user', function () {
        return view('admin.tracking_user', ['title' => 'Rute Taksi']);
    });
    Route::post('/send-koordinat', [App\Http\Controllers\KoordinatController::class, 'store'])->name('send-koordinat');
    Route::get('/get-koordinat/{id_takis}', [App\Http\Controllers\KoordinatController::class, 'getKoordinat'])->name('get-koordinat');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/riwayat-user', [App\Http\Controllers\HomeController::class, 'riwayatUser'])->name('riwayat-user');

    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //pesanan managemen
    Route::post('/pesanan/store',  [PemesananController::class, 'store'])->name('pesanan.store');
    Route::get('/kursi-tersedia/{id_taksi}',  [PemesananController::class, 'kursiTersedia'])->name('kursi-tersedia');
    //ulasan managemen
    Route::post('/ulasan/store',  [UlasanController::class, 'store'])->name('ulasan.store');
    //ulasan managemen
    Route::get('/ulasan-datatable', [UlasanController::class, 'getUlasanDataTable']);
    //rute
    Route::get('/rute-taksi/{id_taksi}', [RuteController::class, 'rute_taksi'])->name('rute-taksi');
});
Route::middleware(['auth:web', 'role:Supir'])->group(function () {
    Route::get('/tracking/supir', function () {
        return view('admin.tracking_supir', ['title' => 'Rute Penjemputan']);
    });
    //rute
    Route::get('/pesanan-selesai/{id}', [PemesananController::class, 'pesananSelesai'])->name('pesanan-selesai');
    Route::get('/tolak-pesanan/{id}', [PemesananController::class, 'tolakPesanan'])->name('tolak-pesanan');
    Route::get('/rute-penjemputan/{id_taksi}', [RuteController::class, 'rute_penjemputan'])->name('rute-penjemputan');
    //taksi
    Route::post('/mobil/store', [TaksiController::class, 'store'])->name('mobil.store');
    Route::post('/mobil/add-rute', [TaksiController::class, 'addRute'])->name('mobil.add-rute');
    Route::delete('/mobil/delete-rute/{id}', [TaksiController::class, 'destroyRute'])->name('mobil.delete-rute');
    Route::post('/mobil/status/{id}', [TaksiController::class, 'updateStatus'])->name('mobil.status');
});
Route::middleware(['auth:web', 'role:Admin,Supir'])->group(function () {
    Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan');
});
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
    // verifikasi taksi
    Route::get('/mobil-verifikasi/{id}', [TaksiController::class, 'verifikasi'])->name('mobil-verifikasi');
    Route::get('/mobil-tolak/{id}', [TaksiController::class, 'tolak'])->name('mobil-tolak');
    //mobil managemen
    Route::get('/mobil', [TaksiController::class, 'index'])->name('mobil');
    Route::get('/mobil-datatable', [TaksiController::class, 'getMobilDataTable']);
    //rute managemen
    Route::get('/rute', [RuteController::class, 'index'])->name('rute');
    Route::post('/rute/store',  [RuteController::class, 'store'])->name('rute.store');
    Route::get('/rute/edit/{id}',  [RuteController::class, 'edit'])->name('rute.edit');
    Route::delete('/rute/delete/{id}',  [RuteController::class, 'destroy'])->name('rute.delete');
    Route::get('/rute-datatable', [RuteController::class, 'getRuteDataTable']);
    //user managemen
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/admin', [UserController::class, 'admin'])->name('users.admin');
    Route::get('/users/supir', [UserController::class, 'supir'])->name('users.supir');
    Route::get('/users/user', [UserController::class, 'user'])->name('users.user');
    Route::post('/users/store',  [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}',  [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/users/delete/{id}',  [UserController::class, 'destroy'])->name('users.delete');
    Route::get('/users-datatable/{jenis}', [UserController::class, 'getUsersDataTable']);
});
