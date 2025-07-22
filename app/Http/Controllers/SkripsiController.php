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
use App\Models\Jurusan;
use App\Models\riwayat_skripsi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Correctly import the Pdf facade
use Illuminate\Support\Facades\View;
use App\Models\Notifikasi;

class SkripsiController extends Controller
{
    // Tambah Data Skripsi ----------------------------------------------------------------------------------------------
    // public function index() {
    //    $skripsi = Skripsi::where('user_id', Auth::user()->id)->first();
    //     $namaDospem = Dosen::all();
    //     $namaPenulis = User::where('roles_id', 2)->get();



    //     // dd($skripsi); // hanya untuk debugging, hapus saat produksi
    //     return view('skripsi', compact('skripsi', 'namaDospem', 'namaPenulis'));
    // }

    public function index() {
       $skripsi = Skripsi::where('user_id', Auth::user()->id)->get();
        $namaDospem = Dosen::all();
        $namaPenulis = User::where('roles_id', 2)->get();

        if ($skripsi->isEmpty()) {
            $skripsi = null;
        }
        // dd($skripsi); // hanya untuk debugging, hapus saat produksi
        return view('skripsi', compact('skripsi', 'namaDospem', 'namaPenulis'));
    }
    
public function index1() {
    $skripsi = Skripsi::orderBy('created_at', 'desc')->paginate(10);
    $namaDospem = Dosen::all();
    $namaPenulis = User::where('roles_id', 2)->get();

    return view('skripsiadmin', compact('skripsi', 'namaDospem', 'namaPenulis'));
}

    public function mahasiswa()
    {
        // Ambil semua jurusan untuk dropdown filter
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        // Ambil skripsi yang sudah terverifikasi, plus relasi mahasiswa→jurusan
        $skripsi = Skripsi::with('mahasiswa.jurusan')
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('skripsi2', compact('skripsi', 'jurusans'));
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
     public function tambah(Request $request)
    {
        // Validasi Data Skripsi
        $request->validate([
            'judul'         => 'required|string|max:200',
            // penulis kita ambil dari Auth, jadi tidak perlu di-validate di sini
            'abstrak'       => 'required|string',
            'dospem'        => 'required|string|max:30',
            'rilis'         => 'required|digits:4|integer|min:1900|max:'.date('Y'),
            'halaman'       => 'required|integer|min:1',
            'katakunci'     => 'required|string',
            'file_skripsi'  => 'required|mimes:pdf|max:10240',   // max 10 MB
            'file_dapus'    => 'required|mimes:pdf|max:10240',   // max 10 MB
            'file_abstrak'    => 'required|mimes:pdf|max:10240',   // max 10 MB
        ]);

        // Siapkan model
        $skripsi = new Skripsi;
        $skripsi->judul       = $request->judul;
        $skripsi->penulis     = Auth::user()->name;
        $skripsi->abstrak     = $request->abstrak;
        $skripsi->dospem      = $request->dospem;
        $skripsi->rilis       = $request->rilis;
        $skripsi->halaman     = $request->halaman;
        $skripsi->katakunci   = $request->katakunci;
        $skripsi->user_id     = Auth::id();

        // Upload file helper
        $uploadFile = function($file, $folder, $prefix) {
            $ext      = $file->extension();
            $name     = $prefix.'_'.time().'.'.$ext;
            $file->storeAs('public/'.$folder, $name);
            return $name;
        };

        // Simpan file skripsi
        if ($request->hasFile('file_skripsi')) {
            $skripsi->file_skripsi = $uploadFile($request->file('file_skripsi'), 'skripsi_files', 'skripsi');
        }

        // Simpan file daftar pustaka
        if ($request->hasFile('file_dapus')) {
            $skripsi->file_dapus = $uploadFile($request->file('file_dapus'), 'daftar_pustaka_files', 'daftar_pustaka');
        }
        // Simpan file daftar pustaka
        if ($request->hasFile('file_abstrak')) {
            $skripsi->file_abstrak = $uploadFile($request->file('file_abstrak'), 'abstrak_files', 'abstrak');
        }

        $skripsi->save();

        return redirect()
            ->route('skripsi')
            ->with([
                'message'    => 'Data Skripsi berhasil ditambahkan',
                'alert-type' => 'success'
            ]);
    }
    
    // Ubah Data Skripsi ----------------------------------------------------------------------------------------------
    public function ubah(Request $req) {
        // Validasi Data Skripsi
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30',
            'abstrak' => 'required|string',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|string',
            'halaman' => 'required|integer',
            'file_skripsi' => 'nullable|file|mimes:pdf|max:10240', // Validasi file (optional)
            'file_dapus' => 'nullable|file|mimes:pdf|max:10240', // Validasi file (optional)
            'file_abstrak' => 'nullable|file|mimes:pdf|max:10240', // Validasi file (optional)
        ]);
    // dd($req);
        $skripsi = Skripsi::findOrFail($req->id);
        
