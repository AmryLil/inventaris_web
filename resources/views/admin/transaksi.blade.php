@extends('layouts.app')

@section('title', 'Transaksi')

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
                <h1 class="h3 mb-0 text-gray-800">Daftar Transaksi</h1>
                <div class="d-flex gap-2">
                    <div>
                        <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                            value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div>
                        <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                            value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div>
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary d-block" onclick="filterData()">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
                    <button type="button" class="btn btn-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->putri_tanggal_pesan)->format('d/m/Y H:i') }}
                                        </td>
                                        <td>{{ $item->putri_nama_user }}</td>
                                        <td>
                                            @if ($item->barang)
                                                {{ $item->barang->putri_nama_barang }}
                                            @else
                                                <span class="text-muted">Data barang tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->putri_jumlah_barang }}</td>
                                        <td>Rp {{ number_format($item->putri_total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $item->putri_status_pembayaran == 'pending'
                                                    ? 'bg-warning'
                                                    : ($item->putri_status_pembayaran == 'verifikasi'
                                                        ? 'bg-info'
                                                        : 'bg-success') }}">
                                                {{ ucfirst($item->putri_status_pembayaran) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->putri_bukti_transfer)
                                                <a href="{{ asset('storage/' . $item->putri_bukti_transfer) }}"
                                                    target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-image"></i> Lihat Bukti
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Belum upload</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($transaksi->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $transaksi->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function filterData() {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalAkhir = document.getElementById('tanggal_akhir').value;

            let url = new URL(window.location.href);
            url.searchParams.set('tanggal_mulai', tanggalMulai);
            url.searchParams.set('tanggal_akhir', tanggalAkhir);

            window.location.href = url.toString();
        }

        function exportToExcel() {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalAkhir = document.getElementById('tanggal_akhir').value;

            let url = '{{ route('admin.export-transaksi') }}';
            url += `?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;

            window.location.href = url;
        }
    </script>
@endsection
