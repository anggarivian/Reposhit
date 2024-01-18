<?php

namespace App\Imports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements WithHeadingRow, ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Dosen([
            'nama' => $row['nama'],
            'nip' => $row['nip'],
            'alamat' => $row['alamat'],
            'email' => $row['email'],
            'tgl_lahir' => $row['tgl_lahir'],
            'kontak' => $row['kontak'],
            'gelar_akademik' => $row['gelar_akademik'],
            'program_studi' => $row['program_studi'],
            'jabatan' => $row['jabatan'],
        ]);
    }
}
