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
        Schema::create('pengajuan_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('jenis_pinjaman_id')->constrained('jenis_pinjaman')->cascadeOnDelete();
            $table->decimal('jumlah_pengajuan', 15, 2);
            $table->decimal('jumlah_disetujui', 15, 2)->nullable();
            $table->integer('tenor_bulan');
            $table->decimal('bunga_total', 15, 2);
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'lunas'])->default('pending');
            $table->date('tgl_pengajuan');
            $table->date('tgl_cair')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pinjaman');
    }
};
