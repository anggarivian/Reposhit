<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\User;
use App\Models\Skripsi;
use Illuminate\Http\Request;

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
}
