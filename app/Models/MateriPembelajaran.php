<?php

// app/Models/MateriPembelajaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MateriPembelajaran extends Model
{
    protected $table = 'materi_pembelajaran';
    
    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'judul',
        'deskripsi',
        'tipe_materi',
        'file_path',
        'file_name',
        'file_size'
    ];

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Accessor untuk URL download
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    // Accessor untuk ukuran file dalam format readable
    public function getFileSizeFormattedAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
}