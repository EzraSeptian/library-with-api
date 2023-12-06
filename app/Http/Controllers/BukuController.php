<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BukuController extends Controller
{
    const API_URL = "http://localhost:8000/api/buku";
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
        return view('buku.index', [
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
        $judul = $request->judul;
        $pengarang = $request->pengarang;
        $tanggal_publikasi = $request->tanggal_publikasi;
        $stok = $request->stok;
        $deskripsi = $request->deskripsi;

        $img = $request->file('image');
        $ext = $img->getClientOriginalExtension();
        $imagename = time() . '.' . $ext;
        $img->move(public_path() . '/uploads/', $imagename);

        $client = new Client();
        $url = "http://localhost:8000/api/buku";

        // Use multipart/form-data encoding for file upload
        $response = $client->request('POST', $url, [
            'multipart' => [
                [
                    'name' => 'judul',
                    'contents' => $judul,
                ],
                [
                    'name' => 'pengarang',
                    'contents' => $pengarang,
                ],
                [
                    'name' => 'tanggal_publikasi',
                    'contents' => $tanggal_publikasi,
                ],
                [
                    'name' => 'stok',
                    'contents' => $stok,
                ],
                [
                    'name' => 'image',
                    'contents' => fopen(public_path() . '/uploads/' . $imagename, 'r'),
                ],
                [
                    'name' => 'deskripsi',
                    'contents' => $deskripsi,
                ],
            ],
        ]);

        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        } else {
            return redirect()->to('buku')->with('success', 'Berhasil Memasukkan Data');
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
        $url = "http://localhost:8000/api/buku/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return redirect()->to('buku')->withErrors($error);
        } else {
            $data = $contentArray['data'];
            return view('buku.index', ['data' => $data]);
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

        return redirect()->to('buku')->with('success', 'Berhasil Update Data');
    }
    public function destroy(string $id)
    {

        $client = new Client();
        $url = "http://localhost:8000/api/buku/$id";
        $response = $client->request('DELETE', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        } else {
            return redirect()->to('buku')->with('success', 'Berhasil Hapus Data');
        }
    }
}
