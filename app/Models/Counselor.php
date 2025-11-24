<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nip',
        'no_hp',
        'specialization',    // TAMBAH INI
        'office_hours',      // TAMBAH INI
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function counselingSessions()
    {
        return $this->hasMany(CounselingSession::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function developments()
    {
        return $this->hasMany(StudentDevelopment::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(CounselorFeedback::class);
    }

    // ACCESSOR UNTUK KOMPATIBILITAS
    public function getSpecializationAttribute($value)
    {
        return $value ?? 'Umum';
    }

    public function getOfficeHoursAttribute($value)
    {
        return $value ?? '08:00 - 16:00';
    }
}