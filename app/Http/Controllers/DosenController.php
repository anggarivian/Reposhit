<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Jurusan;
use App\Imports\DosenImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function index() {
        $dosen = Dosen::with('jurusan')->get();
        $jurusan = Jurusan::all();
        return view('dosen', compact('dosen', 'jurusan'));
    }

    // Tambah Data Dosen ----------------------------------------------------------------------------------------------
    public function tambah(Request $req){
    $req->validate([
        'nama' => 'required|string|max:30',
        'nip' => 'required|string|max:18|min:18|unique:dosens,nip',
        'kontak' => 'required|string|max:12',
        'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        $dosen = new Dosen;
        $dosen->nama = $req->nama;
        $dosen->nip = $req->nip;
        $dosen->kontak = $req->kontak;
        $dosen->jurusan_id = $req->jurusan_id;
        $dosen->save();

    return redirect()->route('dosen')->with(['message' => 'Data Dosen berhasil ditambahkan', 'alert-type' =>'success']);
}

    // Get Data Dosen ----------------------------------------------------------------------------------------------
    public function getDataDosen($id){
        $dosen = Dosen::find($id);
        return response()->json($dosen);
    }
    // Ubah Data Dosen ----------------------------------------------------------------------------------------------
    public function ubah(Request $req){
    $req->validate([
        'nama' => 'required|string|max:30',
        'nip' => 'required|string|max:18|min:18',
        'kontak' => 'required|string|max:12',
        'jurusan_id' => 'required|exists:jurusans,id',
    ]);

    $dosen = Dosen::find($req->id);
    $dosen->nama = $req->nama;
    $dosen->nip = $req->nip;
    $dosen->kontak = $req->kontak;
    $dosen->jurusan_id = $req->jurusan_id;
    $dosen->save();

    return redirect()->route('dosen')->with(['message' => 'Data Dosen berhasil diubah', 'alert-type' => 'success']);
}

    // Hapus Data Dosen ----------------------------------------------------------------------------------------------
    public function hapus($id){
        $dosen = Dosen::find($id);

        $dosen->delete();

        $success = true;
        $message = "Data Dosen berhasil dihapus";

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    // Import Dosen --------------------------------------------------------------------------------------------------
    public function import(Request $req){
        Excel::import(new DosenImport, $req->file('file'));

        $notification = array (
            'message' => 'Import data berhasil dilakukan',
            'alert-type' => 'success'
        );

        return redirect()->route('dosen')->with($notification);
    }
}
