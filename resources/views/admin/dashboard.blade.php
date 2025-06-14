@extends('layouts.dashboard-layout')

@section('title', 'Dashboard Admin')

@section('content')
    <style>
        .menu-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-top: 50px;
            flex-wrap: nowrap;
            /* Ubah ke 'wrap' jika ingin responsive */
            overflow-x: auto;
            /* Opsional: scroll jika melebihi layar */
            padding: 10px;
        }

        .menu-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            width: 200px;
            height: 200px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .menu-box:hover {
            background-color: #e9ecef;
            transform: scale(1.03);
        }

        .menu-box img {
            height: 60px;
            margin-bottom: 10px;
        }

        .menu-box p {
            font-size: 16px;
            margin: 0;
        }
    </style>

    <div class="menu-container">
        <a href="{{ route('admin.poli') }}" class="menu-box">
            <img src="{{ asset('images/hospital.png') }}" alt="Poli">
            <p>Poli</p>
        </a>

        <a href="{{ route('admin.petugas') }}" class="menu-box">
            <img src="{{ asset('images/petugas.png') }}" alt="Petugas">
            <p>Petugas</p>
        </a>

        <a href="{{ route('admin.laporan') }}" class="menu-box">
            <img src="{{ asset('images/laporan.png') }}" alt="Laporan Statistik">
            <p>Laporan Statistik</p>
        </a>

        <a href="{{ route('admin.konfigurasi') }}" class="menu-box">
            <img src="{{ asset('images/konfigurasi.png') }}" alt="Konfigurasi Umum">
            <p>Konfigurasi Umum</p>
        </a>
    </div>
@endsection