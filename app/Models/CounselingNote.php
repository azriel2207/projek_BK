<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'catatan',
        'rekomendasi',
    ];

    public function session()
    {
        return $this->belongsTo(CounselingSession::class, 'session_id');
    }
}