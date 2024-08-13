<?php

namespace App\Http\Controllers;
use App\Models\favorite;
use App\Models\Skripsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        return redirect()->back()->with('favorite', 'Skripsi ini telah ditambahkan ke favorite.');
    }

    public function showFavorites()
    {
        $userId = auth()->user()->id;

        $favoriteSkripsi = Favorite::where('id_user', $userId)
                                   ->with('skripsi') // Pastikan relasi ada di model Favorite
                                   ->get()
                                   ->pluck('skripsi'); // Mengambil data skripsi dari relasi

        return view('tampilfavorite', compact('favoriteSkripsi'));
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

        return redirect()->back()->with('success', 'Skripsi berhasil dihapus dari favorite.');
    }
}
