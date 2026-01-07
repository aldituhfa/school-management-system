@extends('layouts.superadmin')

@section('content')
<div class="page-body">
  <div class="page-header d-print-none mb-4">
    <h2 class="page-title">Detail Data Siswa</h2>

    <div class="mt-2">
      <a href="{{ route('superadmin.data_spp.index') }}"
        class="btn btn-sm btn-secondary">
        ← Kembali
      </a>
    </div>
  </div>

  @if($isNonaktif)
  <div class="alert alert-warning alert-dismissible fade show">
    <strong>Perhatian!</strong>
    Tahun ajaran <b>{{ $tahunAjaran->nama_tahun }}</b> sudah <b>Nonaktif</b>.
    Pastikan tidak melakukan transaksi baru pada tahun ajaran ini.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif
  
  {{-- Alert --}}
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Detail Siswa --}}
  <div class="row">
    {{-- Kolom Kiri: Detail Siswa --}}
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-body">
          <table class="table table-bordered">
            <tbody>
              @foreach($siswa->getAttributes() as $key => $value)
              @if(!in_array($key, ['id','kelas_id','status_id','created_at','updated_at']))
              <tr>
                <th>{{ ucfirst(str_replace('_',' ',$key)) }}</th>
                <td>
                  @if($key === 'tanggal_lahir' && $value)
                  {{ \Carbon\Carbon::parse($value)->translatedFormat('d M Y') }}
                  @else
                  {{ $value ?? '-' }}
                  @endif
                </td>
              </tr>
              @endif
              @endforeach
              <tr>
                <th>Kelas</th>
                <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
              </tr>
              <tr>
                <th>Status</th>
                <td>{{ $siswa->status->nama_status ?? '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Kolom Kanan: Statistik SPP --}}
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-light text-dark d-flex align-items-center justify-content-between">
          <h5 class="mb-0 fw-semibold">Statistik SPP</h5>
          <i class="ti ti-chart-pie text-primary"></i>
        </div>

        <div class="card-body">
          {{-- Bagian atas: 2 card kecil untuk status bulan --}}
          <div class="row g-2 mb-3">
            <div class="col-6">
              <div class="card text-center bg-success-lt border-success">
                <div class="card-body p-2">
                  <div class="text-success fw-bold">Bulan Lunas</div>
                  <div class="fs-3 fw-bold">{{ $bulanLunas }}</div>
                  <small>Bulan</small>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card text-center bg-danger-lt border-danger">
                <div class="card-body p-2">
                  <div class="text-danger fw-bold">Belum Bayar</div>
                  <div class="fs-3 fw-bold">{{ $bulanBelum }}</div>
                  <small>Bulan</small>
                </div>
              </div>
            </div>
          </div>

          <hr class="my-2">

          {{-- Informasi total --}}
          <div class="mb-2">
            <div class="d-flex justify-content-between">
              <span class="fw-semibold">Total Tagihan</span>
              <span>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between text-success">
              <span class="fw-semibold">Total Dibayar</span>
              <span>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between text-danger">
              <span class="fw-semibold">Sisa Tagihan</span>
              <span>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
            </div>
          </div>

          <hr class="my-2">

          {{-- Info terakhir bayar --}}
          <div>
            <p class="mb-1 fw-semibold">Terakhir Bayar:</p>
            <p class="text-muted mb-0">
              {{ $terakhirBayar ? \Carbon\Carbon::parse($terakhirBayar)->translatedFormat('d M Y') : '-' }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Table Tagihan SPP --}}
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Tagihan SPP - {{ $tahunAjaran->nama_tahun }}</h3>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>Bulan</th>
            <th>Nominal</th>
            <th>Tanggal Bayar</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($tagihan as $t)
          <tr>
            <td>{{ $t->bulan }}</td>
            <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
            <td>{{ $t->tanggal_bayar ? \Carbon\Carbon::parse($t->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
            <td>
              <span class="badge {{ $t->status == 'lunas' ? 'bg-success-lt text-success' : 'bg-warning-lt text-warning' }}">
                {{ ucfirst($t->status) }}
              </span>
            </td>
            <td style="white-space: pre-line;">{!! nl2br(e($t->keterangan)) ?? '-' !!}</td>
            <td>
              @if($t->status == 'belum lunas')
              <form method="POST" action="{{ route('superadmin.data_spp.bayar', $t->id) }}" onsubmit="return confirm('Yakin ingin menandai bulan {{ $t->bulan }} sebagai lunas?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">Bayar</button>
              </form>
              @else
              <div class="d-flex align-items-center gap-2">
                <span class="text-success">✓ Lunas</span>

                {{-- Tombol titik tiga --}}
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $t->id }}">
                        <i class="ti ti-edit me-1"></i> Edit
                      </button>
                    </li>
                    <li>
                      <form method="POST" action="{{ route('superadmin.data_spp.cancel', $t->id) }}" onsubmit="return confirm('Batalkan pembayaran bulan {{ $t->bulan }}?')">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                          <i class="ti ti-x me-1"></i> Cancel
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </div>
              @endif
            </td>
            {{-- ================= MODAL EDIT ================= --}}
            <div class="modal fade" id="editModal{{ $t->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $t->id }}" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form method="POST" action="{{ route('superadmin.data_spp.update', $t->id) }}">
                    @csrf
                    <div class="modal-header bg-light">
                      <h5 class="modal-title" id="editModalLabel{{ $t->id }}">Edit Tagihan - {{ $t->bulan }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                      <div class="mb-2">
                        <label class="form-label">Bulan</label>
                        <input type="text" class="form-control" value="{{ $t->bulan }}" readonly>
                      </div>

                      <div class="mb-2">
                        <label class="form-label">Nominal</label>
                        <input type="text" class="form-control" value="Rp {{ number_format($t->nominal, 0, ',', '.') }}" readonly>
                      </div>

                      <div class="mb-2">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst($t->status) }}" readonly>
                      </div>

                      <div class="mb-2">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" class="form-control" value="{{ $t->tanggal_bayar ? \Carbon\Carbon::parse($t->tanggal_bayar)->format('Y-m-d') : '' }}">
                      </div>

                      <div class="mb-2">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan_tambahan" rows="3" placeholder="Tambahkan keterangan tambahan..."></textarea>
                        <small class="text-muted d-block mt-1">
                          {{ $t->tanggal_bayar ? 'Dibayar pada ' . \Carbon\Carbon::parse($t->tanggal_bayar)->translatedFormat('d F Y') : '-' }}
                        </small>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection