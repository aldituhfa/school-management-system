<h2>Slip Gaji</h2>
<p>Nama: {{ $slip->user->name }}</p>
<p>Periode: {{ $slip->period->bulan }}/{{ $slip->period->tahun }}</p>
<hr>
<p>Gaji Pokok: Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}</p>