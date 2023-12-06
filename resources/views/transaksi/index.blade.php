<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

@include('template.navbar')
<body>

    <section class="home">
        @if(Auth::user()->role == 'petugas')
        <!-- START FORM -->
        <div class="my-3 p-3 rounded shadow-sm card">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $item)
                            <li>{{$item}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @endif

            <form action='' method='post'>
                @csrf

                @if(Route::current()->uri == 'transaksi/{id}')
                    @method('put')
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label text">ID Transaksi:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='idtransaksi' id="idtransaksi" value="{{ $data['transaksi']['id'] }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label text">ID Petugas:</label>
                        <div class="col-sm-3">
                            <select name="idpetugas" id="idpetugas" class="form-select border-0 border-bottom" required>
                                <option selected>--Pilih Petugas--</option>
                                @foreach ($dataPetugas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataPetugas->items() : $dataPetugas as $Petugas)
                                <option value="{{ $Petugas['id'] }}" {{ (isset($data['transaksi']['idpetugas']) && $data['transaksi']['idpetugas'] == $Petugas['id']) ? 'selected' : (old('idpetugas') == $Petugas['id'] ? 'selected' : '') }}>
                                    {{ $Petugas['id'] }}--{{ $Petugas['nama'] }}
                                </option>
                            @endforeach
                            
                            </select>
                        </div>
                    </div>
                    <!-- ... (other dropdowns) ... -->
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label text">ID Anggota:</label>
                        <div class="col-sm-3">
                            <select name="idanggota" id="idanggota" class="form-select border-0 border-bottom" required>
                                <option selected>--Pilih Anggota--</option>
                                @foreach ($dataAnggota instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataAnggota->items() : $dataAnggota as $Anggota)
    <option value="{{ $Anggota['id'] }}" {{ (isset($data['transaksi']['idanggota']) && $data['transaksi']['idanggota'] == $Anggota['id']) ? 'selected' : (old('idanggota') == $Anggota['id'] ? 'selected' : '') }}>
        {{ $Anggota['id'] }}--{{ $Anggota['nama'] }}
    </option>
@endforeach
                            
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tanggal_publikasi" class="col-sm-2 col-form-label text">Tanggal Pinjam</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control w-50" name='tanggalpinjam' id="tanggalpinjam" value="{{ $data['transaksi']['tanggalpinjam'] }}">
                        </div>
                    </div>
                    <!-- Other form fields... -->
                    
                    <div class="mb-3 row">
                        <label for="tanggalkembali" class="col-sm-2 col-form-label text">Tanggal Kembali</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control w-50" name='tanggalkembali' id="tanggalkembali" value ="{{ $data['transaksi']['tanggalkembali'] }}">
                        </div>
                    </div>
    
    
                    <!-- Other form fields... -->
    
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10 button">
                            <button type="submit" class="btn btn-primary" name="submit">SIMPAN</button>
                        </div>
                    </div>
                </form>
                @endif
                
                @if(Route::current()->uri == 'transaksi')
                
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">ID Transaksi:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='idtransaksi' id="idtransaksi" value="{{$nextId}}" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">ID Petugas:</label>
                    <div class="col-sm-3">
                        <select name="idpetugas" id="idpetugas" class="form-select border-0 border-bottom" required>
                            <option selected>--Pilih Petugas--</option>
                            @foreach ($dataPetugas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataPetugas->items() : $dataPetugas as $Petugas)
    <option value="{{ $Petugas['id'] }}" {{ (isset($data['transaksi']['idpetugas']) && $data['transaksi']['idpetugas'] == $Petugas['id']) ? 'selected' : (old('idpetugas') == $Petugas['id'] ? 'selected' : '') }}>
        {{ $Petugas['id'] }}--{{ $Petugas['nama'] }}
    </option>
@endforeach

                        </select>
                    </div>
                </div>
                
                <!-- ... (other dropdowns) ... -->
                
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">ID Anggota:</label>
                    <div class="col-sm-3">
                        <select name="idanggota" id="idanggota" class="form-select border-0 border-bottom" required>
                            <option selected>--Pilih Anggota--</option>
                            @foreach ($dataAnggota instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataAnggota->items() : $dataAnggota as $Anggota)
    <option value="{{ $Anggota['id'] }}" {{ (isset($data['transaksi']['idanggota']) && $data['transaksi']['idanggota'] == $Anggota['id']) ? 'selected' : (old('idanggota') == $Anggota['id'] ? 'selected' : '') }}>
        {{ $Anggota['id'] }}--{{ $Anggota['nama'] }}
    </option>
@endforeach

                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">ID Buku:</label>
                    <div class="col-sm-3">
                        <select name="idbuku" id="idbuku" class="form-select border-0 border-bottom" required>
                            <option selected>--Pilih Buku--</option>
                            @foreach ($dataBuku instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataBuku->items() : $dataBuku as $buku)
                            @if($buku['stok']!=0)
    <option value="{{ $buku['id'] }}" {{ (isset($data['transaksi']['idbuku']) && $data['transaksi']['idbuku'] == $buku['id']) ? 'selected' : (old('idbuku') == $buku['id'] ? 'selected' : '') }}>
        {{ $buku['id'] }}--{{ $buku['judul'] }}  Stok:{{$buku['stok']}}
    </option>
    @endif
@endforeach

                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tanggal_publikasi" class="col-sm-2 col-form-label text">Tanggal Pinjam</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control w-50" name='tanggalpinjam' id="tanggalpinjam" value ="{{ isset($data['tanggalpinjam'])?$data['tanggalpinjam']:old('tanggalpinjam') }}">
                    </div>
                </div>


                <!-- Other form fields... -->

                @if(Route::current()->uri == 'transaksi/{id}')
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10 button">
            <button type="submit" class="btn btn-warning" name="submit">EDIT</button>
        </div>
    </div>
    @else
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10 button">
            <button type="submit" class="btn btn-success" name="submit">SIMPAN</button>
        </div>
    </div>
    @endif
            </form>
                @endif
                
        </div>
        <!-- AKHIR FORM -->

        @if(Route::current()->uri=='transaksi')
            <!-- START DATA -->
            <div class="my-3 p-3 rounded shadow-sm card">
                <table class="table">
                    <thead>
                        <tr align="center">
                            <th class="col-md-1 text">ID Transaksi</th>
                            <th class="col-md-2 text">Nama Anggota</th>
                            <th class="col-md-2 text">Nama Petugas</th>
                            <th class="col-md-1 text">Tanggal Pinjam</th>
                            <th class="col-md-2 text">Tanggal Kembali</th>
                            <th class="col-md-1 text">Denda</th>
                            <th class="col-md-2 text">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['data'] as $item)
                            <tr align="center">
                                <td>{{$item['id']}}</td>
                                <td>{{$item['anggota']['nama']}}</td>
                                <td>{{$item['petugas']['nama']}}</td>
                                <td>{{ date('d/m/Y',strtotime($item['tanggalpinjam']))}}</td>
                                @if(!empty($item['tanggalkembali']))
                                <td>{{ date('d/m/Y',strtotime($item['tanggalkembali']))}}</td>
                                @else
                                <td>Belum Kembali</td>
                                @endif
                                <td>{{$item['denda']}}</td>
                                <td>
                                    <a href="{{url('transaksi/'.$item['id'])}}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{url('transaksi/'.$item['id'])}}" method="post" onsubmit="return confirm('Apakah yakin akan melakukan penghapusan data')" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type='submit' name="submit" class="btn btn-danger btn-sm">Del</button>
                                      
                                        <a href="{{url('detail_transaksi/'.$item['id'])}}" class="btn btn-success btn-sm">Detail</a>
                                        

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($data['links'])
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        @foreach($data['links'] as $item)
                      <li class="page-item {{$item['active']?'active':''}}"><a class="page-link" href="{{$item['url2']}}">{!! $item['label']!!}</a></li>
                      @endforeach
                    </ul>
                  </nav>
                @endif
            </div>
           
        @endif
        @else
        <div class="my-3 p-3 rounded shadow-sm card">
        <table class="table">
            <thead>
                <tr align="center">
                    <th class="col-md-1 text">ID Transaksi</th>
                    <th class="col-md-2 text">Nama Anggota</th>
                    <th class="col-md-2 text">Nama Petugas</th>
                    <th class="col-md-2 text">Tanggal Pinjam</th>
                    <th class="col-md-2 text">Tanggal Kembali</th>
                    <th class="col-md-2 text">Denda</th>
                    <th class="col-md-1 text">Aksi</th>
                </tr>
            </thead>
            <tbody>
                
               
                @foreach ($data['data'] as $item)
                @if($item['anggota']['email'] == Auth::user()->email)
                    <tr align="center">
                        <td>{{$item['id']}}</td>
                        <td>{{$item['anggota']['nama']}}</td>
                        <td>{{$item['petugas']['nama']}}</td>
                        <td>{{ date('d/m/Y',strtotime($item['tanggalpinjam']))}}</td>
                        @if(!empty($item['tanggalkembali']))
                        <td>{{ date('d/m/Y',strtotime($item['tanggalkembali']))}}</td>
                        @else
                        <td>Belum Kembali</td>
                        @endif
                        <td>{{$item['denda']}}</td>
                        <td><a href="{{url('detail_transaksi/'.$item['id'])}}" class="btn btn-success btn-sm">Detail</a></td>
                    </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
