<?php

namespace App\Exports;

use App\Models\FinanceLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FinanceLogExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = FinanceLog::with('user')->latest();

        if ($this->request->filled('type')) {
            $query->where('type', $this->request->type);
        }

        if ($this->request->filled('action')) {
            $query->where('action', $this->request->action);
        }

        return view('roles.superadmin.logs-excel', [
            'logs' => $query->get()
        ]);
    }
}
