@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <button class="btn btn-primary position-fixed top-3 start-3" style="z-index: 1001;" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
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
        <h1 class="mb-4">Input Nilai</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('input.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" required>
            </div>

            <div class="mb-3">
                <label for="jenis_nilai" class="form-label">Jenis Nilai</label>
                <select class="form-select" id="jenis_nilai" name="jenis_nilai" required>
                    <option value="Kehadiran">Kehadiran</option>
                    <option value="T1">Tugas 1</option>
                    <option value="T2">Tugas 2</option>
                    <option value="T3">Tugas 3</option>
                    <option value="T4">Tugas 4</option>
                    <option value="T5">Tugas 5</option>
                    <option value="T6">Tugas 6</option>
                    <option value="R1">Respon 1</option>
                    <option value="R2">Respon 2</option>
                    <option value="R3">Respon 3</option>
                    <option value="R4">Respon 4</option>
                    <option value="R5">Respon 5</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="number" class="form-control" id="nilai" name="nilai" min="0" max="100"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
@endsection
