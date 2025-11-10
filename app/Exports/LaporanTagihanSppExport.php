<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\TagihanSpp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanTagihanSppExport implements FromCollection, WithMapping, WithHeadings
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $tahunAjaranId = $this->filters['tahun_ajaran_id'] ?? null;
        $kelasId = $this->filters['kelas_id'] ?? null;
        $search = $this->filters['search'] ?? null;

        $query = Siswa::with(['kelas', 'status'])
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($search, fn($q) => $q->where('nama_siswa', 'like', "%$search%")->orWhere('nisn', 'like', "%$search%"))
            ->orderBy('nama_siswa')
            ->get();

        foreach ($query as $sw) {
            $totalTagihan = TagihanSpp::where('siswa_id', $sw->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->count();
            $bulanLunas = TagihanSpp::where('siswa_id', $sw->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'lunas')
                ->count();
            $sw->bulan_lunas = $bulanLunas;
            $sw->total_bulan = $totalTagihan ?: 12;
        }

        return $query;
    }

    public function map($siswa): array
    {
        return [
            $siswa->nisn,
            $siswa->nama_siswa,
            $siswa->kelas->nama_kelas ?? '-',
            $siswa->status->nama_status ?? '-',
            "{$siswa->bulan_lunas} dari {$siswa->total_bulan}",
        ];
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Kelas',
            'Status',
            'Bulan Lunas',
        ];
    }
}
