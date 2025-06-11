<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// untuk tambahan db
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Penggajian extends Model
{
    /** @use HasFactory<\Database\Factories\PenggajianFactory> */
    use HasFactory;

    protected $table = 'penggajian'; // Nama tabel eksplisit

    protected $guarded = [];

    // Relasi ke tabel presensi
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_karyawan'); // Relasi berdasarkan id_karyawan
    }

    /**
     * Fungsi untuk menghitung gaji otomatis berdasarkan presensi dan potongan
     */
    public static function hitungGajiOtomatis($idKaryawan, $gajiPokok, $potongan, $bulan, $tahun)
    {
        // 1. Hitung jumlah hari masuk dari tabel presensi (status 'hadir')
        $jumlahHariMasuk = Presensi::where('id_karyawan', $idKaryawan)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'hadir') // Status presensi 'hadir'
            ->count();

        // 2. Hitung total hari kerja (Senin–Jumat) dalam bulan itu
        $start = Carbon::createFromDate($tahun, $bulan, 1);
        $end = $start->copy()->endOfMonth();

        // Membuat periode untuk seluruh bulan
        $periode = CarbonPeriod::create($start, $end);

        // Filter untuk menghitung hari kerja (Senin sampai Jumat)
        $totalHariKerja = collect($periode)->filter(fn($date) => !$date->isWeekend())->count();

        // 3. Hitung persentase kehadiran
        $persentaseKehadiran = $totalHariKerja > 0 ? ($jumlahHariMasuk / $totalHariKerja) * 100 : 0;

        // 4. Hitung gaji berdasarkan kehadiran
        $gajiHadir = $gajiPokok * ($persentaseKehadiran / 100);

        // 5. Hitung total gaji setelah potongan
        $totalGaji = $gajiHadir - $potongan;

        // Return hasil perhitungan gaji
        return [
            'gaji_pokok' => $gajiPokok,
            'jumlah_hari_masuk' => $jumlahHariMasuk,
            'total_hari_kerja' => $totalHariKerja,
            'persentase_kehadiran' => round($persentaseKehadiran, 2),
            'potongan' => $potongan,
            'total_gaji' => round($totalGaji, 0),
        ];
    }

    public function karyawan()
{
    return $this->belongsTo(Karyawan::class, 'id_karyawan');
}
}