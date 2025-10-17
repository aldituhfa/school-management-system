<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    // Izinkan semua kolom diisi (termasuk kolom baru yang ditambahkan dinamis)
    protected $guarded = [];

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
