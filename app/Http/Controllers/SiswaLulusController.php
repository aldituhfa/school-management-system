<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SiswaLulus;
use App\Exports\SiswaLulusExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SiswaLulusController extends Controller
{
    public function index(Request $request)
    {
        // dropdown tahun
        $tahunList = SiswaLulus::select('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus');

        // base query
        $baseQuery = SiswaLulus::query();

        if ($request->filled('tahun_lulus')) {
            $baseQuery->where('tahun_lulus', $request->tahun_lulus);
        }

        if ($request->filled('search')) {
            $baseQuery->where(function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        // ambil semua data
        $all = $baseQuery
            ->orderBy('tahun_lulus', 'desc')
            ->orderBy('nama_siswa', 'asc')
            ->get()
            ->groupBy('tahun_lulus');

        $siswa = [];

        foreach ($all as $tahun => $items) {

            $pageKey = 'page_' . $tahun; // unik per tahun
            $currentPage = LengthAwarePaginator::resolveCurrentPage($pageKey);
            $perPage = 10;

            $currentItems = $items
                ->slice(($currentPage - 1) * $perPage, $perPage)
                ->values();

            $siswa[$tahun] = new LengthAwarePaginator(
                $currentItems,
                $items->count(),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => $pageKey,
                    'query' => request()->query(),
                ]
            );
        }

        return view(
            'roles.superadmin.siswa_lulus.index',
            compact('siswa', 'tahunList')
        );
    }


    public function store(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'tahun_lulus' => 'required|digits:4',
        ]);

        $siswaList = Siswa::whereIn('id', $request->siswa_ids)->get();

        foreach ($siswaList as $s) {
            SiswaLulus::create([
                'nisn' => $s->nisn,
                'nama_siswa' => $s->nama_siswa,
                'jenis_kelamin' => $s->jenis_kelamin,
                'agama' => $s->agama,
                'status' => 'Lulus',
                'tahun_lulus' => $request->tahun_lulus,
            ]);

            // hapus dari data siswa aktif
            $s->delete();
        }

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dipindahkan ke Siswa Lulus');
    }

    public function destroy($id)
    {
        $siswa = SiswaLulus::findOrFail($id);
        $siswa->delete();

        return back()->with('success', 'Data siswa lulus berhasil dihapus');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new SiswaLulusExport($request),
            'siswa-lulus.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = SiswaLulus::query();

        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->tahun_lulus);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        $siswa = $query
            ->orderBy('tahun_lulus', 'desc')
            ->get()
            ->groupBy('tahun_lulus');

        $pdf = Pdf::loadView(
            'roles.superadmin.siswa_lulus.export_pdf',
            compact('siswa')
        )->setPaper('a4', 'portrait');

        return $pdf->download('siswa-lulus.pdf');
    }


    public function destroyByYear($tahun)
    {
        SiswaLulus::where('tahun_lulus', $tahun)->delete();

        return back()->with(
            'success',
            "Semua siswa lulus tahun $tahun berhasil dihapus"
        );
    }
}
