<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tingkat extends Model
{
    use HasFactory;

    protected $fillable = ['nama_tingkat'];

    public function biayaSpps()
    {
        return $this->hasMany(BiayaSpp::class);
    }
}
