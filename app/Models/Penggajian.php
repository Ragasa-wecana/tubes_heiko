<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// untuk tambahan db
use Illuminate\Support\Facades\DB;

class Penggajian extends Model
{
    /** @use HasFactory<\Database\Factories\PenggajianFactory> */
    use HasFactory;

    protected $table = 'penggajian'; // Nama tabel eksplisit

    protected $guarded = [];
     
    // relasi ke tabel presensi
    public function presensi()
    {
        return $this->belongsTo(presensi::class, 'id_karyawan');
    }


    function hitungTotalGaji($gaji_pokok, $potongan_gaji)
{
    // Validasi input agar tidak negatif
    if ($gaji_pokok < 0 || $potongan_gaji < 0) {
        throw new InvalidArgumentException("Gaji pokok dan potongan tidak boleh negatif.");
    }

    // Hitung total gaji
    $total_gaji = $gaji_pokok - $potongan_gaji;

    // Pastikan total gaji tidak negatif
    return max(0, $total_gaji);
}
}