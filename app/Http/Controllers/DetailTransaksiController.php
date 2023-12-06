<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idtransaksi = $request->idtransaksi;
        $idbuku = $request->idbuku;

        $parameter = [
            'idtransaksi' => $idtransaksi,
            'idbuku' => $idbuku,
        ];

        $buku = Buku::find($idbuku);
        $buku->stok = $buku->stok - 1;
        $client = new Client();
        $url = "http://localhost:8000/api/detail_transaksi/store";
        $response = $client->request('POST', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('detail_transaksi/' . $idtransaksi)->withErrors($error)->withInput();
        } else {
            return redirect()->to('detail_transaksi/' . $idtransaksi)->with('success', 'Berhasil Memasukkan Data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Auth::user()->role;
        $client = new Client();
        $transaksi = Transaksi::find($id);
        $url = "http://localhost:8000/api/detail_transaksi/show?idtransaksi=$id";
        $response = $client->request('GET', $url);
        $content =  $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        $data = $contentArray['data'];
        $idtransaksi = $contentArray['idtransaksi'];

        $clientBuku = new Client();
        $urlBuku = "http://localhost:8000/api/books/getAllData";
        $responseBuku = $clientBuku->request('GET', $urlBuku);
        $contentBuku =  $responseBuku->getBody()->getContents();
        $contentArrayBuku = json_decode($contentBuku, true);
        $dataBuku = $contentArrayBuku['data'];

        return view('detail_transaksi.index', [
            'data' => $data,
            'transaksi' => $transaksi,
            'idtransaksi' => $idtransaksi,
            'dataBuku' => $dataBuku,
            'role' => $role
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
