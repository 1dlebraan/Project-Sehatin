@extends('layouts.dashboard-layout')

@section('title', 'Manajemen Akun Petugas')

@section('content')
    <div class="container py-4">

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

        <!-- Tombol Tambah Akun Petugas -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal"
                data-bs-target="#modalTambahPetugas">Tambah Akun Petugas</button>
        </div>

        <!-- Tabel Daftar Petugas -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle bg-white">
                <thead class="table-light">
                    <tr>
                        <th>ID User</th>
                        <th>Nama User</th>
                        <th>Email Akun</th>
                        <th>Poli Terkait</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($petugasList as $petugas)
                        <tr>
                            <td>{{ $petugas->id }}</td>
                            <td>{{ $petugas->name }}</td>
                            <td>{{ $petugas->email }}</td>
                            <td>
                                {{-- Menampilkan poli yang terkait, dipisahkan koma --}}
                                @if ($petugas->polis->isNotEmpty())
                                    {{ $petugas->polis->pluck('nama_poli')->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editPetugasModal{{ $petugas->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Tombol Hapus -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#hapusPetugasModal{{ $petugas->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Tidak ada akun petugas yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Akun Petugas -->
    <div class="modal fade" id="modalTambahPetugas" tabindex="-1" aria-labelledby="modalTambahPetugasLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPetugasLabel">Tambah Akun Petugas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.petugas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama User</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Akun</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Poli Terkait (Pilih Beberapa)</label>
                            @forelse ($poliList as $poli)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('polis') is-invalid @enderror" type="checkbox" name="polis[]"
                                        id="add_poli_{{ $poli->id }}" value="{{ $poli->id }}"
                                        {{ in_array($poli->id, old('polis', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="add_poli_{{ $poli->id }}">{{ $poli->nama_poli }}</label>
                                </div>
                            @empty
                                <p>Tidak ada poli yang tersedia. Harap tambahkan poli terlebih dahulu.</p>
                            @endforelse
                            @error('polis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('polis.*')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Petugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modals Edit dan Hapus Petugas --}}
    @foreach ($petugasList as $petugas)
        <!-- Modal Edit Akun Petugas -->
        <div class="modal fade" id="editPetugasModal{{ $petugas->id }}" tabindex="-1"
            aria-labelledby="editPetugasModalLabel{{ $petugas->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPetugasModalLabel{{ $petugas->id }}">Edit Akun Petugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.petugas.update', $petugas->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name_{{ $petugas->id }}" class="form-label">Nama User</label>
                                <input type="text" name="name" id="edit_name_{{ $petugas->id }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $petugas->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_email_{{ $petugas->id }}" class="form-label">Email Akun</label>
                                <input type="email" name="email" id="edit_email_{{ $petugas->id }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $petugas->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_password_{{ $petugas->id }}" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" id="edit_password_{{ $petugas->id }}"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_password_confirmation_{{ $petugas->id }}" class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="edit_password_confirmation_{{ $petugas->id }}"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Poli Terkait (Pilih Beberapa)</label>
                                @php
                                    // Untuk old input, jika ada error validasi, gunakan old()
                                    // Jika tidak, gunakan polis yang terkait dengan petugas dari database
                                    $currentSelectedPoliIds = old('polis', $petugas->polis->pluck('id')->toArray());
                                @endphp
                                @forelse ($poliList as $poli)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input @error('polis') is-invalid @enderror" type="checkbox" name="polis[]"
                                            id="edit_poli_{{ $petugas->id }}_{{ $poli->id }}" value="{{ $poli->id }}"
                                            {{ in_array($poli->id, $currentSelectedPoliIds) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="edit_poli_{{ $petugas->id }}_{{ $poli->id }}">{{ $poli->nama_poli }}</label>
                                    </div>
                                @empty
                                    <p>Tidak ada poli yang tersedia. Harap tambahkan poli terlebih dahulu.</p>
                                @endforelse
                                @error('polis')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('polis.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Hapus Akun Petugas -->
        <div class="modal fade" id="hapusPetugasModal{{ $petugas->id }}" tabindex="-1"
            aria-labelledby="hapusPetugasModalLabel{{ $petugas->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.petugas.destroy', $petugas->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Hapus Akun Petugas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Apakah Anda yakin ingin menghapus akun petugas <strong>{{ $petugas->name }}</strong> ({{ $petugas->email }})?</p>
                            <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
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
