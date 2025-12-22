<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceLog;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinanceLogExport;


class LogController extends Controller
{

    public function index(Request $request)
    {
        // Ambil query utama tanpa join berlebihan
        $query = FinanceLog::select('finance_logs.*')->with('user')->latest();

        // Filter berdasarkan type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan user_id
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Pagination aman
        $logs = $query->paginate(10);

        // Ambil semua user untuk dropdown filter
        $users = User::all();

        return view('roles.superadmin.logs', compact('logs', 'users'));
    }

    public function destroy($id)
    {
        $log = FinanceLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Log keuangan berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = FinanceLog::with('user')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->get();

        $pdf = Pdf::loadView('roles.superadmin.logs-pdf', compact('logs'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('log-keuangan.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new FinanceLogExport($request),
            'log-keuangan.xlsx'
        );
    }

    // public function index(Request $request)
    // {
    //     $query = FinanceLog::with('user')->latest();

    //     if ($request->filled('type')) {
    //         $query->where('type', $request->type);
    //     }

    //     if ($request->filled('action')) {
    //         $query->where('action', $request->action);
    //     }

    //     if ($request->filled('user_id')) {
    //         $query->where('user_id', $request->user_id);
    //     }

    //     $logs = $query->paginate(10);
    //     $users = User::all();

    //     return view('roles.superadmin.logs', compact('logs', 'users'));
    // }
}
// $logs = FinanceLog::with('user','finance')->latest()->get();
// return response()->json($logs);
