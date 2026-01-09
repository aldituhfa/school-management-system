<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaPerKelasExport implements FromCollection, WithHeadings
{
    protected $kelasId;

    public function __construct($kelasId)
    {
        $this->kelasId = $kelasId;
    }

    public function collection()
    {
        return Siswa::where('kelas_id', $this->kelasId)
            ->select(
                'nisn',
                'nama_siswa',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'agama'
            )
            ->orderBy('nama_siswa')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
        ];
    }
}
