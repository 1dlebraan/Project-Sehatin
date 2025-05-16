<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E0F7FB;
            /* biru langit */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #72CAEE;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .content {
            padding: 30px;
        }

        .logout-btn {
            background-color: #72CAEE;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: rgb(250, 250, 250);
        }

        h1 {
            margin: 0;
        }
    </style>
</head>

<body>
    <header>
        <h1>@yield('title', 'Dashboard')</h1>
        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </header>

    <div class="content">
        @yield('content')
    </div>
</body>

</html>