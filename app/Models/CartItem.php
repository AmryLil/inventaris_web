<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  use HasFactory;

  protected $table    = 'cart_items';
  protected $fillable = ['cart_id', 'barang_id', 'quantity'];

  public function barang()
  {
    return $this->belongsTo(Barang::class, 'barang_id');
  }

  public function cart()
  {
    return $this->belongsTo(Cart::class);
  }
}
