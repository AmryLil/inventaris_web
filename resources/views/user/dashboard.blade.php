@extends('layouts.app')

@section('title', 'Dashboard User')

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
        <small class="text-white-50">User Dashboard</small>
    </div>
    
    <ul class="nav flex-column mt-4">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('user.dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.catalog') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Katalog Produk</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.profile') }}">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.riwayat-transaksi') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat Transaksi</span>
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
        <!-- Welcome Message -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h4>Selamat Datang, {{ Auth::user()->putri_nama_user }}!</h4>
                        <p class="mb-0">Selamat berbelanja di toko bangunan kami.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Terbaru -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h5>
            </div>
            <div class="card-body">
                @if($pesanan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Bukti Transfer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->take(5) as $order)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($order->putri_tanggal_pesan)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($order->barang)
                                            {{ $order->barang->putri_nama_barang }}
                                        @else
                                            <span class="text-muted">Data barang tidak tersedia</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->putri_jumlah_barang }}</td>
                                    <td>Rp {{ number_format($order->putri_total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ 
                                            $order->putri_status_pembayaran == 'pending' ? 'bg-warning' : 
                                            ($order->putri_status_pembayaran == 'verifikasi' ? 'bg-info' : 'bg-success') 
                                        }}">
                                            {{ ucfirst($order->putri_status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->putri_status_pembayaran == 'pending')
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary"
                                                    onclick="uploadBukti({{ $order->putri_id_pesanan }})">
                                                <i class="fas fa-upload"></i> Upload Bukti
                                            </button>
                                        @elseif($order->putri_bukti_transfer)
                                            <a href="{{ asset('storage/' . $order->putri_bukti_transfer) }}" 
                                               target="_blank"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-image"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">Belum upload</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('user.riwayat-transaksi') }}" class="btn btn-primary">
                            Lihat Semua Pesanan
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="mb-0">Belum ada pesanan</p>
                        <a href="{{ route('user.catalog') }}" class="btn btn-primary mt-3">
                            Mulai Berbelanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bukti Transfer</label>
                        <input type="file" name="bukti_transfer" class="form-control" required>
                        <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function uploadBukti(id) {
    const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
    document.getElementById('uploadForm').action = `/user/upload-bukti/${id}`;
    modal.show();
}
</script>
@endsection