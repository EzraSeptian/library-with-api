<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    const API_URL = "http://localhost:8000/api/transaksi";
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $current_url =  url()->current();
        $client = new Client();
        $url = static::API_URL;
        if ($request->input('page') != '') {
            $url .= "?page=" . $request->input('page');
        }
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $data = $contentArray['data'];
        $nextId = Transaksi::max('id') + 1;

        $clientPetugas = new Client();
        $urlPetugas = "http://localhost:8000/api/petugass/getAllData";
        $responsePetugas = $clientPetugas->request('GET', $urlPetugas);
        $contentPetugas =  $responsePetugas->getBody()->getContents();
        $contentArrayPetugas = json_decode($contentPetugas, true);

        $dataPetugas = $contentArrayPetugas['data'];

        $clientAnggota = new Client();
        $urlAnggota = "http://localhost:8000/api/anggotas/getAllData";
        $responseAnggota = $clientAnggota->request('GET', $urlAnggota);
        $contentAnggota =  $responseAnggota->getBody()->getContents();
        $contentArrayAnggota = json_decode($contentAnggota, true);

        $dataAnggota = $contentArrayAnggota['data'];

        $clientBuku = new Client();
        $urlBuku = "http://localhost:8000/api/books/getAllData";
        $responseBuku = $clientBuku->request('GET', $urlBuku);
        $contentBuku =  $responseBuku->getBody()->getContents();
        $contentArrayBuku = json_decode($contentBuku, true);
        $dataBuku = $contentArrayBuku['data'];


        foreach ($data['links'] as $key => $value) {
            $data['links'][$key]['url2'] = str_replace(static::API_URL, $current_url, $value['url']);
        }

        return view('transaksi.index', [
            'data' => $data,
            'dataPetugas' => $dataPetugas,
            'dataAnggota' => $dataAnggota,
            'dataBuku' => $dataBuku,
            'nextId' => $nextId,
            'role' => $role,
        ]);
    }

    // Other controller methods...

    public function getDetailTransaksi($idTransaksi)
    {
        $client = new Client();
        $url = "http://localhost:8000/api/detail_transaksi/show?idtransaksi=$idTransaksi";

        try {
            $response = $client->request('GET', $url);
            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            if ($contentArray['status'] == true) {
                return $contentArray['data'];
            }
        } catch (RequestException $e) {
            // Handle error if needed
        }

        return [];
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
        $idanggota  = $request->idanggota;
        $idpetugas = $request->idpetugas;
        $tanggalpinjam = $request->tanggalpinjam;
        $idbuku = $request->idbuku;
        $idtransaksi = $request->idtransaksi;
        $tanggalpinjam = $request->tanggalpinjam;

        // Cek apakah transaksi dengan ID tersebut sudah ada atau belum
        $dataTransaksi = Transaksi::find($idtransaksi);

        if (empty($dataTransaksi)) {
            // Jika belum ada, buat transaksi baru
            $client = new Client();
            $url = "http://localhost:8000/api/transaksi";
            $response = $client->request('POST', $url, [
                'headers' => ['Content-type' => 'application/json'],
                'body' => json_encode([
                    'id' => $idtransaksi,
                    'idanggota' => $idanggota,
                    'idpetugas' => $idpetugas,
                    'tanggalpinjam' => $tanggalpinjam,
                ])
            ]);
            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            if ($contentArray['status'] != true) {
                $error = $contentArray['data'];
                return redirect()->to('transaksi')->withErrors($error)->withInput();
            }
        }

        $buku = Buku::find($idbuku);
        $buku->stok = $buku->stok - 1;
        $parameterdetail = [
            'idtransaksi' => $request->idtransaksi,
            'idbuku' => $request->idbuku
        ];

        $clientdetail = new Client();
        $urldetail = "http://localhost:8000/api/detail_transaksi/store";
        $responsedetail = $clientdetail->request('POST', $urldetail, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameterdetail)
        ]);

        $contentdetail = $responsedetail->getBody()->getContents();
        $contentArraydetail = json_decode($contentdetail, true);

        if ($contentArraydetail['status'] != true) {
            $error = $contentArraydetail['data'];
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan detail transaksi',
                'data' => $error
            ]);
        } else {
            return redirect()->to('transaksi')->with('success', 'Berhasil Menambahkan Data');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = new Client();
        $url = "http://localhost:8000/api/transaksi/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return redirect()->to('transaksi')->withErrors($error);
        } else {
            $data = $contentArray['data'];

            $clientPetugas = new Client();
            $urlPetugas = "http://localhost:8000/api/petugass/getAllData";
            $responsePetugas = $clientPetugas->request('GET', $urlPetugas);
            $contentPetugas =  $responsePetugas->getBody()->getContents();
            $contentArrayPetugas = json_decode($contentPetugas, true);

            $dataPetugas = $contentArrayPetugas['data'];

            $clientAnggota = new Client();
            $urlAnggota = "http://localhost:8000/api/anggotas/getAllData";
            $responseAnggota = $clientAnggota->request('GET', $urlAnggota);
            $contentAnggota =  $responseAnggota->getBody()->getContents();
            $contentArrayAnggota = json_decode($contentAnggota, true);

            $dataAnggota = $contentArrayAnggota['data'];

            $clientBuku = new Client();
            $urlBuku = "http://localhost:8000/api/books/getAllData";
            $responseBuku = $clientBuku->request('GET', $urlBuku);
            $contentBuku =  $responseBuku->getBody()->getContents();
            $contentArrayBuku = json_decode($contentBuku, true);
            $dataBuku = $contentArrayBuku['data'];

            return view('transaksi.index', [
                'data' => $data,
                'dataPetugas' => $dataPetugas,
                'dataAnggota' => $dataAnggota,
                'dataBuku' => $dataBuku
            ]);
        }
    }


    public function update(Request $request, string $id)
    {
        $idanggota  = $request->idanggota;
        $idpetugas = $request->idpetugas;
        $tanggalpinjam = $request->tanggalpinjam;
        $tanggalkembali = $request->tanggalkembali;

        // Cek apakah input password diisi atau tidak

        $parameter = [
            'idanggota' => $idanggota,
            'idpetugas' => $idpetugas,
            'tanggalpinjam' => $tanggalpinjam,
            'tanggalkembali' => $tanggalkembali

        ];


        $client = new Client();
        $url = "http://localhost:8000/api/transaksi/$id";
        $response = $client->request('PUT', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('transaksi')->withErrors($error)->withInput();
        } else {
            return redirect()->to('transaksi')->with('success', 'Berhasil Update Data');
        }

        // ...

    }



    /**
     * Remove the specified resource from storage.
     */


    public function destroy(string $id)
    {
        $client = new Client();
        $url = "http://localhost:8000/api/transaksi/$id";

        $response = $client->request('DELETE', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('transaksi')->withErrors($error)->withInput();
        } else {
            // Hapus semua detail transaksi yang terkait dengan transaksi yang dihapus
            $this->deleteDetailTransaksi($id);

            return redirect()->to('transaksi')->with('success', 'Berhasil Hapus Data');
        }
    }



    private function deleteDetailTransaksi($idTransaksi)
    {
        $client = new Client();
        $url = "http://localhost:8000/api/detail_transaksi?idtransaksi=$idTransaksi";

        try {
            $response = $client->request('DELETE', $url);
            // Handle response jika diperlukan
        } catch (RequestException $e) {
            // Handle kesalahan jika diperlukan
        }
    }
}
