<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusSiswa extends Model
{
    protected $table = 'status_siswa';
    protected $fillable = ['nama_status'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
