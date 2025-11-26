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
        'lokasi',
        'status',
        'keluhan',
        'jenis_bimbingan',
        'guru_bk',
        'catatan_konselor'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Scope untuk statistik
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
    }

    public function scopeBulanLalu($query)
    {
        return $query->whereMonth('tanggal', now()->subMonth()->month)
                    ->whereYear('tanggal', now()->subMonth()->year);
    }

    public function scopePeriode($query, $periode)
    {
        switch ($periode) {
            case '3_bulan':
                return $query->where('tanggal', '>=', now()->subMonths(3));
            case '6_bulan':
                return $query->where('tanggal', '>=', now()->subMonths(6));
            case 'tahun_ini':
                return $query->whereYear('tanggal', now()->year);
            default: // bulan_ini
                return $query->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year);
        }
    }

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