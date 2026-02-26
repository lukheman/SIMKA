<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('jenis_simpanan_id')->constrained('jenis_simpanan')->cascadeOnDelete();
            $table->string('kode_transaksi')->unique();
            $table->enum('tipe_transaksi', \App\Enum\TipeTransaksi::values());
            $table->decimal('jumlah', 15, 2);
            $table->date('tgl_transaksi');
            $table->text('keterangan')->nullable();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_simpanan');
    }
};
