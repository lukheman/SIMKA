<?php

namespace App\Observers;

use App\Mail\NotifikasiAnggotaMail;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifikasiObserver
{
    /**
     * Kirim email ke anggota setiap kali notifikasi dibuat.
     */
    public function created(Notifikasi $notifikasi): void
    {
        // Muat relasi anggota jika belum dimuat
        $notifikasi->loadMissing('anggota');

        $anggota = $notifikasi->anggota;

        if (!$anggota || empty($anggota->email)) {
            Log::warning('NotifikasiObserver: Anggota tidak ditemukan atau email kosong.', [
                'notifikasi_id' => $notifikasi->id,
                'anggota_id' => $notifikasi->anggota_id,
            ]);
            return;
        }

        Mail::to($anggota->email)->send(new NotifikasiAnggotaMail($notifikasi));
    }
}
