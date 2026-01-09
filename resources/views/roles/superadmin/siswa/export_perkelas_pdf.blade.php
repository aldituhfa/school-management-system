<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa Kelas {{ $kelas->nama_kelas }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        h4 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 15px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #333;
            padding: 5px;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>LAPORAN DATA SISWA</h2>
    <h4>Kelas: {{ $kelas->nama_kelas }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Agama</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->nisn }}</td>
                <td>{{ $item->nama_siswa }}</td>
                <td class="text-center">{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->tempat_lahir }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}
                </td>
                <td class="text-center">{{ $item->agama }}</td>
                <td class="text-center">{{ $item->status->nama_status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">
                    Tidak ada data siswa
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
