<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    protected $table = 'payroll_settings';

    protected $fillable = [
        'user_id',
        'gaji_pokok',
        'status_penggajian',
        'status_aktif'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
