<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\favorite;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    public function lastPassword()
    {
        return $this->hasOne(PasswordHistory::class)->latestOfMany();
    }

    public function findForPassport($username) {
        return $this->where('npm', $username)->first();
    }
    public function isAdmin()
    {
        return $this->role === 'admin'; // Sesuaikan dengan kolom dan nilai di database Anda
    }

    public function favorites()
    {
        return $this->belongsToMany(Skripsi::class, 'favorites', 'id_user', 'id_skripsi')->withTimestamps();
    }
    public function skripsis()
    {
        return $this->hasMany(Skripsi::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user');
    }

    public function riwayatSkripsis()
    {
        return $this->belongsToMany(Skripsi::class, 'riwayat_skripsis', 'id_user', 'id_skripsi')->withTimestamps();
    }
    // Mahasiswa.php
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id', 'mahasiswa_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
    public function dosen()
    {
    return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Mengambil password_text terakhir dari history
     */
    public function getLatestPasswordTextAttribute()
    {
        return optional($this->passwordHistories()->latest('created_at')->first())->password_text ?? null;
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
