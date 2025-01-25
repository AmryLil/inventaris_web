<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
  public function addToCart(Request $request, $barangId)
  {
    $barang = Barang::findOrFail($barangId);

    // Cari cart milik user yang login
    $cart = Cart::firstOrCreate(
      ['user_id' => Auth::id()],
      ['status' => 'pending']
    );

    // Cek apakah barang sudah ada di cart
    $cartItem = CartItem::where('cart_id', $cart->id)
      ->where('barang_id', $barang->putri_id_barang)
      ->first();

    if ($cartItem) {
      // Tambah quantity jika barang sudah ada
      $cartItem->quantity += 1;
      $cartItem->save();
    } else {
      // Tambahkan barang baru ke cart
      CartItem::create([
        'cart_id'   => $cart->id,
        'barang_id' => $barang->putri_id_barang,
        'quantity'  => 1,
      ]);
    }

    return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang.');
  }

  public function showCart()
  {
    $cart = Cart::with('items.barang')->where('user_id', Auth::id())->first();

    return view('user.cart', compact('cart'));
  }

  public function removeItem($itemId)
  {
    $cartItem = CartItem::findOrFail($itemId);
    $cartItem->delete();

    return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
  }
}
