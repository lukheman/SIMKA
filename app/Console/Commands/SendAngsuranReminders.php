<?php

namespace App\Console\Commands;

use App\Enum\StatusBayar;
use App\Enum\TipeNotifikasi;
use App\Models\Angsuran;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAngsuranReminders extends Command
{
    protected $signature = 'app:send-angsuran-reminders';

    protected $description = 'Kirim notifikasi pengingat ke anggota 5 hari sebelum jatuh tempo angsuran';

    public function handle(): int
    {
        $targetDate = Carbon::today()->addDays(5);

        $angsurans = Angsuran::where('status_bayar', StatusBayar::BELUM)
            ->whereDate('tgl_jatuh_tempo', $targetDate)
            ->with('pengajuanPinjaman.anggota')
            ->get();

        if ($angsurans->isEmpty()) {
            $this->info('Tidak ada angsuran yang jatuh tempo dalam 5 hari.');
            return self::SUCCESS;
        }

        $created = 0;

        foreach ($angsurans as $angsuran) {
            $pinjaman = $angsuran->pengajuanPinjaman;
            $anggota = $pinjaman->anggota;

            if (!$anggota) {
                continue;
            }

            $judul = "Pengingat Jatuh Tempo Angsuran Ke-{$angsuran->angsuran_ke}";

            // Cek duplikat — jangan kirim notifikasi yang sama dua kali
            $sudahAda = Notifikasi::where('anggota_id', $anggota->id)
                ->where('judul', $judul)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            $totalAngsuran = $angsuran->jumlah_pokok + $angsuran->jumlah_bunga;

            Notifikasi::create([
                'anggota_id' => $anggota->id,
                'judul' => $judul,
                'pesan' => "Angsuran ke-{$angsuran->angsuran_ke} sebesar Rp " . number_format($totalAngsuran, 0, ',', '.') . " akan jatuh tempo pada " . Carbon::parse($angsuran->tgl_jatuh_tempo)->translatedFormat('d F Y') . ". Segera lakukan pembayaran.",
                'tipe' => TipeNotifikasi::PERINGATAN,
                'link' => route('anggota.angsuran'),
            ]);

            $created++;
        }

        $this->info("Berhasil mengirim {$created} notifikasi pengingat jatuh tempo.");

        return self::SUCCESS;
    }
}
