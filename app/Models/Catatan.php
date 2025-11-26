<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    use HasFactory;

    // Ganti table jika berbeda (mis: 'catatan' / 'catatans')
    protected $table = 'catatans';

    protected $guarded = [];
    
    public function siswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}