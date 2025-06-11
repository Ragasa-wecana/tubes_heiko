<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Presensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penggajians = Penggajian::with('karyawan')->latest()->get();
        return view('penggajian.index', compact('penggajians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penggajian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawans,id',
            'gaji_pokok' => 'required|numeric',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
        ]);

        // Hitung potongan berdasarkan presensi
        $hasil = $this->hitungGajiOtomatis(
            $validated['id_karyawan'],
            $validated['gaji_pokok'],
            $validated['bulan'],
            $validated['tahun']
        );

        // Simpan ke database
        Penggajian::create([
            'id_karyawan' => $validated['id_karyawan'],
            'gaji_pokok' => $hasil['gaji_pokok'],
            'jumlah_hari_masuk' => $hasil['jumlah_hari_masuk'],
            'total_hari_kerja' => $hasil['total_hari_kerja'],
            'persentase_kehadiran' => $hasil['persentase_kehadiran'],
            'potongan' => $hasil['potongan'],
            'total_gaji' => $hasil['total_gaji'],
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
        ]);

        return redirect()->route('penggajian.index')->with('success', 'Data gaji berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penggajian $penggajian)
    {
        return view('penggajian.show', compact('penggajian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penggajian $penggajian)
    {
        return view('penggajian.edit', compact('penggajian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penggajian $penggajian)
    {
        // Optional: jika kamu izinkan update perhitungan
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penggajian $penggajian)
    {
        $penggajian->delete();
        return redirect()->route('penggajian.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Fungsi untuk menghitung gaji otomatis dengan potongan berdasarkan presensi
     */
    private function hitungGajiOtomatis($karyawanId, $gajiPokok, $bulan, $tahun)
    {
        // Ambil jumlah hari kerja dalam bulan dan tahun yang ditentukan
        $totalHariKerja = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        // Hitung jumlah ketidakhadiran
        $presensi = Presensi::where('id_karyawan', $karyawanId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'tidak hadir') // Misalnya status 'tidak hadir'
            ->count();

        // Hitung jumlah hari masuk (total hari kerja - jumlah hari tidak hadir)
        $jumlahHariMasuk = $totalHariKerja - $presensi;

        // Hitung persentase kehadiran
        $persentaseKehadiran = $jumlahHariMasuk / $totalHariKerja;

        // Hitung potongan berdasarkan kehadiran
        $potongan = $gajiPokok * (1 - $persentaseKehadiran);

        // Hitung total gaji
        $totalGaji = $gajiPokok - $potongan;

        return [
            'gaji_pokok' => $gajiPokok,
            'jumlah_hari_masuk' => $jumlahHariMasuk,
            'total_hari_kerja' => $totalHariKerja,
            'persentase_kehadiran' => $persentaseKehadiran,
            'potongan' => $potongan,
            'total_gaji' => $totalGaji,
        ];
    }
}
