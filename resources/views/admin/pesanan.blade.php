@extends('layouts.app')

@section('title', 'Pesanan')

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
                <h1 class="h3 mb-0 text-gray-800">Daftar Pesanan</h1>
                <div class="d-flex gap-2">
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>Verifikasi
                        </option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <form class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Cari pesanan..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama User</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Bukti Transfer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesanan as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order->putri_tanggal_pesan)->format('d/m/Y H:i') }}
                                        </td>
                                        <td>{{ $order->putri_nama_user }}</td>
                                        <td>
                                            @if ($order->barang)
                                                {{ $order->barang->putri_nama_barang }}
                                            @else
                                                <span class="text-muted">Data barang tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->putri_jumlah_barang }}</td>
                                        <td>{{ $order->formatted_total_harga }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $order->putri_status_pembayaran == 'pending'
                                                    ? 'bg-warning'
                                                    : ($order->putri_status_pembayaran == 'verifikasi'
                                                        ? 'bg-info'
                                                        : 'bg-success') }}">
                                                {{ $order->status_pembayaran_text }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->putri_bukti_transfer)
                                                <a href="{{ asset('storage/' . $order->putri_bukti_transfer) }}"
                                                    target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-image"></i> Lihat Bukti
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Belum upload</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->putri_status_pembayaran != 'selesai')
                                                <form
                                                    action="{{ route('admin.update-status-pesanan', $order->putri_id_pesanan) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <select class="form-select form-select-sm" name="status"
                                                        onchange="this.form.submit()">
                                                        <option value="pending"
                                                            {{ $order->putri_status_pembayaran == 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>
                                                        <option value="verifikasi"
                                                            {{ $order->putri_status_pembayaran == 'verifikasi' ? 'selected' : '' }}>
                                                            Verifikasi
                                                        </option>
                                                        <option value="selesai"
                                                            {{ $order->putri_status_pembayaran == 'selesai' ? 'selected' : '' }}>
                                                            Selesai
                                                        </option>
                                                    </select>
                                                </form>
                                            @else
                                                <span class="badge bg-success">Transaksi Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data pesanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($pesanan->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $pesanan->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('filterStatus').addEventListener('change', function() {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('status', this.value);
            window.location.href = currentUrl.toString();
        });
    </script>
@endsection
