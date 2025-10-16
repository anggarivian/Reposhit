<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            // [
            //     'npm'       => '5520120053',
            //     'name'      => 'isDosen',
            //     'tgl_lahir' => 'isDosen',
            //     'alamat'    => 'isDosen',
            //     'angkatan'  => 'isDosen',
            //     'prodi'     => 'isDosen',
            //     'jurusan_id'     => 2,
            //     // 'email'     => 'dosen@mail.com',
            //     'password'  => bcrypt('12345'),
            //     'roles_id'  => 3
            // ],
            [
                'npm'       => '5520120052',
                'name'      => 'isMahasiswa',
                'tgl_lahir' => '2000-01-01',
                'alamat'    => 'Alamat Mahasiswa',
                'angkatan'  => '2020',
                'jurusan_id'=> 2,
                'dosen_id'=> 2,
                'password'  => bcrypt('12345'),
                'roles_id'  => 2
            ],

            [
            'npm'        => '1',
            'name'       => 'isAdmin',
            'tgl_lahir'  => '2000-01-01',
            'alamat'     => 'Jl. Admin Raya',
            'angkatan'   => '2020',
            'jurusan_id' => 1,
            'dosen_id' => 1,
            'password'   => bcrypt('12345'),
            'roles_id'   => 1,
            'status'     => 1, // ← ini status aktif
            ]
        ];

        foreach($user as $key => $value){
            User::create($value);
        }
    }
}
