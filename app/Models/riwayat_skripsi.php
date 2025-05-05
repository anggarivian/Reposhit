<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class riwayat_skripsi extends Model
{
     // Set the table name explicitly to match your migration
     protected $table = 'riwayat_skripsis';
    
     protected $fillable = ['id_user', 'id_skripsi'];
 
     // Relationships
     public function user()
     {
         return $this->belongsTo(User::class, 'id_user');
     }
 
     public function skripsi()
     {
         return $this->belongsTo(Skripsi::class, 'id_skripsi');
     }
}
