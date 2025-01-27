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
                <a style="padding: 10px" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" href="{{ route('cart.show') }}"><i class="fas fa-shopping-cart" style="font-size: 30px"></i></a>
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
                    <!-- Gambar Produk -->
                    <img src="{{ asset('storage/' . $item->putri_gambar) }}" 
                         class="card-img-top"
                         style="height: 200px; object-fit: cover;"
                         alt="{{ $item->putri_nama_barang }}"
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
        
                    <!-- Body Card -->
                    <div class="card-body">
                        <!-- Nama Produk -->
                        <h5 class="card-title text-truncate">{{ $item->putri_nama_barang }}</h5>
                        
                        <!-- Informasi Satuan -->
                        <p class="card-text text-muted mb-1">
                            <small>Satuan: {{ $item->putri_satuan }}</small>
                        </p>
        
                        <!-- Harga dan Stok -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-primary">
                                Rp {{ number_format($item->putri_harga_jual, 0, ',', '.') }}
                            </span>
                            <span class="badge {{ $item->putri_stok > 10 ? 'bg-success' : 'bg-warning' }}">
                                Stok: {{ $item->putri_stok }}
                            </span>
                        </div>
        
                        <!-- Input Quantity dan Tombol -->
                        <div class="d-flex flex-column gap-2">
                            <!-- Input Quantity -->
                            <div class="d-flex align-items-center gap-2">
                                <label for="quantity-{{ $item->putri_id_barang }}" class="form-label mb-0">
                                    <small>Jumlah:</small>
                                </label>
                                <input type="number" name="quantity" 
                                       id="quantity-{{ $item->putri_id_barang }}" 
                                       class="form-control w-50"
                                       min="1" 
                                       value="1">
                            </div>
        
                            <!-- Tombol Tambah ke Keranjang -->
                            <form action="{{ route('cart.add', $item->putri_id_barang) }}" method="POST" class="mt-2" id="add-to-cart-form-{{ $item->putri_id_barang }}">
                                @csrf
                                <button type="button" class="btn btn-primary w-100 d-flex align-items-center justify-content-center add-to-cart" 
                                        data-id="{{ $item->putri_id_barang }}">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            </form>
                        </div>
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



<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Keranjang Anda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="cartItems">
                    @foreach($cart->items as $item)
                    <li class="list-group-item d-flex align-items-center">
                        <img src="{{ asset('storage/' . $item->barang->putri_gambar) }}" 
                             alt="{{ $item->barang->putri_nama_barang }}" 
                             class="me-3" 
                             style="width: 70px; max-height: 100px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6>{{ $item->barang->putri_nama_barang }}</h6>
                            <small>Harga Satuan: Rp{{ number_format($item->barang->putri_harga_jual, 0, ',', '.') }}</small>
                            <br>
                            <small>Jumlah: {{ $item->quantity }}</small>
                            <br>
                            <small>Subtotal: Rp{{ number_format($item->barang->putri_harga_jual * $item->quantity, 0, ',', '.') }}</small>
                        </div>
                        <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="ms-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <span>Total Harga:</span>
                    <span id="cartTotal" class="fw-bold">
                        Rp{{ number_format($total) }}
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadPaymentProofModal">Checkout</a>
                {{-- <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal">Checkout</a> --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadPaymentProofModal" tabindex="-1" aria-labelledby="uploadPaymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPaymentProofModalLabel">Upload Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('user.createPesanan') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Section QRIS -->
                    <div class="mb-3 text-center">
                        <h6>Scan QRIS untuk Pembayaran</h6>
                        <img src="{{ asset('frame.png') }}" alt="QRIS Code" class="img-fluid mb-2" style="max-width: 200px;">
                        <small class="text-muted d-block">Silakan pindai kode QR ini untuk melakukan pembayaran.</small>
                    </div>
                    
                    <!-- Upload Bukti Pembayaran -->
                    <div class="mb-3">
                        <label for="paymentProof" class="form-label">Unggah Bukti Pembayaran</label>
                        <input type="file" name="payment_proof" id="paymentProof" class="form-control" required>
                        <small class="text-muted">Format yang diperbolehkan: JPG, PNG, PDF. Ukuran maksimal: 2MB.</small>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Tambahkan catatan jika perlu"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Kirim Bukti Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</div>



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
                <form id="checkoutForm" method="POST" action="#">
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




@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


document.addEventListener('DOMContentLoaded', () => {
    // Tambahkan event listener ke semua tombol "Add to Cart"
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function () {
            const formId = `add-to-cart-form-${this.dataset.id}`;
            const form = document.getElementById(formId);

            Swal.fire({
                title: 'Tambahkan ke Keranjang?',
                text: "Produk ini akan ditambahkan ke keranjang Anda.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tambahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form jika user memilih "Ya, tambahkan!"
                }
            });
        });
    });
});


</script>
@endsection