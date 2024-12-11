<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class riwayat_skripsi extends Model
{
    protected $fillable = ['id_user', 'id_skripsi'];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class, 'id_skripsi');
    }
}
