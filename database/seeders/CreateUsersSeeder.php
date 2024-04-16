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
            [
                'npm'       => '5520120053',
                'name'      => 'isDosen',
                'tgl_lahir' => 'isDosen',
                'alamat'    => 'isDosen',
                'angkatan'  => 'isDosen',
                'prodi'     => 'isDosen',
                // 'email'     => 'dosen@mail.com',
                'password'  => bcrypt('12345'),
                'roles_id'  => 3
            ],
            [
                'npm'       => '5520120052',
                'name'      => 'isMahasiswa',
                'tgl_lahir' => 'isMahasiswa',
                'alamat'    => 'isMahasiswa',
                'angkatan'  => 'isMahasiswa',
                'prodi'     => 'isMahasiswa',
                // 'email'     => 'mahasiswa@mail.com',
                'password'  => bcrypt('12345'),
                'roles_id'  => 2
            ],
            [
                'npm'      => '5520120051',
                'name'      => 'isAdmin',
                'tgl_lahir' => 'isAdmin',
                'alamat'    => 'isAdmin',
                'angkatan'  => 'isAdmin',
                'prodi'     => 'isAdmin',
                // 'email'     => 'admin@mail.com',
                'password'  => bcrypt('12345'),
                'roles_id'  => 1
            ]
        ];

        foreach($user as $key => $value){
            User::create($value);
        }
    }
}
