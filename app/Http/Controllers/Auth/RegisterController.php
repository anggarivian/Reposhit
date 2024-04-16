<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'npm' => ['required', 'string', 'max:10', 'min:10', 'unique:users,npm'],
            'name' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'string', 'max:4', 'min:4'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'prodi' => ['required', 'string', 'max:25'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'npm' => $data['npm'],
            'name' => $data['name'],
            'status'=>0,
            'tgl_lahir' => $data['tgl_lahir'],
            'alamat' => $data['alamat'],
            'angkatan' => $data['angkatan'],
            'prodi' => $data['prodi'],
            'password' => Hash::make($data['password']),
            'roles_id' =>2,
        ]);
    }
}
