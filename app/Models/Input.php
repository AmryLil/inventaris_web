<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
  use HasFactory;

  protected $table = 'nilai';

  protected $fillable = [
    'nim',
    'nilai_hadir',
    'nilai_tugas',
    'nilai_project',
    'total',
    'huruf'
  ];

  public $timestamps = false;
}
