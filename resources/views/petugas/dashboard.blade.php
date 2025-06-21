@extends('layouts.dashboard-layout')

@section('title', 'Dashboard Petugas')

@section('content')
    <style>
        body {
            background-color: #e6f7fa;
        }

        .menu-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            margin-top: 60px;
            flex-wrap: wrap;
            padding: 10px;
        }

        .menu-box {
            background-color: white;
            border-radius: 16px;
            text-align: center;
            padding: 30px 20px;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            width: 180px;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .menu-box:hover {
            transform: scale(1.05);
            background-color: #f0faff;
        }

        .menu-box img {
            height: 64px;
            margin-bottom: 12px;
        }

        .menu-box p {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        footer {
            text-align: center;
            color: #1583b5;
            font-weight: bold;
            margin-top: 60px;
        }
    </style>

    <div class="menu-container">
        <a href="{{ route('petugas.daftar_pasien') }}" class="menu-box">
            <img src="{{ asset('images/daftar_pasien.png') }}" alt="Daftar Pasien">
            <p>Daftar Pasien</p>
        </a>


        <a href="{{ route('petugas.monitor_efisiensi') }}" class="menu-box">
            <img src="{{ asset('images/medical-record.png') }}" alt="Monitor Efisiensi"> {{-- Anda perlu menempatkan
            gambar ini di public/images/ --}}
            <p>Monitor Efisiensi</p>
        </a>

    </div>


@endsection