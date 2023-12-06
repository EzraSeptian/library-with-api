<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\DetailTransaksi;
use App\Models\Petugas;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaksi::with('anggota', 'petugas')->orderBy('id', 'asc')->paginate(10);

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
        $dataTransaksi = new Transaksi;
        $idanggota = Anggota::find($request->idanggota);
        $idpetugas = Petugas::find($request->idpetugas);
        $rules = [
            'idanggota' => 'required',
            'idpetugas' => 'required',
            'tanggalpinjam' => 'required|date',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }
        if (empty($idanggota)) {
            return response()->json([
                'status' => false,
                'message' => 'ID Anggota tidak ditemukan'
            ]);
        }
        if (empty($idpetugas)) {
            return response()->json([
                'status' => false,
                'message' => 'ID Petugas tidak ditemukan'
            ]);
        }

        $dataTransaksi->id = $request->idtransaksi;
        $dataTransaksi->idanggota = $request->idanggota;
        $dataTransaksi->idpetugas = $request->idpetugas;
        $dataTransaksi->tanggalpinjam = $request->tanggalpinjam;
        $dataTransaksi->tanggalkembali = null;
        $dataTransaksi->denda = 0;

        $post = $dataTransaksi->save();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan data'
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Transaksi::find($id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        $anggota = Anggota::find($data->idanggota);
        $petugas = Petugas::find($data->idpetugas);
        $transaksiData = [
            'transaksi' => $data,
            'anggota' => $anggota,
            'petugas' => $petugas,
        ];
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $transaksiData
        ], 200);
    }


    public function update(Request $request, string $id)
    {
        // Mendapatkan data transaksi
        $dataTransaksi  = Transaksi::find($id);

        if (empty($dataTransaksi)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // Validasi input
        $rules = [
            'idanggota' => 'required',
            'idpetugas' => 'required',
            'tanggalpinjam' => 'required|date',
            'tanggalkembali' => 'required|date'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate data',
                'data' => $validator->errors()
            ]);
        }


        $idanggota = Anggota::find($request->idanggota);
        $idpetugas = Petugas::find($request->idpetugas);

        if (empty($idanggota) || empty($idpetugas)) {
            return response()->json([
                'status' => false,
                'message' => 'ID Anggota atau ID Petugas tidak ditemukan'
            ]);
        }

        $i = 0;
        $dataTransaksi->idanggota =  $request->idanggota;
        $dataTransaksi->idpetugas = $request->idpetugas;
        $dataTransaksi->tanggalpinjam  = $request->tanggalpinjam;
        $dataTransaksi->tanggalkembali = $request->tanggalkembali;


        // Mendapatkan semua detail transaksi dengan idtransaksi tertentu
        $detailTransaksi = DetailTransaksi::where('idtransaksi', $id)->get();

        // Lakukan operasi lainnya pada $detailTransaksi jika diperlukan
        foreach ($detailTransaksi as $detail) {
            // Mendapatkan data buku
            $buku = Buku::find($detail->idbuku);

            $i++;
            $buku->stok = $buku->stok + 1; // Sesuaikan dengan kebutuhan logika bisnis Anda
            $buku->save();
        }
        $tanggalPinjam = Carbon::parse($request->tanggalpinjam);
        $tanggalKembali = Carbon::parse($request->tanggalkembali);

        $selisihHari = $tanggalKembali->diffInDays($tanggalPinjam);
        $denda = max(0, $selisihHari - 3) * 2000 * $i; // Misalnya, denda per hari adalah 1000

        $dataTransaksi->denda = $denda;
        $post = $dataTransaksi->save();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengupdate data',
            'dataTransaksi' => $dataTransaksi,
            'detailTransaksi' => $detailTransaksi
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->deleteDetailTransaksi($id);

        // Delete the main transaksi record
        $dataTransaksi = Transaksi::find($id);

        if (empty($dataTransaksi)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $dataTransaksi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil delete data'
        ]);
    }
    public function deleteDetailTransaksi($idtransaksi)
    {
        $detailTransaksi = DetailTransaksi::where('idtransaksi', $idtransaksi);
        $detailTransaksi->delete();

        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}
