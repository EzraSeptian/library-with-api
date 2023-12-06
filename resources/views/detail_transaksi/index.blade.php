<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

@include('template.navbar')

<body>
    <section class="home">
        @if(Auth::user()->role == 'petugas')
        <div class="my-3 p-3 rounded shadow-sm card">
            @if ($errors->any())
            
                <div class = "alert alert-danger">
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
            
            <form action='{{ url('detail_transaksi/' . $idtransaksi) }}' method='post'>
                @csrf


                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">ID Transaksi:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='idtransaksi' id="idtransaksi" value ="{{ $transaksi['id'] }}" readonly>
                    </div>
                </div>
                @if(empty($transaksi['tanggalkembali']))
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">ID Buku:</label>
                    <div class="col-sm-3">
                        <select name="idbuku" id="idbuku" class="form-select border-0 border-bottom" required>
                            <option selected>--Pilih Buku--</option>
                            @foreach ($dataBuku instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataBuku->items() : $dataBuku as $buku)
                                @if (!in_array($buku['id'], array_column($data['Buku'], 'id')) && $buku['stok']!=0)
                                    <option value="{{ $buku['id'] }}" {{ (old('idbuku') == $buku['id']) ? 'selected' : '' }}>
                                        {{ $buku['id'] }}--{{ $buku['judul'] }}-- Stok: {{$buku['stok']}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10 button"><button type="submit" class="btn btn-success" name="submit">SIMPAN</button>
                    </div>
                </div>
                @endif
            </form>
            
        </div>
        @endif
        <div class="my-3 p-3 rounded shadow-sm card">
            <a href="{{url('transaksi')}}" class="btn btn-primary float-end col-1">Kembali</a>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-md-1 text">No</th>
                        <th class="col-md-4 text">Judul</th>
                        <th class="col-md-3 text">Pengarang</th>
                        <th class="col-md-2 text">Tanggal Publikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($data['Buku'] as $buku)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$buku['judul']}}</td>
                            <td>{{$buku['pengarang']}}</td>
                            <td>{{ date('d/m/Y', strtotime($buku['tanggal_publikasi'])) }}</td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- AKHIR DATA -->
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
