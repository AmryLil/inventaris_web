@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <button class="btn btn-primary position-fixed top-3 start-3" style="z-index: 1001;" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('logo.png') }}" alt="Logo">
            <h5 class="mb-0">Inventory System</h5>
            <small class="text-white-50">Management Dashboard</small>
        </div>

        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.data-barang') }}">
                    <i class="fas fa-box"></i>
                    <span>Data Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.tambah-barang') }}">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pesanan') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pesanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('input') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Respon 4</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.laporan') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="nav-item mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
    <div class="main-content">
        <h1 class="mb-4">Hasil Nilai</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nilai Kehadiran</th>
                    <th>Nilai Tugas</th>
                    <th>Nilai Response</th>
                    <th>Total</th>
                    <th>Huruf</th>
                </tr>
            </thead>
            <tbody>
                @forelse($input as $nilai)
                    <tr>
                        <td>{{ $nilai->nim }}</td>
                        <td>{{ $nilai->nilai_hadir ?? 0 }}</td>
                        <td>{{ $nilai->nilai_tugas ?? 0 }}</td>
                        <td>{{ $nilai->nilai_project ?? 0 }}</td>
                        <td>{{ $nilai->total ?? 0 }}</td>
                        <td>{{ $nilai->huruf ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data nilai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('input') }}" class="btn btn-primary">Input Nilai</a>
    </div>
@endsection
