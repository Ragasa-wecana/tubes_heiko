<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Import DB facade

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang'; // Nama tabel eksplisit

    // Mengizinkan semua kolom diisi secara massal
    protected $guarded = [];

    /**
     * Booted method untuk menangani event pembuatan record baru.
     * Jika kolom kode_barang kosong, maka secara otomatis akan diisi dengan
     * nilai yang dihasilkan dari generateKodeBarang().
     */
    protected static function booted()
    {
        static::creating(function (Barang $barang) {
            if (empty($barang->kode_barang)) {
                $barang->kode_barang = self::generateKodeBarang();
            }
        });
    }

    /**
     * Menghasilkan kode barang baru secara otomatis.
     *
     * Jika belum ada record, akan mengembalikan 'AB001'.
     * Jika sudah ada, metode ini akan mengambil nilai maksimum dari kode_barang,
     * menambahkan 1, dan mengembalikan nilai baru dengan format 'AB' + 3 digit angka.
     *
     * @return string
     */
    public static function getKodeBarang()
    {
        // Query untuk mendapatkan kode_barang maksimal (default 'AB000' jika belum ada)
        $sql = "SELECT IFNULL(MAX(kode_barang), 'AB000') as kode_barang FROM barang";
        $kodebarang = DB::select($sql);

        // Mengambil hasil query
        foreach ($kodebarang as $kdbrg) {
            $kd = $kdbrg->kode_barang;
        }

        // Ambil tiga digit terakhir, tambahkan 1, lalu format kembali
        $noawal  = substr($kd, -3);
        $noakhir = $noawal + 1;
        $noakhir = 'AB' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);

        return $noakhir;
    }

    /**
     * Metode generateKodeBarang() dibuat sebagai alias dari getKodeBarang()
     * agar resource yang memanggil generateKodeBarang() tidak menimbulkan error.
     *
     * @return string
     */
    public static function generateKodeBarang(): string
    {
        return self::getKodeBarang();
    }

    /**
     * Mutator untuk harga_barang.
     * Menghapus tanda titik (.) agar nilai tersimpan dalam format numerik yang bersih.
     *
     * @param string $value
     */
    public function setHargaBarangAttribute($value)
    {
        $this->attributes['harga_barang'] = str_replace('.', '', $value);
    }

    /**
     * Relasi Barang ke Supplier.
     * Mengasumsikan bahwa tabel barang memiliki kolom foreign key supplier_id
     * yang mengacu ke kolom id pada tabel supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    
    // Relasi dengan tabel relasi many to many nya
    public function penjualanBarang()
    {
        return $this->hasMany(PenjualanBarang::class, 'barang_id');
    }
}
