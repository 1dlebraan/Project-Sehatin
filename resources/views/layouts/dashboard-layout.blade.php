<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Sehatin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="{{ asset('images/logoapp2.png') }}" alt="Logo" height="100">
            <span class="brand">Sehatin Medical Care</span>
        </div>
        <div class="nav-buttons">
            <a href="#" class="icon-button" title="Profil">
                <img src="{{ asset('images/user.png') }}" alt="Profil" height="24">
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="icon-button" title="Logout">
                    <img src="{{ asset('images/logout.png') }}" alt="Logout" height="24">
                </button>
            </form>
        </div>
    </div>

    <!-- Konten Halaman -->
    <div class="menu-container">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        © SEHATIN
    </footer>

</body>

</html>