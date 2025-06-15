<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Sehatin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="{{ asset('images/logoapp2.png') }}" alt="Logo" height="100">
            <span class="brand">Sehatin Medical Care</span>
        </div>
        <div class="nav-buttons">
            <!-- <a href="#" class="icon-button" title="Profil">
                <img src="{{ asset('images/user.png') }}" alt="Profil" height="24">
            </a> -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>