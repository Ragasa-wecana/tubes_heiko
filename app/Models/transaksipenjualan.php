<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class transaksipenjualan extends Model
{
    /** @use HasFactory<\Database\Factories\TransaksiPenjualanFactory> */
    use HasFactory;

    protected $table = 'transaksipenjualan'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getNoTransaksi()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(no_transaksi), 'AC000') as no_transaksi 
                FROM transaksipenjualan ";
        $notransaksi = DB::select($sql);

        // cacah hasilnya
        foreach ($notransaksi as $ntrans) {
            $nt = $ntrans->no_transaksi;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($nt,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'AC'.str_pad($noakhir,3,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
        return $noakhir;

    }
    // Dengan mutator ini, setiap kali data harga_barang dikirim ke database, koma akan otomatis dihapus.
    public function setTotalAttribute($value)
    {
        // Hapus koma (,) dari nilai sebelum menyimpannya ke database
        $this->attributes['total'] = str_replace('.', '', $value);
    }
}