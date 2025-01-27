<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Cart;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $pesanan = Pesanan::with('barang')
            ->where('putri_nama_user', Auth::user()->putri_nama_user)
            ->orderBy('putri_tanggal_pesan', 'desc')
            ->get();

        return view('user.dashboard', compact('pesanan'));
    }

    public function catalog(Request $request)
    {
        // Ambil data barang yang aktif
        $barang = Barang::where('putri_status', '1')
            ->when($request->search, function ($query) use ($request) {
                $query->where('putri_nama_barang', 'like', "%{$request->search}%");
            })
            ->orderBy('putri_id_barang', 'desc')  // Ganti latest() dengan ini
            ->paginate(12);

        $cart  = Cart::with('items.barang')->where('user_id', Auth::id())->first();
        $total = $cart->items->reduce(function ($carry, $item) {
            return $carry + ($item->barang->putri_harga_jual * $item->quantity);
        }, 0);

        return view('user.catalog', compact('barang', 'cart', 'total'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_user'   => 'required|string|max:255',
            'email'       => 'required|email|unique:putri_user,putri_email,' . $user->putri_id_user . ',putri_id_user',
            'nohp_user'   => 'required|string|max:12',
            'alamat_user' => 'required|string|max:255',
            'password'    => 'nullable|string|min:6|confirmed'
        ]);

        $user->putri_nama_user   = $request->nama_user;
        $user->putri_email       = $request->email;
        $user->putri_nohp_user   = $request->nohp_user;
        $user->putri_alamat_user = $request->alamat_user;

        if ($request->filled('password')) {
            $user->putri_password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function riwayatTransaksi()
    {
        $transaksi = Pesanan::with('barang')
            ->where('putri_nama_user', Auth::user()->putri_nama_user)
            ->latest('putri_tanggal_pesan')
            ->paginate(10);

        return view('user.riwayat_transaksi', compact('transaksi'));
    }

    public function createPesanan(Request $request)
    {
        // Validasi file bukti pembayaran
        $request->validate([
            'payment_proof' => 'required|mimes:jpg,png,pdf|max:2048',
        ]);

        // Simpan file bukti pembayaran
        $filePath = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Ambil ID pengguna yang sedang login
        $userId = Auth::user()->putri_id_user;

        // Ambil keranjang pengguna yang sedang login
        $cart = Cart::where('user_id', $userId)->with('items.barang')->first();

        // Jika keranjang kosong atau tidak ada item di dalamnya
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong!');
        }

        // Loop untuk membuat pesanan berdasarkan item di keranjang
        foreach ($cart->items as $item) {
            Pesanan::create([
                'putri_id_barang'         => $item->barang->putri_id_barang,  // Ambil ID barang dari relasi
                'putri_nama_user'         => Auth::user()->putri_nama_user,  // Nama pengguna
                'putri_jumlah_barang'     => $item->quantity,  // Jumlah barang dari keranjang
                'putri_total_harga'       => $item->barang->putri_harga_jual * $item->quantity,  // Total harga
                'putri_status_pembayaran' => 'pending',  // Status awal
                'putri_tanggal_pesan'     => now(),  // Tanggal pesan
                'putri_bukti_transfer'    => $filePath,  // Bukti pembayaran
            ]);
        }

        // Hapus semua item dalam keranjang setelah checkout
        $cart->items()->delete();

        return redirect()->route('user.catalog')->with('success', 'Pesanan berhasil diproses!');
    }

    public function uploadBuktiTransfer(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $path    = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $pesanan->putri_bukti_transfer    = $path;
        $pesanan->putri_status_pembayaran = 'verifikasi';
        $pesanan->save();

        return back()->with('success', 'Bukti transfer berhasil diupload. Pesanan Anda sedang diverifikasi.');
    }
}
