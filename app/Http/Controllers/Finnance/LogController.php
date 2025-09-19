<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceLog;

class LogController extends Controller
{
    public function index()
    {
        $logs = FinanceLog::with('user','finance')->latest()->get();
        return response()->json($logs);
    }
}
