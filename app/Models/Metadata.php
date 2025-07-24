<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $table = 'metadata';

    protected $fillable = [
        'skripsi_id',
        'title',
        'creator',
        'contributor',
        'subject',
        'description',
        'publisher',
        'date_issued',
        'language',
        'type',
        'format',
        'identifier',
        'source',
        'rights',
        'keywords',
        'coverage'
    ];

    protected $casts = [
        'date_issued' => 'integer',
    ];

    // Relasi ke skripsi (belongs to)
    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class, 'skripsi_id');
    }

    // Accessor untuk mendapatkan keywords sebagai array
    public function getKeywordsArrayAttribute()
    {
        return $this->keywords ? explode(',', $this->keywords) : [];
    }

    // Mutator untuk menyimpan keywords dari array
    public function setKeywordsArrayAttribute($value)
    {
        $this->attributes['keywords'] = is_array($value) ? implode(',', $value) : $value;
    }

    // Scope untuk pencarian berdasarkan subjek
    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', 'LIKE', '%' . $subject . '%');
    }

    // Scope untuk pencarian berdasarkan tahun
    public function scopeByYear($query, $year)
    {
        return $query->where('date_issued', $year);
    }

    // Scope untuk pencarian berdasarkan tipe dokumen
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope untuk pencarian berdasarkan bahasa
    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    // Scope untuk pencarian global dalam metadata
    public function scopeGlobalSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'LIKE', '%' . $term . '%')
              ->orWhere('creator', 'LIKE', '%' . $term . '%')
              ->orWhere('contributor', 'LIKE', '%' . $term . '%')
              ->orWhere('subject', 'LIKE', '%' . $term . '%')
              ->orWhere('description', 'LIKE', '%' . $term . '%')
              ->orWhere('publisher', 'LIKE', '%' . $term . '%')
              ->orWhere('keywords', 'LIKE', '%' . $term . '%')
              ->orWhere('coverage', 'LIKE', '%' . $term . '%')
              ->orWhere('identifier', 'LIKE', '%' . $term . '%')
              ->orWhere('source', 'LIKE', '%' . $term . '%')
              ->orWhere('rights', 'LIKE', '%' . $term . '%');
        });
    }
}
