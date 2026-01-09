<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>

<body>

    <h3 align="center">Laporan SPP Bulanan</h3>
    <p>
        Tahun Ajaran: {{ $tahunAjaranNama }} <br>
        Bulan: {{ $bulan }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Nominal</th>
                <th>Tgl Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanBulanan as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->bulan }}</td>
                <td>{{ $row->nama_siswa }}</td>
                <td>{{ $row->nama_kelas ?? '-' }}</td>
                <td>Rp {{ number_format($row->nominal,0,',','.') }}</td>
                <td>
                    {{ $row->status == 'Lunas'
            ? \Carbon\Carbon::parse($row->tanggal_bayar)->format('d/m/Y')
            : '-' }}
                </td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>