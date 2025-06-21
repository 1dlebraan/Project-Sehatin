@extends('layouts.dashboard-layout')

@section('title', 'Monitor Efisiensi Layanan')

@section('content')
  <div class="container py-4">
    <h2>Monitor Efisiensi Layanan</h2>
    <p>Data efisiensi berdasarkan antrean yang telah diselesaikan atau dibatalkan untuk poli Anda.</p>

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

    <!-- Form Filter Tanggal (Tambahan untuk monitor efisiensi) -->
    <div class="card shadow-sm rounded-lg mb-4 p-3">
    <form action="{{ route('petugas.monitor_efisiensi') }}" method="GET" class="row g-3 align-items-end">
      <div class="col-md-4">
      <label for="tanggal" class="form-label">Tanggal:</label>
      <input type="date" name="tanggal" id="tanggal" class="form-control"
        value="{{ request('tanggal', \Carbon\Carbon::today()->format('Y-m-d')) }}">
      </div>
      <div class="col-md-4">
      <button type="submit" class="btn btn-primary w-100">Filter Laporan</button>
      </div>
    </form>
    </div>
    <!-- Akhir Form Filter Tanggal -->

    <!-- Ringkasan Efisiensi -->
    <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <div class="card shadow-sm rounded-lg p-3">
      <h5 class="card-title text-primary">Rata-rata Waktu Tunggu</h5>
      {{-- ✅ Perbaikan: Tampilkan langsung string rata-rata --}}
      <p class="card-text fs-1 fw-bold">{{ $rataRataWaktuTungguFormatted }}</p>
      <p class="text-muted">Untuk antrean yang dipanggil/selesai di poli Anda hari ini</p>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="card shadow-sm rounded-lg p-3">
      <h5 class="card-title text-success">Rata-rata Lama Layanan</h5>
      {{-- ✅ Perbaikan: Tampilkan langsung string rata-rata --}}
      <p class="card-text fs-1 fw-bold">{{ $rataRataLamaLayananFormatted }}</p>
      <p class="text-muted">Untuk antrean yang selesai di poli Anda hari ini</p>
      </div>
    </div>
    </div>

    <!-- Tabel Statistik Ringkasan -->
    <div class="row mb-4">
    <div class="col-md-12">
      <div class="card shadow-sm rounded-lg">
      <div class="card-header bg-light">
        <h5 class="mb-0">Ringkasan Antrean (Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }})</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
        <div class="col-6 col-md-3 mb-2">
          <p class="mb-0 text-muted">Total Antrean</p>
          <h4 class="fw-bold text-dark">{{ $totalAntrian }}</h4>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <p class="mb-0 text-muted">Selesai</p>
          <h4 class="fw-bold text-success">{{ $antrianSelesai }}</h4>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <p class="mb-0 text-muted">Menunggu</p>
          <h4 class="fw-bold text-warning">{{ $antrianMenunggu }}</h4>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <p class="mb-0 text-muted">Dipanggil</p>
          <h4 class="fw-bold text-info">{{ $antrianDipanggil }}</h4>
        </div>
        <div class="col-6 col-md-3 mb-2">
          <p class="mb-0 text-muted">Batal</p>
          <h4 class="fw-bold text-danger">{{ $antrianBatal }}</h4>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>


    <!-- Tabel Detail Antrean yang Selesai/Batal -->
    <div class="table-responsive">
    <table class="table table-bordered text-center align-middle bg-white">
      <thead class="table-light">
      <tr>
        <th>No. Antrean</th>
        <th>Tanggal</th>
        <th>Poli</th>
        <th>NIK Pasien</th> {{-- ✅ Tampilkan NIK --}}
        <th>Nama Pasien</th>
        <th>TTL</th> {{-- ✅ Tampilkan TTL --}}
        <th>Jenis Kelamin</th> {{-- ✅ Tampilkan Jenis Kelamin --}}
        <th>Waktu Datang</th>
        <th>laynggil</th>
        <th>Waktu Selesai</th>
        <th>Waktu Tunggu (min)</th>
        <th>Lama Layanan (min)</th>
        <th>Status</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($efisiensiData as $antrian)
      <tr>
        <td>{{ $antrian->nomor_antrian }}</td>
        <td>{{ \Carbon\Carbon::parse($antrian->tanggal_antrian)->format('d M Y') }}</td>
        <td>{{ $antrian->poli->nama_poli ?? 'N/A' }}</td>
        {{-- ✅ Akses langsung dari $antrian->pasien --}}
        <td>{{ $antrian->pasien->nik ?? 'N/A' }}</td>
        <td>{{ $antrian->pasien->name ?? 'Pasien Tidak Ditemukan' }}</td>
        <td>
        @php
        $tempatLahir = $antrian->pasien->tempat_lahir ?? 'N/A';
        $tanggalLahirFormatted = 'N/A';
        if (!empty($antrian->pasien->tanggal_lahir)) {
        try {
        $tanggalLahirFormatted = \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->format('d M Y');
        } catch (\Exception $e) {
        $tanggalLahirFormatted = 'Tanggal Invalid';
        }
        }
      @endphp
        {{ $tempatLahir . ', ' . $tanggalLahirFormatted }}
        </td>
        <td>{{ $antrian->pasien->jenis_kelamin ?? 'N/A' }}</td>
        <td>{{ $antrian->waktu_datang ? \Carbon\Carbon::parse($antrian->waktu_datang)->format('H:i') : '-' }}</td>
        <td>{{ $antrian->waktu_dipanggil ? \Carbon\Carbon::parse($antrian->waktu_dipanggil)->format('H:i') : '-' }}
        </td>
        <td>{{ $antrian->waktu_selesai ? \Carbon\Carbon::parse($antrian->waktu_selesai)->format('H:i') : '-' }}</td>
        <td>{{ $antrian->waktu_tunggu ?? '-' }}</td>
        <td>{{ $antrian->lama_layanan ?? '-' }}</td>
        <td>
        <span class="badge
        @if($antrian->status_antrian == 'menunggu') bg-secondary
      @elseif($antrian->status_antrian == 'dipanggil') bg-info
      @elseif($antrian->status_antrian == 'selesai') bg-success
      @elseif($antrian->status_antrian == 'batal') bg-danger
      @endif">
        {{ ucfirst($antrian->status_antrian) }}
        </span>
        </td>
      </tr>
    @empty
      <tr>
      <td colspan="13">Tidak ada data efisiensi untuk poli yang Anda tangani.</td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection