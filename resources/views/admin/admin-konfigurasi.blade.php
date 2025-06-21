@extends('layouts.dashboard-layout')

@section('title', 'Konfigurasi Umum')

@section('content')
    <div class="container py-4">
        <h2>Konfigurasi Umum</h2>
        <p>Atur parameter operasional dan tinjau log aktivitas sistem.</p>

        {{-- AREA UNTUK MENAMPILKAN PESAN SUKSES ATAU ERROR UMUM --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Ada Kesalahan Validasi!</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Pengaturan Umum -->
        <div class="card shadow-sm rounded-lg mb-5">
            <div class="card-header bg-light">
                <h5 class="mb-0">Pengaturan Sistem</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.konfigurasi.update') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jam_operasional_buka" class="form-label">Jam Operasional Buka</label>
                            <input type="time" name="jam_operasional_buka" id="jam_operasional_buka"
                                class="form-control @error('jam_operasional_buka') is-invalid @enderror"
                                value="{{ old('jam_operasional_buka', $jamBuka) }}" required>
                            @error('jam_operasional_buka')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jam_operasional_tutup" class="form-label">Jam Operasional Tutup</label>
                            <input type="time" name="jam_operasional_tutup" id="jam_operasional_tutup"
                                class="form-control @error('jam_operasional_tutup') is-invalid @enderror"
                                value="{{ old('jam_operasional_tutup', $jamTutup) }}" required>
                            @error('jam_operasional_tutup')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="batas_kuota_antrian" class="form-label">Batas Kuota Antrian Harian</label>
                        <input type="number" name="batas_kuota_antrian" id="batas_kuota_antrian"
                            class="form-control @error('batas_kuota_antrian') is-invalid @enderror"
                            value="{{ old('batas_kuota_antrian', $batasKuotaAntrian) }}" min="1" required>
                        @error('batas_kuota_antrian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Konfigurasi</button>
                </form>
            </div>
        </div>

        <!-- Log Aktivitas Pengguna -->
        <div class="card shadow-sm rounded-lg">
            <div class="card-header bg-light">
                <h5 class="mb-0">Log Aktivitas Pengguna Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pengguna</th>
                                <th>Aktivitas</th>
                                <th>Deskripsi</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logAktivitas as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                    <td>{{ $log->user ? $log->user->name . ' (' . $log->user->role . ')' : 'Sistem/Guest' }}
                                    </td>
                                    <td>{{ $log->aktivitas }}</td>
                                    <td>{{ $log->deskripsi }}</td>
                                    <td>{{ $log->ip_address ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada log aktivitas terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection