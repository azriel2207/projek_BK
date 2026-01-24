<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliKelasNote extends Model
{
    use HasFactory;

    protected $table = 'wali_kelas_notes';

    protected $fillable = [
        'student_id',
        'wali_kelas_id',
        'catatan',
        'tanggal_catatan',
        'tipe_catatan',
    ];

    protected $casts = [
        'tanggal_catatan' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }
}
