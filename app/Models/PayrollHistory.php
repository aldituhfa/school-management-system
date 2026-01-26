<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollHistory extends Model
{
    protected $fillable = [
        'user_id',
        'payroll_period_id',
        'gaji_pokok',
        'status',
        'paid_at',
        'finance_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }
}
