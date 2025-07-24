<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Skripsi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // public function __construct()
    // {
    //     $user = Auth::user();
    //     if($user->roles_id == 2 && $user->status == 0){
    //         auth()->logout();
    //     }
    // }

    public function index()
        {
            $user = Auth::user();
            $jumlahDosen = Dosen::count();
            $jumlahMahasiswa = User::where('roles_id', 2)->count();
            $jumlahSkripsi = Skripsi::count();
            $status = Skripsi::where('user_id', Auth()->user()->id)->first();

            if (!$status) {
                $status = null;
            }

            return view('home', compact(
                'user',
                'jumlahDosen',
                'jumlahMahasiswa',
                'jumlahSkripsi',
                'status'
            ));
        }


    public function welcome(Request $request)
    {
        // Apakah user sudah menekan tombol Cari (atau setidaknya mengisi satu filter)?
        $hasFilter = $request->filled('judul')
                   || $request->filled('penulis')
                   || $request->filled('tahun')
                   || $request->filled('subject')
                   || $request->filled('keywords');

        $results = null;

        if ($hasFilter) {
            $q = Skripsi::with('metadata')
                ->select('id','judul','penulis','dospem','rilis','halaman')
                ->orderBy('created_at', 'desc');

            if ($request->filled('judul')) {
                $q->where('judul', 'LIKE', '%'.$request->judul.'%');
            }
            if ($request->filled('penulis')) {
                $q->where('penulis', 'LIKE', '%'.$request->penulis.'%');
            }
            if ($request->filled('tahun')) {
                $q->where('rilis', $request->tahun);
            }
            if ($request->filled('subject') || $request->filled('keywords')) {
                $q->whereHas('metadata', function($m) use ($request) {
                    if ($request->filled('subject')) {
                        $m->where('subject', 'LIKE', '%'.$request->subject.'%');
                    }
                    if ($request->filled('keywords')) {
                        $m->where('keywords', 'LIKE', '%'.$request->keywords.'%');
                    }
                });
            }

            // paginate dan sertakan query string (so panggilan page=2 tetap bawa filter)
            $results = $q->paginate(10)->withQueryString();
        }

        // Get total number of students
        $jumlahMahasiswa = User::where('roles_id', 2)->count();

        // Get total number of lecturers
        $jumlahDosen = Dosen::count(); // Replace 'Dosen' with your actual model name

        // Get total number of skripsi for statistics
        $jumlahSkripsi = Skripsi::count();

        // return view('welcome', compact('skripsis', 'jumlahMahasiswa', 'jumlahDosen', 'jumlahSkripsi', 'hasFilter'));

        return view('welcome', [
            'results'   => $results,
            'hasFilter' => $hasFilter,
            'jumlahMahasiswa' => $jumlahMahasiswa,
            'jumlahDosen' => $jumlahDosen,
            'jumlahSkripsi' => $jumlahSkripsi,
            'skripsis' => Skripsi::with('metadata')->orderBy('created_at', 'desc')->paginate(10)->withQueryString(),
        ]);
    }
}
