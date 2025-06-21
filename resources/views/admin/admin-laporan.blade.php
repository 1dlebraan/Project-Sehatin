@extends('layouts.dashboard-layout')

@section('title', 'Laporan Statistik')

@section('content')
    <div class="container py-4">
        <h2>Laporan Statistik</h2>
        <p>Lihat ringkasan dan performa sistem Anda di sini.</p>

        <!-- Form Filter Tanggal -->
        <div class="card shadow-sm rounded-lg mb-4 p-3">
            <form action="{{ route('admin.laporan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai:</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                        value="{{ request('tanggal_mulai', \Carbon\Carbon::now()->subDays(30)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai:</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                        value="{{ request('tanggal_selesai', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Filter Laporan</button>
                </div>
            </form>
        </div>
        <!-- Akhir Form Filter Tanggal -->

        <div class="row mb-4">
            <!-- Card Total Pasien -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Total Pasien</h5>
                        <p class="card-text fs-1 fw-bold">{{ $totalPasien }}</p>
                        <p class="text-muted">Pasien terdaftar di sistem</p>
                    </div>
                </div>
            </div>

            <!-- Card Rata-rata Waktu Tunggu -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success">Rata-rata Waktu Tunggu</h5>
                        <p class="card-text fs-1 fw-bold">{{ $rataRataWaktuTunggu }}</p>
                        <p class="text-muted">Waktu tunggu pasien di poli</p>
                    </div>
                </div>
            </div>

            <!-- Card Kunjungan Total (dalam periode filter) -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title text-info">Total Kunjungan</h5>
                        {{-- totalKunjungan dihitung di controller dan dikirimkan ke view --}}
                        {{-- Namun, jika Anda tidak ingin mengirimnya dari controller, Anda bisa menghitungnya langsung di
                        sini
                        tapi lebih baik di controller untuk efisiensi dan kejelasan --}}
                        <p class="card-text fs-1 fw-bold">
                            {{ \App\Models\AntrianPoliModel::whereBetween('tanggal_antrian', [
        Carbon\Carbon::parse(request('tanggal_mulai', Carbon\Carbon::now()->subDays(30)->format('Y-m-d'))),
        Carbon\Carbon::parse(request('tanggal_selesai', Carbon\Carbon::now()->format('Y-m-d')))
    ])->count() }}
                        </p>
                        <p class="text-muted">Dalam periode ini</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Performa per Hari (Tabel dan Grafik) -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Performa Per Hari</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performaHarianChart"></canvas>
                        <h6 class="mt-4">Ringkasan Tabel:</h6>
                        <table class="table table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Total Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($performaPerHari as $hari => $total)
                                    <tr>
                                        <td>{{ $hari }}</td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Tidak ada data kunjungan untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Performa per Poli (Tabel dan Grafik) -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Performa Per Poli</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performaPoliChart"></canvas>
                        <h6 class="mt-4">Ringkasan Tabel:</h6>
                        <table class="table table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>Poli</th>
                                    <th>Total Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($performaPerPoli as $poli => $total)
                                    <tr>
                                        <td>{{ $poli }}</td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Tidak ada data kunjungan untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sertakan Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data Performa Per Hari dari Laravel
        const performaPerHariData = @json($performaPerHari);
        const hariLabels = Object.keys(performaPerHariData);
        const dataKunjunganHarian = Object.values(performaPerHariData);

        // Grafik Performa Per Hari
        const ctxHarian = document.getElementById('performaHarianChart').getContext('2d');
        new Chart(ctxHarian, {
            type: 'bar', // Anda bisa mencoba 'line' juga
            data: {
                labels: hariLabels,
                datasets: [{
                    label: 'Total Kunjungan',
                    data: dataKunjunganHarian,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Warna biru
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Kunjungan Pasien per Hari'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Kunjungan'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Hari'
                        }
                    }
                }
            }
        });

        // Data Performa Per Poli dari Laravel
        const performaPerPoliData = @json($performaPerPoli);
        const poliLabels = Object.keys(performaPerPoliData);
        const dataKunjunganPoli = Object.values(performaPerPoliData);

        // Grafik Performa Per Poli
        const ctxPoli = document.getElementById('performaPoliChart').getContext('2d');
        new Chart(ctxPoli, {
            type: 'pie', // Pie chart cocok untuk distribusi
            data: {
                labels: poliLabels,
                datasets: [{
                    label: 'Total Kunjungan',
                    data: dataKunjunganPoli,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)', // Biru
                        'rgba(255, 206, 86, 0.7)', // Kuning
                        'rgba(75, 192, 192, 0.7)', // Hijau
                        'rgba(153, 102, 255, 0.7)',// Ungu
                        'rgba(255, 159, 64, 0.7)' // Oranye
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribusi Kunjungan per Poli'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' kunjungan';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection