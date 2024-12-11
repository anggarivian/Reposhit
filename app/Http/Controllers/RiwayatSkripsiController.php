<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RiwayatSkripsiController extends Controller
{
    public function showHistory()
    {
        $userId = auth()->user()->id;
    
        $history = DB::table('riwayat_skripsis')
                ->join('skripsis', 'skripsis.id', '=', 'riwayat_skripsis.id_skripsi')
                ->join('users', 'skripsis.penulis', '=', 'users.name')
                ->where('riwayat_skripsis.id_user', $userId)
                ->select('skripsis.*', 'users.prodi') // Tidak menyertakan 'created_at'
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
}
