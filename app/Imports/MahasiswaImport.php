<?php
// Dari kode yang Anda berikan, sepertinya Anda menggunakan package Laravel Excel/Importer
// Berikut adalah cara memodifikasi kode import agar password asli juga tersimpan

namespace App\Imports;

use App\Models\User;
use App\Models\Jurusan;
use App\Models\PasswordHistory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class MahasiswaImport implements ToModel, WithHeadingRow, WithEvents
{
    // Array untuk menyimpan ID user dan password asli selama proses import
    protected $userPasswords = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
      public function model(array $row)
    {
        // Pastikan kolom jurusan tersedia
        if (!isset($row['jurusan'])) {
            throw new \Exception("Kolom 'jurusan' tidak ditemukan di file Excel.");
        }

        // Cari jurusan berdasarkan nama
        $jurusan = Jurusan::where('nama_jurusan', trim($row['jurusan']))->first();

        if (!$jurusan) {
            throw new \Exception("Jurusan '{$row['jurusan']}' tidak ditemukan di database.");
        }

        // Buat user baru
        $user = new User([
            'npm'        => $row['npm'],
            'name'       => $row['name'],
            'tgl_lahir'  => $row['tgl_lahir'],
            'alamat'     => $row['alamat'],
            'angkatan'   => $row['angkatan'],
            'jurusan_id' => $jurusan->id,
            'password'   => Hash::make($row['password']),
            'roles_id'   => 2, // Mahasiswa
        ]);

        // Simpan password asli untuk dicatat
        $this->userPasswords[] = [
            'user'     => $user,
            'password' => $row['password']
        ];

        return $user;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                foreach ($this->userPasswords as $data) {
                    $user = $data['user'];

                    if ($user && $user->id) {
                        PasswordHistory::create([
                            'user_id'       => $user->id,
                            'password_text' => $data['password'],
                            'created_by'    => auth()->id() ?? 1,
                        ]);
                    }
                }
            },
        ];
    }
}