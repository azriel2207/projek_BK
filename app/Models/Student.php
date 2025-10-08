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
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function counselingSessions()
    {
        return $this->hasMany(CounselingSession::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function developments()
    {
        return $this->hasMany(StudentDevelopment::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(CounselorFeedback::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}