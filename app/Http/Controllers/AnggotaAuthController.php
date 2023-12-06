<?php

// app/Http/Controllers/AnggotaAuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('anggota.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('anggota')->attempt($credentials)) {
            // Jika berhasil login
            return redirect()->intended('buku.index'); // Ganti dengan rute yang sesuai
        }

        // Jika login gagal
        return back()->withErrors(['loginError' => 'Username atau password salah.']);
    }

    public function logout()
    {
        Auth::guard('anggota')->logout();
        return redirect('/anggota/login'); // Ganti dengan rute yang sesuai
    }
}
