<?php

namespace Database\Seeders;

use App\Enum\StatusBayar;
use App\Enum\StatusPengajuan;
use App\Enum\TipeNotifikasi;
use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\JenisPinjaman;
use App\Models\Notifikasi;
use App\Models\PengajuanPinjaman;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotifikasiAngsuranSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::parse('2026-02-28 22:00:00');
        $anggota1 = Anggota::where('email', 'anggota1@gmail.com')->first();

        if (!$anggota1) {
            $this->command->warn('Anggota anggota1@gmail.com tidak ditemukan. Jalankan DatabaseSeeder dulu.');
            return;
        }

        $jenisPinjaman = JenisPinjaman::first();
        if (!$jenisPinjaman) {
            $this->command->warn('Jenis Pinjaman tidak ditemukan. Jalankan JenisPinjamanSeeder dulu.');
            return;
        }

        // ─── Pinjaman 1: Disetujui 3 bulan lalu, 2 angsuran lunas, 1 menunggu, sisanya belum ───
        $pinjaman1 = PengajuanPinjaman::create([
            'anggota_id' => $anggota1->id,
            'jenis_pinjaman_id' => $jenisPinjaman->id,
            'jumlah_pengajuan' => 6000000,
            'jumlah_disetujui' => 6000000,
            'tenor_bulan' => 6,
            'bunga_total' => 540000, // 1.5% x 6 bulan
            'status' => StatusPengajuan::DISETUJUI,
            'tgl_pengajuan' => $now->copy()->subMonths(3)->subDays(5)->toDateString(),
            'tgl_cair' => $now->copy()->subMonths(3)->toDateString(),
        ]);

        $pokokPerBulan = 1000000;
        $bungaPerBulan = 90000;
        $tglCair1 = $now->copy()->subMonths(3);

        for ($i = 1; $i <= 6; $i++) {
            $tglJatuhTempo = $tglCair1->copy()->addMonths($i);
            $data = [
                'pengajuan_pinjaman_id' => $pinjaman1->id,
                'angsuran_ke' => $i,
                'tgl_jatuh_tempo' => $tglJatuhTempo->toDateString(),
                'jumlah_pokok' => $pokokPerBulan,
                'jumlah_bunga' => $bungaPerBulan,
            ];

            if ($i <= 2) {
                // Lunas — sudah dibayar dan diverifikasi
                $data['tgl_bayar'] = $tglJatuhTempo->copy()->subDays(2)->toDateString();
                $data['total_bayar'] = $pokokPerBulan + $bungaPerBulan;
                $data['status_bayar'] = StatusBayar::LUNAS;
            } elseif ($i === 3) {
                // Menunggu verifikasi — anggota sudah upload bukti
                $data['tgl_bayar'] = $now->copy()->subDays(1)->toDateString();
                $data['status_bayar'] = StatusBayar::MENUNGGU;
                $data['bukti_bayar'] = null; // No actual file in seeder
            }
            // $i >= 4: belum bayar (default)

            Angsuran::create($data);
        }

        // ─── Pinjaman 2: Disetujui 6 bulan lalu, sudah lunas semua ───
        $anggota2 = Anggota::where('email', '!=', 'anggota1@gmail.com')->first();
        if ($anggota2) {
            $jenisPinjaman2 = JenisPinjaman::skip(1)->first() ?? $jenisPinjaman;

            $pinjaman2 = PengajuanPinjaman::create([
                'anggota_id' => $anggota2->id,
                'jenis_pinjaman_id' => $jenisPinjaman2->id,
                'jumlah_pengajuan' => 3000000,
                'jumlah_disetujui' => 3000000,
                'tenor_bulan' => 3,
                'bunga_total' => 180000,
                'status' => StatusPengajuan::LUNAS,
                'tgl_pengajuan' => $now->copy()->subMonths(6)->subDays(3)->toDateString(),
                'tgl_cair' => $now->copy()->subMonths(6)->toDateString(),
            ]);

            $tglCair2 = $now->copy()->subMonths(6);
            for ($i = 1; $i <= 3; $i++) {
                $tglJatuhTempo = $tglCair2->copy()->addMonths($i);
                Angsuran::create([
                    'pengajuan_pinjaman_id' => $pinjaman2->id,
                    'angsuran_ke' => $i,
                    'tgl_jatuh_tempo' => $tglJatuhTempo->toDateString(),
                    'jumlah_pokok' => 1000000,
                    'jumlah_bunga' => 60000,
                    'tgl_bayar' => $tglJatuhTempo->copy()->subDays(3)->toDateString(),
                    'total_bayar' => 1060000,
                    'status_bayar' => StatusBayar::LUNAS,
                ]);
            }
        }

        // ─── Notifikasi untuk anggota1 ───
        $notifikasis = [
            [
                'judul' => 'Pinjaman Disetujui',
                'pesan' => 'Pengajuan pinjaman Anda sebesar Rp 6.000.000 telah disetujui. Jadwal angsuran telah dibuat.',
                'tipe' => TipeNotifikasi::SUKSES,
                'dibaca' => true,
                'created_at' => $now->copy()->subMonths(3),
            ],
            [
                'judul' => 'Pembayaran Angsuran Ke-1 Diterima',
                'pesan' => 'Pembayaran angsuran ke-1 sebesar Rp 1.090.000 telah diverifikasi.',
                'tipe' => TipeNotifikasi::SUKSES,
                'dibaca' => true,
                'created_at' => $now->copy()->subMonths(2)->addDays(1),
            ],
            [
                'judul' => 'Pembayaran Angsuran Ke-2 Diterima',
                'pesan' => 'Pembayaran angsuran ke-2 sebesar Rp 1.090.000 telah diverifikasi.',
                'tipe' => TipeNotifikasi::SUKSES,
                'dibaca' => true,
                'created_at' => $now->copy()->subMonth()->addDays(1),
            ],
            [
                'judul' => 'Pengingat Jatuh Tempo',
                'pesan' => 'Angsuran ke-3 akan jatuh tempo dalam 2 hari. Segera lakukan pembayaran.',
                'tipe' => TipeNotifikasi::PERINGATAN,
                'dibaca' => false,
                'created_at' => $now->copy()->subDays(2),
            ],
            [
                'judul' => 'Selamat Datang',
                'pesan' => 'Selamat bergabung di CU Mentari Kasih TP Pomalaa. Semoga keanggotaan Anda membawa berkah.',
                'tipe' => TipeNotifikasi::INFO,
                'dibaca' => true,
                'created_at' => $now->copy()->subMonths(4),
            ],
        ];

        foreach ($notifikasis as $n) {
            Notifikasi::create(array_merge($n, [
                'anggota_id' => $anggota1->id,
                'link' => route('anggota.angsuran'),
            ]));
        }

        $this->command->info('NotifikasiAngsuranSeeder: Data pinjaman, angsuran, dan notifikasi berhasil dibuat.');
    }
}
