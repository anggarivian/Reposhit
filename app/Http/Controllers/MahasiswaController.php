<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    // Read Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function index() {
        $mahasiswa = User::where('roles_id', 2 )->get();
        return view('mahasiswa', compact('mahasiswa'));
    }

    // Tambah Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function tambah(Request $req){

        // Validasi Data Mahasiswa ----------------------------------------------------------------
        $req->validate([
            'name' => 'required|string|max:30',
            'npm' => 'required|string|max:10|min:10|unique:users,npm',
            // 'email' => 'required|email|max:50|unique:users,email',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'angkatan' => 'required|string|max:4|min:4',
            'password' => 'required|string|min:8|max:255',
            'prodi' => 'required|string|max:25',
        ]);

        // Create Data Mahasiswa ------------------------------------------------------------------
        $mahasiswa = new User;

        $mahasiswa->name = $req->get('name');
        $mahasiswa->npm = $req->get('npm');
        // $mahasiswa->email = $req->get('email');
        $mahasiswa->tgl_lahir = $req->get('tgl_lahir');
        $mahasiswa->alamat = $req->get('alamat');
        $mahasiswa->angkatan = $req->get('angkatan');
        $mahasiswa->prodi = $req->get('prodi');
        $mahasiswa->password = Hash::make($req->get('password'));
        $mahasiswa->roles_id = 2 ;

        $mahasiswa->save();

        $notification = array(
            'message' =>'Data Mahasiswa berhasil ditambahkan', 'alert-type' =>'success'
        );
        return redirect()->route('mahasiswa')->with($notification);
    }

    // Get Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function getDataMahasiswa($id){
        $mahasiswa = User::find($id);
        return response()->json($mahasiswa);
    }

    // Ubah Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function ubah(Request $req) {

        // Validasi Data Mahasiswa ----------------------------------------------------------------
        $req->validate([
            'name' => 'required|string|max:30',
            'npm' => 'required|string|max:10|min:10',
            // 'email' => 'required|email|max:50',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'angkatan' => 'required|string|max:4|min:4',
            'prodi' => 'required|string|max:25',
        ]);

        // Update Data Mahasiswa ------------------------------------------------------------------
        $mahasiswa = User::find($req->get('id'));

        $mahasiswa->name = $req->get('name');
        $mahasiswa->npm = $req->get('npm');
        // $mahasiswa->email = $req->get('email');
        $mahasiswa->tgl_lahir = $req->get('tgl_lahir');
        $mahasiswa->alamat = $req->get('alamat');
        $mahasiswa->angkatan = $req->get('angkatan');
        $mahasiswa->prodi = $req->get('prodi');

        $mahasiswa->save();

        $notification = array(
            'message' => 'Data Mahasiswa berhasil diubah',
            'alert-type' => 'success'
        );
        return redirect()->route('mahasiswa')->with($notification);
    }

    // Hapus Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function hapus($id){
        $mahasiswa = User::find($id);

        $mahasiswa->delete();

        $success = true;
        $message = "Data Mahasiswa berhasil dihapus";

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    // Import Mahasiswa --------------------------------------------------------------------------------------------------
    public function import(Request $req){
        Excel::import(new MahasiswaImport, $req->file('file'));

        $notification = array (
            'message' => 'Import data berhasil dilakukan',
            'alert-type' => 'success'
        );

        return redirect()->route('mahasiswa')->with($notification);
    }

    public function toggleVerifikasi($id) {
        $mahasiswa = User::find($id);

        // Toggle status verifikasi
        if ($mahasiswa->status == 0) {
            $mahasiswa->status = 1;
            $message = "Data Mahasiswa berhasil diverifikasi";
        } else {
            $mahasiswa->status = 0;
            $message = "Data Mahasiswa berhasil dibatalkan verifikasinya";
        }

        $mahasiswa->save();

        $success = true;

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

}
