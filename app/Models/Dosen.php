<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function findForPassport($username) {
        return $this->orWhere('email', $username)->orWhere('npm', $username)->first();
    }
    public function jurusan(){
    
        return $this->belongsTo(Jurusan::class);
    }
    public function skripsis()
    {
    return $this->hasMany(Skripsi::class, 'dosen_id');
    }

}
