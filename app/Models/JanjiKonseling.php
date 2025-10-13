<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JanjiKonseling extends Model
{
    use HasFactory;

    protected $table = 'janji_konselings';

    protected $fillable = [
        'user_id',
        'tanggal', 
        'waktu',
        'status',
        'keluhan',
        'jenis_bimbingan',
        'guru_bk',
        'catatan_konselor'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Accessor untuk status_color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'menunggu' => 'yellow',
            'dikonfirmasi' => 'blue',
            'selesai' => 'green',
            'dibatalkan' => 'red',
            default => 'gray'
        };
    }

    // Accessor untuk jenis_bimbingan_text
    public function getJenisBimbinganTextAttribute()
    {
        return match($this->jenis_bimbingan) {
            'pribadi' => 'Bimbingan Pribadi',
            'belajar' => 'Bimbingan Belajar',
            'karir' => 'Bimbingan Karir',
            'sosial' => 'Bimbingan Sosial',
            default => $this->jenis_bimbingan
        };
    }

    // Accessor untuk deskripsi (alias keluhan)
    public function getDeskripsiAttribute()
    {
        return $this->keluhan;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}