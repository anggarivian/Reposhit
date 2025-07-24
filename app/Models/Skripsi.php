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

    // Relasi ke favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'id_skripsi');
    }

    public function user(){

        return $this->belongsTo(User::class, 'user_id');
    }
    public function comments(){

        return $this->hasMany(Comment::class, 'skripsi_id');
    }

    public function riwayatUsers(){

        return $this->belongsToMany(User::class, 'riwayat_skripsis', 'id_skripsi', 'id_user')->withTimestamps();
    }
    public function mahasiswa(){

        return $this->belongsTo(User::class, 'user_id');
    }

    public function metadata()
    {
        return $this->hasOne(Metadata::class, 'skripsi_id');
    }

    // Accessor untuk mendapatkan kata kunci sebagai array
    public function getKataKunciArrayAttribute()
    {
        return explode(',', $this->katakunci);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('judul', 'LIKE', '%' . $term . '%')
              ->orWhere('penulis', 'LIKE', '%' . $term . '%')
              ->orWhere('abstrak', 'LIKE', '%' . $term . '%')
              ->orWhere('katakunci', 'LIKE', '%' . $term . '%')
              ->orWhereHas('metadata', function($metaQuery) use ($term) {
                  $metaQuery->where('title', 'LIKE', '%' . $term . '%')
                           ->orWhere('creator', 'LIKE', '%' . $term . '%')
                           ->orWhere('contributor', 'LIKE', '%' . $term . '%')
                           ->orWhere('subject', 'LIKE', '%' . $term . '%')
                           ->orWhere('description', 'LIKE', '%' . $term . '%')
                           ->orWhere('keywords', 'LIKE', '%' . $term . '%')
                           ->orWhere('coverage', 'LIKE', '%' . $term . '%');
              });
        });
    }

    // Scope untuk filter berdasarkan tahun
    public function scopeByYear($query, $year)
    {
        return $query->where(function($q) use ($year) {
            $q->where('rilis', $year)
              ->orWhereHas('metadata', function($metaQuery) use ($year) {
                  $metaQuery->where('date_issued', $year);
              });
        });
    }

    // Scope untuk pencarian berdasarkan penulis
    public function scopeByAuthor($query, $author)
    {
        return $query->where(function($q) use ($author) {
            $q->where('penulis', 'LIKE', '%' . $author . '%')
              ->orWhereHas('metadata', function($metaQuery) use ($author) {
                  $metaQuery->where('creator', 'LIKE', '%' . $author . '%')
                           ->orWhere('contributor', 'LIKE', '%' . $author . '%');
              });
        });
    }
}
