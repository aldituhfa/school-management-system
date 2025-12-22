<table>
    <thead>
        <tr>
            <th>No</th>
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
            <td>{{ $log->action }}</td>
            <td>{{ $log->before_amount }}</td>
            <td>{{ $log->after_amount }}</td>
            <td>{{ $log->type }}</td>
            <td>{{ $log->meta }}</td>
            <td>{{ $log->user->name ?? '-' }}</td>
            <td>{{ $log->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
