<?php

namespace App\Http\Controllers;
use App\Models\favorite;
use App\Models\Skripsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function addFavorite($id)
{
    // Ambil ID pengguna
    $userId = auth()->user()->id;

    // Tambahkan skripsi ke favorite
    favorite::updateOrCreate([
        'id_user' => $userId,
        'id_skripsi' => $id
    ]);

    // Kembali dengan notifikasi
    return redirect()->back()->with('favorite', 'Skripsi berhasil ditambahkan ke favorite.')
        ->with('favorite_type', 'add'); // Tambahkan tipe aksi
}

    public function showFavorites()
    {
        $userId = auth()->user()->id;

            $skripsifavorite = DB::table('favorites')
                ->join('skripsis', 'skripsis.id', '=', 'favorites.id_skripsi')
                ->join('users', 'skripsis.penulis', '=', 'users.name')
                ->join('jurusans', 'users.jurusan_id', '=', 'jurusans.id')
                ->where('favorites.id_user', $userId)
                ->select('skripsis.*', 'jurusans.nama_jurusan as prodi')
                ->get();
        // $favoriteSkripsi = Favorite::where('id_user', $userId)
        //                            ->with('skripsi') // Pastikan relasi ada di model Favorite
        //                            ->get()
        //                            ->pluck('skripsi'); // Mengambil data skripsi dari relasi

        return view('tampilfavorite', compact('skripsifavorite'));
    }

    public function removeFavorite($id)
    {
        $user = auth()->user();

        // Menghapus favorite dari user yang login
        $user->favorites()->detach($id);

        return redirect()->route('favorites')->with('success', 'Skripsi berhasil dihapus dari favorite.');
    }
    public function removeFavorite1($id)
{
    $user = auth()->user();

    // Menghapus favorite dari user yang login
    $user->favorites()->detach($id);

    return redirect()->back()->with('favorite', 'Skripsi berhasil dihapus dari favorite.')
        ->with('favorite_type', 'remove'); // Tambahkan tipe aksi
}
}
