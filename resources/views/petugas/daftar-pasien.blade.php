@extends('layouts.dashboard-layout') {{-- Asumsi Anda memiliki layout ini --}}

@section('title', 'Daftar Antrian Pasien')

@section('content')
  <div class="container py-4">
    <h2>Daftar Antrian Pasien Hari Ini</h2>
    <p>Antrian untuk poli yang Anda layani.</p>

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

    <div class="table-responsive">
    <table class="table table-bordered text-center align-middle bg-white">
      <thead class="table-light">
      <tr>
        <th>No. Antrian</th>
        <th>NIK</th>
        <th>Nama Pasien</th>
        <th>TTL</th>
        <th>Jenis Kelamin</th>
        {{-- <th>No. Telepon</th> --}} {{-- KOLOM INI DIHAPUS --}}
        <th>Poli</th>
        <th>Waktu Datang</th>
        <th>Waktu Dipanggil</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($antrianHariIni as $antrian)
      <tr>
        <td>{{ $antrian->nomor_antrian }}</td>
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
        {{-- <td>{{ $antrian->pasien->no_hp ?? 'N/A' }}</td> --}} {{-- KOLOM INI DIHAPUS --}}
        <td>{{ $antrian->poli->nama_poli }}</td>
        <td>{{ $antrian->waktu_datang ? \Carbon\Carbon::parse($antrian->waktu_datang)->format('H:i') : '-' }}</td>
        <td>{{ $antrian->waktu_dipanggil ? \Carbon\Carbon::parse($antrian->waktu_dipanggil)->format('H:i') : '-' }}
        </td>
        <td>
        <span class="badge {{
      $antrian->status_antrian == 'menunggu' ? 'bg-warning' :
      ($antrian->status_antrian == 'dipanggil' ? 'bg-info' :
      ($antrian->status_antrian == 'selesai' ? 'bg-success' : 'bg-danger'))
      }}">
        {{ ucfirst($antrian->status_antrian) }}
        </span>
        </td>
        <td>
        @if ($antrian->status_antrian == 'menunggu')
      <form action="{{ route('petugas.call_antrian', $antrian->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary" title="Panggil Pasien">
        <i class="fas fa-phone-alt"></i> {{-- Ikon telepon --}}
        </button>
      </form>
      <form action="{{ route('petugas.update_antrian_status', $antrian->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        <input type="hidden" name="status" value="batal">
        <button type="submit" class="btn btn-sm btn-warning text-dark" title="Skip Antrean">
        <i class="fas fa-forward"></i> {{-- Ikon maju --}}
        </button>
      </form>
      @elseif ($antrian->status_antrian == 'dipanggil')
      <form action="{{ route('petugas.update_antrian_status', $antrian->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        <input type="hidden" name="status" value="selesai">
        <button type="submit" class="btn btn-sm btn-success" title="Selesai">
        <i class="fas fa-check-circle"></i>
      </form>
      <form action="{{ route('petugas.update_antrian_status', $antrian->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        <input type="hidden" name="status" value="batal">
        <button type="submit" class="btn btn-sm btn-warning text-dark" title="Skip Antrean">
        <i class="fas fa-forward"></i> {{-- Ikon maju --}}
        </button>
      </form>
      @else
      -
      @endif
        </td>
      </tr>
    @empty
      <tr>
      <td colspan="10">Tidak ada antrian pasien untuk hari ini di poli Anda.</td> {{-- COLSPAN DIUBAH DARI 11
      MENJADI 10 --}}
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection


@push('styles')
  <style>
    /* CSS kustom untuk tombol ikon Font Awesome */
    .btn-sm i.fas {
    font-size: 1rem;
    /* Ukuran ikon, bisa disesuaikan */
    vertical-align: middle;
    line-height: 1;
    /* Penting untuk konsistensi tinggi */
    }

    /* Sesuaikan padding tombol jika diperlukan, karena ikon FA memiliki spasi internal yang baik */
    .btn-sm {
    padding: .25rem .5rem;
    /* Padding default Bootstrap untuk btn-sm */
    }
  </style>
@endpush