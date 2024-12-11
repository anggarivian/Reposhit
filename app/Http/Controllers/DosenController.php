<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Imports\DosenImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function index() {
        $dosen = Dosen::All();
        return view('dosen', compact('dosen'));
    }

    // Tambah Data Dosen ----------------------------------------------------------------------------------------------
    public function tambah(Request $req){

        // Validasi Data Dosen ----------------------------------------------------------------
        $req->validate([
            'nama' => 'required|string|max:30',
            'nip' => 'required|string|max:18|min:18|unique:dosens,nip',
            // 'jabatan' => 'required|string|max:15',
            'kontak' => 'required|string|max:12',
            // 'alamat' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'program_studi' => 'required|string|max:40',
            // 'password' => 'required|string|min:8|max:255',
        ]);

        // dd($req);

        // $user = new User;

        // $user->name = $req->get('nama');
        // $user->npm = $req->get('nip');
        // $user->status = 1;
        // $user->tgl_lahir = $req->get('tgl_lahir');
        // $user->alamat = $req->get('alamat');
        // $user->angkatan = '2020';
        // $user->prodi = $req->get('program_studi');
        // $user->password = Hash::make($req->get('password'));
        // $user->roles_id = 3;
        // $user->save();

        // Create Data Dosen ------------------------------------------------------------------
        $dosen = new Dosen;

        $dosen->nama = $req->get('nama');
        $dosen->nip = $req->get('nip');
        // $dosen->jabatan = $req->get('jabatan');
        $dosen->kontak = $req->get('kontak');
        // $dosen->alamat = $req->get('alamat');
        $dosen->tgl_lahir = $req->get('tgl_lahir');
        $dosen->program_studi = $req->get('program_studi');
        // $dosen->password = Hash::make($req->get('password'));

        $dosen->save();

        $notification = array(
            'message' =>'Data Dosen berhasil ditambahkan', 'alert-type' =>'success'
        );
        return redirect()->route('dosen')->with($notification);
    }

    // Get Data Dosen ----------------------------------------------------------------------------------------------
    public function getDataDosen($id){
        $dosen = Dosen::find($id);
        return response()->json($dosen);
    }

    // Ubah Data Dosen ----------------------------------------------------------------------------------------------
    public function ubah(Request $req) {

        // Validasi Data Dosen ----------------------------------------------------------------
        $req->validate([
            'nama' => 'required|string|max:30',
            'nip' => 'required|string|max:18|min:18',
            // 'email' => 'required|email|max:50',
            // 'jabatan' => 'required|string|max:15',
            'kontak' => 'required|string|max:12',
            // 'alamat' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'program_studi' => 'required|string|max:40',
        ]);

        // Update Data Dosen ------------------------------------------------------------------
        $dosen = Dosen::find($req->get('id'));

        $dosen->nama = $req->get('nama');
        $dosen->nip = $req->get('nip');
        // $dosen->email = $req->get('email');
        // $dosen->jabatan = $req->get('jabatan');
        $dosen->kontak = $req->get('kontak');
        // $dosen->alamat = $req->get('alamat');
        $dosen->tgl_lahir = $req->get('tgl_lahir');
        $dosen->program_studi = $req->get('program_studi');

        $dosen->save();

        $notification = array(
            'message' => 'Data Dosen berhasil diubah',
            'alert-type' => 'success'
        );
        return redirect()->route('dosen')->with($notification);
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
