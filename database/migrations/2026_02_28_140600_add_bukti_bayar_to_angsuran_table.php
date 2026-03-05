<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add bukti_bayar column
        Schema::table('angsuran', function (Blueprint $table) {
            $table->string('bukti_bayar')->nullable()->after('total_bayar');
        });

        // Update enum to include 'menunggu'
        DB::statement("ALTER TABLE angsuran MODIFY COLUMN status_bayar ENUM('belum','menunggu','lunas') DEFAULT 'belum'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE angsuran MODIFY COLUMN status_bayar ENUM('belum','lunas') DEFAULT 'belum'");

        Schema::table('angsuran', function (Blueprint $table) {
            $table->dropColumn('bukti_bayar');
        });
    }
};
