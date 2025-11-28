<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamBelajar extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'total_jam_belajar',
        'jam_mulai',
        'jam_selesai',

        // Istirahat 1
        'waktu_istirahat_mulai',
        'waktu_istirahat_selesai',

        // Istirahat 2 (tambahan)
        'waktu_istirahat2_mulai',
        'waktu_istirahat2_selesai',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
