<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanSpp extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'bulan',
        'nominal',
        'tanggal_bayar',
        'status',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
