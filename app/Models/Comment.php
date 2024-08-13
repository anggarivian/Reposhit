<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'content', 'skripsi_id', 'parent_id', 'id_user', 'favorite'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relasi ke komentar induk
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
}

}
