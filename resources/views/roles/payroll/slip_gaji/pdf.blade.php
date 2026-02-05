<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-weight: 600;
        }

        .header small {
            color: #777;
        }

        .box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }

        .row {
            margin-bottom: 8px;
        }

        .label {
            color: #666;
            font-size: 11px;
        }

        .value {
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background: #f5f5f5;
            font-weight: 500;
            text-align: left;
        }

        .total {
            font-size: 14px;
            font-weight: 600;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Slip Gaji</h2>
        <small>
            Periode {{ $slip->period->bulan }} {{ $slip->period->tahun }}
        </small>
    </div>

    <div class="box">
        <div class="row">
            <div class="label">Nama Pegawai</div>
            <div class="value">{{ $slip->user->name }}</div>
        </div>

        <div class="row">
            <div class="label">Role</div>
            <div class="value">{{ ucfirst($slip->user->role) }}</div>
        </div>

        <div class="row">
            <div class="label">Tanggal Dibayar</div>
            <div class="value">
                {{ $slip->paid_at ? $slip->paid_at->format('d M Y') : '-' }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th align="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td align="right">
                    Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}
                </td>
            </tr>

            {{-- kalau nanti ada tunjangan / potongan tinggal nambah di sini --}}
        </tbody>
        <tfoot>
            <tr>
                <th class="total">Gaji Bersih</th>
                <th class="total" align="right">
                    Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Slip gaji ini dihasilkan secara otomatis oleh sistem.<br>
        Tidak memerlukan tanda tangan basah.
    </div>

</body>
</html>
