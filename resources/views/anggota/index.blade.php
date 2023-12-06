
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
@include('template.navbar')
<body>
    <section class="home">
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
            <form action='' method='post'>
                @csrf

                @if(Route::current()->uri == 'anggota/{id}')
                @method('put')
                @endif

                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">Nama:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='nama' id="nama" value ="{{ isset($data['nama'])?$data['nama']:old('nama') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">Alamat:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='alamat' id="alamat" value = "{{ isset($data['alamat'])?$data['alamat']:old('alamat') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jurusan" class="col-sm-2 col-form-label text">Jurusan:</label>
                    <div class="col-sm-3">
                        <select name="jurusan" id="jurusan" class="form-select border-0 border-bottom" required>
                            <option selected>--Pilih Jurusan--</option>
                            <option value="D4IT" {{ (isset($data['jurusan']) && $data['jurusan'] == 'D4IT') ? 'selected' : (old('jurusan') == 'D4IT' ? 'selected' : '') }}>D4 Teknik Informatika</option>
                            <option value="D3IT" {{ (isset($data['jurusan']) && $data['jurusan'] == 'D3IT') ? 'selected' : (old('jurusan') == 'D3IT' ? 'selected' : '') }}>D3 Teknik Informatika</option>
                            <option value="D4DS" {{ (isset($data['jurusan']) && $data['jurusan'] == 'D4DS') ? 'selected' : (old('jurusan') == 'D4DS' ? 'selected' : '') }}>D4 Sains Data</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">email:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='email' id="email" value = "{{ isset($data['email'])?$data['email']:old('email') }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label text">Password:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='password' id="password" value = "" placeholder="Biarkan kosong jika tidak ingin mengganti password">
                    </div>
                </div>
                @if(Route::current()->uri == 'anggota/{id}')
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
        </div>
        <!-- AKHIR FORM -->
        @if(Route::current()->uri=='anggota')
        <!-- START DATA -->
        <div class="my-3 p-3 rounded shadow-sm card">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-md-1 text">No</th>
                        <th class="col-md-4 text">Nama</th>
                        <th class="col-md-3 text">Alamat</th>
                        <th class="col-md-2 text">Jurusan</th>
                        <th class="col-md-2 text">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=$data['from'];?>
                    @foreach ($data['data'] as $item)
                    
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$item['nama']}}</td>
                        <td>{{$item['alamat']}}</td>
                        <td>{{$item['jurusan']}}</td>
                        <td>
                            <a href="{{url('anggota/'.$item['id'])}}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{url('anggota/'.$item['id'])}}" method = "post" onsubmit="return confirm('Apakah yakin akan melakukan penghapusan data')" class = "d-inline">
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
        <!-- AKHIR DATA -->
</section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>