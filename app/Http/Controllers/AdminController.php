<?php

namespace App\Http\Controllers;

use App\Models\AntrianPoliModel;
use App\Models\JadwalPoli;
use App\Models\Poli;
use App\Models\UsersModel;
use App\Models\SettingModel;
use App\Models\LogAktivitasModel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // -------------------------------POLI AREA----------------------------------------------------
    // Method untuk menampilkan halaman Daftar Jadwal Poli
    public function showDaftarPoli(Request $request)
    {
        $search = $request->query('search');

        $jadwalPoli = JadwalPoli::with('poli')
            ->when($search, function ($query, $search) {
                $query->whereHas('poli', function ($q) use ($search) {
                    $q->where('nama_poli', 'like', '%' . $search . '%');
                });
            })
            ->get();

        $poliList = Poli::all();

        return view('admin.admin-daftarpoli', compact('jadwalPoli', 'poliList'));
    }

    // ✅ Untuk tambah data jadwal poli dari modal "Tambah Layanan"
    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'poli_id' => 'required|exists:poli,id',
                'hari' => 'required|array|min:1', // HARUS array, minimal 1 pilihan
                'hari.*' => 'string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu', // Setiap item dalam array harus string hari yang valid
                'jam_buka' => 'required|date_format:H:i',
                'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
                'kuota' => 'required|integer|min:1',
            ]);

            // Ubah array 'hari' menjadi string yang dipisahkan koma sebelum disimpan
            $validated['hari'] = implode(',', $validated['hari']);

            JadwalPoli::create($validated);

            return redirect()->back()->with('success', 'Jadwal Poli berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    // ✅ Untuk ubah data jadwal poli dari modal "Edit"
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'poli_id' => 'required|exists:poli,id',
                'hari' => 'required|array|min:1', // HARUS array, minimal 1 pilihan
                'hari.*' => 'string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu', // Setiap item dalam array harus string hari yang valid
                'jam_buka' => 'required|date_format:H:i',
                'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
                'kuota' => 'required|integer|min:1',
            ]);


            // Ubah array 'hari' menjadi string yang dipisahkan koma sebelum disimpan
            $validated['hari'] = implode(',', $validated['hari']);

            $jadwal = JadwalPoli::findOrFail($id);
            $jadwal->update($validated);

            return redirect()->back()->with('success', 'Jadwal Poli berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ✅ Untuk hapus data jadwal poli dari tombol hapus (dengan modal konfirmasi)
    public function destroy($id)
    {
        try {
            $jadwal = JadwalPoli::findOrFail($id);
            $jadwal->delete();

            return redirect()->back()->with('success', 'Jadwal Poli berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal poli: ' . $e->getMessage());
        }
    }

    // ✅ Untuk toggle status aktif/non-aktif jadwal poli
    public function toggleStatus($id)
    {
        try {
            $jadwal = JadwalPoli::findOrFail($id);
            $jadwal->status = $jadwal->status === 'aktif' ? 'non-aktif' : 'aktif';
            $jadwal->save();

            return redirect()->back()->with('success', 'Status berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    // ✅ Method untuk Menambah Poli
    public function storePoli(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_poli' => 'required|string|max:255|unique:poli,kode_poli',
                'nama_poli' => 'required|string|max:255',
            ]);

            Poli::create($validated);

            return redirect()->back()->with('success', 'Poli baru berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan poli: ' . $e->getMessage());
        }
    }

    // -----------------------------   PETUGAS AREA -----------------------------------------
    // Method untuk menampilkan halaman Daftar Petugas
    public function showPetugas()
    {
        // Ambil semua user dengan role 'petugas' beserta relasi poli yang terkait
        $petugasList = UsersModel::with('polis')
            ->where('role', 'petugas')
            ->get();

        // Ambil semua daftar poli untuk dropdown/checkbox di form
        $poliList = Poli::all();

        return view('admin.admin-petugas', compact('petugasList', 'poliList'));
    }

    // Method untuk menambah akun petugas baru
    public function storePetugas(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email', // Pastikan email unik di tabel users
                'password' => 'required|string|min:8|confirmed', // 'confirmed' butuh field password_confirmation
                'polis' => 'nullable|array', // Array ID poli yang dipilih
                'polis.*' => 'exists:poli,id', // Setiap ID di array harus ada di tabel poli
            ]);

            // Buat user baru dengan role 'petugas'
            $petugas = UsersModel::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']), // Hash password sebelum disimpan
                'role' => 'petugas', // Set role secara default ke 'petugas'
            ]);


            $petugas->polis()->sync($request->input('polis', []));

            return redirect()->back()->with('success', 'Akun petugas baru berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan akun petugas: ' . $e->getMessage());
        }
    }

    // Method untuk mengupdate akun petugas
    public function updatePetugas(Request $request, $id)
    {
        try {
            $petugas = UsersModel::where('role', 'petugas')->findOrFail($id); // Cari petugas berdasarkan ID dan role

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $petugas->id, // Email unik kecuali untuk user ini sendiri
                'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update, 'confirmed' butuh password_confirmation
                'polis' => 'nullable|array',
                'polis.*' => 'exists:poli,id',
            ]);

            // Update data dasar petugas
            $petugas->name = $validated['name'];
            $petugas->email = $validated['email'];

            // Jika password baru disediakan, hash dan update
            if (!empty($validated['password'])) {
                $petugas->password = Hash::make($validated['password']);
            }
            $petugas->save();

            // Sinkronkan relasi many-to-many dengan poli yang dipilih
            $petugas->polis()->sync($request->input('polis', []));

            return redirect()->back()->with('success', 'Akun petugas berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui akun petugas: ' . $e->getMessage());
        }
    }

    // Method untuk menghapus akun petugas
    public function destroyPetugas($id)
    {
        try {
            $petugas = UsersModel::where('role', 'petugas')->findOrFail($id); // Cari petugas berdasarkan ID dan role

            // Detach semua poli terkait sebelum menghapus user (opsional, cascade delete di migrasi sudah membantu)
            $petugas->polis()->detach();

            $petugas->delete();

            return redirect()->back()->with('success', 'Akun petugas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus akun petugas: ' . $e->getMessage());
        }
    }
    //------------------------ Laporan Statistik Area -----------------------------------------
    public function showLaporan()
    {
        // 1. Menghitung Total Pasien
        $totalPasien = UsersModel::where('role', 'pasien')->count();

        // 2. Perhitungan Rata-rata Waktu Tunggu dari data REAL 'antrian_poli'
        //    Hanya hitung yang 'selesai' atau 'dipanggil' dan memiliki 'waktu_tunggu'
        $rataRataWaktuTunggu = AntrianPoliModel::whereNotNull('waktu_tunggu')
            ->whereIn('status_antrian', ['selesai', 'dipanggil'])
            ->avg('waktu_tunggu'); // Mengambil rata-rata kolom waktu_tunggu

        if ($rataRataWaktuTunggu !== null) {
            $rataRataWaktuTunggu = round($rataRataWaktuTunggu) . ' menit';
        } else {
            $rataRataWaktuTunggu = 'Belum ada data tunggu';
        }


        // 3. Perhitungan Performa per Hari dari data REAL 'antrian_poli'
        $performaPerHari = AntrianPoliModel::selectRaw('DAYNAME(tanggal_antrian) as hari, COUNT(*) as total_kunjungan')
            ->groupBy('hari')
            ->orderByRaw("FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')") // Urutkan hari
            ->get()
            ->pluck('total_kunjungan', 'hari')
            ->toArray();
        // Terjemahkan nama hari ke Bahasa Indonesia
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $performaPerHariID = [];
        foreach ($performaPerHari as $dayNameEn => $count) {
            $performaPerHariID[$hariMap[$dayNameEn] ?? $dayNameEn] = $count;
        }
        $performaPerHari = $performaPerHariID;


        // 4. Perhitungan Performa per Poli dari data REAL 'antrian_poli'
        $performaPerPoliDb = AntrianPoliModel::select('poli_id', DB::raw('COUNT(*) as total_kunjungan'))
            ->groupBy('poli_id')
            ->with('poli') // Memuat relasi poli
            ->get();

        $performaPerPoli = [];
        foreach ($performaPerPoliDb as $data) {
            if ($data->poli) { // Pastikan poli terkait ditemukan
                $performaPerPoli[$data->poli->nama_poli] = $data->total_kunjungan;
            }
        }


        return view('admin.admin-laporan', compact('totalPasien', 'rataRataWaktuTunggu', 'performaPerHari', 'performaPerPoli'));
    }

    //  ------------------------------- Konfigurasi Umum Area --------------------------------
    public function showKonfigurasi()
    {
        // Ambil pengaturan yang ada dari tabel 'settings'
        $settings = SettingModel::pluck('value', 'key')->toArray();

        // Tentukan nilai default jika pengaturan belum ada di database
        $jamBuka = $settings['jam_operasional_buka'] ?? '08:00';
        $jamTutup = $settings['jam_operasional_tutup'] ?? '17:00';
        $batasKuotaAntrian = $settings['batas_kuota_antrian'] ?? 50; // Default 50

        // Ambil log aktivitas pengguna terbaru (misal 20 log terakhir)
        $logAktivitas = LogAktivitasModel::with('user') // Memuat relasi user jika ada
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.admin-konfigurasi', compact('jamBuka', 'jamTutup', 'batasKuotaAntrian', 'logAktivitas'));
    }

    // Method untuk mengupdate Konfigurasi Umum
    public function updateKonfigurasi(Request $request)
    {
        try {
            $validated = $request->validate([
                'jam_operasional_buka' => 'required|date_format:H:i',
                'jam_operasional_tutup' => 'required|date_format:H:i|after:jam_operasional_buka',
                'batas_kuota_antrian' => 'required|integer|min:1|max:999', // Sesuaikan batas maks
            ]);

            // Simpan atau perbarui pengaturan ke tabel 'settings'
            // updateOrCreate akan mencari berdasarkan 'key', jika ada update, jika tidak buat baru
            SettingModel::updateOrCreate(['key' => 'jam_operasional_buka'], ['value' => $validated['jam_operasional_buka']]);
            SettingModel::updateOrCreate(['key' => 'jam_operasional_tutup'], ['value' => $validated['jam_operasional_tutup']]);
            SettingModel::updateOrCreate(['key' => 'batas_kuota_antrian'], ['value' => $validated['batas_kuota_antrian']]);

            // Catat aktivitas di log (opsional, jika Anda memiliki logger yang sudah terintegrasi)
            // if (auth()->check()) {
            //     LogAktivitas::create([
            //         'user_id' => auth()->user()->id,
            //         'aktivitas' => 'Update Konfigurasi',
            //         'deskripsi' => 'Admin ' . auth()->user()->name . ' memperbarui pengaturan umum.',
            //         'ip_address' => $request->ip(),
            //         'user_agent' => $request->header('User-Agent'),
            //     ]);
            // }

            return redirect()->back()->with('success', 'Konfigurasi berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui konfigurasi: ' . $e->getMessage());
        }
    }


}
