@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="login-container">
        <div class="login-card">
            <!-- Switch Button yang lebih menarik -->
            <div class="btn-group w-100 mb-4" role="group">
                <button type="button" class="btn btn-outline-primary w-50 active" id="userLoginBtn"
                    onclick="switchTab('userLogin')">
                    <i class="fas fa-user me-2"></i>User Login
                </button>
                <button type="button" class="btn btn-outline-primary w-50" id="adminLoginBtn"
                    onclick="switchTab('adminLogin')">
                    <i class="fas fa-user-shield me-2"></i>Admin Login
                </button>
            </div>

            <!-- Form Login User -->
            <div class="form-container active fade-in" id="userLogin">
                <div class="text-center mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="mb-3"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <h4 class="fw-bold">User Login</h4>
                </div>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <input type="hidden" name="user_type" value="user">

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input type="email" name="email" class="form-control" placeholder="Email"
                                value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>

                <div class="text-center">
                    <p class="mb-0">Belum punya akun?</p>
                    <a href="{{ asset(route('register')) }}"  class="text-primary text-decoration-none">
                        <i class="fas fa-user-plus me-1"></i>Daftar Sekarang
                    </a>
                </div>
            </div>

            <!-- Form Login Admin -->
            <div class="form-container fade-in" id="adminLogin" style="display: none;">
                <div class="text-center mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="mb-3"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <h4 class="fw-bold">Admin Login</h4>
                </div>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <input type="hidden" name="user_type" value="admin">

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input type="email" name="email" class="form-control" value="admin@gmail.com" readonly
                                required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock text-primary"></i>
                            </span>
                            <input type="password" name="password" class="form-control"
                                placeholder="Password (5-6 karakter)" required>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    @error('login')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                    </button>
                </form>
            </div>

            <!-- Form Register -->
            

            <!-- Dan di bagian scripts, pastikan fungsi switchTab benar -->
        @section('scripts')
            <script>
                function switchTab(tabId) {
                    // Reset active state dari button group
                    document.querySelectorAll('.btn-group .btn').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Set active state sesuai tab
                    if (tabId === 'userLogin') {
                        document.getElementById('userLoginBtn').classList.add('active');
                    } else if (tabId === 'adminLogin') {
                        document.getElementById('adminLoginBtn').classList.add('active');
                    }

                    // Sembunyikan semua form
                    document.querySelectorAll('.form-container').forEach(container => {
                        container.style.display = 'none';
                        container.classList.remove('fade-in');
                    });

                    // Tampilkan form yang dipilih dengan animasi
                    const selectedForm = document.getElementById(tabId);
                    selectedForm.style.display = 'block';
                    setTimeout(() => selectedForm.classList.add('fade-in'), 50);

                  

                    // Set email admin jika di tab admin
                    if (tabId === 'adminLogin') {
                        document.querySelector('#adminLogin input[name="email"]').value = 'admin@gmail.com';
                    }
                }
            </script>
        @endsection
