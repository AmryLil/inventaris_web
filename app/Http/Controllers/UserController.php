<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Pesanan;
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
            ->when($request->search, function($query) use ($request) {
                $query->where('putri_nama_barang', 'like', "%{$request->search}%");
            })
            ->orderBy('putri_id_barang', 'desc')  // Ganti latest() dengan ini
            ->paginate(12);

        return view('user.catalog', compact('barang'));
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
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:putri_user,putri_email,' . $user->putri_id_user . ',putri_id_user',
            'nohp_user' => 'required|string|max:12',
            'alamat_user' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        $user->putri_nama_user = $request->nama_user;
        $user->putri_email = $request->email;
        $user->putri_nohp_user = $request->nohp_user;
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

    public function createPesanan(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $barang->putri_stok
        ]);

        $total_harga = $barang->putri_harga_jual * $request->jumlah;

        $pesanan = Pesanan::create([
            'putri_id_barang' => $id,
            'putri_nama_user' => Auth::user()->putri_nama_user,
            'putri_jumlah_barang' => $request->jumlah,
            'putri_total_harga' => $total_harga,
            'putri_status_pembayaran' => 'pending',
            'putri_tanggal_pesan' => now()
        ]);

        // Kurangi stok
        $barang->putri_stok -= $request->jumlah;
        $barang->save();

        return redirect()->route('user.riwayat-transaksi')
            ->with('success', 'Pesanan berhasil dibuat');
    }

    public function uploadBuktiTransfer(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // Upload bukti transfer
        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $pesanan->putri_bukti_transfer = $path;
        $pesanan->putri_status_pembayaran = 'verifikasi';
        $pesanan->save();

        return back()->with('success', 'Bukti transfer berhasil diupload');
    }
}