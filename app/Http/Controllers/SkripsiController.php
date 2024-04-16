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

    // Tambah Data Skripsi ----------------------------------------------------------------------------------------------
    public function tambah(Request $req){

        // Validasi Data Skripsi ----------------------------------------------------------------
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|max:4|min:4',
            'halaman' => 'required|min:1',
            'cover' => 'nullable|mimes:pdf|max:2048',
            'pengesahan' => 'nullable|mimes:pdf|max:2048',
            'abstrak' => 'nullable|mimes:pdf|max:2048',
            'daftarisi' => 'nullable|mimes:pdf|max:2048',
            'daftargambar' => 'nullable|mimes:pdf|max:2048',
            'daftarlampiran' => 'nullable|mimes:pdf|max:2048',
            'bab1' => 'nullable|mimes:pdf|max:2048',
            'bab2' => 'nullable|mimes:pdf|max:2048',
            'bab3' => 'nullable|mimes:pdf|max:2048',
            'bab4' => 'nullable|mimes:pdf|max:2048',
            'bab5' => 'nullable|mimes:pdf|max:2048',
            'dapus' => 'nullable|mimes:pdf|max:2048',
            // 'lampiran' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Create Data Skripsi ------------------------------------------------------------------
        $skripsi = new Skripsi;

        $skripsi->judul = $req->get('judul');
        $skripsi->penulis = $req->get('penulis');
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
        $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi');
        $skripsi->abstrak = uploadFile($req, 'abstrak', 'abstrak_skripsi');
        $skripsi->daftarisi = uploadFile($req, 'daftarisi', 'daftarisi_skripsi');
        $skripsi->daftargambar = uploadFile($req, 'daftargambar', 'daftargambar_skripsi');
        $skripsi->daftarlampiran = uploadFile($req, 'daftarlampiran', 'daftarlampiran_skripsi');
        $skripsi->bab1 = uploadFile($req, 'bab1', 'bab1_skripsi');
        $skripsi->bab2 = uploadFile($req, 'bab2', 'bab2_skripsi');
        $skripsi->bab3 = uploadFile($req, 'bab3', 'bab3_skripsi');
        $skripsi->bab4 = uploadFile($req, 'bab4', 'bab4_skripsi');
        $skripsi->bab5 = uploadFile($req, 'bab5', 'bab5_skripsi');
        $skripsi->dapus = uploadFile($req, 'dapus', 'dapus_skripsi');
        // $skripsi->lampiran = uploadFile($req, 'lampiran', 'dapus_skripsi');
        $skripsi->save();
        $notification = array(
            'message' =>'Data Skripsi berhasil ditambahkan', 'alert-type' =>'success'
        );
        return redirect()->route('skripsi')->with($notification);
    }

    // Ubah Data Skripsi ----------------------------------------------------------------------------------------------
    public function ubah(Request $req) {

        // Validasi Data Skripsi ----------------------------------------------------------------
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|max:4|min:4',
            'halaman' => 'required|min:1',
        ]);

        // Update Data Skripsi ------------------------------------------------------------------
        $skripsi = Skripsi::find($req->get('id'));

        $skripsi->judul = $req->get('judul');
        $skripsi->penulis = $req->get('penulis');
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
        $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi', $skripsi->pengesahan);
        $skripsi->abstrak = uploadFile($req, 'abstrak', 'abstrak_skripsi', $skripsi->abstrak);
        $skripsi->daftarisi = uploadFile($req, 'daftarisi', 'daftarisi_skripsi', $skripsi->daftarisi);
        $skripsi->daftargambar = uploadFile($req, 'daftargambar', 'daftargambar_skripsi', $skripsi->daftargambar);
        $skripsi->daftarlampiran = uploadFile($req, 'daftarlampiran', 'daftarlampiran_skripsi', $skripsi->daftarlampiran);
        $skripsi->bab1 = uploadFile($req, 'bab1', 'bab1_skripsi', $skripsi->bab1);
        $skripsi->bab2 = uploadFile($req, 'bab2', 'bab2_skripsi', $skripsi->bab2);
        $skripsi->bab3 = uploadFile($req, 'bab3', 'bab3_skripsi', $skripsi->bab3);
        $skripsi->bab4 = uploadFile($req, 'bab4', 'bab4_skripsi', $skripsi->bab4);
        $skripsi->bab5 = uploadFile($req, 'bab5', 'bab5_skripsi', $skripsi->bab5);
        $skripsi->dapus = uploadFile($req, 'dapus', 'dapus_skripsi', $skripsi->dapus);
        // $skripsi->lampiran = uploadFile($req, 'lampiran', 'lampiran_skripsi', $skripsi->lampiran);

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

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function getDataSkripsi($id){
        $skripsi = Skripsi::find($id);
        return response()->json($skripsi);
    }

    // Tampil PDF  Skripsi ----------------------------------------------------------------------------------------------
    public function showPdf($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
            'abstrak' => storage_path('app/public/abstrak_skripsi/' . $data->abstrak),
            'daftarisi' => storage_path('app/public/daftarisi_skripsi/' . $data->daftarisi),
            'daftargambar' => storage_path('app/public/daftargambar_skripsi/' . $data->daftargambar),
            'daftarlampiran' => storage_path('app/public/daftarlampiran_skripsi/' . $data->daftarlampiran),
            'bab1' => storage_path('app/public/bab1_skripsi/' . $data->bab1),
            'bab2' => storage_path('app/public/bab2_skripsi/' . $data->bab2),
            'bab3' => storage_path('app/public/bab3_skripsi/' . $data->bab3),
            'bab4' => storage_path('app/public/bab4_skripsi/' . $data->bab4),
            'bab5' => storage_path('app/public/bab5_skripsi/' . $data->bab5),
            'dapus' => storage_path('app/public/dapus_skripsi/' . $data->dapus),
            // 'lampiran' => storage_path('app/public/lampiran_skripsi/' . $data->lampiran),
        ];

        // Memeriksa apakah setiap file PDF ada di storage
        foreach ($pdfPaths as $attribute => $pdfPath) {
            if (!Storage::exists('public/' . $attribute . '_skripsi/' . $data->$attribute)) {
                abort(404);
            }
        }

        // Mengambil konten setiap file PDF
        $pdfContents = [];
        foreach ($pdfPaths as $attribute => $pdfPath) {
            $pdfContents[$attribute] = Storage::get('public/' . $attribute . '_skripsi/' . $data->$attribute);
        }

        // Mengubah konten setiap file PDF menjadi base64
        $pdfs = [];
        foreach ($pdfContents as $attribute => $pdfContent) {
            $pdfs[$attribute] = base64_encode($pdfContent);
        }

        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('skripsiadmin', compact('pdfs', 'user', 'skripsi'));
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function detailskripsi($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
            'abstrak' => storage_path('app/public/abstrak_skripsi/' . $data->abstrak),
            'daftarisi' => storage_path('app/public/daftarisi_skripsi/' . $data->daftarisi),
            'daftargambar' => storage_path('app/public/daftargambar_skripsi/' . $data->daftargambar),
            'daftarlampiran' => storage_path('app/public/daftarlampiran_skripsi/' . $data->daftarlampiran),
            'bab1' => storage_path('app/public/bab1_skripsi/' . $data->bab1),
            'bab2' => storage_path('app/public/bab2_skripsi/' . $data->bab2),
            'bab3' => storage_path('app/public/bab3_skripsi/' . $data->bab3),
            'bab4' => storage_path('app/public/bab4_skripsi/' . $data->bab4),
            'bab5' => storage_path('app/public/bab5_skripsi/' . $data->bab5),
            'dapus' => storage_path('app/public/dapus_skripsi/' . $data->dapus),
            // 'lampiran' => storage_path('app/public/lampiran_skripsi/' . $data->lampiran),
        ];

        // Memeriksa apakah setiap file PDF ada di storage
        foreach ($pdfPaths as $attribute => $pdfPath) {
            if (!Storage::exists('public/' . $attribute . '_skripsi/' . $data->$attribute)) {
                abort(404);
            }
        }

        // Mengambil konten setiap file PDF
        $pdfContents = [];
        foreach ($pdfPaths as $attribute => $pdfPath) {
            $pdfContents[$attribute] = Storage::get('public/' . $attribute . '_skripsi/' . $data->$attribute);
        }

        // Mengubah konten setiap file PDF menjadi base64
        $pdfs = [];
        foreach ($pdfContents as $attribute => $pdfContent) {
            $pdfs[$attribute] = base64_encode($pdfContent);
        }

        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('detail', compact('pdfs', 'user', 'skripsi'));
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function welcomeskripsi($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
            'abstrak' => storage_path('app/public/abstrak_skripsi/' . $data->abstrak),
            'daftarisi' => storage_path('app/public/daftarisi_skripsi/' . $data->daftarisi),
            'daftargambar' => storage_path('app/public/daftargambar_skripsi/' . $data->daftargambar),
            'daftarlampiran' => storage_path('app/public/daftarlampiran_skripsi/' . $data->daftarlampiran),
            'bab1' => storage_path('app/public/bab1_skripsi/' . $data->bab1),
            'bab2' => storage_path('app/public/bab2_skripsi/' . $data->bab2),
            'bab3' => storage_path('app/public/bab3_skripsi/' . $data->bab3),
            'bab4' => storage_path('app/public/bab4_skripsi/' . $data->bab4),
            'bab5' => storage_path('app/public/bab5_skripsi/' . $data->bab5),
            'dapus' => storage_path('app/public/dapus_skripsi/' . $data->dapus),
            // 'lampiran' => storage_path('app/public/lampiran_skripsi/' . $data->lampiran),
        ];

        // Memeriksa apakah setiap file PDF ada di storage
        foreach ($pdfPaths as $attribute => $pdfPath) {
            if (!Storage::exists('public/' . $attribute . '_skripsi/' . $data->$attribute)) {
                abort(404);
            }
        }

        // Mengambil konten setiap file PDF
        $pdfContents = [];
        foreach ($pdfPaths as $attribute => $pdfPath) {
            $pdfContents[$attribute] = Storage::get('public/' . $attribute . '_skripsi/' . $data->$attribute);
        }

        // Mengubah konten setiap file PDF menjadi base64
        $pdfs = [];
        foreach ($pdfContents as $attribute => $pdfContent) {
            $pdfs[$attribute] = base64_encode($pdfContent);
        }

        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('welcomeskripsi', compact('pdfs', 'user', 'skripsi'));
    }

    // Sebagai Dosen
    public function indexDosen() {
        $skripsi = Skripsi::All();
        $namaDospem = Dosen::All();
        $namaPenulis = User::where('roles_id', 2)->get();
        $users = Auth::user();
        return view('dosenskripsi', compact('skripsi', 'namaDospem', 'namaPenulis', 'users'));
    }

    // Dosen Tambah Data Skripsi ----------------------------------------------------------------------------------------------
    public function add(Request $req){

        // Validasi Data Skripsi ----------------------------------------------------------------
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|max:4|min:4',
            'halaman' => 'required|min:1',
            'cover' => 'nullable|mimes:pdf|max:2048',
            'pengesahan' => 'nullable|mimes:pdf|max:2048',
            'abstrak' => 'nullable|mimes:pdf|max:2048',
            'daftarisi' => 'nullable|mimes:pdf|max:2048',
            'daftargambar' => 'nullable|mimes:pdf|max:2048',
            'daftarlampiran' => 'nullable|mimes:pdf|max:2048',
            'bab1' => 'nullable|mimes:pdf|max:2048',
            'bab2' => 'nullable|mimes:pdf|max:2048',
            'bab3' => 'nullable|mimes:pdf|max:2048',
            'bab4' => 'nullable|mimes:pdf|max:2048',
            'bab5' => 'nullable|mimes:pdf|max:2048',
            'dapus' => 'nullable|mimes:pdf|max:2048',
            // 'lampiran' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Create Data Skripsi ------------------------------------------------------------------
        $skripsi = new Skripsi;

        $skripsi->judul = $req->get('judul');
        $skripsi->penulis = $req->get('penulis');
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
        $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi');
        $skripsi->abstrak = uploadFile($req, 'abstrak', 'abstrak_skripsi');
        $skripsi->daftarisi = uploadFile($req, 'daftarisi', 'daftarisi_skripsi');
        $skripsi->daftargambar = uploadFile($req, 'daftargambar', 'daftargambar_skripsi');
        $skripsi->daftarlampiran = uploadFile($req, 'daftarlampiran', 'daftarlampiran_skripsi');
        $skripsi->bab1 = uploadFile($req, 'bab1', 'bab1_skripsi');
        $skripsi->bab2 = uploadFile($req, 'bab2', 'bab2_skripsi');
        $skripsi->bab3 = uploadFile($req, 'bab3', 'bab3_skripsi');
        $skripsi->bab4 = uploadFile($req, 'bab4', 'bab4_skripsi');
        $skripsi->bab5 = uploadFile($req, 'bab5', 'bab5_skripsi');
        $skripsi->dapus = uploadFile($req, 'dapus', 'dapus_skripsi');
        // $skripsi->lampiran = uploadFile($req, 'lampiran', 'dapus_skripsi');
        $skripsi->save();
        $notification = array(
            'message' =>'Data Skripsi berhasil ditambahkan', 'alert-type' =>'success'
        );
        return redirect()->route('dosenskripsi')->with($notification);
    }

    // Ubah Data Skripsi ----------------------------------------------------------------------------------------------
    public function edit(Request $req) {

        // Validasi Data Skripsi ----------------------------------------------------------------
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|max:4|min:4',
            'halaman' => 'required|min:1',
        ]);

        // Update Data Skripsi ------------------------------------------------------------------
        $skripsi = Skripsi::find($req->get('id'));

        $skripsi->judul = $req->get('judul');
        $skripsi->penulis = $req->get('penulis');
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
        $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi', $skripsi->pengesahan);
        $skripsi->abstrak = uploadFile($req, 'abstrak', 'abstrak_skripsi', $skripsi->abstrak);
        $skripsi->daftarisi = uploadFile($req, 'daftarisi', 'daftarisi_skripsi', $skripsi->daftarisi);
        $skripsi->daftargambar = uploadFile($req, 'daftargambar', 'daftargambar_skripsi', $skripsi->daftargambar);
        $skripsi->daftarlampiran = uploadFile($req, 'daftarlampiran', 'daftarlampiran_skripsi', $skripsi->daftarlampiran);
        $skripsi->bab1 = uploadFile($req, 'bab1', 'bab1_skripsi', $skripsi->bab1);
        $skripsi->bab2 = uploadFile($req, 'bab2', 'bab2_skripsi', $skripsi->bab2);
        $skripsi->bab3 = uploadFile($req, 'bab3', 'bab3_skripsi', $skripsi->bab3);
        $skripsi->bab4 = uploadFile($req, 'bab4', 'bab4_skripsi', $skripsi->bab4);
        $skripsi->bab5 = uploadFile($req, 'bab5', 'bab5_skripsi', $skripsi->bab5);
        $skripsi->dapus = uploadFile($req, 'dapus', 'dapus_skripsi', $skripsi->dapus);
        // $skripsi->lampiran = uploadFile($req, 'lampiran', 'lampiran_skripsi', $skripsi->lampiran);

        $skripsi->save();

        $notification = array(
            'message' => 'Data Skripsi berhasil diubah',
            'alert-type' => 'success'
        );
        return redirect()->route('dosenskripsi')->with($notification);
    }

    // Hapus Data Skripsi ----------------------------------------------------------------------------------------------
    public function delete($id){
        $skripsi = Skripsi::find($id);

        $skripsi->delete();

        $success = true;
        $message = "Data Skripsi berhasil dihapus";

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function ambilDataSkripsi($id){
        $skripsi = Skripsi::find($id);
        return response()->json($skripsi);
    }

    // Tampil PDF  Skripsi ----------------------------------------------------------------------------------------------
    public function tampilPdf($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
            'abstrak' => storage_path('app/public/abstrak_skripsi/' . $data->abstrak),
            'daftarisi' => storage_path('app/public/daftarisi_skripsi/' . $data->daftarisi),
            'daftargambar' => storage_path('app/public/daftargambar_skripsi/' . $data->daftargambar),
            'daftarlampiran' => storage_path('app/public/daftarlampiran_skripsi/' . $data->daftarlampiran),
            'bab1' => storage_path('app/public/bab1_skripsi/' . $data->bab1),
            'bab2' => storage_path('app/public/bab2_skripsi/' . $data->bab2),
            'bab3' => storage_path('app/public/bab3_skripsi/' . $data->bab3),
            'bab4' => storage_path('app/public/bab4_skripsi/' . $data->bab4),
            'bab5' => storage_path('app/public/bab5_skripsi/' . $data->bab5),
            'dapus' => storage_path('app/public/dapus_skripsi/' . $data->dapus),
            // 'lampiran' => storage_path('app/public/lampiran_skripsi/' . $data->lampiran),
        ];

        // Memeriksa apakah setiap file PDF ada di storage
        foreach ($pdfPaths as $attribute => $pdfPath) {
            if (!Storage::exists('public/' . $attribute . '_skripsi/' . $data->$attribute)) {
                abort(404);
            }
        }

        // Mengambil konten setiap file PDF
        $pdfContents = [];
        foreach ($pdfPaths as $attribute => $pdfPath) {
            $pdfContents[$attribute] = Storage::get('public/' . $attribute . '_skripsi/' . $data->$attribute);
        }

        // Mengubah konten setiap file PDF menjadi base64
        $pdfs = [];
        foreach ($pdfContents as $attribute => $pdfContent) {
            $pdfs[$attribute] = base64_encode($pdfContent);
        }

        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('dosendetail', compact('pdfs', 'user', 'skripsi'));
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function detailskripsi1($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
            'abstrak' => storage_path('app/public/abstrak_skripsi/' . $data->abstrak),
            'daftarisi' => storage_path('app/public/daftarisi_skripsi/' . $data->daftarisi),
            'daftargambar' => storage_path('app/public/daftargambar_skripsi/' . $data->daftargambar),
            'daftarlampiran' => storage_path('app/public/daftarlampiran_skripsi/' . $data->daftarlampiran),
            'bab1' => storage_path('app/public/bab1_skripsi/' . $data->bab1),
            'bab2' => storage_path('app/public/bab2_skripsi/' . $data->bab2),
            'bab3' => storage_path('app/public/bab3_skripsi/' . $data->bab3),
            'bab4' => storage_path('app/public/bab4_skripsi/' . $data->bab4),
            'bab5' => storage_path('app/public/bab5_skripsi/' . $data->bab5),
            'dapus' => storage_path('app/public/dapus_skripsi/' . $data->dapus),
            // 'lampiran' => storage_path('app/public/lampiran_skripsi/' . $data->lampiran),
        ];

        // Memeriksa apakah setiap file PDF ada di storage
        foreach ($pdfPaths as $attribute => $pdfPath) {
            if (!Storage::exists('public/' . $attribute . '_skripsi/' . $data->$attribute)) {
                abort(404);
            }
        }

        // Mengambil konten setiap file PDF
        $pdfContents = [];
        foreach ($pdfPaths as $attribute => $pdfPath) {
            $pdfContents[$attribute] = Storage::get('public/' . $attribute . '_skripsi/' . $data->$attribute);
        }

        // Mengubah konten setiap file PDF menjadi base64
        $pdfs = [];
        foreach ($pdfContents as $attribute => $pdfContent) {
            $pdfs[$attribute] = base64_encode($pdfContent);
        }

        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('detail', compact('pdfs', 'user', 'skripsi'));
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function welcomeskripsi1($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
            'abstrak' => storage_path('app/public/abstrak_skripsi/' . $data->abstrak),
            'daftarisi' => storage_path('app/public/daftarisi_skripsi/' . $data->daftarisi),
            'daftargambar' => storage_path('app/public/daftargambar_skripsi/' . $data->daftargambar),
            'daftarlampiran' => storage_path('app/public/daftarlampiran_skripsi/' . $data->daftarlampiran),
            'bab1' => storage_path('app/public/bab1_skripsi/' . $data->bab1),
            'bab2' => storage_path('app/public/bab2_skripsi/' . $data->bab2),
            'bab3' => storage_path('app/public/bab3_skripsi/' . $data->bab3),
            'bab4' => storage_path('app/public/bab4_skripsi/' . $data->bab4),
            'bab5' => storage_path('app/public/bab5_skripsi/' . $data->bab5),
            'dapus' => storage_path('app/public/dapus_skripsi/' . $data->dapus),
            // 'lampiran' => storage_path('app/public/lampiran_skripsi/' . $data->lampiran),
        ];

        // Memeriksa apakah setiap file PDF ada di storage
        foreach ($pdfPaths as $attribute => $pdfPath) {
            if (!Storage::exists('public/' . $attribute . '_skripsi/' . $data->$attribute)) {
                abort(404);
            }
        }

        // Mengambil konten setiap file PDF
        $pdfContents = [];
        foreach ($pdfPaths as $attribute => $pdfPath) {
            $pdfContents[$attribute] = Storage::get('public/' . $attribute . '_skripsi/' . $data->$attribute);
        }

        // Mengubah konten setiap file PDF menjadi base64
        $pdfs = [];
        foreach ($pdfContents as $attribute => $pdfContent) {
            $pdfs[$attribute] = base64_encode($pdfContent);
        }

        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('dosendetail', compact('pdfs', 'user', 'skripsi'));
    }
}
