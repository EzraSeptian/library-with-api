<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        @vite(['resources/js/app.js'])
</head>

<body>
    {{$role = Auth::user()->role;}}
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <i class="bx bx-book icon"></i>
                </span>
                <div class="text header-text">
                    <span class="name">Perpustakaan</span>
                </div>
            </div>
            <i class="bx bx-chevron-right toggle"></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                    <li class="nav-link">
                        <a href="http://localhost:8001/buku">
                            <i class="bx bx-library icon"></i>
                            <span class="text nav-text"> Buku</span>
                        </a>
                    </li>
                    @if($role == 'petugas')
                    <li class="nav-link">
                        <a href="http://localhost:8001/anggota">
                            <i class="bx bx-group icon"></i>
                            <span class="text nav-text"> Anggota</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="http://localhost:8001/petugas">
                            <i class="fa-regular fa-user icon"></i>
                            <span class="text nav-text"> Petugas</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-link">
                        <a href="http://localhost:8001/transaksi">
                            <i class="bx bx-receipt icon"></i>
                            <span class="text nav-text"> Transaksi</span>
                        </a>
                    </li>
            </div>
            <div class="bottom-content">
                <li class="">
                    <a href="http://localhost:8001/logout">
                        <i class="bx bx-log-out icon"></i>
                        <span class="text nav-text"> Logout</span>
                    </a>
                </li>
                <li class="mode">
                    <div class="moon-sun">
                        <i class="bx bx-moon icon moon"></i>
                        <i class="bx bx-sun icon sun"></i>
                    </div>
                    <span class="mode-text text">Dark Mode</span>
                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
        </div>
    </nav>
</body>

</html>