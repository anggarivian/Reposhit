<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordHistory;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
        // Read Data Mahasiswa ----------------------------------------------------------------------------------------------
         public function index() {
             $jurusans = Jurusan::all(); // untuk dropdown
            $mahasiswa = User::with(['jurusan', 'lastPassword'])
                ->where('roles_id', 2)
                ->paginate(5);

            return view('mahasiswa', compact('mahasiswa','jurusans'));
        }

        // Tambah Data Mahasiswa ----------------------------------------------------------------------------------------------
        public function tambah(Request $req){

            // Validasi Data Mahasiswa ----------------------------------------------------------------
            $req->validate([
                'name' => 'required|string|max:30',
                'npm' => 'required|string|max:10|min:10|unique:users,npm',
                // 'email' => 'required|email|max:50|unique:users,email',
                'tgl_lahir' => 'required|date',
                'alamat' => 'required|string|max:255',
                'angkatan' => 'required|string|max:4|min:4',
                'password' => 'required|string|min:8|max:255',
                'jurusan_id' => 'required|exists:jurusans,id',
            ]);

            // Create Data Mahasiswa ------------------------------------------------------------------
            $mahasiswa = new User;

            $mahasiswa->name = $req->get('name');
            $mahasiswa->npm = $req->get('npm');
            // $mahasiswa->email = $req->get('email');
            $mahasiswa->tgl_lahir = $req->get('tgl_lahir');
            $mahasiswa->alamat = $req->get('alamat');
            $mahasiswa->angkatan = $req->get('angkatan');
            $mahasiswa->jurusan_id = $req->get('jurusan_id');
            $mahasiswa->password = Hash::make($req->get('password'));
            $mahasiswa->status = true; // ✅ default aktif
            $mahasiswa->roles_id = 2;

            $mahasiswa->save();

            // Simpan password asli ke dalam password_histories
            $passwordHistory = new PasswordHistory;
            $passwordHistory->user_id = $mahasiswa->id;
            $passwordHistory->password_text = $req->get('password');
            $passwordHistory->created_by = auth()->id(); // ID admin yang membuat
            $passwordHistory->save();

            $notification = array(
                'message' =>'Data Mahasiswa berhasil ditambahkan', 'alert-type' =>'success'
            );
            return redirect()->route('mahasiswa')->with($notification);
        }

    // Get Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function getDataMahasiswa($id){
        $mahasiswa = User::find($id);
        return User::with('jurusan')->find($id);
        return response()->json($mahasiswa);
    }

    // Ubah Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function ubah(Request $req)
{
    // Validasi Data Mahasiswa ----------------------------------------------------------------
    $req->validate([
        'id'               => 'required|exists:users,id',
        'name'             => 'required|string|max:30',
        'npm'              => 'required|string|min:10|max:10',
        // 'email'          => 'required|email|max:50',
        'tgl_lahir'        => 'required|date',
        'alamat'           => 'required|string|max:255',
        'angkatan'         => 'required|string|min:4|max:4',
        'jurusan_id'       => 'required|exists:jurusans,id',
        'password'         => 'nullable|string|min:8|max:255|confirmed',
        'status' => 'required|in:0,1',
    ]);

    // Ambil model Mahasiswa ------------------------------------------------------------------
    $mahasiswa = User::findOrFail($req->get('id'));

    // Update Data Profil ---------------------------------------------------------------------
    $mahasiswa->name       = $req->get('name');
    $mahasiswa->npm        = $req->get('npm');
    // $mahasiswa->email    = $req->get('email');
    $mahasiswa->tgl_lahir  = $req->get('tgl_lahir');
    $mahasiswa->alamat     = $req->get('alamat');
    $mahasiswa->angkatan   = $req->get('angkatan');
    $mahasiswa->jurusan_id = $req->get('jurusan_id');
    $mahasiswa->status = $req->get('status');

    // Jika ada password baru, hash & catat riwayatnya ----------------------------------------
    if ($req->filled('password')) {
        $plainPassword = $req->get('password');
        $mahasiswa->password = Hash::make($plainPassword);

        PasswordHistory::create([
            'user_id'      => $mahasiswa->id,
            'password_text'=> $plainPassword,
            'created_by'   => auth()->id(),
        ]);
    }

    // Simpan perubahan -----------------------------------------------------------------------
    $mahasiswa->save();

    // Notifikasi & Redirect ------------------------------------------------------------------
    return redirect()
        ->route('mahasiswa')
        ->with([
            'message'    => 'Data Mahasiswa berhasil diubah',
            'alert-type' => 'success'
        ]);
}


    // Hapus Data Mahasiswa ----------------------------------------------------------------------------------------------
    public function hapus($id) {
        try {
            $mahasiswa = User::find($id);

            if ($mahasiswa) {
                // Hapus password history terlebih dahulu (cascade sebenarnya sudah menangani ini)
                \App\Models\PasswordHistory::where('user_id', $id)->delete();

                // Hapus user
                $mahasiswa->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil dihapus',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    // Reset Password Mahasiswa ----------------------------------------------------------------------------------------------
    public function resetPassword(Request $req)
{
    $req->validate([
        'id' => 'required|exists:users,id',
        'password' => 'required|string|min:8|max:255',
    ]);

    $mahasiswa = User::find($req->get('id'));

    // Update password
    $plainPassword = $req->get('password');
    $mahasiswa->password = Hash::make($plainPassword);
    $mahasiswa->save();

    // Simpan riwayat password
    PasswordHistory::create([
        'user_id'      => $mahasiswa->id,
        'password_text'=> $plainPassword,
        'created_by'   => Auth::id(),
    ]);

    return redirect()->route('mahasiswa')->with([
        'message'    => 'Password mahasiswa berhasil direset',
        'alert-type' => 'success'
    ]);
}


    // Import Mahasiswa --------------------------------------------------------------------------------------------------
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));

            return redirect()->route('mahasiswa')->with([
                'message' => 'Data Mahasiswa berhasil diimport',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            \Log::error('Import Mahasiswa Error: ' . $e->getMessage());

            return redirect()->route('mahasiswa')->with([
                'message' => 'Gagal import data: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    // public function toggleVerifikasi($id) {
    //     $mahasiswa = User::find($id);

    //     // Toggle status verifikasi
    //     if ($mahasiswa->status == 0) {
    //         $mahasiswa->status = 1;
    //         $message = "Data Mahasiswa berhasil diverifikasi";
    //     } else {
    //         $mahasiswa->status = 0;
    //         $message = "Data Mahasiswa berhasil dibatalkan verifikasinya";
    //     }

    //     $mahasiswa->save();

    //     $success = true;

    //     return response()->json([
    //         'success' => $success,
    //         'message' => $message,
    //     ]);
    // }

    // Tampilkan form ubah password
    public function ubahPassword()
    {
        // Pastikan yang mengakses adalah user yang sudah login
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        return view('resetpassword', compact('user'));
    }

    /**
     * Memproses perubahan password
     */
    public function updatePassword(Request $request)
{
    $customMessages = [
        'current_password.required'  => 'Password saat ini harus diisi.',
        'password.required'          => 'Password baru harus diisi.',
        'password.string'            => 'Password baru harus berupa teks.',
        'password.min'               => 'Password minimal harus memiliki 8 karakter.',
        'password.confirmed'         => 'Konfirmasi password tidak sesuai.',
        'password.different'         => 'Password baru harus berbeda dengan password saat ini.',
    ];

    $request->validate([
        'current_password' => 'required',
        'password'         => 'required|string|min:8|confirmed|different:current_password',
    ], $customMessages);

    $user = Auth::user();

    // Verifikasi password saat ini
    if (! Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'Password saat ini tidak cocok.'
        ]);
    }

    // Update password
    $plainPassword = $request->password;
    $user->password = Hash::make($plainPassword);
    $user->save();

    // Simpan riwayat password
    PasswordHistory::create([
        'user_id'      => $user->id,
        'password_text'=> $plainPassword,
        'created_by'   => $user->id, // atau Auth::id(), sama saja
    ]);

    return redirect()->route('home')->with([
        'message'    => 'Password berhasil diperbarui.',
        'alert-type' => 'success'
    ]);
}

}
