<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Comment;
// use App\Models\Comment;
use App\Models\Jurusan;
use App\Models\Skripsi;
use App\Models\Metadata;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Models\riwayat_skripsi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf; // Correctly import the Pdf facade

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

        public function index()
        {
            // Ambil satu skripsi milik user + eager load metadata
            $skripsi = Skripsi::with('metadata')
                        ->where('user_id', Auth::id())
                        ->first();      // ambil 1 record, bukan collection

            $namaDospem = Dosen::where('status', 1)->get(); // hanya dosen aktif

            $notifikasi = Notifikasi::where('mahasiswa_id', Auth::id())->latest()->first();

            return view('skripsi', compact('skripsi', 'namaDospem', 'notifikasi'));
        }


        public function index1() {
            $skripsi = Skripsi::orderBy('created_at', 'desc')->paginate(5);
            $namaDospem = Dosen::all();
            $namaPenulis = User::where('roles_id', 2)->get();

            return view('skripsiadmin', compact('skripsi', 'namaDospem', 'namaPenulis'));
        }

    // public function mahasiswa()
    // {
    //     // Ambil semua jurusan untuk dropdown filter
    //     $jurusans = Jurusan::orderBy('nama_jurusan')->get();

    //     // Ambil skripsi yang sudah terverifikasi, plus relasi mahasiswa→jurusan
    //     $skripsi = Skripsi::with('mahasiswa.jurusan')
    //                 ->where('status', 1)
    //                 ->orderBy('created_at', 'desc')
    //                 ->get();

    //     return view('skripsi2', compact('skripsi', 'jurusans'));
    // }

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
        // 1) Validasi semua input
        $request->validate([
            // Skripsi
            'title'         => 'required|string|max:200',
            'description'   => 'required|string',
            // 'contributor'   => 'required|string|max:100',
            'date_issued'   => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'coverage'      => 'required|integer|min:1',
            'keywords'      => 'required|string',
            // File uploads
            'file_skripsi'  => 'required|mimes:pdf|max:10240',
            'file_dapus'    => 'required|mimes:pdf|max:10240',
            'file_abstrak'  => 'required|mimes:pdf|max:10240',
            // Optional metadata
            'identifier'    => 'nullable|string|max:100',
            'source'        => 'nullable|string|max:100',
            'rights'        => 'nullable|string|max:100',
        ]);

        // 2) Simpan data skripsi
        $skripsi = new Skripsi;
        $skripsi->judul     = $request->title;
        $skripsi->penulis   = Auth::user()->name;
        $skripsi->abstrak   = $request->description;
        $skripsi->dosen_id = Auth::user()->dosen_id; // AMBIL OTOMATIS DARI MAHASISWA
        $skripsi->rilis     = $request->date_issued;
        $skripsi->halaman   = $request->coverage;
        $skripsi->katakunci = $request->keywords;
        $skripsi->status    = 0 ;
        $skripsi->user_id   = Auth::id();

        // Simpan file skripsi
if ($request->hasFile('file_skripsi')) {
    $file = $request->file('file_skripsi');
    $nama_file = 'skripsi_' . time() . '.' . $file->extension();
    $file->move(public_path('uploads/skripsi_files'), $nama_file);
    $skripsi->file_skripsi = $nama_file;
}

// Simpan file daftar pustaka
if ($request->hasFile('file_dapus')) {
    $file = $request->file('file_dapus');
    $nama_file = 'daftar_pustaka_' . time() . '.' . $file->extension();
    $file->move(public_path('uploads/daftar_pustaka_files'), $nama_file);
    $skripsi->file_dapus = $nama_file;
}

// Simpan file abstrak
if ($request->hasFile('file_abstrak')) {
    $file = $request->file('file_abstrak');
    $nama_file = 'abstrak_' . time() . '.' . $file->extension();
    $file->move(public_path('uploads/abstrak_files'), $nama_file);
    $skripsi->file_abstrak = $nama_file;
}


        $skripsi->save();

        // 3) Simpan metadata
        Metadata::create([
            'skripsi_id'   => $skripsi->id,
            'title'        => $request->title,
            'creator'      => Auth::user()->name,
            'contributor'  => Auth::user()->dosen->nama, // ✅ AUTO AMBIL DARI DOSEN_ID USER
            'subject'      => Auth::user()->jurusan->nama_jurusan,
            'description'  => $request->description,
            'publisher'    => 'Universitas Suryakancana Fakultas Sains Terapan',
            'date_issued'  => $request->date_issued,
            'language'     => 'id',
            'type'         => 'Skripsi',
            'format'       => 'PDF',
            'identifier'   => $request->identifier,
            'source'       => $request->source,
            'rights'       => $request->rights,
            'keywords'     => $request->keywords,
            'coverage'     => $request->coverage,
        ]);

        // 4) Redirect with pesan sukses
        return redirect()
            ->route('skripsi')
            ->with([
                'message'    => 'Data Skripsi dan Metadata berhasil ditambahkan',
                'alert-type' => 'success',
            ]);
    }
    // Ubah Data Skripsi ----------------------------------------------------------------------------------------------
    public function ubah(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'date_issued' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'coverage' => 'required|integer|min:1',
            'keywords' => 'required|string',
            'file_skripsi' => 'nullable|mimes:pdf|max:10240',
            'file_dapus' => 'nullable|mimes:pdf|max:10240',
            'file_abstrak' => 'nullable|mimes:pdf|max:10240',
        ]);

        // Temukan skripsi yang akan diperbarui
        $skripsi = Skripsi::findOrFail($id);

        // Update data skripsi
        $skripsi->judul = $request->title;
        $skripsi->abstrak = $request->description;
        $skripsi->dosen_id = Auth::user()->dosen_id; // AMBIL OTOMATIS DARI USER
        $skripsi->rilis = $request->date_issued;
        $skripsi->halaman = $request->coverage;
        $skripsi->katakunci = $request->keywords;

        // Reset status menjadi 0 (belum diverifikasi) setelah perbaikan
        $skripsi->status = 0;

        // Fungsi helper untuk upload file
        $uploadFile = function($file, $folder, $prefix) {
            $ext = $file->extension();
            $name = "{$prefix}_" . time() . ".{$ext}";
            $file->storeAs("public/{$folder}", $name);
            return $name;
        };

        // Handle file uploads jika ada file baru
        if ($request->hasFile('file_skripsi')) {
            // Hapus file lama jika ada
            if ($skripsi->file_skripsi) {
                Storage::delete("public/skripsi_files/{$skripsi->file_skripsi}");
            }
            $skripsi->file_skripsi = $uploadFile($request->file('file_skripsi'), 'skripsi_files', 'skripsi');
        }

        if ($request->hasFile('file_dapus')) {
            if ($skripsi->file_dapus) {
                Storage::delete("public/daftar_pustaka_files/{$skripsi->file_dapus}");
            }
            $skripsi->file_dapus = $uploadFile($request->file('file_dapus'), 'daftar_pustaka_files', 'daftar_pustaka');
        }

        if ($request->hasFile('file_abstrak')) {
            if ($skripsi->file_abstrak) {
                Storage::delete("public/abstrak_files/{$skripsi->file_abstrak}");
            }
            $skripsi->file_abstrak = $uploadFile($request->file('file_abstrak'), 'abstrak_files', 'abstrak');
        }

        $skripsi->save();

        // Update metadata jika ada perubahan
        if ($skripsi->metadata) {
            $metadata = $skripsi->metadata;
            $metadata->title = $request->title;
            $metadata->contributor = Auth::user()->dosen->nama; // ✅ Update otomatis dari dosen_id
            $metadata->description = $request->description;
            $metadata->date_issued = $request->date_issued;
            $metadata->keywords = $request->keywords;
            $metadata->coverage = $request->coverage;
            $metadata->save();
        }

        // Redirect dengan notifikasi sukses
        return redirect()
            ->route('skripsi')
            ->with([
                'message' => 'Data skripsi berhasil diperbarui. Silakan menunggu verifikasi ulang oleh admin.',
                'alert-type' => 'success'
            ]);
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
                    'dospem' => $skripsi->dosen->nama ?? 'Belum ada data', // ✅ FIX
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
        // dd($skripsi);
        // Mengirim data PDF, data user, data skripsi, dan status admin ke view 'detail'
        return view('detailskripsimahasiswa', compact('user', 'skripsi', 'comments', 'isAdmin'));
    }

     public function skripsiPdf(int $id): StreamedResponse
    {
        $skripsi = Skripsi::findOrFail($id);

        if (! $skripsi->file_skripsi) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        $path = public_path('uploads/skripsi_files/' . $skripsi->file_skripsi);

        if (! file_exists($path)) {
            abort(404, 'File PDF tidak ditemukan di server.');
        }

        return new StreamedResponse(function () use ($path) {
            readfile($path);
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }
    public function dapusPdf(int $id): StreamedResponse
    {
        $skripsi = Skripsi::findOrFail($id);

        if (! $skripsi->file_dapus) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        $path = public_path('uploads/daftar_pustaka_files/' . $skripsi->file_dapus);

        if (! file_exists($path)) {
            abort(404, 'File PDF tidak ditemukan di server.');
        }

        return new StreamedResponse(function () use ($path) {
            readfile($path);
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }
    public function abstrakPdf(int $id): StreamedResponse
    {
        $skripsi = Skripsi::findOrFail($id);

        if (! $skripsi->file_abstrak) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        $path = public_path('uploads/abstrak_files/' . $skripsi->file_abstrak);

        if (! file_exists($path)) {
            abort(404, 'File PDF tidak ditemukan di server.');
        }

        return new StreamedResponse(function () use ($path) {
            readfile($path);
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
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

    public function tolakVerifikasi(Request $request, $id)
{
    try {
        $skripsi = Skripsi::find($id);
        if (!$skripsi) {
            return response()->json([
                'success' => false,
                'message' => 'Skripsi tidak ditemukan.',
            ], 404);
        }

        // Cek alasan dari request
        $reasons = $request->input('reasons', []);
        if (empty($reasons) || !is_array($reasons)) {
            return response()->json([
                'success' => false,
                'message' => 'Alasan penolakan belum dipilih.',
            ], 422);
        }

        // Set status ditolak (2)
        if ($skripsi->status == 0) {
            $skripsi->status = 2;
            $message = "Skripsi berhasil ditolak";
        } elseif ($skripsi->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Skripsi sudah diverifikasi, tidak bisa ditolak.',
            ], 400);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Status skripsi tidak valid.',
            ], 400);
        }

        $skripsi->save();

        // Simpan notifikasi dengan deskripsi lengkap
        Notifikasi::create([
            'skripsi_id'   => $skripsi->id,
            'mahasiswa_id' => $skripsi->user_id,
            'deskripsi'    => 'Skripsi Anda ditolak karena: ' . implode(', ', $reasons),
        ]);

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
    public function searchSkripsi(Request $request)
    {
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        $query = Skripsi::with(['mahasiswa.jurusan', 'metadata'])
                ->where('status', 1);

        // Pencarian berdasarkan judul - cek di skripsi dan metadata
        if ($request->filled('judul')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->judul.'%')
                ->orWhereHas('metadata', function($metaQuery) use ($request) {
                    $metaQuery->where('title', 'like', '%'.$request->judul.'%')
                            ->orWhere('subject', 'like', '%'.$request->judul.'%')
                            ->orWhere('description', 'like', '%'.$request->judul.'%')
                            ->orWhere('keywords', 'like', '%'.$request->judul.'%');
                });
            });
        }

        // Pencarian berdasarkan penulis - cek di skripsi dan metadata
        if ($request->filled('penulis')) {
            $query->where(function($q) use ($request) {
                $q->where('penulis', 'like', '%'.$request->penulis.'%')
                ->orWhereHas('metadata', function($metaQuery) use ($request) {
                    $metaQuery->where('creator', 'like', '%'.$request->penulis.'%')
                            ->orWhere('contributor', 'like', '%'.$request->penulis.'%');
                });
            });
        }

        // Pencarian berdasarkan tahun rilis - cek di skripsi dan metadata
        if ($request->filled('rilis')) {
            $query->where(function($q) use ($request) {
                $q->where('rilis', $request->rilis)
                ->orWhereHas('metadata', function($metaQuery) use ($request) {
                    $metaQuery->where('date_issued', $request->rilis);
                });
            });
        }

        // Filter berdasarkan program studi
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa.jurusan', function($q) use ($request) {
                $q->where('nama_jurusan', $request->prodi);
            });
        }

        $skripsi = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('skripsi2', compact('skripsi', 'jurusans'));
    }

    public function findSkripsi(Request $request)
    {
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        $query = Skripsi::with(['mahasiswa.jurusan', 'metadata', 'dosen'])
                        ->where('status', 1);

        // Filter Judul
        if ($request->filled('judul')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'LIKE', '%' . $request->judul . '%')
                ->orWhereHas('metadata', function($metaQuery) use ($request) {
                    $metaQuery->where('title', 'LIKE', '%' . $request->judul . '%')
                            ->orWhere('subject', 'LIKE', '%' . $request->judul . '%')
                            ->orWhere('description', 'LIKE', '%' . $request->judul . '%')
                            ->orWhere('keywords', 'LIKE', '%' . $request->judul . '%');
                });
            });
        }

        // Filter Penulis
        if ($request->filled('penulis')) {
            $query->where(function($q) use ($request) {
                $q->where('penulis', 'LIKE', '%' . $request->penulis . '%')
                ->orWhereHas('metadata', function($metaQuery) use ($request) {
                    $metaQuery->where('creator', 'LIKE', '%' . $request->penulis . '%')
                            ->orWhere('contributor', 'LIKE', '%' . $request->penulis . '%');
                });
            });
        }

        // Filter Tahun Rilis
        if ($request->filled('rilis')) {
            $query->where(function($q) use ($request) {
                $q->where('rilis', 'LIKE', '%' . $request->rilis . '%')
                ->orWhereHas('metadata', function($metaQuery) use ($request) {
                    $metaQuery->where('date_issued', 'LIKE', '%' . $request->rilis . '%');
                });
            });
        }

        // Filter Program Studi
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa.jurusan', function($q) use ($request) {
                $q->where('nama_jurusan', $request->prodi);
            });
        }

        // ✅ Filter Dosen Pembimbing SESUAI DATABASE (pakai relasi `dosen_id`)
        if ($request->filled('dospem')) {
            $query->whereHas('dosen', function($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->dospem . '%');
            });
        }

        // Filter Kata Kunci
        if ($request->filled('katakunci')) {
            $query->where(function($q) use ($request) {
                $q->where('katakunci', 'LIKE', '%' . $request->katakunci . '%')
                ->orWhereHas('metadata', function($metaQuery) use ($request) {
                    $metaQuery->where('keywords', 'LIKE', '%' . $request->katakunci . '%');
                });
            });
        }

        $skripsi = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('skripsi2', compact('skripsi', 'jurusans'));
    }

