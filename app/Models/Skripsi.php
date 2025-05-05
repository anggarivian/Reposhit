<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skripsi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'penulis', 'dospem', 'rilis', 'halaman',
        'abstrak', 'file_skripsi', 'status', 'views', 'user_id'
    ];
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'id_skripsi', 'id_skripsi')->withTimestamps();
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
    public function comments()
        {
            return $this->hasMany(Comment::class, 'skripsi_id');
        }

        public function riwayatUsers()
        {
            return $this->belongsToMany(User::class, 'riwayat_skripsis', 'id_skripsi', 'id_user')->withTimestamps();
        }
}
