<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranBarang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_barang'; // Nama tabel eksplisit

    protected $guarded = [];
}