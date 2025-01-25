<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    public $timestamps = false; // Menandakan bahwa tabel tidak menggunakan timestamps
    protected $table = 'putri_pesanan';
    protected $primaryKey = 'putri_id_pesanan';

    protected $fillable = [
        'putri_id_barang',
        'putri_nama_user',
        'putri_jumlah_barang',
        'putri_total_harga',
        'putri_status_pembayaran',
        'putri_tanggal_pesan',
        'putri_bukti_transfer'
    ];

    /**
     * Relasi dengan model Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'putri_id_barang', 'putri_id_barang');
    }

    /**
     * Format total harga
     */
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->putri_total_harga, 0, ',', '.');
    }

    /**
     * Format status pembayaran yang lebih readable
     */
    public function getStatusPembayaranTextAttribute()
    {
        switch ($this->putri_status_pembayaran) {
            case 'pending':
                return 'Menunggu Pembayaran';
            case 'verifikasi':
                return 'Sedang Diverifikasi';
            case 'selesai':
                return 'Selesai';
            default:
                return 'Status Tidak Diketahui';
        }
    }

    /**
     * Get formatted tanggal pesan
     */
    public function getFormattedTanggalPesanAttribute()
    {
        return \Carbon\Carbon::parse($this->putri_tanggal_pesan)->format('d F Y H:i');
    }

    /**
     * Scope query untuk pesanan yang pending
     */
    public function scopePending($query)
    {
        return $query->where('putri_status_pembayaran', 'pending');
    }

    /**
     * Scope query untuk pesanan yang dalam verifikasi
     */
    public function scopeVerifikasi($query)
    {
        return $query->where('putri_status_pembayaran', 'verifikasi');
    }

    /**
     * Scope query untuk pesanan yang selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('putri_status_pembayaran', 'selesai');
    }
}