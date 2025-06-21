<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\UsersModel; // Mengacu ke model User Anda

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends Controller
{
    /**
     * Handle user registration for API, including patient details directly in the users table.
     * Mengelola pendaftaran pengguna baru untuk API, termasuk detail pasien langsung di tabel users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // ✅ Validasi untuk semua data yang akan disimpan di UsersModel
            $request->validate([
                'name' => 'required|string|max:255', // Nama lengkap pasien, akan disimpan di users.name
                'email' => 'required|string|email|max:255|unique:users,email', // Email unik di tabel users
                'password' => 'required|string|min:8|confirmed', // Password dan konfirmasinya

                // Data detail pasien, akan disimpan langsung di kolom tabel 'users'
                'nik' => 'required|string|max:20|unique:users,nik', // NIK unik di tabel users
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
                'no_hp' => 'required|string|max:15',
                'alamat' => 'nullable|string|max:255',
            ], [
                'nik.unique' => 'NIK ini sudah terdaftar.',
                'email.unique' => 'Email ini sudah terdaftar.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'tanggal_lahir.required' => 'Tanggal Lahir wajib diisi.',
                'tanggal_lahir.date' => 'Format Tanggal Lahir tidak valid.',
                'jenis_kelamin.in' => 'Jenis Kelamin harus Laki-laki  atau Perempuan.',
            ]);

            // 1. Buat akun user, sertakan juga semua detail pasien di UsersModel
            $user = UsersModel::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien', // Set role secara default sebagai 'pasien'
                'email_verified_at' => null, // Atau Carbon::now() jika email langsung terverifikasi
                'remember_token' => null,
                // ✅ Tambahkan semua kolom detail pasien ke sini
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Respon berhasil
            return response()->json([
                'message' => 'Registrasi pasien berhasil!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    // ✅ Sertakan semua detail pasien yang baru disimpan
                    'nik' => $user->nik,
                    'tempat_lahir' => $user->tempat_lahir,
                    'tanggal_lahir' => $user->tanggal_lahir,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'no_hp' => $user->no_hp,
                    'alamat' => $user->alamat,
                ],
                'token' => $token
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data validasi tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user login for API.
     * Mengelola proses login pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('email', 'password');
            $token = null;

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json([
                    'message' => 'Kredensial tidak valid.'
                ], 401);
            }

            $user = Auth::guard('api')->user();

            // Respon login: kembalikan objek user lengkap
            return response()->json([
                'message' => 'Login berhasil!',
                'user' => $user, // Mengembalikan objek user lengkap dengan semua kolomnya
                'token' => $token
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data validasi tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Tidak dapat membuat token.',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user logout for API.
     * Mengelola proses logout pengguna (membutuhkan token JWT yang valid).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('api')->logout();

            return response()->json([
                'message' => 'Logout berhasil. Token berhasil dicabut.'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Gagal mencabut token. Token mungkin sudah tidak valid.',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat logout: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
