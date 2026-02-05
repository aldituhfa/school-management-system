<?php

namespace App\Exports;

use App\Models\PayrollHistory;
use App\Models\PayrollPeriod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollReportExport implements FromCollection, WithHeadings
{
    protected $period;

    public function __construct(PayrollPeriod $period)
    {
        $this->period = $period;
    }

    public function collection()
    {
        return PayrollHistory::with('user')
            ->where('payroll_period_id', $this->period->id)
            ->get()
            ->map(function ($item) {
                return [
                    'Nama' => $item->user->name,
                    'Role' => ucfirst($item->user->role),
                    'Gaji Pokok' => $item->gaji_pokok,
                    'Status' => strtoupper($item->status),
                    'Tanggal Dibayar' => $item->paid_at
                        ? $item->paid_at->format('d/m/Y')
                        : '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Role',
            'Gaji Pokok',
            'Status',
            'Tanggal Dibayar',
        ];
    }
}
