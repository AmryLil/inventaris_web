@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Sidebar Toggle Button -->
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

    <!-- Main Content -->
    <div class="main-content">
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-primary mb-1 fw-bold">Total Products</div>
                            <div class="h5 mb-0 fw-bold">{{ number_format($stats['total_products']) }}</div>
                        </div>
                        <div class="icon text-primary">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-warning mb-1 fw-bold">Low Stock Items</div>
                            <div class="h5 mb-0 fw-bold">{{ number_format($stats['low_stock']) }}</div>
                        </div>
                        <div class="icon text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-success mb-1 fw-bold">Total Value</div>
                            <div class="h5 mb-0 fw-bold">Rp {{ number_format($stats['total_value']) }}</div>
                        </div>
                        <div class="icon text-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-info mb-1 fw-bold">Pending Orders</div>
                            <div class="h5 mb-0 fw-bold">{{ number_format($stats['pending_orders']) }}</div>
                        </div>
                        <div class="icon text-info">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Product Inventory</h6>
                <a href="{{ route('admin.tambah-barang') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </a>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ asset('storage/' . $product->putri_gambar) }}"
                                        alt="{{ $product->putri_nama_barang }}">
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title text-truncate">
                                        {{ $product->putri_nama_barang }}
                                    </h5>
                                    <p class="card-text text-muted mb-2">
                                        <small>Unit: {{ $product->putri_satuan }}</small>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="fw-bold text-primary">
                                            Rp {{ number_format($product->putri_harga_jual) }}
                                        </div>
                                        <span class="badge {{ $product->putri_stok > 10 ? 'bg-success' : 'bg-warning' }}">
                                            Stock: {{ $product->putri_stok }}
                                        </span>
                                    </div>
                                    <div class="mt-2 text-muted small">
                                        Buy Price: Rp {{ number_format($product->putri_harga_beli) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
