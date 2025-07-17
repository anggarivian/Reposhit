<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
 protected $fillable = ['nama_jurusan', 'kode_jurusan'];

 public function users()
    {
        return $this->hasMany(User::class);
    }
}
