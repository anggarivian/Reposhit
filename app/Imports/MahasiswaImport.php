<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements WithHeadingRow, ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'npm' => $row['npm'],
            'name' => $row['name'],
            'status' => $row['status'],
            'tgl_lahir' => $row['tgl_lahir'],
            'alamat' => $row['alamat'],
            'angkatan' => $row['angkatan'],
            'prodi' => $row['prodi'],
            'password' => Hash::make($row['password']),
            'roles_id' => 2 ,
        ]);
    }
}
