<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier'; // Nama tabel eksplisit

    protected $guarded = []; //semua kolom boleh di isi

    public static function getKodeSupplier()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_supplier), 'S-00000') as kode_supplier
                FROM supplier ";
        $kodesupplier = DB::select($sql);

        // cacah hasilnya
        foreach ($kodesupplier as $kdsupp) {
            $kd = $kdsupp->kode_supplier;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-5);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'S-'.str_pad($noakhir,5,"0",STR_PAD_LEFT); //menyambung dengan string S-00001
        return $noakhir;

    }

}

