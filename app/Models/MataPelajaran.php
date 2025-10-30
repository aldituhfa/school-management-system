<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $fillable = ['nama_mata_pelajaran'];

    public function guru()
{
    return $this->belongsToMany(User::class, 'guru_mata_pelajaran', 'mata_pelajaran_id', 'guru_id');
}

}


