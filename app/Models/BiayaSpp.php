<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaSpp extends Model
{
    use HasFactory;

    protected $fillable = ['tahun_ajaran_id', 'tingkat_id', 'status_id', 'nominal'];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
