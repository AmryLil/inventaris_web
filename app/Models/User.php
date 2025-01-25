<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false; // Tambahkan ini untuk nonaktifkan timestamps

    protected $table = 'putri_user';
    protected $primaryKey = 'putri_id_user';

    protected $fillable = [
        'putri_nama_user',
        'putri_email',
        'putri_password',
        'putri_nohp_user',
        'putri_alamat_user',
    ];

    protected $hidden = [
        'putri_password',
    ];

    public function getAuthPassword()
    {
        return $this->putri_password;
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'putri_nama_user', 'putri_nama_user');
    }
}