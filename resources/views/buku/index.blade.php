
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
@include('template.navbar')
<body>
    
    <section class="home">
        @if(Auth::user()->role == 'petugas')
        <!-- START FORM -->
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
            
            <!-- ... (your HTML code) ... -->

<form action="" method="post" enctype="multipart/form-data">
    @csrf
    @if(Route::current()->uri == 'buku/{id}')
        @method('put')
    @endif
    <div class="mb-3 row">
        <label for="judul" class="col-sm-2 col-form-label text">Judul Buku</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name='judul' id="judul"
                value="{{ old('judul', isset($data['judul']) ? $data['judul'] : '') }}">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="pengarang" class="col-sm-2 col-form-label text">Pengarang</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name='pengarang' id="pengarang"
                value="{{ old('pengarang', isset($data['pengarang']) ? $data['pengarang'] : '') }}">
        </div>
    </div>
    @if(Route::current()->uri == 'buku/{id}')
    <div class="mb-3 row">
        <label for="stok" class="col-sm-2 col-form-label text">Tambah Stok</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name='stok' id="stok"
                value= "0">
        </div>
    </div>
    @else
    <div class="mb-3 row">
        <label for="stok" class="col-sm-2 col-form-label text">Stok</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name='stok' id="stok"
                value= "0">
        </div>
    </div>
    @endif
    <div class="mb-3 row">
        <label for="deskripsi" class="col-sm-2 col-form-label text">Deskripsi</label>
        <div class="col-sm-10">
            <textarea name="deskripsi">{{ old('deskripsi', isset($data['deskripsi']) ? $data['deskripsi'] : '') }}</textarea>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="image" class="col-sm-2 col-form-label text">Image:</label>
        <div class="col-sm-10">
            @if(isset($data['image']))
            <p>Existing Image: {{ $data['image'] }}</p>
            @endif
            <input type="file" name="image" accept="image/*">
            
        </div>
    </div>

    <div class="mb-3 row">
        <label for="tanggal_publikasi" class="col-sm-2 col-form-label text">Tanggal Publikasi</label>
        <div class="col-sm-10">
            <input type="date" class="form-control w-50" name='tanggal_publikasi' id="tanggal_publikasi"
                value="{{ old('tanggal_publikasi', isset($data['tanggal_publikasi']) ? $data['tanggal_publikasi'] : '') }}">
        </div>
    </div>
    @if(Route::current()->uri == 'buku/{id}')
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

<!-- ... (your HTML code) ... -->

        </div>
        <!-- AKHIR FORM -->
        @if(Route::current()->uri == 'buku' || Route::current()->uri == '')
        <!-- START DATA -->
        <div class="my-3 p-3 rounded shadow-sm card">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-md-1 text">No</th>
                        <th class="col-md-3 text">Judul</th>
                        <th class="col-md-2 text">Pengarang</th>
                        <th class="col-md-2 text">Tanggal Publikasi</th>
                        <th class="col-md-2 text">Stok</th>
                        <th class="col-md-2 text">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=$data['from'];?>
                    @foreach ($data['data'] as $item)
                    
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$item['judul']}}</td>
                        <td>{{$item['pengarang']}}</td>
                        <td>{{ date('d/m/Y',strtotime($item['tanggal_publikasi']))}}</td>
                        <td>{{$item['stok']}}</td>
                        <td>
                            <a href="{{url('buku/'.$item['id'])}}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{url('buku/'.$item['id'])}}" method = "post" onsubmit="return confirm('Apakah yakin akan melakukan penghapusan data')" class = "d-inline">
                            @csrf
                            @method('delete')

                            <button type= 'submit' name="submit" class = "btn btn-danger btn-sm">Del</button>
                        </td>
                    </tr>
                    <?php $i++; ?>
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
        <div class="d-flex flex-row flex-wrap space-between">
            @foreach ($data['data'] as $item)
                @if($item['stok'] != 0)
                <div class="my-2 ps-2 col-3 mx-md-1 col-rounded shadow-sm card mb-3 mx-auto" style="width: calc(33.33% - 83.33px);">
                        <img src="{{asset('uploads/'.$item['image'])}}" alt="" width="250px"  class="my-1 d-block mx-auto justify-content-center rounded">
                        <label for="judul" class="col-form-label text">Judul Buku:  {{$item['judul']}}</label>
                        <label for="pengarang" class="col-form-label text">Pengarang:  {{$item['pengarang']}}</label>
                        <label for="tanggal_publikasi" class="col-form-label text">Tanggal Publikasi:  {{ date('d/m/Y',strtotime($item['tanggal_publikasi']))}}</label>
                        <label for="tentang" class="col-form-label text">Tentang:  <br>{{$item['deskripsi']}}</label>
                    </div>
                @endif
            @endforeach
        </div>        
        
        @endif
</section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>