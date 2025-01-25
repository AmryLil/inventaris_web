<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'putri_admin';
    protected $primaryKey = 'putri_id_admin';

    protected $fillable = [
        'putri_nama_admin',
        'putri_email',
        'putri_password',
    ];

    protected $hidden = [
        'putri_password',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->putri_password;
    }
}