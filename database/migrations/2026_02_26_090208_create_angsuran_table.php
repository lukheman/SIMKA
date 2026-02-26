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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_pinjaman_id')->constrained('pengajuan_pinjaman')->cascadeOnDelete();
            $table->integer('angsuran_ke');
            $table->date('tgl_jatuh_tempo');
            $table->date('tgl_bayar')->nullable();
            $table->decimal('jumlah_pokok', 15, 2);
            $table->decimal('jumlah_bunga', 15, 2);
            $table->decimal('denda', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2)->nullable();
            $table->enum('status_bayar', ['belum', 'lunas'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
