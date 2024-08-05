<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\User;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        $id_user = user ::all();
        $skripsi_id = Skripsi::all();
        return view('comment', compact('comments','comment','id_user','skripsi_id'));
    }

    public function postkomentar(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);
        // dd($request->id_skripsi);
        $comment = new Comment();
        $comment->skripsi_id = $request->id_skripsi; // Pastikan user memiliki atribut skripsi_id atau relasi
        $comment->id_user = auth()->id();
        $comment->content = $request->content;
        $comment->save();


         // Ambil ulang daftar komentar yang terkait dengan skripsi ini, urutkan berdasarkan waktu pembuatan (terbaru ke terlama)
        $comment = Comment::where('skripsi_id', $request->id_skripsi)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }

    public function deletekomentar($id)
    {
        $comment = Comment::find($id);

        if ($comment) {
            $comment->delete();
            return redirect()->back()->with('success', 'Komentar berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Komentar tidak ditemukan');
        }
    }
    public function postBalasan(Request $request){
        // dd($request);
    // Validasi input
    $request->validate([
        'content' => 'required',
        'id_skripsi' => 'required',
        'parent_id' => 'required',
    ]);

    // Membuat objek baru untuk balasan komentar
    $balasan = new Comment();
    $balasan->content = $request->input('content');
    $balasan->skripsi_id = $request->input('id_skripsi');
    $balasan->id_user = auth()->user()->id;
    $balasan->parent_id = $request->input('parent_id');
    $balasan->save();

    // Redirect kembali ke halaman sebelumnya dengan notifikasi
    return redirect()->back()->with('success', 'Balasan berhasil ditambahkan');
    }
    public function postBalasan1(Request $request){
        // dd($request);
    // Validasi input
    $request->validate([
        'content' => 'required',
        'id_skripsi' => 'required',
        'parent_id' => 'required',
    ]);

    // Membuat objek baru untuk balasan komentar
    $balasan = new Comment();
    $balasan->content = $request->input('content');
    $balasan->skripsi_id = $request->input('id_skripsi');
    $balasan->id_user = auth()->user()->id;
    $balasan->parent_id = $request->input('parent_id');
    $balasan->save();

    // Redirect kembali ke halaman sebelumnya dengan notifikasi
    return redirect()->back()->with('success', 'Balasan berhasil ditambahkan');
    }

    // ADMIN KOMENTAR
    public function postkomentar1(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);
        // dd($request->id_skripsi);
        $comment = new Comment();
        $comment->skripsi_id = $request->id_skripsi; // Pastikan user memiliki atribut skripsi_id atau relasi
        $comment->id_user = auth()->id();
        $comment->content = $request->content;
        $comment->save();


         // Ambil ulang daftar komentar yang terkait dengan skripsi ini, urutkan berdasarkan waktu pembuatan (terbaru ke terlama)
        $comment = Comment::where('skripsi_id', $request->id_skripsi)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }

    public function toggleFavorite($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->favorite === 'yes') {
            $comment->favorite = 'no';
        } else {
            $comment->favorite = 'yes';
        }

        $comment->save();

        return redirect()->back()->with('message', 'Status favorit berhasil diubah.');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Temukan komentar berdasarkan ID
        $comment = Comment::findOrFail($id);

        // Cek apakah user yang login adalah pemilik komentar
        if (auth()->user()->id != $comment->id_user) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan untuk mengedit komentar ini.');
        }

        // Update komentar
        $comment->content = $request->input('content');
        $comment->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Komentar berhasil diperbarui.');
    }
}
