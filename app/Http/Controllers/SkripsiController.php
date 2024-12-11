<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Skripsi;
// use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;
use App\Models\riwayat_skripsi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Correctly import the Pdf facade
use Illuminate\Support\Facades\View;

class SkripsiController extends Controller
{
    // Tambah Data Skripsi ----------------------------------------------------------------------------------------------
    public function index() {
        $skripsi = Skripsi::where('penulis', Auth::user()->name)->get();
        $namaDospem = Dosen::All();
        $namaPenulis = User::where('roles_id', 2)->get();
        return view('skripsi', compact('skripsi', 'namaDospem', 'namaPenulis'));
    }
    public function index1() {
        $skripsi = Skripsi::All();
        $namaDospem = Dosen::All();
        $namaPenulis = User::where('roles_id', 2)->get();
        return view('skripsiadmin', compact('skripsi', 'namaDospem', 'namaPenulis'));
    }

    public function mahasiswa() {
        $skripsi = DB::table('skripsis')
                    ->join('users', 'skripsis.penulis', '=', 'users.name')
                    ->select('skripsis.*', 'users.prodi')
                    ->where('skripsis.status', 1)  // Menambahkan kondisi status
                    ->get();
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
            'abstrak' => 'required|string',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|max:4|min:4',
            'halaman' => 'required|min:1',
            'cover' => 'nullable|mimes:pdf|max:2048',
            'pengesahan' => 'nullable|mimes:pdf|max:2048',
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
        $skripsi->penulis = Auth::user()->name;
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
        $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi');
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
    // Tambah Data Skripsi ----------------------------------------------------------------------------------------------
    // public function tambah1(Request $req){

    //     // Validasi Data Skripsi ----------------------------------------------------------------
    //     $req->validate([
    //         'judul' => 'required|string|max:200',
    //         'penulis' => 'required|string|max:30',
    //         'abstrak' => 'required|string',
    //         'dospem' => 'required|string|max:30',
    //         'rilis' => 'required|max:4|min:4',
    //         'halaman' => 'required|min:1',
    //         'cover' => 'nullable|mimes:pdf|max:2048',
    //         'pengesahan' => 'nullable|mimes:pdf|max:2048',
    //         'daftarisi' => 'nullable|mimes:pdf|max:2048',
    //         'daftargambar' => 'nullable|mimes:pdf|max:2048',
    //         'daftarlampiran' => 'nullable|mimes:pdf|max:2048',
    //         'bab1' => 'nullable|mimes:pdf|max:2048',
    //         'bab2' => 'nullable|mimes:pdf|max:2048',
    //         'bab3' => 'nullable|mimes:pdf|max:2048',
    //         'bab4' => 'nullable|mimes:pdf|max:2048',
    //         'bab5' => 'nullable|mimes:pdf|max:2048',
    //         'dapus' => 'nullable|mimes:pdf|max:2048',
    //         // 'lampiran' => 'nullable|mimes:pdf|max:2048',
    //     ]);

    //     // Create Data Skripsi ------------------------------------------------------------------
    //     $skripsi = new Skripsi;

    //     $skripsi->judul = $req->get('judul');
    //     $skripsi->penulis = Auth::user()->name;
    //     $skripsi->abstrak = $req->get('abstrak');
    //     $skripsi->dospem = $req->get('dospem');
    //     $skripsi->rilis = $req->get('rilis');
    //     $skripsi->halaman = $req->get('halaman');

    //     function uploadFile($req, $fieldName, $storagePath){
    //         if ($req->hasFile($fieldName)) {
    //             $extension = $req->file($fieldName)->extension();
    //             $filename = $fieldName . '_skripsi' . time() . '.' . $extension;
    //             $req->file($fieldName)->storeAs('public/' . $storagePath, $filename);
    //             return $filename;
    //         }
    //         return null;
    //     }

    //     $skripsi->cover = uploadFile($req, 'cover', 'cover_skripsi');
    //     $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi');
    //     $skripsi->daftarisi = uploadFile($req, 'daftarisi', 'daftarisi_skripsi');
    //     $skripsi->daftargambar = uploadFile($req, 'daftargambar', 'daftargambar_skripsi');
    //     $skripsi->daftarlampiran = uploadFile($req, 'daftarlampiran', 'daftarlampiran_skripsi');
    //     $skripsi->bab1 = uploadFile($req, 'bab1', 'bab1_skripsi');
    //     $skripsi->bab2 = uploadFile($req, 'bab2', 'bab2_skripsi');
    //     $skripsi->bab3 = uploadFile($req, 'bab3', 'bab3_skripsi');
    //     $skripsi->bab4 = uploadFile($req, 'bab4', 'bab4_skripsi');
    //     $skripsi->bab5 = uploadFile($req, 'bab5', 'bab5_skripsi');
    //     $skripsi->dapus = uploadFile($req, 'dapus', 'dapus_skripsi');
    //     // $skripsi->lampiran = uploadFile($req, 'lampiran', 'dapus_skripsi');
    //     $skripsi->save();
    //     $notification = array(
    //         'message' =>'Data Skripsi berhasil ditambahkan', 'alert-type' =>'success'
    //     );
    //     return redirect()->route('skripsiadmin')->with($notification);
    // }

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
        $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi', $skripsi->pengesahan);
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
    // Ubah Data Skripsi ----------------------------------------------------------------------------------------------
    public function edit(Request $req) {

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
        $skripsi->pengesahan = uploadFile($req, 'pengesahan', 'pengesahan_skripsi', $skripsi->pengesahan);
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
        return redirect()->route('skripsiadmin')->with($notification);
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
    // Hapus Data Skripsi ----------------------------------------------------------------------------------------------
    public function hapus1($id){
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
        $comment = Comment::where('skripsi_id', $id)
        ->whereNull('parent_id')
        ->join('users', 'comments.id_user', '=', 'users.id')
        ->select('comments.*', 'users.name as user_name') // select the required fields
        ->get();

    $childcomments = Comment::where('skripsi_id', $id)
        ->join('users', 'comments.id_user', '=', 'users.id')
        ->select('comments.*', 'users.name as user_name') // select the required fields
        ->get();
    $comments = collect();

    foreach ($childcomments as $comment) {
        if ($comment->parent_id === null) {
            // This is a top-level comment
            $comments->put($comment->id, [
                'comment' => $comment,
                'replies' => collect()  // Initialize replies as a collection
            ]);
        } else {
            // This is a reply
            if ($comments->has($comment->parent_id)) {
                // Add reply to its parent comment's 'replies' collection
                $comments->get($comment->parent_id)['replies']->push($comment);
            }
        }
    }

    // dd($comments);
        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
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
        return view('detailskripsimahasiswa', compact('pdfs', 'user', 'skripsi','comments'));
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function detailskripsi($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);
        $comment = Comment::where('skripsi_id', $id)
            ->whereNull('parent_id')
            ->join('users', 'comments.id_user', '=', 'users.id')
            ->select('comments.*', 'users.name as user_name') // select the required fields
            ->get();

        $childcomments = Comment::where('skripsi_id', $id)
            ->join('users', 'comments.id_user', '=', 'users.id')
            ->select('comments.*', 'users.name as user_name') // select the required fields
            ->get();
        $comments = collect();

        // Mengecek apakah riwayat sudah ada untuk pengguna ini
    $existingHistory = riwayat_skripsi::where('id_user', $user->id)
    ->where('id_skripsi', $id)
    ->exists();
        // Jika belum ada, tambahkan riwayat baru
        if (!$existingHistory) {
            riwayat_skripsi::create([
                'id_user' => $user->id,
                'id_skripsi' => $id,
            ]);
        }
        foreach ($childcomments as $comment) {
            if ($comment->parent_id === null) {
                // This is a top-level comment
                $comments->put($comment->id, [
                    'comment' => $comment,
                    'replies' => collect()  // Initialize replies as a collection
                ]);
            } else {
                // This is a reply
                if ($comments->has($comment->parent_id)) {
                    // Add reply to its parent comment's 'replies' collection
                    $comments->get($comment->parent_id)['replies']->push($comment);
                }
            }
        }

        // dd($comments);

        // Menyusun path untuk setiap file PDF yang ingin diambil
        $pdfPaths = [
            'cover' => storage_path('app/public/cover_skripsi/' . $data->cover),
            'pengesahan' => storage_path('app/public/pengesahan_skripsi/' . $data->pengesahan),
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
        $skripsi->increment('views');


        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('detail', compact('pdfs', 'user', 'skripsi', 'comments'));
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

    public function verifikasi($id) {
        $skripsi = Skripsi::find($id);

        // Toggle status verifikasi
        if ($skripsi->status == 0) {
            $skripsi->status = 1;
            $message = "Verifikasi skripsi berhasil diverifikasi";
        } else {
            $skripsi->status = 0;
            $message = "Verifikasi skripsi berhasil dibatalkan verifikasinya";
        }

        $skripsi->save();

        $success = true;

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function cariYangMirip(Request $request)
    {
        $judul = $request->input('judul');
        // Memecah judul menjadi kata-kata kunci
        $keywords = explode(' ', $judul);

        // Membangun query untuk mencari skripsi yang memiliki salah satu kata kunci di judulnya dan berstatus 1
        $skripsi = DB::table('skripsis')
                    ->join('users', 'skripsis.penulis', '=', 'users.name')
                    ->select('skripsis.*', 'users.prodi')
                    ->where('skripsis.status', 1) // Menambahkan kondisi status
                    ->where(function ($query) use ($keywords) {
                        foreach ($keywords as $keyword) {
                            $query->orWhere('skripsis.judul', 'LIKE', '%' . $keyword . '%');
                        }
                    })
                    ->get();

        return view('skripsi2', compact('skripsi'));
    }

    public function searchSkripsi(Request $req) {
    // Mengubah query untuk pencarian
    $query = Skripsi::query();
    $query->select('id', 'judul', 'penulis', 'rilis', 'dospem', 'halaman','views');

    // Menambahkan kondisi pencarian berdasarkan judul jika tersedia
    if (!empty($req->input('judul'))) {
        $judulKeywords = explode(' ', strtolower($req->input('judul'))); // Memecah string menjadi array kata
        $query->where(function ($subQuery) use ($judulKeywords) {
            foreach ($judulKeywords as $word) {
                $subQuery->orWhereRaw('LOWER(judul) LIKE ?', ['%' . $word . '%']);
            }
        });
    }

    // Menambahkan kondisi pencarian berdasarkan penulis jika tersedia
    if (!empty($req->input('penulis'))) {
        $query->whereRaw('LOWER(penulis) LIKE ?', ['%' . strtolower($req->input('penulis')) . '%']);
    }

    // Menambahkan kondisi pencarian berdasarkan rilis jika tersedia
    if (!empty($req->input('rilis'))) {
        $query->whereRaw('LOWER(rilis) LIKE ?', ['%' . strtolower($req->input('rilis')) . '%']);
    }

    // Mengurutkan hasil pencarian berdasarkan tanggal pembuatan terbaru
    $query->join('users', 'skripsis.penulis', '=', 'users.name')
    ->select('skripsis.*', 'users.prodi')
    ->orderBy('skripsis.created_at', 'desc');

    // Mengambil hasil pencarian dengan paginasi
    $skripsi = $query->paginate(10);

    // Mengembalikan tampilan dengan data pencarian
    return view('skripsi2', compact('skripsi'));
}

// public function showMetadataPdf($id)
// {
//     $skripsi = DB::table('skripsis')
//     ->join('users', 'skripsis.penulis', '=', 'users.name')
//     ->select('skripsis.*', 'users.prodi')
//     ->where('skripsis.id', $id)  // Menambahkan kondisi status
//     ->first();

//     // Buat PDF dari view
//     $pdf = Pdf::loadView('metadata', compact('skripsi'))
//                ->setPaper('A4', 'portrait');

//     // Menambahkan header inline agar tidak langsung diunduh
//     return response($pdf->output(), 200)
//     ->header('Content-Type', 'application/pdf')
//     ->header('Content-Disposition', 'inline; filename="metadata-skripsi.pdf"');
// }
    // public function postkomentar (Request $request){
    //     dd($request->all());
    // }

}
