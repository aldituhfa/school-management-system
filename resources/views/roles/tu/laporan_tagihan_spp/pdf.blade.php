<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan SPP</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>
        Laporan Tagihan SPP
        @if(isset($tahunAjaran))
        {{ $tahunAjaran->nama_tahun ?? '' }}
        @endif
    </h2>
    <table>
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Bulan Lunas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswa as $sw)
            <tr>
                <td>{{ $sw->nisn }}</td>
                <td>{{ $sw->nama_siswa }}</td>
                <td>{{ $sw->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ $sw->status->nama_status ?? '-' }}</td>
                <td>{{ $sw->bulan_lunas }} dari {{ $sw->total_bulan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>