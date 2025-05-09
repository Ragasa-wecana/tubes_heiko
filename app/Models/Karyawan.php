<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Karyawan extends Model
{
    /** @use HasFactory<\Database\Factories\KaryawanFactory> */
    use HasFactory;

    protected $table = 'karyawan'; // Nama tabel eksplisit

    protected $guarded = [];
    public static function getIdKaryawan()
{
    // query kode perusahaan
    $sql = "SELECT IFNULL(MAX(id_karyawan), 'K-0000001') as id_karyawan
            FROM karyawan ";
    $id_karyawan = DB::select($sql);

    // cacah hasilnya
    foreach ($id_karyawan as $idkrywn) {
        $nt = $idkrywn->id_karyawan;
    }
    // Mengambil substring tiga digit akhir dari string PR-000
    $noawal = substr($nt,-3);
    $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
    $noakhir = 'K-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
    return $noakhir;
}

}