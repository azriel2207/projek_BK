<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'password',
        'role',
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

    public function isKoordinatorBK()
    {
        return $this->role === 'koordinator_bk';
    }

    public function isGuruBK()
    {
        return $this->role === 'guru_bk';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}