<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaLulus extends Model
{
    protected $table = 'siswa_lulus';

    protected $fillable = [
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'agama',
        'status',
        'tahun_lulus',
    ];
}
