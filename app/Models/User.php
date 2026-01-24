<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmailNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'password',
        'role',
        'phone',
        'class',
        'nis_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role check methods
    public function isKoordinatorBK()
    {
        return $this->role === 'koordinator_bk' || $this->role === 'koordinator';
    }

    public function isGuruBK()
    {
        return $this->role === 'guru_bk' || $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isWaliKelas()
    {
        return $this->role === 'wali_kelas';
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification());
    }

    // Relationships
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function counselor()
    {
        return $this->hasOne(Counselor::class, 'user_id');
    }

    public function janjiKonselings()
    {
        return $this->hasMany(JanjiKonseling::class, 'user_id');
    }

    public function catatanKonselings()
    {
        return $this->hasMany(Catatan::class, 'user_id');
    }

    public function walisiswa()
    {
        return $this->hasMany(Student::class, 'wali_kelas_id');
    }

    public function studentBehaviors()
    {
        return $this->hasMany(\App\Models\StudentBehavior::class, 'recorded_by');
    }

    public function waliKelasNotes()
    {
        return $this->hasMany(\App\Models\WaliKelasNote::class, 'wali_kelas_id');
    }
}