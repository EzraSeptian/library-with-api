<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Login</title>
    @vite(['resources/css/login.css']);
</head>
<body>
    <section class="home">
        <div class="form_container">
            <div class="form login_form">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $item)
                            <li>{{$item}}</li>
                        @endforeach
                    </ul>
                </div>
                    @endif
                <form action="" method="POST">
                    @csrf
                    <h2>Login</h2>
                <div class="input-box">
                    <input type="email" placeholder="Isi email" name="email" required>
                    <i class="uil uil-envelope-alt email"></i>
                </div>
                    <div class="input-box">
                    <input type="password" placeholder="Isi password" name="password" required>
                    <i class="uil uil-lock password"></i>
                 </div>
                    <button name="submit" type="submit" class="button">Login</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>