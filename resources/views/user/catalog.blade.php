@extends('layouts.app')

@section('title', 'Katalog Produk')

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

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div style="display: flex;gap: 10px ">
                <a href="{{ route('cart.show') }}"><i class="fas fa-shopping-cart" style="font-size: 30px"></i></a>
                <h1 class="h3 mb-0 text-gray-800">Katalog Produk</h1>
            </div>
            <form class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @forelse($barang as $item)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $item->putri_gambar) }}" 
                         class="card-img-top"
                         style="height: 200px; object-fit: cover;"
                         alt="{{ $item->putri_nama_barang }}"
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    <div class="card-body">
                        <h5 class="card-title text-truncate">{{ $item->putri_nama_barang }}</h5>
                        <p class="card-text text-muted mb-1">
                            <small>Satuan: {{ $item->putri_satuan }}</small>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-primary">
                                Rp {{ number_format($item->putri_harga_jual, 0, ',', '.') }}
                            </span>
                            <span class="badge {{ $item->putri_stok > 10 ? 'bg-success' : 'bg-warning' }}">
                                Stok: {{ $item->putri_stok }}
                            </span>
                        </div>
                        <form action="{{ route('cart.add', $item->putri_id_barang) }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" class="form-control" min="1" value="1">
                                <button class="btn btn-primary" type="submit">                            <i class="fas fa-shopping-cart me-2"></i>
                                </button>
                            </div>
                        </form>
                        <button type="button" 
                            class="btn btn-primary w-100" 
                            style="cursor: pointer;"
                            onclick="showOrderModal({{ $item->putri_id_barang }}, '{{ $item->putri_nama_barang }}', {{ $item->putri_stok }}, {{ $item->putri_harga_jual }})"
                            {{ $item->putri_stok < 1 ? 'disabled' : '' }}>
                            {{ $item->putri_stok < 1 ? 'Stok Habis' : 'Pesan Sekarang' }}
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Tidak ada produk yang tersedia.
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-4">
            {{ $barang->links() }}
        </div>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pesan Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="orderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <h6 class="product-name mb-3"></h6>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="form-control bg-light" id="productPrice"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                        <small class="text-muted">Stok tersedia: <span id="availableStock"></span></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <div class="form-control bg-light" id="totalPrice"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Pesan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Keranjang Anda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="cartItems">
                    <!-- Items akan dimasukkan melalui JavaScript -->
                </ul>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <span>Total Harga:</span>
                    <span id="cartTotal" class="fw-bold">Rp 0</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-primary">Checkout</a>
            </div>
        </div>
    </div>
</div>



@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showOrderModal(id, name, stock, price) {
    const modal = new bootstrap.Modal(document.getElementById('orderModal'));
    const form = document.getElementById('orderForm');

    // Escape name untuk menghindari error karakter khusus
    name = name.replace(/'/g, "\\'");
    
    // Set form action dengan benar
    form.action = `/user/pesanan/${id}`;
    form.method = 'POST';
    
    // Update modal content
    document.querySelector('.product-name').textContent = name;
    document.querySelector('#productPrice').textContent = formatRupiah(price);
    document.querySelector('#availableStock').textContent = stock;
    
    // Reset and set input quantity
    const quantityInput = form.querySelector('input[name="jumlah"]');
    quantityInput.value = 1;
    quantityInput.max = stock;
    
    // Calculate initial total
    calculateTotal(price);
    
    // Add event listener for quantity change
    quantityInput.oninput = () => calculateTotal(price);
    
    modal.show();
}

function handleSubmit(e) {
    e.preventDefault();
    
    // Submit form dengan method POST
    const form = e.target;
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        },
        body: new FormData(form)
    }).then(response => {
        if (response.ok) {
            window.location.href = "{{ route('user.riwayat-transaksi') }}";
        }
    });
}

function calculateTotal(price) {
    const quantity = document.querySelector('input[name="jumlah"]').value;
    const total = price * quantity;
    document.querySelector('#totalPrice').textContent = formatRupiah(total);
}


function showCartModal() {
    $.ajax({
        url: '/user/cart',
        method: 'GET',
        success: function(response) {
            const cart = response.cart;
            let cartItemsHtml = '';

            cart.items.forEach(item => {
                cartItemsHtml += `
                    <div class="cart-item">
                        <img src="{{ asset('storage/') }}/${item.barang.putri_gambar}" alt="${item.barang.putri_nama_barang}" class="img-thumbnail">
                        <div>
                            <h5>${item.barang.putri_nama_barang}</h5>
                            <p>Quantity: ${item.quantity}</p>
                            <p>Price: ${formatRupiah(item.barang.putri_harga_jual)}</p>
                        </div>
                    </div>
                `;
            });

            document.querySelector('.cart-items').innerHTML = cartItemsHtml;
            const modal = new bootstrap.Modal(document.getElementById('cartModal'));
            modal.show();
        }
    });
}

function formatRupiah(number) {
    return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

</script>
@endsection