public function mahasiswa()
{
    // Ambil semua jurusan untuk dropdown filter
    $jurusans = Jurusan::orderBy('nama_jurusan')->get();
    

    // Ambil skripsi yang sudah terverifikasi, plus relasi mahasiswa→jurusan dan metadata
    $skripsi = Skripsi::with(['mahasiswa.jurusan', 'metadata'])
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get();

    return view('skripsi2', compact('skripsi', 'jurusans'));
}

// public function cariYangMirip(Request $request)
// {
//     $judul = $request->input('judul');
//     $keywords = explode(' ', $judul);

//     $skripsi = Skripsi::with(['mahasiswa.jurusan', 'metadata'])
//                     ->where('status', 1)
//                     ->where(function ($query) use ($keywords) {
//                         foreach ($keywords as $keyword) {
//                             $query->orWhere('judul', 'LIKE', '%' . $keyword . '%')
//                                   ->orWhere('katakunci', 'LIKE', '%' . $keyword . '%')
//                                   ->orWhere('abstrak', 'LIKE', '%' . $keyword . '%')
//                                   ->orWhereHas('metadata', function($metaQuery) use ($keyword) {
//                                       $metaQuery->where('title', 'LIKE', '%' . $keyword . '%')
//                                                ->orWhere('subject', 'LIKE', '%' . $keyword . '%')
//                                                ->orWhere('description', 'LIKE', '%' . $keyword . '%')
//                                                ->orWhere('keywords', 'LIKE', '%' . $keyword . '%')
//                                                ->orWhere('coverage', 'LIKE', '%' . $keyword . '%');
//                                   });
//                         }
//                     })
//                     ->get();

