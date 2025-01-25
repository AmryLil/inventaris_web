@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="login-container">
  <div class="login-card">
            <div class="text-center mb-4">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="mb-3"
                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <h4 class="fw-bold">Register</h4>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-user text-primary"></i>
                        </span>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap"
                            value="{{ old('nama') }}" required>
                    </div>
                    @error('nama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-envelope text-primary"></i>
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-phone text-primary"></i>
                        </span>
                        <input type="text" name="nohandphone" class="form-control" placeholder="No. Handphone"
                            value="{{ old('nohandphone') }}" required>
                    </div>
                    @error('nohandphone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </span>
                        <textarea name="alamat" class="form-control" placeholder="Alamat" required>{{ old('alamat') }}</textarea>
                    </div>
                    @error('alamat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-lock text-primary"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-4">
                  <div class="input-group">
                      <span class="input-group-text bg-light">
                          <i class="fas fa-lock text-primary"></i>
                      </span>
                      <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                  </div>
                  @error('password_confirmation')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
              </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i>Daftar
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
