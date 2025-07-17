<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;

class JurusanController extends Controller
{
        public function index()
    {
        $jurusans = Jurusan::all();
        return view('jurusan', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:50|unique:jurusans,kode_jurusan'
        ]);

        Jurusan::create([
            'nama_jurusan' => $request->nama_jurusan,
            'kode_jurusan' => $request->kode_jurusan
        ]);

        return redirect()->route('jurusan')->with([
            'message' => 'Jurusan berhasil ditambahkan',
            'alert-type' => 'success'
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jurusans,id',
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:50'
        ]);

        $jurusan = Jurusan::findOrFail($request->id);

        // Validasi unique jika kode_jurusan berubah
        if ($request->kode_jurusan !== $jurusan->kode_jurusan) {
            $request->validate([
                'kode_jurusan' => 'unique:jurusans,kode_jurusan'
            ]);
        }

        $jurusan->update([
            'nama_jurusan' => $request->nama_jurusan,
            'kode_jurusan' => $request->kode_jurusan
        ]);

        return redirect()->route('jurusan')->with([
            'message' => 'Jurusan berhasil diubah',
            'alert-type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jurusan berhasil dihapus'
        ]);
    }

    public function getData($id)
    {
        $data = Jurusan::findOrFail($id);
        return response()->json($data);
    }
}