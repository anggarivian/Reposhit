<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class favorite extends Model
{
    use HasFactory;

    protected $fillable = ['id_user', 'id_skripsi'];

    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class, 'id_skripsi');
    }

    // public function favoriteSkripsi()
    // {
    //     return $this->belongsToMany(Skripsi::class, 'favorite_skripsi', 'id_user', 'id_skripsi');
    // }
}
