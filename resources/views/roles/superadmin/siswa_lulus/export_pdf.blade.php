<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Data Siswa Lulus</title>
  <style>
    body { font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 5px; }
    th { background: #eee; }
  </style>
</head>
<body>

<h3>Data Siswa Lulus</h3>

@foreach($siswa as $tahun => $list)
  <h4>Tahun Lulus {{ $tahun }}</h4>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>NISN</th>
        <th>Nama</th>
        <th>JK</th>
        <th>Agama</th>
      </tr>
    </thead>
    <tbody>
      @foreach($list as $i => $s)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $s->nisn }}</td>
          <td>{{ $s->nama_siswa }}</td>
          <td>{{ $s->jenis_kelamin }}</td>
          <td>{{ $s->agama }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endforeach

</body>
</html>
