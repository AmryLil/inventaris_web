<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    public $timestamps = false; // Tambahkan ini
    protected $table = 'putri_barang';
    protected $primaryKey = 'putri_id_barang';

    protected $fillable = [
        'putri_nama_barang',
        'putri_satuan',
        'putri_harga_jual',
        'putri_harga_beli', 
        'putri_stok',
        'putri_status',
        'putri_gambar'
    ];

    /**
     * Relasi dengan model Pesanan
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'putri_id_barang', 'putri_id_barang');
    }

    /**
     * Mendapatkan status dalam format yang readable
     */
    public function getStatusTextAttribute()
    {
        return $this->putri_status == '1' ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Mendapatkan format harga jual
     */
    public function getFormattedHargaJualAttribute()
    {
        return 'Rp ' . number_format($this->putri_harga_jual, 0, ',', '.');
    }

    /**
     * Mendapatkan format harga beli
     */
    public function getFormattedHargaBeliAttribute()
    {
        return 'Rp ' . number_format($this->putri_harga_beli, 0, ',', '.');
    }

    /**
     * Check apakah stok sudah menipis (kurang dari atau sama dengan 10)
     */
    public function getLowStockAttribute()
    {
        return $this->putri_stok <= 10;
    }
}