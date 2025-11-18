<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPembayaran extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_pembayaran';

    protected $fillable = [
        'kegiatan_id',
        'siswa_id',
        'kelas_id',
        'jumlah',
        'status',
        'tanggal_bayar',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function siswa()
    {
        return $this->belongsTo(\App\Models\Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(\App\Models\Kelas::class, 'kelas_id');
    }
}
