@extends('layouts.superadmin')

@section('content')
<div class="page-body">
    <div class="container-xl mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 19a9 9 0 0 1 9 -9h9" />
                        <path d="M3 19v-14a9 9 0 0 1 9 -9h9v18h-9a9 9 0 0 0 -9 9z" />
                    </svg>
                    Pilih Mata Pelajaran untuk <span class="fw-bold">{{ $guru->name }}</span>
                </h3>
                <a href="{{ route('roles.superadmin.pilihmapel.index') }}" class="btn btn-light btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 icon-tabler icon-tabler-arrow-left" width="20"
                        height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <line x1="5" y1="12" x2="11" y2="18" />
                        <line x1="5" y1="12" x2="11" y2="6" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="card-body">
                {{-- Success alert --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="20" height="20"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v4" />
                                    <path d="M12 17h.01" />
                                    <path d="M4 19h16l-8 -14z" />
                                </svg>
                            </div>
                            <div>{{ session('success') }}</div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <form action="{{ route('roles.superadmin.pilihmapel.update', $guru->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="mata_pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>
                        <select name="mata_pelajaran[]" id="mata_pelajaran"
                            class="form-select" multiple size="8">
                            @foreach($mataPelajaran as $mp)
                                <option value="{{ $mp->id }}" 
                                    {{ $guru->mataPelajaran->contains($mp->id) ? 'selected' : '' }}>
                                    {{ $mp->nama_mata_pelajaran }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-hint">
                            Tekan <strong>CTRL</strong> (atau <strong>CMD</strong> di Mac) untuk memilih lebih dari satu mata pelajaran.
                        </small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.superadmin.pilihmapel.index') }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 icon-tabler icon-tabler-x" width="20"
                                height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 icon-tabler icon-tabler-check" width="20"
                                height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection