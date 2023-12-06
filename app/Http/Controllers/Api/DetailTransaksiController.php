<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Petugas;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DetailTransaksi::orderBy('idtransaksi', 'asc')->get();
        $DetailTransaksiData = [];

        foreach ($data as $DetailTransaksi) {
            $transaksi = Transaksi::find($DetailTransaksi->idtransaksi);
            $buku = Buku::find($DetailTransaksi->idbuku);
            $DetailTransaksiData[] = [
                'DetailTransaksi' => $DetailTransaksi,
                'Transaksi' => $transaksi,
                'Buku' => $buku,
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $DetailTransaksiData,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataDetailTransaksi  = new DetailTransaksi;

        $idtransaksi = Transaksi::find($request->idtransaksi);
        $idbuku = Buku::find($request->idbuku);
        $rules = [
            'idtransaksi' => 'required',
            'idbuku' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }
        if (empty($idtransaksi)) {
            return response()->json([
                'status' => false,
                'message' => 'ID Anggota tidak ditemukan'
            ]);
        }
        if (empty($idbuku)) {
            return response()->json([
                'status' => false,
                'message' => 'ID Buku tidak ditemukan'
            ]);
        }
        $idbuku->stok = $idbuku->stok - 1;
        $idbuku->save();
        $dataDetailTransaksi->idtransaksi =  $request->idtransaksi;
        $dataDetailTransaksi->idbuku = $request->idbuku;

        $post = $dataDetailTransaksi->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan data'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $idtransaksi = $request->idtransaksi;

        $data = DetailTransaksi::where('idtransaksi', $idtransaksi)->with(['transaksi', 'buku'])->get();
        $DetailTransaksiData = [];

        foreach ($data as $DetailTransaksi) {
            $DetailTransaksiData['Buku'][] = $DetailTransaksi->buku; // Menambahkan informasi buku ke dalam array Buku
        }

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'idtransaksi' => $idtransaksi,
                'data' => $DetailTransaksiData
            ], 200);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idtransaksi, $idbuku)
    {

        $detailTransaksi = DetailTransaksi::where('idtransaksi', $idtransaksi)->where('idbuku', $idbuku);
        if (!$detailTransaksi) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $detailTransaksi->update($request->all());

        return response()->json(['message' => 'Data berhasil diperbarui'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    // DetailTransaksiController.php

    public function destroy($idtransaksi)
    {
        $detailTransaksi = DetailTransaksi::where('idtransaksi', $idtransaksi);
        $detailTransaksi->delete();

        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}
