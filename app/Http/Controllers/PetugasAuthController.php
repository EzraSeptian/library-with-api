<?php

// app/Http/Controllers/AnggotaAuthController.php

// app/Http/Controllers/PetugasAuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('petugas.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('petugas')->attempt($credentials)) {
            // Jika berhasil login
            return redirect()->intended('buku.index'); // Ganti dengan rute yang sesuai
        }

        // Jika login gagal
        return back()->withErrors(['loginError' => 'Username atau password salah.']);
    }

    public function logout()
    {
        Auth::guard('petugas')->logout();
        return redirect('/petugas/login'); // Ganti dengan rute yang sesuai
    }
}
