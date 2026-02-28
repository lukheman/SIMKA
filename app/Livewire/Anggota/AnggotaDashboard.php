<?php

namespace App\Livewire\Anggota;

use App\Models\PengajuanPinjaman;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.admin.livewire-layout')]
#[Title('Dashboard Anggota')]
class AnggotaDashboard extends Component
{
    public function render()
    {
        $anggota = Auth::guard('anggota')->user();

        $totalPengajuan = PengajuanPinjaman::where('anggota_id', $anggota->id)->count();
        $pengajuanPending = PengajuanPinjaman::where('anggota_id', $anggota->id)->where('status', 'pending')->count();
        $pengajuanDisetujui = PengajuanPinjaman::where('anggota_id', $anggota->id)->where('status', 'disetujui')->count();

        $recentPengajuan = PengajuanPinjaman::with('jenisPinjaman')
            ->where('anggota_id', $anggota->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.anggota.dashboard', [
            'anggota' => $anggota,
            'totalPengajuan' => $totalPengajuan,
            'pengajuanPending' => $pengajuanPending,
            'pengajuanDisetujui' => $pengajuanDisetujui,
            'recentPengajuan' => $recentPengajuan,
        ]);
    }
}
