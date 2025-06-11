<?php

use App\Models\Supplier;
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
        Schema::create('list_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('supplier')->cascadeOnDelete(); //jika parent di hapus, maka anak akan ikut terhapus
            $table->string('kode_supplier'); 
            $table->string('nama_supplier'); 
            $table->string('alamat'); 
            $table->string('telepon'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_supplier');
    }
};
