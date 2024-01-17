<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Skripsi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        $user = Auth::user();
        $jumlahDosen = Dosen::count();
        $jumlahMahasiswa = User::count();
        $jumlahSkripsi = Skripsi::count();

        return view('home', compact('user', 'jumlahDosen', 'jumlahMahasiswa', 'jumlahSkripsi'));
    }
    public function welcome(Request $req){

        $query = Skripsi::query();
        $query->select('id','judul','penulis','rilis', 'abstrak','dospem','halaman');
        if(!empty($req->judul)){
            $query->where('judul', 'LIKE', '%' . $req->judul . '%');
        }
        if(!empty($req->penulis)){
            $query->where('penulis', 'LIKE', '%' . $req->penulis . '%');
        }
        if(!empty($req->rilis)){
            $query->where('rilis', 'LIKE', '%' . $req->rilis . '%');
        }
        $query->orderBy('created_at','desc');
        //End Searching
        $skripsi = $query->paginate(10);

        return view('welcome', compact('skripsi'));
    }
}
