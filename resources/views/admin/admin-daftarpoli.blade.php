@extends('layouts.dashboard-layout')

@section('title', 'Daftar Jadwal Poli')

@section('content')
    <div class="container py-4">

        <!-- Tombol Tambah Layanan atau Jadwal Poli dan Cari -->
        <div class="d-flex justify-content-between mb-3">
            <div>
                <button class="btn btn-outline-primary rounded-pill px-4 me-2" data-bs-toggle="modal"
                    data-bs-target="#modalTambah">Tambah Layanan</button>
                {{-- Tombol Baru untuk Tambah Poli --}}
                <button class="btn btn-outline-success rounded-pill px-4" data-bs-toggle="modal"
                    data-bs-target="#modalTambahPoli">Tambah Poli</button>
            </div>
            <form action="{{ route('jadwalpoli.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-pill me-2" placeholder="Cari nama poli..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary rounded-pill px-4">Cari</button>
            </form>
        </div>

        <!-- Tabel Jadwal Poli -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th>Kode Poli</th>
                        <th>Poli</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwalPoli as $jadwal)
                        <tr>
                            <td>{{ $jadwal->poli->kode_poli }}</td>
                            <td><i>{{ $jadwal->poli->nama_poli }}</i></td>
                            <td><b>{{ $jadwal->hari }}</b></td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->jam_buka)->format('H.i') }} -
                                {{ \Carbon\Carbon::parse($jadwal->jam_tutup)->format('H.i') }}
                            </td>
                            <td><b>{{ $jadwal->kuota }}</b></td>
                            <td>
                                <form action="{{ route('jadwalpoli.toggleStatus', $jadwal->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $jadwal->status === 'aktif' ? 'btn-success' : 'btn-danger' }}">
                                        {{ $jadwal->status === 'aktif' ? '✔️' : '❌' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $jadwal->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Tombol Hapus -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#hapusModal{{ $jadwal->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Tidak ada data jadwal poli.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Jadwal Poli (EXISTING) -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Jadwal Poli</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('jadwalpoli.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="poli_id" class="form-label">Poli</label>
                            <select name="poli_id" id="poli_id" class="form-select" required>
                                <option value="">Pilih Poli</option>
                                @foreach ($poliList as $poli)
                                    <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari</label>
                            <input type="text" name="hari" id="hari" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jam_buka" class="form-label">Jam Buka</label>
                            <input type="time" name="jam_buka" id="jam_buka" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jam_tutup" class="form-label">Jam Tutup</label>
                            <input type="time" name="jam_tutup" id="jam_tutup" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="kuota" class="form-label">Kuota</label>
                            <input type="number" name="kuota" id="kuota" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Poli BARU -->
    <div class="modal fade" id="modalTambahPoli" tabindex="-1" aria-labelledby="modalTambahPoliLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPoliLabel">Tambah Poli Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('poli.store') }}" method="POST"> {{-- Route baru untuk simpan poli --}}
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_poli" class="form-label">Kode Poli</label>
                            <input type="text" name="kode_poli" id="kode_poli" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_poli" class="form-label">Nama Poli</label>
                            <input type="text" name="nama_poli" id="nama_poli" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Poli</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modals Edit dan Hapus (pastikan ada di setiap iterasi @forelse) --}}
    @foreach ($jadwalPoli as $jadwal)
        <!-- Modal Edit -->
        <div class="modal fade" id="editModal{{ $jadwal->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $jadwal->id }}"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('jadwalpoli.update', $jadwal->id) }}" method="POST"> @csrf @method('PUT') <div
                        class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $jadwal->id }}">Edit Jadwal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Isi Form -->
                            <div class="mb-3">
                                <label for="nama_poli" class="form-label">Nama Poli</label>
                                <select name="poli_id" class="form-control" required>
                                    @foreach ($poliList as $poli)
                                        <option value="{{ $poli->id }}" {{ $poli->id == $jadwal->poli_id ? 'selected' : '' }}>
                                            {{ $poli->nama_poli }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <input type="text" name="hari" class="form-control" value="{{ $jadwal->hari }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="jam_buka" class="form-label">Jam Buka</label>
                                <input type="time" name="jam_buka" class="form-control" value="{{ $jadwal->jam_buka }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="jam_tutup" class="form-label">Jam Tutup</label>
                                <input type="time" name="jam_tutup" class="form-control" value="{{ $jadwal->jam_tutup }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="kuota" class="form-label">Kuota</label>
                                <input type="number" name="kuota" class="form-control" value="{{ $jadwal->kuota }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div class="modal fade" id="hapusModal{{ $jadwal->id }}" tabindex="-1"
            aria-labelledby="hapusModalLabel{{ $jadwal->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('jadwalpoli.destroy', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Apakah kamu yakin ingin menghapus jadwal poli <strong>{{ $jadwal->poli->nama_poli }}</strong>?
                            </p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection