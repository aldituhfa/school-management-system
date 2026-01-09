<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use DB;

class LaporanSppBulananExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $tahunAjaranId = $this->request->tahun_ajaran_id;
        $bulan = $this->request->bulan;
        $kelasId = $this->request->kelas_id;

        $tahunAjaranNama = \App\Models\TahunAjaran::where('id', $tahunAjaranId)
            ->value('nama_tahun') ?? '-';

        $laporanBulanan = \App\Models\Siswa::query()
            ->leftJoin('kelas', 'kelas.id', '=', 'siswa.kelas_id')

            ->leftJoin('biaya_spps', function ($join) use ($tahunAjaranId) {
                $join->on('biaya_spps.tahun_ajaran_id', DB::raw($tahunAjaranId));
            })

            ->leftJoin('tagihan_spps', function ($join) use ($tahunAjaranId, $bulan) {
                $join->on('siswa.id', '=', 'tagihan_spps.siswa_id')
                    ->where('tagihan_spps.tahun_ajaran_id', $tahunAjaranId)
                    ->where('tagihan_spps.bulan', $bulan);
            })

            ->when(
                $kelasId,
                fn($q) =>
                $q->where('siswa.kelas_id', $kelasId)
            )

            ->select(
                DB::raw("'$bulan' as bulan"),
                'siswa.nama_siswa',
                'kelas.nama_kelas',
                DB::raw('COALESCE(biaya_spps.nominal, 0) as nominal'),
                'tagihan_spps.tanggal_bayar',
                DB::raw("
                CASE 
                    WHEN tagihan_spps.tanggal_bayar IS NOT NULL 
                    THEN 'Lunas' 
                    ELSE 'Menunggak' 
                END as status
            ")
            )
            ->orderBy('siswa.nama_siswa')
            ->get();

        return view('roles.tu.laporan_spp_bulanan.excel', [
            'laporanBulanan' => $laporanBulanan,
            'bulan' => $bulan,
            'tahunAjaranNama' => $tahunAjaranNama,
        ]);
    }
}
