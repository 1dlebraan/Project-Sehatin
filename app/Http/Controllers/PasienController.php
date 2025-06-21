<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PendaftaranPasien;
use App\Models\UsersModel;

class PasienController extends Controller
{
    public function edit($id)
    {
        $pasien = UsersModel::with('pendaftaran_pasien')->findOrFail($id);
        return view('petugas.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        ]);

        $user = UsersModel::findOrFail($id);
        $user->name = $request->name;
        $user->save();

        $pendaftaran = $user->pendaftaran_pasien;
        if ($pendaftaran) {
            $pendaftaran->update([
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        }

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

}
