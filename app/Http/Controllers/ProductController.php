<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:60',
            'satuan'      => 'required|string|max:20',
            'harga_jual'  => 'required|numeric|min:0',
            'harga_beli'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Upload gambar
        $gambarPath = $request->file('gambar')->store('products', 'public');

        Barang::create([
            'putri_nama_barang' => $request->nama_barang,
            'putri_satuan'      => $request->satuan,
            'putri_harga_jual'  => $request->harga_jual,
            'putri_harga_beli'  => $request->harga_beli,
            'putri_stok'        => $request->stok,
            'putri_status'      => '1',
            'putri_gambar'      => $gambarPath
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Produk berhasil ditambahkan');
    }
}
