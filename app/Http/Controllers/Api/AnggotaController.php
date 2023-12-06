<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Anggota::orderBy('id', 'asc')->paginate(10);
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataAnggota  = new Anggota;
        $dataUser = new User;


        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'jurusan' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }
        $dataAnggota->nama =  $request->nama;
        $dataAnggota->alamat = $request->alamat;
        $dataAnggota->jurusan  = $request->jurusan;
        $dataAnggota->email = $request->email;
        $dataAnggota->password = bcrypt($request->password);

        $dataUser->name = $request->nama;
        $dataUser->password = bcrypt($request->password);
        $dataUser->email = $request->email;
        $dataUser->role = 'anggota';


        $post = $dataAnggota->save();

        $post2 = $dataUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan data'
        ]);
    }

    public function getAllData()
    {
        $allData = Anggota::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Semua data buku ditemukan',
            'data' => $allData
        ], 200);
    }

    public function show(string $id)
    {
        $data = Anggota::find($id);
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dataAnggota  = Anggota::find($id);
        $dataUser = User::where('email', $request->email)->first();
        if (empty($dataAnggota)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'jurusan' => 'required',
            'email' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate data',
                'data' => $validator->errors()
            ]);
        }
        $dataAnggota->nama =  $request->nama;
        $dataAnggota->alamat = $request->alamat;
        $dataAnggota->jurusan  = $request->jurusan;
        $dataAnggota->email = $request->email;
        if (!empty($request->password)) {
            $dataAnggota->password = bcrypt($request->password);
            $dataUser->password = bcrypt($request->password);
        }
        $dataUser->name = $request->nama;

        $dataUser->email = $request->email;
        $dataUser->role = 'anggota';

        $post = $dataAnggota->save();
        $post2 = $dataUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengupdate data'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataAnggota  = Anggota::find($id);
        $email = $dataAnggota->email;
        $dataUser = User::where('email', $email)->first();
        if (empty($dataAnggota)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }


        $post = $dataAnggota->delete();

        $post2 = $dataUser->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil delete data'
        ]);
    }
}