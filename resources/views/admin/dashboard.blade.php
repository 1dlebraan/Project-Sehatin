@extends('layouts.admin-layout')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Poli -->
    <a href="{{ route('admin.poli') }}" class="menu-box">
        <img src="{{ asset('images/hospital.png') }}" alt="Poli" height="60">
        <p>Poli</p>
    </a>

    <!-- Petugas -->
    <a href="{{ route('admin.petugas') }}" class="menu-box">
        <img src="{{ asset('images/petugas.png') }}" alt="Petugas" height="60">
        <p>Petugas</p>
    </a>

    <!-- Laporan Statistik -->
    <a href="{{ route('admin.laporan') }}" class="menu-box">
        <img src="{{ asset('images/laporan.png') }}" alt="Laporan" height="60">
        <p>Laporan Statistik</p>
    </a>

    <!-- Konfigurasi Umum -->
    <a href="{{ route('admin.konfigurasi') }}" class="menu-box">
        <img src="{{ asset('images/konfigurasi.png') }}" alt="Konfigurasi" height="60">
        <p>Konfigurasi Umum</p>
    </a>
@endsection