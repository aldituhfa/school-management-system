<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Gaji</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }
    </style>
</head>

<body>

    <h2>Laporan Gaji Pegawai</h2>
    <p>
        Periode:
        <strong>{{ $period->bulan }}/{{ $period->tahun }}</strong>
    </p>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Role</th>
                <th>Gaji Pokok</th>
                <th>Status</th>
                <th>Tanggal Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $item)
            <tr>
                <td>{{ $item->user->name }}</td>
                <td>{{ ucfirst($item->user->role) }}</td>
                <td>
                    Rp {{ number_format($item->gaji_pokok,0,',','.') }}
                </td>
                <td>{{ strtoupper($item->status) }}</td>
                <td>
                    {{ $item->paid_at
                        ? $item->paid_at->format('d/m/Y')
                        : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>