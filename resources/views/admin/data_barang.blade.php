@extends('layouts.app')

@section('title', 'Data Barang')

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
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Data Barang</h1>
                <a href="{{ route('admin.tambah-barang') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Barang
                </a>
            </div>

            <!-- Content -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('admin.data-barang') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari barang..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barang as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->putri_gambar) }}"
                                                alt="{{ $item->putri_nama_barang }}" class="img-thumbnail"
                                                style="max-width: 50px;">
                                        </td>
                                        <td>{{ $item->putri_nama_barang }}</td>
                                        <td>{{ $item->putri_satuan }}</td>
                                        <td>Rp {{ number_format($item->putri_harga_beli) }}</td>
                                        <td>Rp {{ number_format($item->putri_harga_jual) }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $item->putri_stok <= 10 ? 'bg-warning' : 'bg-success' }}">
                                                {{ $item->putri_stok }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $item->putri_status == '1' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $item->putri_status == '1' ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.edit-barang', $item->putri_id_barang) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.delete-barang', $item->putri_id_barang) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data barang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($barang->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $barang->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