//     $jurusans = Jurusan::orderBy('nama_jurusan')->get();

//     return view('skripsi2', compact('skripsi', 'jurusans'));
// }

// Method tambahan untuk pencarian advanced menggunakan metadata
public function advancedSearch(Request $request)
{
    $jurusans = Jurusan::orderBy('nama_jurusan')->get();

    $query = Skripsi::with(['mahasiswa.jurusan', 'metadata'])
                    ->where('status', 1);

    // Pencarian berdasarkan metadata spesifik
    if ($request->filled('subject')) {
        $query->whereHas('metadata', function($metaQuery) use ($request) {
            $metaQuery->where('subject', 'LIKE', '%' . $request->subject . '%');
        });
    }

    if ($request->filled('publisher')) {
        $query->whereHas('metadata', function($metaQuery) use ($request) {
            $metaQuery->where('publisher', 'LIKE', '%' . $request->publisher . '%');
        });
    }

    if ($request->filled('language')) {
        $query->whereHas('metadata', function($metaQuery) use ($request) {
            $metaQuery->where('language', $request->language);
        });
    }

    if ($request->filled('type')) {
        $query->whereHas('metadata', function($metaQuery) use ($request) {
            $metaQuery->where('type', $request->type);
        });
    }

    if ($request->filled('coverage')) {
        $query->whereHas('metadata', function($metaQuery) use ($request) {
            $metaQuery->where('coverage', 'LIKE', '%' . $request->coverage . '%');
        });
    }

    // Pencarian global di semua field metadata
    if ($request->filled('global_search')) {
        $searchTerm = $request->global_search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('judul', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('penulis', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('abstrak', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('katakunci', 'LIKE', '%' . $searchTerm . '%')
              ->orWhereHas('metadata', function($metaQuery) use ($searchTerm) {
                  $metaQuery->where('title', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('creator', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('contributor', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('subject', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('publisher', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('keywords', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('coverage', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('identifier', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('source', 'LIKE', '%' . $searchTerm . '%')
                           ->orWhere('rights', 'LIKE', '%' . $searchTerm . '%');
              });
        });
    }

    $skripsi = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('skripsi2', compact('skripsi', 'jurusans'));
}
}
