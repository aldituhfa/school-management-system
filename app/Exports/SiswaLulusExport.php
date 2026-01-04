<?php

namespace App\Exports;

use App\Models\SiswaLulus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaLulusExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $query = SiswaLulus::query();

        if ($this->request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $this->request->tahun_lulus);
        }

        if ($this->request->filled('search')) {
            $query->where(function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->request->search . '%');
            });
        }

        return $query
            ->orderBy('tahun_lulus', 'desc')
            ->get([
                'nisn',
                'nama_siswa',
                'jenis_kelamin',
                'agama',
                'status',
                'tahun_lulus'
            ]);
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Jenis Kelamin',
            'Agama',
            'Status',
            'Tahun Lulus'
        ];
    }
}

