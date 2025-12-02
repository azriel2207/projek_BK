<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    use HasFactory;

    // Ganti table jika berbeda (mis: 'catatan' / 'catatans')
    protected $table = 'catatan';

    protected $guarded = [];
    
    /**
     * Relationship dengan User (Siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship dengan JanjiKonseling
     */
    public function janjiKonseling()
    {
        return $this->belongsTo(JanjiKonseling::class, 'janji_id');
    }

    /**
     * Relationship dengan User (Guru BK) melalui nama
     * Catatan: ini adalah relasi berdasarkan nama, bukan foreign key
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_bk', 'name');
    }
}