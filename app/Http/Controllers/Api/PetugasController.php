<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Petugas::orderBy('id', 'asc')->paginate(10);
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
        $dataPetugas  = new Petugas;
        $dataUser = new User;


        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'notelpon' => 'required',
            'email' => 'required',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }
        $dataPetugas->nama =  $request->nama;
        $dataPetugas->alamat = $request->alamat;
        $dataPetugas->notelpon  = $request->notelpon;
        $dataPetugas->email = $request->email;
        $dataPetugas->password = bcrypt($request->password);

        $dataUser->name = $request->nama;
        $dataUser->password = bcrypt($request->password);
        $dataUser->email = $request->email;
        $dataUser->role = 'petugas';

        $post = $dataPetugas->save();
        $post2 = $dataUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan data'
        ]);
    }

    public function getAllData()
    {
        $allData = Petugas::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Semua data buku ditemukan',
            'data' => $allData
        ], 200);
    }

    public function show(string $id)
    {
        $data = Petugas::find($id);
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
        $dataPetugas  = Petugas::find($id);
        $dataUser = User::where('email', $request->email)->first();
        if (empty($dataPetugas)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'notelpon' => 'required',
            'email' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate data',
                'data' => $validator->errors()
            ]);
        }
        $dataPetugas->nama =  $request->nama;
        $dataPetugas->alamat = $request->alamat;
        $dataPetugas->notelpon  = $request->notelpon;
        $dataPetugas->email = $request->email;
        if (!empty($request->password)) {
            $dataPetugas->password = bcrypt($request->password);
            $dataUser->password = bcrypt($request->password);
        }
        $dataUser->name = $request->nama;

        $dataUser->email = $request->email;
        $dataUser->role = 'petugas';


        $post = $dataPetugas->save();
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
        $dataPetugas  = Petugas::find($id);
        $email = $dataPetugas->email;
        $dataUser = User::where('email', $email)->first();
        if (empty($dataPetugas)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }


        $post = $dataPetugas->delete();
        $post2 = $dataUser->delete();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil delete data'
        ]);
    }
}
