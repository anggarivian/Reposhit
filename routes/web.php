<?php

use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\SkripsiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\CommentController;
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
    Route::get('/admin/mahasiswa/toggle-verifikasi/{id}', [MahasiswaController::class, 'toggleVerifikasi'])->name('toggle.verifikasi');

    // Route Skripsi ------------------------------------------------------------------------------
    Route::get('/admin/skripsi', [SkripsiController::class, 'index1'])->name('skripsiadmin');
    Route::patch('/admin/skripsi/edit', [SkripsiController::class, 'edit'])->name('edit.skripsi');
    Route::get('admin/ajaxadmin/dataSkripsi/{id}', [SkripsiController::class, 'getDataSkripsi']);
    Route::get('/admin/skripsi/hapus/{id}', [SkripsiController::class, 'hapus1'])->name('hapus.skripsi');
    Route::get('/admin/skripsi/detail/{id}', [SkripsiController::class, 'showPdf'])->name('pdf.show');
    Route::get('/admin/skripsi/verifikasi/{id}', [SkripsiController::class,'verifikasi'])->name('verifikasi');

    // Route Komentar ------------------------------------------------------------------------------
    Route::post('admin//comment/detail', [CommentController::class, 'postkomentar1'])->name('postkomentar1');
    Route::delete('/admin/comment/delete/{id}', [CommentController::class, 'delete'])->name('deletekomentar1');
    Route::put('admin/comment/updatekomentar/{id}', [CommentController::class, 'update1'])->name('updatekomentar1');
    Route::post('admin/comment/postbalaskomentar', [CommentController::class, 'postBalasan1'])->name('postBalasan1');

    // Route Import -------------------------------------------------------------------------------
    Route::post('/admin/mahasiswa/import', [MahasiswaController::class,'import'])->name('mahasiswa.import');
    Route::post('/admin/dosen/import', [DosenController::class,'import'])->name('dosen.import');
    Route::get('/admin/skripsi/detail/{id}', [SkripsiController::class, 'showPdf'])->name('pdf.show');
});


Route::get('/welcome/detail/{id}', [SkripsiController::class, 'welcomeskripsi']);
// Route Detail Mahasiswa ---------------------------------------------------------------------------
Route::middleware('is_mahasiswa')->group(function () {

    Route::get('/home/skripsi/detail/{id}', [SkripsiController::class, 'detailskripsi']);
    Route::get('/home/skripsi', [SkripsiController::class, 'mahasiswa'])->name('mahasiswa.skripsi');
    Route::get('/home/skripsi/cariYangMirip', [SkripsiController::class, 'cariYangMirip'])->name('cariYangMirip');
    Route::get('/home/skripsi/search', [SkripsiController::class, 'searchSkripsi'])->name('searchSkripsi');
    // route::get('/home/skripsi/detail/{id}', [SkripsiController::class, 'showDetail'])->name('skripsi.detail');
    // Route Komentar ---------------------------------------------------------------------------
    Route::post('/home/comment/detail', [CommentController::class, 'postkomentar'])->name('postkomentar');
    Route::post('home/skripsi/detail/comment/postbalaskomentar', [CommentController::class, 'postBalasan'])->name('postBalasan');
    Route::put('home/skripsi/detail/comment/{id}/toggleFavorite', [CommentController::class, 'toggleFavorite'])->name('toggleFavorite');
    Route::put('home/skripsi/detail/comment/updatekomentar/{id}', [CommentController::class, 'update'])->name('updatekomentar');
    Route::delete('/home/skripsi/detail/comment/hapus/{id}', [CommentController::class, 'deletekomentar'])->name('deletekomentar');
// Route favorite  ---------------------------------------------------------------------------
    Route::post('/home/skripsi/{id}/add-favorite', [FavoriteController::class, 'addFavorite'])->name('addFavorite');
    Route::get('/home/favorite', [FavoriteController::class, 'showFavorites'])->name('favorites');
    Route::delete('/home/favorites/{id}', [FavoriteController::class, 'removeFavorite'])->name('removeFavorite');
    Route::delete('home/skripsi/detail/favorites/remove/{id}', [FavoriteController::class, 'removeFavorite1'])->name('removeFavorite1');
     // Route Skripsi ------------------------------------------------------------------------------
     Route::get('/mahasiswa/skripsi', [SkripsiController::class, 'index'])->name('skripsi');
     Route::post('/mahasiswa/skripsi', [SkripsiController::class, 'tambah'])->name('tambah.skripsi');
     Route::patch('/mahasiswa/skripsi/ubah', [SkripsiController::class, 'ubah'])->name('ubah.skripsi');
     Route::get('mahasiswa/ajaxmahasiswa/dataSkripsi/{id}', [SkripsiController::class, 'getDataSkripsi']);
     Route::get('/mahasiswa/skripsi/hapus/{id}', [SkripsiController::class, 'hapus'])->name('hapus.skripsi');
     Route::get('/mahasiswa/skripsi/detail/{id}', [SkripsiController::class, 'detailskripsi']);
     route::get('/home/skripsi/cariYangMirip', [SkripsiController::class, 'cariYangMirip'])->name('cariYangMirip');
});

// View File PDF -----------------------------------------------------------------------------------
// Route::middleware('is_dosen')->group(function () {

//     Route::get('/dosen/skripsi', [SkripsiController::class, 'indexDosen'])->name('dosenskripsi');
//     Route::post('/dosen/skripsi', [SkripsiController::class, 'add'])->name('add.skripsi');
//     Route::patch('/dosen/skripsi/ubah1', [SkripsiController::class, 'ubah1'])->name('ubah1.skripsi');
//     Route::get('dosen/ajaxdosen/dataSkripsi/{id}', [SkripsiController::class, 'ambilDataSkripsi']);
//     Route::get('/dosen/skripsi/delete/{id}', [SkripsiController::class, 'delete'])->name('delete.skripsi');

//     Route::get('/dosen/skripsi/detail/{id}', [SkripsiController::class, 'tampilPdf'])->name('pdf.show');

// });
