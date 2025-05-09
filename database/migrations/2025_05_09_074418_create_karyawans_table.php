<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan');
            $table->string('nama_karyawan');
            $table->string('jabatan');
            $table->text('alamat_karyawan'); // Menggunakan text untuk alamat
            $table->string('nomor_telepon', 15); // Mengatur panjang maksimum untuk nomor telepon
            $table->date('tgl_bergabung'); // Menggunakan date untuk tanggal bergabung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};