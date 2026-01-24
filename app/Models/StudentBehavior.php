<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBehavior extends Model
{
    use HasFactory;

    protected $table = 'student_behaviors';

    protected $fillable = [
        'student_id',
        'recorded_by',
        'kategori',
        'deskripsi',
        'tanggal_kejadian',
        'status',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
