<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class PasswordHistory extends Model
{
    use HasFactory;

    protected $table = 'password_histories';
    
    protected $fillable = [
        'user_id',
        'password_text',
        'created_by'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

