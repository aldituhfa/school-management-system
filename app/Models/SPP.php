<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPP extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name','student_identifier','month','year','amount','status','tu_id','paid_at'
    ];

    public function tu()
    {
        return $this->belongsTo(User::class, 'tu_id');
    }
}
