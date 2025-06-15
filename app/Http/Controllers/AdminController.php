<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use App\Models\JadwalPoli;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $jadwalPoli = JadwalPoli::with('poli')->get();
        $poliList = Poli::all();

        return view('admin.admin-daftarpoli', compact('jadwalPoli', 'poliList'));
    }

    // ✅ Untuk tambah data dari modal "Tambah"
    public function store(Request $request)
    {
        $validated = $request->validate([
            'poli_id' => 'required|exists:poli,id',
            'hari' => 'required|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'kuota' => 'required|integer|min:1',
        ]);

        JadwalPoli::create($validated);

        return redirect()->back()->with('success', 'Jadwal Poli berhasil ditambahkan.');
    }

    // ✅ Untuk ubah data dari modal "Edit"
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'poli_id' => 'required|exists:poli,id',
            'hari' => 'required|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'kuota' => 'required|integer|min:1',
        ]);

        $jadwal = JadwalPoli::findOrFail($id);
        $jadwal->update($validated);

        return redirect()->back()->with('success', 'Jadwal Poli berhasil diperbarui.');
    }

    // ✅ Untuk hapus data dari tombol hapus (dengan modal konfirmasi)
    public function destroy($id)
    {
        $jadwal = JadwalPoli::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal Poli berhasil dihapus.');
    }

    // ✅ Untuk toggle status aktif/non-aktif
    public function toggleStatus($id)
    {
        $jadwal = JadwalPoli::findOrFail($id);
        $jadwal->status = $jadwal->status === 'aktif' ? 'non-aktif' : 'aktif';
        $jadwal->save();

        return redirect()->back()->with('success', 'Status berhasil diubah.');
    }

    // ✅ Method BARU untuk Menambah Poli
    public function storePoli(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_poli' => 'required|string|max:255|unique:poli,kode_poli', // unique:table,column
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
}
