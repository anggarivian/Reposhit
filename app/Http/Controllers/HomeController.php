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
        // $jumlahSkripsiBelumDiverifikasi = Skripsi::where('status', 0)->count();
        // $jumlahSkripsiDiverifikasi = Skripsi::where('status', 1)->count();

        // if($user->roles_id == 2 && $user->status == 0){
        //     Auth::logout();
        //     $notification = array(
        //         'message' =>'Anda Belum Verifikasi', 'alert-type' =>'warning'
        //     );
        //     return redirect()->route('welcome')->with($notification);
        // }

        return view('home', compact('user', 'jumlahDosen', 'jumlahMahasiswa', 'jumlahSkripsi'));
    }

    public function welcome(Request $req){

        $query = Skripsi::query();
        $query->select('id','judul','penulis','rilis','dospem','halaman');
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
