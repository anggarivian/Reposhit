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
                'npm'       => '-',
                'name'      => 'isMahasiswa',
                'tgl_lahir' => 'isMahasiswa',
                'alamat'    => 'isMahasiswa',
                'angkatan'  => 'isMahasiswa',
                'prodi'     => 'isMahasiswa',
                'email'     => 'mahasiswa@mail.com',
                'password'  => bcrypt('12345'),
                'roles_id'  => 2
            ],
            [
                'npm'       => '-',
                'name'      => 'isAdmin',
                'tgl_lahir' => 'isAdmin',
                'alamat'    => 'isAdmin',
                'angkatan'  => 'isAdmin',
                'prodi'     => 'isAdmin',
                'email'     => 'admin@mail.com',
                'password'  => bcrypt('12345'),
                'roles_id'  => 1
            ]
        ];

        foreach($user as $key => $value){
            User::create($value);
        }
    }
}
