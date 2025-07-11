<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function fetch()
    {
        $user = Auth::user();

        $notifikasis = Notifikasi::where('mahasiswa_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'count' => $notifikasis->count(),
            'data' => $notifikasis
        ]);
    }
}
