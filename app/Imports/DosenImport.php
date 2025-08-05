<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Jurusan;
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
    // Pastikan kolom jurusan ada
    if (!isset($row['jurusan'])) {
        throw new \Exception("Kolom 'jurusan' tidak ditemukan di file Excel.");
    }

    // Cari jurusan berdasarkan nama jurusan
    $jurusan = Jurusan::where('nama_jurusan', trim($row['jurusan']))->first();

    if (!$jurusan) {
        throw new \Exception("Jurusan '{$row['jurusan']}' tidak ditemukan di database.");
    }

      // Ubah status dari teks menjadi boolean
    $status = strtolower(trim($row['status'])) === 'aktif' ? true : false;
    return new Dosen([
        'nama'        => $row['nama'],
        'nip'         => $row['nip'],
        // 'tgl_lahir'   => $row['tgl_lahir'],
        'kontak'      => $row['kontak'],
        'jurusan_id'  => $jurusan->id,
        'status'      => $status, // Atau bisa juga 1
    ]);
}

}
