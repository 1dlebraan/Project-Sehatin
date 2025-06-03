<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sehatin</title>
    <!-- Import font Inter dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #E0F7FB;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-box {
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-box img {
            max-width: 100px;
            margin-bottom: 1rem;
        }

        .login-box h2 {
            margin-bottom: 1.5rem;
            color: #2C5871;
        }

        label {
            display: block;
            margin-bottom: 0.3rem;
            color: #2C5871;
            font-weight: 500;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            /* dibulatkan lebih halus */
            border: none;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
            color: #2C5871;
        }

        button {
            padding: 0.7rem 2rem;
            /* menyesuaikan teks */
            background: #72CAEE;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background: #005377;
        }

        .error {
            color: white;
            background-color: #c0392b;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <img src="{{ asset('images/logoapp2.png') }}" alt="Logo Rumah Sakit" width="250px" height="100px">
        <h2>Log in</h2>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <label for="email"></label>
            <input type="email" name="email" id="email" required placeholder="Email">

            <label for="password"></label>
            <input type="password" name="password" id="password" required placeholder="Password">

            <button type="submit">Log in</button>
        </form>
    </div>

</body>

</html>