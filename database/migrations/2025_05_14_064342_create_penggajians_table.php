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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('presensi')->onDelete('cascade');
            $table->string('jabatan'); 
            $table->string('gaji_pokok');
            $table->string('potongan_gaji');
            $table->string('total_gaji'); 
            $table->string('tanggal_pembayaran'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};