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
            $table->string('no_ktp');
            $table->string('nama_karyawan');
            $table->string('jabatan');
            $table->string('nomor_telepon', 15);
            $table->string('email');
            $table->text('alamat_karyawan');
            $table->date('tgl_bergabung');
            $table->string('nama_bank');
            $table->string('no_rek');
            $table->unsignedBigInteger('gaji_karyawan');
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
