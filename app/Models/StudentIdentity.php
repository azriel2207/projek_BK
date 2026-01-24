<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentIdentity extends Model
{
    use HasFactory;

    protected $table = 'student_identities';

    protected $fillable = [
        'student_id',
        'no_induk',
        'tempat_lahir',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'catatan_khusus',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
