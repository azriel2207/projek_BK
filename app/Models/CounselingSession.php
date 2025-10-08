<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingSession extends Model
{
    use HasFactory;

    protected $table = 'counseling_sessions';

    protected $fillable = [
        'student_id',
        'counselor_id',
        'jadwal',
        'topik',
        'status',
        'alasan_batal',
        'jadwal_ulang',
    ];

    protected $casts = [
        'jadwal' => 'datetime',
        'jadwal_ulang' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function counselingNote()
    {
        return $this->hasOne(CounselingNote::class, 'session_id');
    }
}