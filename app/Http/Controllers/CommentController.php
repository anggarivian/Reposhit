<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Skripsi;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        // Ambil semua komentar
        $comments = Comment::all();
        $skripsi = Skripsi::All();
        return view('comments.index', compact('comments'));
    }

    public function store(Request $req)
    {
        // Validasi input
        $req->validate([
            'content' => 'required|string',
        ]);

        // Buat komentar baru
        $comment = new comment ;
            $comment->content = $req->get('content');

            $comment->save();
            $notification = array(
                'message' =>'Komentar berhasil ditambahkan', 'alert-type' =>'success'
        );
        return redirect()->route('comment')->with($notification);
    }

    public function show($id)
    {
        $skripsi = Skripsi::findOrFail($id);
        $comments = $skripsi->comments; // Mengambil komentar terkait dengan skripsi

        return view('detail', compact('skripsi', 'comments'));
    }
}
