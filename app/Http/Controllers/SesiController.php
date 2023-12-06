<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SesiController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        $dataUser = User::where('email', $request->email)->first();

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $password = $dataUser->password;
        $infologin = [
            'email' => $request->email,
            'password' => Hash::check($request->password, $password),
        ];
        if (!empty($dataUser) && $infologin['password'] == true) {
            // Authenticate the user
            Auth::login($dataUser);


            $role = $dataUser->role;

            if ($role = 'petugas') {
                $data = Buku::orderBy('id', 'asc')->paginate(10);
            } else {
                $data = Buku::orderBy('id', 'asc')->get();
            }
            return redirect('buku')->with([
                'role' => $role,
                'data' => $data
            ]);
        } else {
            return redirect('')->withErrors('Email dan password yang dimasukkan tidak sesuai')->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
