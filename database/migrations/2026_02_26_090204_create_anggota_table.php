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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('no_anggota')->unique();
            $table->string('nik');
            $table->string('nama_lengkap');
            $table->text('alamat');
            $table->string('pekerjaan');
            $table->string('no_telp');
            $table->date('tgl_bergabung');
            $table->enum('status_aktif', \App\Enum\StatusAktif::values())->default(\App\Enum\StatusAktif::AKTIF->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
