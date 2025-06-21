<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AntrianPoliModel;
use App\Models\UsersModel;
use App\Models\Poli;
use App\Models\LogAktivitasModel; // Pastikan ini adalah LogAktivitas, bukan LogAktivitasModel
use Illuminate\Validation\ValidationException;

class PetugasController extends Controller
{
    /**
     * Menampilkan daftar pasien hari ini sesuai poli petugas, dengan opsi filter poli.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showDaftarPasien(Request $request)
    {
        $petugas = Auth::user();

        $poliYangDitangani = $petugas->polis;
        $poliIdsYangDitangani = $poliYangDitangani->pluck('id')->toArray();

        $selectedPoliId = $request->input('poli_id');

        if ($selectedPoliId && !in_array($selectedPoliId, $poliIdsYangDitangani)) {
            $selectedPoliId = null;
            session()->flash('error', 'Anda tidak memiliki akses untuk melihat antrean di poli yang dipilih.');
        }

        $tanggalHariIni = Carbon::today()->format('Y-m-d');

        // ✅ PERBAIKAN PENTING: Hapus eager load pasien.pendaftaranPasien
        // karena kolom detail pasien sudah digabung ke UsersModel.
        $antrianHariIniQuery = AntrianPoliModel::with(['pasien', 'poli'])
            ->where('tanggal_antrian', $tanggalHariIni);

        if (!empty($poliIdsYangDitangani)) {
            $antrianHariIniQuery->whereIn('poli_id', $poliIdsYangDitangani);
        } else {
            $antrianHariIniQuery->where('id', -1);
        }

        if ($selectedPoliId) {
            $antrianHariIniQuery->where('poli_id', $selectedPoliId);
        }

        $antrianHariIni = $antrianHariIniQuery->orderBy('nomor_antrian', 'asc')->get();

        $namaPoliAktif = 'Semua Poli Terkait';
        if ($selectedPoliId) {
            $poliObj = $poliYangDitangani->firstWhere('id', $selectedPoliId);
            if ($poliObj) {
                $namaPoliAktif = $poliObj->nama_poli;
            }
        } else if (!empty($poliIdsYangDitangani)) {
            $namaPoliAktif = $poliYangDitangani->pluck('nama_poli')->implode(', ');
        } else {
            $namaPoliAktif = 'Tidak ada poli yang ditugaskan';
        }

        return view('petugas.daftar-pasien', compact('antrianHariIni', 'namaPoliAktif', 'poliYangDitangani', 'selectedPoliId'));
    }

    /**
     * Memanggil antrean pasien. Mengubah status menjadi 'dipanggil' dan mencatat waktu panggil.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id Id dari entri antrian_poli
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callAntrian(Request $request, $id)
    {
        try {
            // ✅ PERBAIKAN PENTING: Hapus eager load pasien.pendaftaranPasien
            $antrian = AntrianPoliModel::with('pasien')->findOrFail($id);
            $petugas = Auth::user();

            $poliIdsPetugas = $petugas->polis->pluck('id')->toArray();
            if (!$antrian->poli || !in_array($antrian->poli_id, $poliIdsPetugas)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke poli antrean ini.');
            }

            if ($antrian->status_antrian == 'selesai' || $antrian->status_antrian == 'batal' || $antrian->status_antrian == 'dipanggil') {
                return redirect()->back()->with('error', 'Antrean sudah ' . $antrian->status_antrian . ' dan tidak bisa dipanggil lagi.');
            }

            $antrian->status_antrian = 'dipanggil';
            $antrian->waktu_dipanggil = Carbon::now()->format('H:i:s');

            if ($antrian->waktu_datang) {
                $waktuDatangCarbon = Carbon::parse($antrian->tanggal_antrian . ' ' . $antrian->waktu_datang);
                $waktuDipanggilCarbon = Carbon::parse($antrian->tanggal_antrian . ' ' . $antrian->waktu_dipanggil);
                $antrian->waktu_tunggu = $waktuDipanggilCarbon->diffInMinutes($waktuDatangCarbon);
            } else {
                $antrian->waktu_tunggu = 0;
            }

            $antrian->save();

            if (Auth::check() && class_exists(LogAktivitasModel::class)) {
                // Akses nama pasien langsung dari $antrian->pasien
                $pasienName = $antrian->pasien->name ?? 'N/A';
                $poliName = $antrian->poli->nama_poli ?? 'N/A';
                LogAktivitasModel::create([
                    'user_id' => Auth::id(),
                    'nama_pengguna' => Auth::user()->name,
                    'peran' => Auth::user()->role,
                    'aktivitas' => 'Panggil Antrean',
                    'deskripsi' => 'Memanggil nomor antrean ' . $antrian->nomor_antrian . ' untuk pasien ' . $pasienName . ' di poli ' . $poliName,
                    'waktu' => Carbon::now()->format('H:i:s'),
                    'timestamp' => Carbon::now(),
                ]);
            }

            return redirect()->back()->with('success', 'Antrean ' . $antrian->nomor_antrian . ' berhasil dipanggil!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memanggil antrean: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui status antrean pasien (termasuk "Skip Antrean").
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id Id dari entri antrian_poli
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAntrianStatus(Request $request, $id)
    {
        try {
            // ✅ PERBAIKAN PENTING: Hapus eager load pasien.pendaftaranPasien
            $antrian = AntrianPoliModel::with('pasien')->findOrFail($id);
            $petugas = Auth::user();

            $poliIdsPetugas = $petugas->polis->pluck('id')->toArray();
            if (!$antrian->poli || !in_array($antrian->poli_id, $poliIdsPetugas)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke poli antrean ini.');
            }

            $request->validate([
                'status' => 'required|in:menunggu,dipanggil,selesai,batal',
            ]);

            if ($request->status == 'batal') {
                $antrian->waktu_dipanggil = null;
                $antrian->waktu_tunggu = null;
                $antrian->waktu_selesai = null;
                $antrian->lama_layanan = null;
            } elseif ($request->status == 'selesai') {
                $antrian->waktu_selesai = Carbon::now()->format('H:i:s');
                if ($antrian->waktu_dipanggil) {
                    $waktuDipanggilCarbon = Carbon::parse($antrian->tanggal_antrian . ' ' . $antrian->waktu_dipanggil);
                    $waktuSelesaiCarbon = Carbon::parse($antrian->tanggal_antrian . ' ' . $antrian->waktu_selesai);
                    $antrian->lama_layanan = $waktuSelesaiCarbon->diffInMinutes($waktuDipanggilCarbon);
                } else {
                    $antrian->lama_layanan = 0;
                }
            }


            $antrian->status_antrian = $request->status;
            $antrian->save();

            if (Auth::check() && class_exists(LogAktivitasModel::class)) {
                // Akses nama pasien langsung dari $antrian->pasien
                $pasienName = $antrian->pasien->name ?? 'N/A';
                LogAktivitasModel::create([
                    'user_id' => Auth::id(),
                    'nama_pengguna' => Auth::user()->name,
                    'peran' => Auth::user()->role,
                    'aktivitas' => 'Ubah Status Antrean',
                    'deskripsi' => 'Mengubah status antrean ' . $antrian->nomor_antrian . ' pasien ' . $pasienName . ' menjadi ' . $request->status,
                    'waktu' => Carbon::now()->format('H:i:s'),
                    'timestamp' => Carbon::now(),
                ]);
            }

            return redirect()->back()->with('success', 'Status antrean berhasil diperbarui menjadi ' . $request->status . '.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status antrean: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan monitor efisiensi untuk petugas.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showMonitorEfisiensi(Request $request)
    {
        $petugas = Auth::user();
        $poliYangDitangani = $petugas->polis;
        $poliIdsYangDitangani = $poliYangDitangani->pluck('id')->toArray();

        // Filter berdasarkan tanggal (default hari ini)
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        // ✅ PERBAIKAN PENTING: Hapus eager load pasien.pendaftaranPasien
        $queryAntrian = AntrianPoliModel::with(['pasien', 'poli']) // Eager load pasien dan poli
            ->whereIn('poli_id', $poliIdsYangDitangani)
            ->whereDate('tanggal_antrian', $tanggal);

        $efisiensiData = (clone $queryAntrian)
            ->orderBy('nomor_antrian', 'asc')
            ->get();

        // Statistik ringkasan
        $totalAntrian = (clone $queryAntrian)->count();
        $antrianSelesai = (clone $queryAntrian)->where('status_antrian', 'selesai')->count();
        $antrianMenunggu = (clone $queryAntrian)->where('status_antrian', 'menunggu')->count();
        $antrianDipanggil = (clone $queryAntrian)->where('status_antrian', 'dipanggil')->count();
        $antrianBatal = (clone $queryAntrian)->where('status_antrian', 'batal')->count();

        // Rata-rata waktu tunggu (hanya untuk yang sudah dipanggil atau selesai)
        $rataRataWaktuTunggu = (clone $queryAntrian)
            ->whereNotNull('waktu_tunggu')
            ->whereIn('status_antrian', ['selesai', 'dipanggil'])
            ->avg('waktu_tunggu');

        $rataRataWaktuTungguFormatted = $rataRataWaktuTunggu !== null ? round($rataRataWaktuTunggu) . ' menit' : 'N/A';

        // Rata-rata lama layanan (hanya untuk yang sudah selesai)
        $rataRataLamaLayanan = (clone $queryAntrian)
            ->whereNotNull('lama_layanan')
            ->where('status_antrian', 'selesai')
            ->avg('lama_layanan');

        $rataRataLamaLayananFormatted = $rataRataLamaLayanan !== null ? round($rataRataLamaLayanan) . ' menit' : 'N/A';

        // Mengirim data ke view
        return view('petugas.monitor-efisiensi', compact(
            'tanggal',
            'totalAntrian',
            'antrianSelesai',
            'antrianMenunggu',
            'antrianDipanggil',
            'antrianBatal',
            'rataRataWaktuTungguFormatted', // Ini adalah string tunggal
            'rataRataLamaLayananFormatted', // Ini adalah string tunggal
            'efisiensiData',
            'poliYangDitangani'
        ));
    }
}
