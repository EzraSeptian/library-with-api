<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Buku::orderBy('id', 'asc')->paginate(10);
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
        $databuku  = new Buku;


        $rules = [
            'judul' => 'required',
            'pengarang' => 'required',
            'tanggal_publikasi' => 'required|date',
            'stok' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg,gif',
            'deskripsi' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }
        $img = $request->image;
        $ext = $img->getClientOriginalExtension();
        $imagename = time() . '.' . $ext;
        $img->move(public_path() . '/uploads/', $imagename);

        $databuku->image = $imagename;

        $databuku->judul =  $request->judul;
        $databuku->pengarang = $request->pengarang;
        $databuku->tanggal_publikasi  = $request->tanggal_publikasi;
        $databuku->stok = $request->stok;
        $databuku->deskripsi = $request->deskripsi;


        $post = $databuku->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan data'
        ]);
    }
    public function getAllData()
    {
        $allData = Buku::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Semua data buku ditemukan',
            'data' => $allData
        ], 200);
    }

    public function show($id)
    {
        $data = Buku::find($id);
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
        // Find the book by ID
        $databuku = Buku::find($id);

        // Validate other fields
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'pengarang' => 'required',
            'tanggal_publikasi' => 'required|date',
            'stok' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ]);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);

            // Delete old image
            File::delete(public_path('uploads/' . $databuku->image));

            // Update image field
            $databuku->image = $imageName;
        }

        // Update other fields
        $databuku->judul = $request->judul;
        $databuku->pengarang = $request->pengarang;
        $databuku->tanggal_publikasi = $request->tanggal_publikasi;
        $databuku->stok = $databuku->stok + $request->stok;
        $databuku->deskripsi = $request->deskripsi;

        // Save changes
        $databuku->save();

        return response()->json([
            'status' => true,
            'message' => 'Data updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $databuku  = Buku::find($id);
        if (empty($databuku)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        $imagename = $databuku->image;
        File::delete(public_path(('uploads/' . $imagename)));
        $post = $databuku->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil delete data'
        ]);
    }
}
