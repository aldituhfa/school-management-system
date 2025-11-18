<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal_pelaksanaan',
        'target_dana',
        'nominal_per_siswa',
        'kelas_ids',
        'status',
        'terkumpul',
    ];

    protected $casts = [
        'kelas_ids' => 'array',
    ];

    // Jika butuh relasi kelas (kumpulan kelas)
    public function kelasCollection()
    {
        return $this->hasManyThrough(
            \App\Models\Kelas::class,
            self::class,
            'id',
            'id'
        );
    }

    // Accessor progress otomatis (0-100%)
    public function getProgressAttribute()
    {
        if ($this->target_dana > 0) {
            $p = ($this->terkumpul / $this->target_dana) * 100;
            return $p > 100 ? 100 : $p;
        }
        return 0;
    }

    // relation to pembayaran
    public function pembayarans()
    {
        return $this->hasMany(\App\Models\KegiatanPembayaran::class, 'kegiatan_id');
    }
}
