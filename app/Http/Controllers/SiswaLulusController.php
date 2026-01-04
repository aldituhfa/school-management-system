<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SiswaLulus;
use App\Exports\SiswaLulusExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SiswaLulusController extends Controller
{
    public function index(Request $request)
    {
        // ambil semua tahun lulus unik (untuk dropdown filter)
        $tahunList = SiswaLulus::select('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus');

        // query utama
        $query = SiswaLulus::query();

        // filter tahun lulus
        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->tahun_lulus);
        }

        // search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        // ambil data + group per tahun
        $siswa = $query
            ->orderBy('tahun_lulus', 'desc')
            ->orderBy('nama_siswa', 'asc')
            ->get()
            ->groupBy('tahun_lulus');

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
}
