<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nis',
        'tgl_lahir',
        'alamat',
        'no_hp',
        'kelas',
        'wali_kelas_id',
        'nis_verified',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function behaviors()
    {
        return $this->hasMany(\App\Models\StudentBehavior::class, 'student_id');
    }

    public function identity()
    {
        return $this->hasOne(\App\Models\StudentIdentity::class, 'student_id');
    }

    public function waliKelasNotes()
    {
        return $this->hasMany(\App\Models\WaliKelasNote::class, 'student_id');
    }
}
