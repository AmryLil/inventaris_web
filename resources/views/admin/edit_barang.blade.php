@extends('layouts.app')

@section('title', 'Edit Barang')

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
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-primary">Edit Barang</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update-barang', $barang->putri_id_barang) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang"
                                value="{{ old('nama_barang', $barang->putri_nama_barang) }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                                name="satuan" value="{{ old('satuan', $barang->putri_satuan) }}" required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga_jual" class="form-label">Harga Jual</label>
                                    <input type="number" class="form-control @error('harga_jual') is-invalid @enderror"
                                        id="harga_jual" name="harga_jual"
                                        value="{{ old('harga_jual', $barang->putri_harga_jual) }}" required>
                                    @error('harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga_beli" class="form-label">Harga Beli</label>
                                    <input type="number" class="form-control @error('harga_beli') is-invalid @enderror"
                                        id="harga_beli" name="harga_beli"
                                        value="{{ old('harga_beli', $barang->putri_harga_beli) }}" required>
                                    @error('harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok"
                                name="stok" value="{{ old('stok', $barang->putri_stok) }}" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status" required>
                                <option value="1" {{ $barang->putri_status == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $barang->putri_status == '0' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar Produk</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                id="gambar" name="gambar" required>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($barang->putri_gambar)
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini</label>
                                <div>
                                    <img src="{{ asset('storage/' . $barang->putri_gambar) }}" alt="Current Image"
                                        class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.data-barang') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
