<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','finance_id','action','before_amount','after_amount','type','meta'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }
}
