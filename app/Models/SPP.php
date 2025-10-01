<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPP extends Model
{
    use HasFactory;

    protected $table = 'spps'; // 👈 kasih tahu Laravel tabelnya

    protected $fillable = [
        'student_name',
        'student_identifier',
        'month',
        'year',
        'amount',
        'status',
        'tu_id',
        'paid_at',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function tu()
    {
        return $this->belongsTo(User::class, 'tu_id');
    }
}
