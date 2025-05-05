<?php
// Dari kode yang Anda berikan, sepertinya Anda menggunakan package Laravel Excel/Importer
// Berikut adalah cara memodifikasi kode import agar password asli juga tersimpan

namespace App\Imports;

use App\Models\User;
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
        // Simpan user baru
        $user = new User([
            'npm' => $row['npm'],
            'name' => $row['name'],
            // 'status' => $row['status'],
            'tgl_lahir' => $row['tgl_lahir'],
            'alamat' => $row['alamat'],
            'angkatan' => $row['angkatan'],
            'prodi' => $row['prodi'],
            'password' => Hash::make($row['password']),
            'roles_id' => 2,
        ]);
        
        // Simpan password asli untuk diproses nanti
        $this->userPasswords[] = [
            'user' => $user,
            'password' => $row['password']
        ];
        
        return $user;
    }
    
    /**
     * Menyimpan password asli setelah import selesai
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                foreach ($this->userPasswords as $data) {
                    $user = $data['user'];
                    
                    // Pastikan user sudah memiliki ID (sudah tersimpan di database)
                    if ($user && $user->id) {
                        // Simpan password asli ke tabel password_histories
                        PasswordHistory::create([
                            'user_id' => $user->id,
                            'password_text' => $data['password'],
                            'created_by' => auth()->id() ?? 1, // ID admin yang melakukan import
                        ]);
                    }
                }
            },
        ];
    }
}