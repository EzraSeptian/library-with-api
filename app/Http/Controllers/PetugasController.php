<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    const API_URL = "http://localhost:8000/api/petugas";
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
        foreach ($data['links'] as $key => $value) {
            $data['links'][$key]['url2'] = str_replace(static::API_URL, $current_url, $value['url']);
        }
        return view('petugas.index', [
            'data' => $data,
            'role' => $role
        ]);
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
        $nama  = $request->nama;
        $alamat = $request->alamat;
        $notelpon = $request->notelpon;
        $email = $request->email;
        $password = $request->password;

        $parameter = [
            'nama' => $nama,
            'alamat' => $alamat,
            'notelpon' => $notelpon,
            'email' => $email,
            'password' => $password,
        ];


        $client = new Client();
        $url = "http://localhost:8000/api/petugas";
        $response = $client->request('POST', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('petugas')->withErrors($error)->withInput();
        } else {
            return redirect()->to('petugas')->with('success', 'Berhasil Memasukkan Data');
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
        $url = "http://localhost:8000/api/petugas/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return redirect()->to('petugas')->withErrors($error);
        } else {
            $data = $contentArray['data'];
            return view('petugas.index', ['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $nama = $request->nama;
        $alamat = $request->alamat;
        $notelpon = $request->notelpon;
        $email = $request->email;
        $password = $request->password;
        $dataUser = User::where('email', $email)->first();

        // Cek apakah input password diisi atau tidak
        if (!empty($password)) {
            // Jika diisi, tambahkan password ke parameter
            $parameter = [
                'nama' => $nama,
                'alamat' => $alamat,
                'notelpon' => $notelpon,
                'email' => $email,
                'password' => bcrypt($password), // Use bcrypt to hash the password
            ];
        } else {
            // Jika password tidak diisi, buat parameter tanpa password
            $parameter = [
                'nama' => $nama,
                'alamat' => $alamat,
                'notelpon' => $notelpon,
                'email' => $email,
            ];
        }

        $client = new Client();
        $url = "http://localhost:8000/api/petugas/$id";
        $response = $client->request('PUT', $url, [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);

        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('petugas')->withErrors($error)->withInput();
        } else {
            // If the password is updated, update it in the local User model as well
            if (!empty($password)) {
                $dataUser->password = bcrypt($password);
                $dataUser->save();
            }

            return redirect()->to('petugas')->with('success', 'Berhasil Update Data');
        }
    }



    /**
     * Remove the specified resource from storage.
     */


    public function destroy(string $id)
    {
        $client = new Client();
        $url = "http://localhost:8000/api/petugas/$id";

        try {
            $response = $client->request('DELETE', $url);
            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            if ($contentArray['status'] != true) {
                $error = $contentArray['data'];
                return redirect()->to('petugas')->withErrors($error)->withInput();
            } else {
                return redirect()->to('petugas')->with('success', 'Berhasil Hapus Data');
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();

                // Handle 409 Conflict (Integrity Constraint Violation)
                if ($statusCode == 409) {
                    $error = "Data petugas tidak dapat dihapus karena terkait dengan transaksi.";
                    return redirect()->to('petugas')->withErrors($error)->withInput();
                } else {
                    // Handle other exceptions if needed
                    $error = "Terjadi kesalahan saat menghapus data petugas dikarenakan petugas sedang memiliki transaksi ";
                    return redirect()->to('petugas')->withErrors($error)->withInput();
                }
            } else {
                // Handle other exceptions without a response
                $error = "Terjadi kesalahan saat menghapus data petugas dikarenakan petugas sedang memiliki transaksi " . $e->getMessage();
                return redirect()->to('petugas')->withErrors($error)->withInput();
            }
        }
    }
}
