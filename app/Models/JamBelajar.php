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
        'waktu_istirahat_mulai',
        'waktu_istirahat_selesai',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
