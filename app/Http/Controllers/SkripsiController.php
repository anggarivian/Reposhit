<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SkripsiController extends Controller
{
    // Tambah Data Skripsi ----------------------------------------------------------------------------------------------
    public function index() {
        $skripsi = Skripsi::All();
        $namaDospem = Dosen::All();
        $namaPenulis = User::where('roles_id', 2)->get();
        return view('skripsi', compact('skripsi', 'namaDospem', 'namaPenulis'));
    }

    public function mahasiswa() {
        $skripsi = Skripsi::All();
        return view('skripsi2', compact('skripsi'));
    }

    // Landing Page -----------------------------------------------------------------------------------------------------
    public function landingpage(Request $req) {

        //Searching Data Skripsi ----------------------------------------------------------------------------------------
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

    // Tambah Data Skripsi ----------------------------------------------------------------------------------------------
    public function tambah(Request $req){

        // Validasi Data Skripsi ----------------------------------------------------------------
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30',
            'abstrak' => 'required|string',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|max:4|min:4',
            'halaman' => 'required|min:1',
            'cover' => 'nullable|mimes:pdf|max:2048',
            'bab1' => 'nullable|mimes:pdf|max:2048',
            'bab2' => 'nullable|mimes:pdf|max:2048',
            'bab3' => 'nullable|mimes:pdf|max:2048',
            'bab4' => 'nullable|mimes:pdf|max:2048',
            'bab5' => 'nullable|mimes:pdf|max:2048',
            'dapus' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Create Data Skripsi ------------------------------------------------------------------
        $skripsi = new Skripsi;

        $skripsi->judul = $req->get('judul');
        $skripsi->penulis = $req->get('penulis');
        $skripsi->abstrak = $req->get('abstrak');
        $skripsi->dospem = $req->get('dospem');
        $skripsi->rilis = $req->get('rilis');
        $skripsi->halaman = $req->get('halaman');

        function uploadFile($req, $fieldName, $storagePath){
            if ($req->hasFile($fieldName)) {
                $extension = $req->file($fieldName)->extension();
                $filename = $fieldName . '_skripsi' . time() . '.' . $extension;
                $req->file($fieldName)->storeAs('public/' . $storagePath, $filename);
                return $filename;
            }
            return null;
        }

        $skripsi->cover = uploadFile($req, 'cover', 'cover_skripsi');
        $skripsi->bab1 = uploadFile($req, 'bab1', 'bab1_skripsi');
        $skripsi->bab2 = uploadFile($req, 'bab2', 'bab2_skripsi');
        $skripsi->bab3 = uploadFile($req, 'bab3', 'bab3_skripsi');
        $skripsi->bab4 = uploadFile($req, 'bab4', 'bab4_skripsi');
        $skripsi->bab5 = uploadFile($req, 'bab5', 'bab5_skripsi');
        $skripsi->dapus = uploadFile($req, 'dapus', 'dapus_skripsi');
        $skripsi->save();
        $notification = array(
            'message' =>'Data Skripsi berhasil ditambahkan', 'alert-type' =>'success'
        );
        return redirect()->route('skripsi')->with($notification);
    }

    public function uploadFile($req, $fieldName, $storagePath){
        if ($req->hasFile($fieldName)) {
            $extension = $req->file($fieldName)->extension();
            $filename = $fieldName . '_skripsi' . time() . '.' . $extension;
            $req->file($fieldName)->storeAs('public/' . $storagePath, $filename);
            return $filename;
        }

        return null;
    }

    // Tampil PDF  Skripsi ----------------------------------------------------------------------------------------------
    public function showPdf($id){
        $data = Skripsi::find($id);
        if ($data) {
            $pdfPath = $data->file; // Tentukan kolom yang berisi path file PDF di model Anda
            return response()->file(Storage::path('public/file_skripsi/'.$pdfPath));
        }
        return abort(404);
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function getDataSkripsi($id){
        $skripsi = Skripsi::find($id);
        return response()->json($skripsi);
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function detailskripsi($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        $pdfPath = storage_path('app/public/file_skripsi/' . $data->file);

        if (!Storage::exists('public/file_skripsi/' . $data->file)) {
            abort(404);
        }

        $pdfContent = Storage::get('public/file_skripsi/' . $data->file);
        $pdf = base64_encode($pdfContent);

        return view('detail', compact('pdf', 'user', 'skripsi'));
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function welcomeskripsi($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        $pdfPath = storage_path('app/public/file_skripsi/' . $data->file);

        if (!Storage::exists('public/file_skripsi/' . $data->file)) {
            abort(404);
        }

        $pdfContent = Storage::get('public/file_skripsi/' . $data->file);
        $pdf = base64_encode($pdfContent);

        return view('welcomeskripsi', compact('pdf', 'user', 'skripsi'));
    }

    // Ubah Data Skripsi ----------------------------------------------------------------------------------------------
    public function ubah(Request $req) {

        // Validasi Data Skripsi ----------------------------------------------------------------
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30',
            'abstrak' => 'required|string',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|max:4|min:4',
            'halaman' => 'required|min:1',
        ]);

        // Update Data Skripsi ------------------------------------------------------------------
        $skripsi = Skripsi::find($req->get('id'));

        $skripsi->judul = $req->get('judul');
        $skripsi->penulis = $req->get('penulis');
        $skripsi->abstrak = $req->get('abstrak');
        $skripsi->dospem = $req->get('dospem');
        $skripsi->rilis = $req->get('rilis');
        $skripsi->halaman = $req->get('halaman');

        function uploadFile($req, $fieldName, $storagePath, $oldFileName = null){
            if ($req->hasFile($fieldName)) {
                $extension = $req->file($fieldName)->extension();
                $filename = $fieldName . '_skripsi' . time() . '.' . $extension;
                $req->file($fieldName)->storeAs('public/' . $storagePath, $filename);
                // Delete old file
                if($oldFileName) {
                    Storage::delete('public/' . $storagePath . '/' . $oldFileName);
                }
                return $filename;
            }
            return $oldFileName;
        }

        $skripsi->cover = uploadFile($req, 'cover', 'cover_skripsi', $skripsi->cover);
        $skripsi->bab1 = uploadFile($req, 'bab1', 'bab1_skripsi', $skripsi->bab1);
        $skripsi->bab2 = uploadFile($req, 'bab2', 'bab2_skripsi', $skripsi->bab2);
        $skripsi->bab3 = uploadFile($req, 'bab3', 'bab3_skripsi', $skripsi->bab3);
        $skripsi->bab4 = uploadFile($req, 'bab4', 'bab4_skripsi', $skripsi->bab4);
        $skripsi->bab5 = uploadFile($req, 'bab5', 'bab5_skripsi', $skripsi->bab5);
        $skripsi->dapus = uploadFile($req, 'dapus', 'dapus_skripsi', $skripsi->dapus);

        $skripsi->save();

        $notification = array(
            'message' => 'Data Skripsi berhasil diubah',
            'alert-type' => 'success'
        );
        return redirect()->route('skripsi')->with($notification);
    }

    // Hapus Data Skripsi ----------------------------------------------------------------------------------------------
    public function hapus($id){
        $skripsi = Skripsi::find($id);

        $skripsi->delete();

        $success = true;
        $message = "Data Skripsi berhasil dihapus";

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
