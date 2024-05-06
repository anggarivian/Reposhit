<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['skripsi_id', 'content'];
    public function comments()
    {
        return $this->belongsTo(Skripsi::class);
    }
}
