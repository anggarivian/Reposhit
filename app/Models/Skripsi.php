<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skripsi extends Model
{
    use HasFactory;

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'id_skripsi', 'id_skripsi')->withTimestamps();
    }
}
