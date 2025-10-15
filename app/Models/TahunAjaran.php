<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = ['nama_tahun'];

    public function biayaSpps()
    {
        return $this->hasMany(BiayaSpp::class);
    }
}
