<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // <- import DB facade

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier'; // Nama tabel eksplisit

    // Agar tidak bisa di‐override lewat mass assignment
    protected $guarded = ['kode_supplier'];

    /**
     * Boot model events.
     */
    protected static function booted()
    {
        static::creating(function (Supplier $supplier) {
            // Set kode_supplier otomatis sebelum insert
            $supplier->kode_supplier = self::getKodeSupplier();
        });
    }

    /**
     * Hitung kode baru berdasarkan MAX(kode_supplier) di tabel.
     *
     * @return string Contoh: 'SP001', 'SP002', dst.
     */
    public static function getKodeSupplier()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_supplier), 'SP000') as kode_supplier 
                FROM supplier";
        $kodesupplier = DB::select($sql);

        // ambil hasilnya
        $kd = 'SP000';
        foreach ($kodesupplier as $kdsp) {
            $kd = $kdsp->kode_supplier;
        }

        // ambil 3 digit terakhir, tambahkan 1, lalu format kembali
        $noawal  = (int) substr($kd, -3);
        $noakhir = $noawal + 1;
        return 'SP' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
    }
    public function barang()
    {
        return $this->hasMany(Barang::class, 'kode_supplier');
    }
}