        // Jika ada file baru yang diupload, hapus file lama dan simpan yang baru
        if ($req->hasFile('file_skripsi')) {
            // Hapus file lama jika ada
            if ($skripsi->file_skripsi && file_exists(storage_path('app/public/skripsi_files/' . $skripsi->file_skripsi))) {
                unlink(storage_path('app/public/skripsi_files/' . $skripsi->file_skripsi));
            }
    
            // Simpan file baru
            $file = $req->file('file_skripsi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/skripsi_files', $fileName);
    
            $skripsi->file_skripsi = $fileName;
        }
    
        // Update data skripsi lainnya
        $skripsi->judul = $req->judul;
        $skripsi->penulis = $req->penulis;
        $skripsi->abstrak = $req->abstrak;
        $skripsi->dospem = $req->dospem;
        $skripsi->rilis = $req->rilis;
        $skripsi->halaman = $req->halaman;
        $skripsi->status = 0;
        
        $skripsi->save();
        $notification = array(
            'message' => 'Data Skripsi berhasil di edit', 'alert-type' => 'success'
        );
    
        return redirect()->route('skripsi')->with($notification);
    }
    
        // Get Data Skripsi ----------------------------------------------------------------------------------------------
        public function getDataSkripsi($id)
        {
            $skripsi = Skripsi::find($id);
        
            if ($skripsi) {
                return response()->json([
                    'id' => $skripsi->id,
                    'judul' => $skripsi->judul,
                    'abstrak' => $skripsi->abstrak,
                    'penulis' => $skripsi->penulis,
                    'dospem' => $skripsi->dospem,
                    'rilis' => $skripsi->rilis,
                    'halaman' => $skripsi->halaman,
                    'katakunci' => $skripsi->katakunci,
                    'file_skripsi' => $skripsi->file_skripsi, // pastikan ini sesuai dengan kolom di database
                    'file_dapus' => $skripsi->file_skripsi, // pastikan ini sesuai dengan kolom di database
                    'file_abstrak' => $skripsi->file_skripsi, // pastikan ini sesuai dengan kolom di database
                ]);
            } else {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
        }        
    
    // Ubah Data Skripsi ----------------------------------------------------------------------------------------------
    public function edit(Request $req) {
        // Validasi Data Skripsi
        $req->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:30', // Penulis tetap wajib, meski tidak boleh diubah oleh admin
            'abstrak' => 'required|string',
            'dospem' => 'required|string|max:30',
            'rilis' => 'required|string',
            'halaman' => 'required|integer',
            'file_skripsi' => 'nullable|file|mimes:pdf|max:10240', // Validasi file (optional)
        ]);
    
        $skripsi = Skripsi::findOrFail($req->id);
    
        // Jika ada file baru yang diupload, hapus file lama dan simpan yang baru
        if ($req->hasFile('file_skripsi')) {
            // Hapus file lama jika ada
            if ($skripsi->file_skripsi && file_exists(storage_path('app/public/skripsi_files/' . $skripsi->file_skripsi))) {
                unlink(storage_path('app/public/skripsi_files/' . $skripsi->file_skripsi));
            }
    
            // Simpan file baru
            $file = $req->file('file_skripsi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/skripsi_files', $fileName);
    
            $skripsi->file_skripsi = $fileName;
        }
    
        // Update data skripsi lainnya, kecuali penulis
        $skripsi->judul = $req->judul;
        // Mengabaikan perubahan penulis, tetap dari data aslinya (dari mahasiswa yang mengupload)
        $skripsi->abstrak = $req->abstrak;
        $skripsi->dospem = $req->dospem;
        $skripsi->rilis = $req->rilis;
        $skripsi->halaman = $req->halaman;
    
        // Menyimpan perubahan ke database
        $skripsi->save();
    
        $notification = array(
            'message' => 'Data Skripsi berhasil diperbarui', 'alert-type' => 'success'
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
        
        // Check if the user is an admin
        $isAdmin = $user->role === 'admin';
        
        // Mengirim data PDF, data user, data skripsi, dan status admin ke view 'detail'
        return view('detailskripsimahasiswa', compact('user', 'skripsi', 'comments', 'isAdmin'));
    }

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function detailskripsi($id){
    $user = Auth::user();
    $skripsi = Skripsi::findOrFail($id);
    $comment = Comment::where('skripsi_id', $id)
        ->whereNull('parent_id')
        ->join('users', 'comments.id_user', '=', 'users.id')
        ->select('comments.*', 'users.name as user_name')
        ->get();

    $childcomments = Comment::where('skripsi_id', $id)
        ->join('users', 'comments.id_user', '=', 'users.id')
        ->select('comments.*', 'users.name as user_name')
        ->get();

    $comments = collect();

    // Mengecek apakah riwayat sudah ada untuk pengguna ini
    $existingHistory = riwayat_skripsi::where('id_user', $user->id)
        ->where('id_skripsi', $id)
        ->exists();

    if (!$existingHistory) {
        // Tambahkan riwayat baru
        riwayat_skripsi::create([
            'id_user' => $user->id,
            'id_skripsi' => $id,
        ]);

        // Tambahkan 1 pada jumlah views
        $skripsi->increment('views');
    }

    foreach ($childcomments as $comment) {
        if ($comment->parent_id === null) {
            $comments->put($comment->id, [
                'comment' => $comment,
                'replies' => collect(),
            ]);
        } else {
            if ($comments->has($comment->parent_id)) {
                $comments->get($comment->parent_id)['replies']->push($comment);
            }
        }
    }

    // Mengirim data ke view 'detail'
    return view('detail', compact('user', 'skripsi', 'comments'));
}


    

    // Get Data Skripsi ----------------------------------------------------------------------------------------------
    public function welcomeskripsi($id){
        $user = Auth::user();
        $skripsi = Skripsi::find($id);
        $data = Skripsi::findOrFail($id);

        // Mengirim data PDF, data user, dan data skripsi ke view 'detail'
        return view('welcomeskripsi', compact( 'user', 'skripsi'));
    }

    public function verifikasi($id) {
    try {
        $skripsi = Skripsi::find($id);

        if (!$skripsi) {
            return response()->json([
                'success' => false,
                'message' => 'Skripsi tidak ditemukan',
            ], 404);
        }

        // Ubah status
        if ($skripsi->status == 0) {
            $skripsi->status = 1;
            $message = "Skripsi berhasil diverifikasi";
        } elseif ($skripsi->status == 1) {
            // $skripsi->status = 0;
            $message = "Skripsi sudah diverifikasi";
        } elseif ($skripsi->status == 2) {
            // $skripsi->status = 0;
            $message = "Skripsi gagal diverifikasi";
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Status skripsi tidak valid',
            ], 400);
        }

        $skripsi->save();
        if ($skripsi) {
            Notifikasi::create([
                'skripsi_id' => $skripsi->id,
                'mahasiswa_id' => $skripsi->user_id,
                'deskripsi' => 'Skripsi Anda Diverifikasi',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        ], 500);
    }
}

    public function tolakVerifikasi($id)
    {
    try{
        $skripsi = Skripsi::find($id);

        if (!$skripsi) {
            return response()->json([
                'success' => false,
                'message' => 'Skripsi tidak ditemukan.',
            ], 404);
        }

        // Set status ditolak
        if ($skripsi->status == 0) {
            $skripsi->status = 2;
            $message = "Skripsi berhasil diverifikasi";
        } elseif ($skripsi->status == 1) {
            $message = "Skripsi sudah diverifikasi, dan tidak bisa ditolak";
        } elseif ($skripsi->status == 2) {
            $message = "Skripsi gagal diverifikasi";
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Status skripsi tidak valid',
            ], 400);
        }

        $skripsi->save();
        if ($skripsi) {
            Notifikasi::create([
                'skripsi_id' => $skripsi->id,
                'mahasiswa_id' => $skripsi->user_id,
                'deskripsi' => 'Skripsi Anda Ditolak',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);   
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        ], 500);
    }
    }

    public function cariYangMirip(Request $request)
{
    $judul = $request->input('judul');
    $keywords = explode(' ', $judul);

    $skripsi = Skripsi::with('mahasiswa.jurusan')
                    ->where('status', 1)
                    ->where(function ($query) use ($keywords) {
                        foreach ($keywords as $keyword) {
                            $query->orWhere('judul', 'LIKE', '%' . $keyword . '%');
                        }
                    })
                    ->get();

    $jurusans = Jurusan::orderBy('nama_jurusan')->get(); // agar tetap bisa render form

    return view('skripsi2', compact('skripsi', 'jurusans'));
}

    public function searchSkripsi(Request $request){
    $jurusans = Jurusan::orderBy('nama_jurusan')->get();

    $query = Skripsi::with('mahasiswa.jurusan')
            ->where('status', 1);

    if ($request->filled('judul')) {
        $query->where('judul', 'like', '%'.$request->judul.'%');
    }
    if ($request->filled('penulis')) {
        $query->where('penulis', 'like', '%'.$request->penulis.'%');
    }
    if ($request->filled('rilis')) {
        $query->where('rilis', $request->rilis);
    }
    if ($request->filled('prodi')) {
        // filter berdasarkan nama jurusan
        $query->whereHas('mahasiswa.jurusan', function($q) use ($request) {
            $q->where('nama_jurusan', $request->prodi);
        });
    }

    $skripsi = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('skripsi2', compact('skripsi', 'jurusans'));
}

    public function findSkripsi(Request $request)
    {
        $jurusans = Jurusan::orderBy('nama_jurusan')->get(); // Tambahkan agar blade tetap bisa menampilkan opsi jurusan

        $query = Skripsi::with('mahasiswa.jurusan')
                        ->where('status', 1);

        if ($request->filled('judul')) {
            $query->where('judul', 'LIKE', '%' . $request->judul . '%');
        }
        if ($request->filled('penulis')) {
            $query->where('penulis', 'LIKE', '%' . $request->penulis . '%');
        }
        if ($request->filled('rilis')) {
            $query->where('rilis', 'LIKE', '%' . $request->rilis . '%');
        }
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa.jurusan', function($q) use ($request) {
                $q->where('nama_jurusan', $request->prodi);
            });
        }

        $skripsi = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('skripsi2', compact('skripsi', 'jurusans'));
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
