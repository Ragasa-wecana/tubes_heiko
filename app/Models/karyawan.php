<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// untuk tambahan db
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'Karyawan'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getKodeFaktur()
    {
        // query kode karyawan
        $sql = "SELECT IFNULL(MAX(id_karyawan), 'k-001') as id_karyawan 
                FROM karyawan ";
        $kodefaktur = DB::select($sql);

        // cacah hasilnya
        foreach ($kodeKaryawan as $kdkrw) {
            $kd = $kdkrw->id_karyawan;
;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-7);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'K-'.str_pad($noakhir,7,"0",STR_PAD_LEFT); //menyambung dengan string P-00001
        return $noakhir;

    }

    // relasi ke tabel karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    // relasi ke tabel presensi
    public function presensi()
    {
        return $this->hasMany(presensi::class, 'presensi_id');
    }

    // relasi ke tabel penggajian
    public function penggajian()
    {
        return $this->hasMany(penggajian::class, 'penggajian_id');
    }
}
    //
