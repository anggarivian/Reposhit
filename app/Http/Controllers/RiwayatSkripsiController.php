<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\History; // Make sure this model exists
use App\Models\Skripsi; // Make sure this model exists

class RiwayatSkripsiController extends Controller
{
    public function showHistory()
    {
        $userId = auth()->user()->id;

        $history = DB::table('riwayat_skripsis')
            ->join('skripsis', 'skripsis.id', '=', 'riwayat_skripsis.id_skripsi')
            ->join('users', 'skripsis.penulis', '=', 'users.name')
            ->leftJoin('jurusans', 'users.jurusan_id', '=', 'jurusans.id') // Tambahan join ke jurusan
            ->where('riwayat_skripsis.id_user', $userId)
            ->select('skripsis.*', 'jurusans.nama_jurusan as prodi') // Ubah dari users.prodi ke jurusans.nama_jurusan
            ->get();

        return view('riwayatskripsi', compact('history'));
    }

    public function showDetail($id)
    {
        $skripsi = Skripsi::findOrFail($id);

        // Tambahkan ke riwayat
        History::updateOrCreate(
            ['id_user' => auth()->id(), 'id_skripsi' => $id],
            ['created_at' => now()]
        );

        return view('detail', compact('skripsi'));
    }

    // Add this new method to delete a single history item
    public function deleteHistory($id)
    {
        $userId = auth()->user()->id;
        
        DB::table('riwayat_skripsis')
            ->where('id_user', $userId)
            ->where('id_skripsi', $id)
            ->delete();
        
        return redirect()->route('riwayatskripsi')->with([
            'history' => 'Skripsi berhasil dihapus dari riwayat',
            'history_type' => 'success'
        ]);
    }

    // Add this new method to delete all history items
    public function deleteAllHistory()
    {
        $userId = auth()->user()->id;
        
        DB::table('riwayat_skripsis')
            ->where('id_user', $userId)
            ->delete();
        
        return redirect()->route('riwayatskripsi')->with([
            'history' => 'Semua riwayat skripsi berhasil dihapus',
            'history_type' => 'success'
        ]);
    }
}