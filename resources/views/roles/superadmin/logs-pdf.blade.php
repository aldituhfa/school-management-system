<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h3 align="center">Log Keuangan</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Aksi</th>
            <th>Sebelum</th>
            <th>Sesudah</th>
            <th>Jenis</th>
            <th>Kategori</th>
            <th>User</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $i => $log)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ strtoupper($log->action) }}</td>
            <td>Rp {{ number_format($log->before_amount,0,',','.') }}</td>
            <td>Rp {{ number_format($log->after_amount,0,',','.') }}</td>
            <td>{{ $log->type }}</td>
            <td>{{ $log->meta }}</td>
            <td>{{ $log->user->name ?? '-' }}</td>
            <td>{{ $log->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
