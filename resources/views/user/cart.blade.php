@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
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
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('user.catalog') }}">
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

<div class="main-content mt-5">
    <h1 class="mb-4">Keranjang Belanja</h1>

    @if($cart && $cart->items->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                    <tr>
                        <td><img src="{{ asset('storage/' . $item->barang->putri_gambar) }}" alt="{{ $item->barang->putri_nama_barang }}" class="" style="width: 70px" style="max-height: 100px; object-fit: cover;"></td>
                        <td>{{ $item->barang->putri_nama_barang }}</td>
                        <td>Rp{{ number_format($item->barang->putri_harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->barang->putri_harga_jual * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right mt-4">
            <h4>Total Keseluruhan: 
                Rp{{ number_format($cart->items->sum(function ($item) { 
                    return $item->barang->putri_harga_jual * $item->quantity; 
                }), 0, ',', '.') }}
            </h4>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                <i class="fas fa-credit-card"></i> Checkout
            </button>
        </div>
    @else
        <div class="alert alert-info">
            <p>Keranjang belanja Anda kosong. <a href="{{ route('user.dashboard') }}">Belanja sekarang</a></p>
        </div>
    @endif
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Rincian Pesanan:</h5>
                <ul id="order-summary"></ul>

                <h5 class="mt-4">Total: 
                    <strong id="totalAmount"></strong>
                </h5>

                <!-- Form Checkout -->
                @if($cart && $cart->items->count() > 0)
                <form id="checkoutForm" method="POST" action="{{ route('user.create-pesanan', $item->barang->putri_id_barang) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Barang</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" max="{{ $item->barang->putri_stok }}" value="1" hidden>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Konfirmasi Pembelian</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Update order summary and total amount in modal
    document.querySelector('.btn-success').addEventListener('click', function() {
        const items = @json($cart->items);  // Pass items from cart to JS
        const orderSummary = document.getElementById('order-summary');
        const totalAmount = document.getElementById('totalAmount');

        orderSummary.innerHTML = ''; // Clear the list

        let total = 0;
        items.forEach(item => {
            const itemTotal = item.barang.putri_harga_jual * item.quantity;
            total += itemTotal;

            const li = document.createElement('li');
            li.textContent = `${item.barang.putri_nama_barang} - ${item.quantity} x Rp${item.barang.putri_harga_jual.toLocaleString()} = Rp${itemTotal.toLocaleString()}`;
            orderSummary.appendChild(li);
        });

        totalAmount.textContent = `Rp${total.toLocaleString()}`;
    });
</script>

@endsection
