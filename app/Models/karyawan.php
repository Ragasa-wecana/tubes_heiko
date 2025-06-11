<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan'; // Gunakan lowercase agar konsisten dengan standar Laravel

    protected $guarded = [];

    public static function getKodeFaktur()
    {
        // Ambil ID karyawan terakhir (maksimal)
        $sql = "SELECT MAX(id_karyawan) as id_karyawan FROM karyawan";
        $kodeFaktur = DB::select($sql);

        $kd = $kodeFaktur[0]->id_karyawan ?? 'K-0000000';

        // Ambil angka dari id_karyawan (angka setelah K-)
        $noAwal = (int)substr($kd, 2); // misal 'K-0000005' → 5
        $noAkhir = $noAwal + 1;

        // Format ulang ke format 'K-0000001'
        $kodeBaru = 'K-' . str_pad($noAkhir, 7, "0", STR_PAD_LEFT);

        return $kodeBaru;
    }

    // Relasi ke presensi
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'karyawan_id');
    }

    // Relasi ke penggajian
    public function penggajian()
    {
        return $this->hasMany(Penggajian::class, 'karyawan_id');
    }
}
