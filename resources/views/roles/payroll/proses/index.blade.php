@extends('layouts.payroll')

@section('content')
<div class="container-xl">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-check-circle me-2 fs-4"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error-circle me-2 fs-4"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-2">Proses Penggajian</h2>
                    <div class="text-muted">
                        Periode:
                        <strong>{{ DateTime::createFromFormat('!m',$period->bulan)->format('F') }} {{ $period->tahun }}</strong>
                        • Total: {{ $users->count() }} pegawai
                        • Dibayar:
                        <strong class="text-success">
                            {{ $users->filter(function($user) {
                                $history = $user->payrollHistories->first();
                                return $history && $history->status === 'paid';
                            })->count() }}
                        </strong>
                    </div>
                </div>

                {{-- TUTUP PERIODE --}}
                <form method="POST"
                    action="{{ route('payroll.proses.close',$period->id) }}"
                    onsubmit="return confirm('Tutup periode penggajian?')">
                    @csrf
                    <button class="btn btn-outline-danger px-4">
                        Tutup Periode
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL SUMBER DANA --}}
    <div class="modal fade" id="sourceModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Sumber Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <div class="fw-medium mb-1" id="modalUserName"></div>
                        <div class="text-success fw-bold mb-3" id="modalSalary"></div>
                        <div class="text-muted small mb-2">Pilih sumber dana:</div>
                    </div>

                    <form id="paymentForm" method="POST">
                        @csrf
                        <input type="hidden" id="modalPeriodId">
                        <input type="hidden" id="modalUserId">

                        <div class="mb-4">
                            <div class="border rounded p-3 mb-2 cursor-pointer"
                                onclick="selectSource('kas')">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="source" value="kas">
                                    <label class="form-check-label">
                                        <div class="fw-medium">Kas</div>
                                        <div class="text-muted small">Pembayaran dari kas perusahaan</div>
                                    </label>
                                </div>
                            </div>

                            <div class="border rounded p-3 cursor-pointer"
                                onclick="selectSource('dana_bos')">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="source" value="dana_bos">
                                    <label class="form-check-label">
                                        <div class="fw-medium">Dana BOS</div>
                                        <div class="text-muted small">Pembayaran dari dana BOS</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100" disabled id="submitPaymentBtn">
                            Konfirmasi Bayar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-bottom py-3 px-4">Nama</th>
                            <th class="border-bottom py-3 px-4">Role</th>
                            <th class="border-bottom py-3 px-4">Gaji Pokok</th>
                            <th class="border-bottom py-3 px-4">Status</th>
                            <th class="border-bottom py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        @php
                        $history = $u->payrollHistories->first();
                        $paid = $history && $history->status === 'paid';
                        @endphp

                        <tr style="border-bottom: 1px solid #e9ecef;">
                            <td class="py-3 px-4">
                                <div class="fw-medium">{{ $u->name }}</div>
                                <div class="text-muted small">{{ $u->email }}</div>
                            </td>

                            <td class="py-3 px-4">
                                <span class="badge" style="background-color: #e0f2fe; color: #0369a1;">
                                    {{ strtoupper($u->role) }}
                                </span>
                            </td>

                            <td class="py-3 px-4">
                                <div class="fw-bold">
                                    Rp {{ number_format($u->payrollSetting->gaji_pokok,0,',','.') }}
                                </div>
                            </td>

                            <td class="py-3 px-4">
                                @if($paid)
                                <span class="badge" style="background-color: #d1fae5; color: #065f46;">
                                    Dibayar
                                </span>
                                <div class="text-muted small mt-1">
                                    {{ $history->created_at->format('d M Y H:i') }}
                                </div>
                                @else
                                <span class="badge" style="background-color: #fef3c7; color: #92400e;">
                                    Belum Dibayar
                                </span>
                                @endif
                            </td>

                            <td class="py-3 px-4 text-center">
                                @if(!$paid)
                                <button class="btn btn-success btn-sm pay-btn"
                                    data-action="{{ route('payroll.proses.pay', ['period' => $period->id, 'user' => $u->id]) }}"
                                    data-user-name="{{ $u->name }}"
                                    data-salary="{{ number_format($u->payrollSetting->gaji_pokok,0,',','.') }}">
                                    Bayar
                                </button>
                                @else
                                <form method="POST"
                                    action="{{ route('payroll.proses.cancel', [$period->id, $u->id]) }}"
                                    onsubmit="return confirm('Batalkan pembayaran untuk {{ $u->name }}?')"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">
                                        Cancel
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        @if($users->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">Tidak ada pegawai</div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                bootstrap.Alert.getInstance(alert)?.close();
            });
        }, 5000);

        // Modal
        const sourceModal = new bootstrap.Modal('#sourceModal');
        const paymentForm = document.getElementById('paymentForm');

        // Bayar button
        document.querySelectorAll('.pay-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const periodId = this.dataset.periodId;
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                const salary = this.dataset.salary;

                document.getElementById('modalPeriodId').value = periodId;
                document.getElementById('modalUserId').value = userId;
                document.getElementById('modalUserName').textContent = userName;
                document.getElementById('modalSalary').textContent = `Rp ${salary}`;

                paymentForm.action = this.dataset.action;

                // Reset
                paymentForm.reset();
                document.getElementById('submitPaymentBtn').disabled = true;

                sourceModal.show();
            });
        });

        // Select source
        window.selectSource = function(source) {
            const radio = document.querySelector(`input[value="${source}"]`);
            if (radio) {
                radio.checked = true;
                document.getElementById('submitPaymentBtn').disabled = false;
            }
        };

        // Form submission
        paymentForm.addEventListener('submit', function() {
            document.getElementById('submitPaymentBtn').disabled = true;
            document.getElementById('submitPaymentBtn').innerHTML = 'Memproses...';
        });
    });
</script>

<style>
    .table th,
    .table td {
        border-color: #e9ecef;
    }

    tr:hover {
        background-color: #f8f9fa;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .cursor-pointer:hover {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
</style>
@endsection