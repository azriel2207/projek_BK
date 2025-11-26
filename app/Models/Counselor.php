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
        'email',
        'specialization',
        'office_hours',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ...existing relations...

    // Compatibility accessors / mutators (alias)
    public function getNamaAttribute()
    {
        return $this->nama_lengkap;
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama_lengkap'] = $value;
    }

    public function getTeleponAttribute()
    {
        return $this->no_hp;
    }

    public function setTeleponAttribute($value)
    {
        $this->attributes['no_hp'] = $value;
    }

    public function getSpesialisasiAttribute()
    {
        // gunakan kolom specialization di DB
        return $this->attributes['specialization'] ?? 'Umum';
    }

    public function setSpesialisasiAttribute($value)
    {
        $this->attributes['specialization'] = $value;
    }

    public function getOfficeHoursAttribute($value)
    {
        return $value ?? '08:00 - 16:00';
    }
}