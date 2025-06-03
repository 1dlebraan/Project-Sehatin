<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SEHATIN Admin')</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#e3f9ff] font-sans">

    <!-- Header -->
    <div class="bg-[#ade0f6] flex justify-between items-center px-8 py-5 rounded-b-2xl shadow-md">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/icons/logo.png') }}" alt="Logo" class="h-10">
            <span class="text-2xl text-[#2791c9] font-bold">sehatin</span>
        </div>
        <div class="flex gap-4">
            <button class="bg-white p-2 rounded-full shadow hover:scale-110 transition">
                <img src="{{ asset('assets/icons/profile.png') }}" alt="Profile" class="h-6">
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-white p-2 rounded-full shadow hover:scale-110 transition">
                    <img src="{{ asset('assets/icons/logout.png') }}" alt="Logout" class="h-6">
                </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="py-20">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center text-[#00aaff] font-bold mt-20 py-6">
        © SEHATIN
    </footer>

</body>

</html>