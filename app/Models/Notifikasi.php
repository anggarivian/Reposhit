<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
protected $fillable = [
    'skripsi_id',
    'mahasiswa_id',
    'deskripsi',
];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
