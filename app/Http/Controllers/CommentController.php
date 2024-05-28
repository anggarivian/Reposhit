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

        $comment = new Comment();
        $comment->skripsi_id = auth()->user()->skripsi_id; // Pastikan user memiliki atribut skripsi_id atau relasi
        $comment->id_user = auth()->id();
        $comment->content = $request->content;
        $comment->save();

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }
}

    // public function store(Request $req)
    // {
    //     // Validasi input
    //     $req->validate([
    //         'content' => 'required|string',
    //     ]);

    //     // Buat komentar baru
    //     $comment = new comment ;
    //         $comment->content = $req->get('content');

    //         $comment->save();
    //         $notification = array(
    //             'message' =>'Komentar berhasil ditambahkan', 'alert-type' =>'success'
    //     );
    //     return redirect()->route('comment')->with($notification);
    // }

    // public function show($id)
    // {
    //     $skripsi = Skripsi::findOrFail($id);
    //     $comments = $skripsi->comments; // Mengambil komentar terkait dengan skripsi

    //     return view('detail', compact('skripsi', 'comments'));
    // }
