<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name','admin_id','amount','status','pay_date'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
