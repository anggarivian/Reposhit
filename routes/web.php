<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\SkripsiController;
use App\Http\Controllers\MahasiswaController;

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

Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');

Route::get('/', function () {
    return redirect('/welcome');
});

Auth::routes();

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::middleware('is_admin')->group(function () {
    // Route Kelola Dosen -------------------------------------------------------------------------
    Route::get('/admin/dosen', [DosenController::class, 'index'])->name('dosen');
    Route::post('/admin/dosen', [DosenController::class, 'tambah'])->name('tambah.dosen');
    Route::patch('/admin/dosen/ubah', [DosenController::class, 'ubah'])->name('ubah.dosen');
    Route::get('admin/ajaxadmin/dataDosen/{id}', [DosenController::class, 'getDataDosen']);
    Route::get('/admin/dosen/hapus/{id}', [DosenController::class, 'hapus'])->name('hapus.dosen');

    // Route Kelola Mahasiswa ---------------------------------------------------------------------
    Route::get('/admin/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
    Route::post('/admin/mahasiswa', [MahasiswaController::class, 'tambah'])->name('tambah.mahasiswa');
    Route::patch('/admin/mahasiswa/ubah', [MahasiswaController::class, 'ubah'])->name('ubah.mahasiswa');
    Route::get('admin/ajaxadmin/dataMahasiswa/{id}', [MahasiswaController::class, 'getDataMahasiswa']);
    Route::get('/admin/mahasiswa/hapus/{id}', [MahasiswaController::class,'hapus'])->name('hapus.mahasiswa');
    Route::get('/admin/mahasiswa/verifikasi/{id}', [MahasiswaController::class,'verifikasi'])->name('verifikasi.mahasiswa');

    // Route Skripsi ------------------------------------------------------------------------------
    Route::get('/admin/skripsi', [SkripsiController::class, 'index'])->name('skripsi');
    Route::post('/admin/skripsi', [SkripsiController::class, 'tambah'])->name('tambah.skripsi');
    Route::patch('/admin/skripsi/ubah', [SkripsiController::class, 'ubah'])->name('ubah.skripsi');
    Route::get('admin/ajaxadmin/dataSkripsi/{id}', [SkripsiController::class, 'getDataSkripsi']);
    Route::get('/admin/skripsi/hapus/{id}', [SkripsiController::class, 'hapus'])->name('hapus.skripsi');

    // Route Import -------------------------------------------------------------------------------
    Route::post('/admin/mahasiswa/import', [MahasiswaController::class,'import'])->name('mahasiswa.import');
    Route::post('/admin/dosen/import', [DosenController::class,'import'])->name('dosen.import');

    Route::get('/admin/skripsi/detail/{id}', [SkripsiController::class, 'showPdf'])->name('pdf.show');
});

// Route Detail Skripsi ---------------------------------------------------------------------------

Route::middleware('is_mahasiswa')->group(function () {
    
    Route::get('/home/skripsi/detail/{id}', [SkripsiController::class, 'detailskripsi']);
    Route::get('/welcome/detail/{id}', [SkripsiController::class, 'welcomeskripsi']);
    Route::get('/home/skripsi', [SkripsiController::class, 'mahasiswa'])->name('mahasiswa.skripsi');

});

// View File PDF -----------------------------------------------------------------------------------
