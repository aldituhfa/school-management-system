<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = [
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'kelas_id',
        'agama',
        'status_id'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusSiswa::class);
    }
}
