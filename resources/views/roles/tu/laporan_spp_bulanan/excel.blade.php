<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Bulan</th>
      <th>Nama Siswa</th>
      <th>Kelas</th>
      <th>Nominal</th>
      <th>Tanggal Bayar</th>
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
      <td>{{ $row->nominal }}</td>
      <td>{{ $row->tanggal_bayar }}</td>
      <td>{{ $row->status }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
