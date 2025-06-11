<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class ListSupplier extends Model
{
    use HasFactory;

    protected $table = 'list_supplier'; // Nama tabel eksplisit

    protected $guarded = []; //semua kolom boleh di isi

    public static function getKodeSupplier()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_supplier), 'SP-00000') as kode_supplier 
                FROM list_supplier ";
        $kodesupplier = DB::select($sql);

        // cacah hasilnya
        foreach ($kodesupplier as $kdsp) {
            $kd = $kdsp->kode_supplier;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-5);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'SP-'.str_pad($noakhir,5,"0",STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;

    }

    // relasi ke tabel supplier
    public function supplier()
    {
        return $this->belongsTo(supplier::class, 'supplier_id'); 
        // pastikan 'supplier_id' adalah nama kolom foreign key
    }

    // relasi ke tabel penjualan
    public function pembelian()
    {
        return $this->hasMany(pembelian::class, 'pembelian_id');
    }
}