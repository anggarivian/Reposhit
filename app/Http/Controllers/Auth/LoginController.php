<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/home';
    protected $npm = 'npm';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ...

    protected function attemptLogin(Request $request)
    {
        $field = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'npm';

        $credentials = [
            $field => $request->input('email'),
            'password' => $request->input('password'),
        ];

        // Cek status verifikasi dan role
        $user = \App\Models\User::where($field, $request->input('email'))->first();
        if ($user && $user->roles_id == 2 && $user->status == 0) {
            // Jika role_id adalah 2 (mahasiswa) dan status = 0 (belum diverifikasi)
            return redirect()->back()->with('error', 'Akun Anda belum diverifikasi oleh admin.');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath())->with('success', 'Selamat datang, Anda berhasil login.');
        }

        return redirect()->back()->with('error', 'Login gagal, silakan periksa kembali kredensial Anda.');
    }
}
