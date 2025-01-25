<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login pengguna berdasarkan tipe (admin atau user).
     */
    public function login(Request $request)
    {
        // Validasi input data login
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
            'user_type' => 'required|in:admin,user',
        ], [
            'email.required'     => 'Email harus diisi.',
            'email.email'        => 'Format email tidak valid.',
            'password.required'  => 'Password harus diisi.',
            'user_type.required' => 'Tipe pengguna harus dipilih.',
            'user_type.in'       => 'Tipe pengguna tidak valid.',
        ]);

        if ($request->user_type === 'admin') {
            // Proses login untuk admin
            $admin = Admin::where('putri_email', $request->email)->first();

            if ($admin && Hash::check($request->password, $admin->putri_password)) {
                Auth::guard('admin')->login($admin);
                $request->session()->regenerate();  // Regenerasi sesi
                return redirect()->route('admin.dashboard');
            }
        } else {
            // Proses login untuk user
            $user = User::where('putri_email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->putri_password)) {
                Auth::guard('web')->login($user);
                $request->session()->regenerate();  // Regenerasi sesi
                return redirect()->route('user.dashboard');
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'login' => 'Email atau Password salah!'
        ])->withInput($request->only('email'));
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function register(Request $request)
    {
        // Validasi input data registrasi
        $request->validate([
            'nama'        => 'required|string|max:255',
            'email'       => 'required|email|unique:putri_user,putri_email',
            'password'    => 'required|min:6|confirmed',
            'nohandphone' => 'required|numeric',
            'alamat'      => 'required|string|max:500',
        ], [
            'nama.required'        => 'Nama harus diisi.',
            'email.required'       => 'Email harus diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email sudah terdaftar.',
            'password.required'    => 'Password harus diisi.',
            'password.min'         => 'Password minimal 6 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'nohandphone.required' => 'Nomor handphone harus diisi.',
            'alamat.required'      => 'Alamat harus diisi.',
        ]);

        // Simpan data pengguna baru ke database
        User::create([
            'putri_nama_user'   => $request->nama,
            'putri_email'       => $request->email,
            'putri_password'    => Hash::make($request->password),
            'putri_nohp_user'   => $request->nohandphone,
            'putri_alamat_user' => $request->alamat,
        ]);
        // Redirect ke halaman login dengan pesan sukses
        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    /**
     * Logout pengguna berdasarkan tipe (admin atau user).
     */
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        // Invalidate sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
