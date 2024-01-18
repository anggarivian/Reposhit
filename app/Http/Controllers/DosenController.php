<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Imports\DosenImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function index() {
        $dosen = Dosen::All();
        return view('dosen', compact('dosen'));
    }

    // Tambah Data Dosen ----------------------------------------------------------------------------------------------
    public function tambah(Request $req){
        $dosen = new Dosen;

        $dosen->nama = $req->get('name');
        $dosen->nip = $req->get('nip');
        $dosen->email = $req->get('email');
        $dosen->jabatan = $req->get('jabatan');
        $dosen->kontak = $req->get('kontak');
        $dosen->alamat = $req->get('alamat');
        $dosen->tgl_lahir = $req->get('tgl_lahir');
        if ($req->gelar_akademik == null){
            $dosen->gelar_akademik = '-';
        } else {
            $dosen->gelar_akademik = $req->get('gelar_akademik');
        }
        $dosen->program_studi = $req->get('program_studi');

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
        $dosen = Dosen::find($req->get('id'));

        $dosen->nama = $req->get('name');
        $dosen->nip = $req->get('nip');
        $dosen->email = $req->get('email');
        $dosen->jabatan = $req->get('jabatan');
        $dosen->kontak = $req->get('kontak');
        $dosen->alamat = $req->get('alamat');
        $dosen->tgl_lahir = $req->get('tgl_lahir');
        $dosen->gelar_akademik = $req->get('gelar_akademik');
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
