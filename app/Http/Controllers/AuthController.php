<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersModel;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = UsersModel::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email atau password salah.');
        }

        Auth::login($user);

        if ($user->role == 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role == 'petugas') {
            return redirect('/petugas/dashboard');
        } else {
            return abort(403, 'Pasien tidak login lewat web.');
        }
    }
}
