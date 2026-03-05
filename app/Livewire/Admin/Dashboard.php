<?php

namespace App\Livewire\Admin;

use App\Enum\StatusPengajuan;
use App\Enum\TipeTransaksi;
use App\Models\Anggota;
use App\Models\PengajuanPinjaman;
use App\Models\TransaksiSimpanan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.admin.livewire-layout')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $totalAnggota = Anggota::count();

        $totalSimpanan = TransaksiSimpanan::where('status', StatusPengajuan::DISETUJUI)
            ->where('tipe_transaksi', TipeTransaksi::SETOR)
            ->sum('jumlah')
            - TransaksiSimpanan::where('status', StatusPengajuan::DISETUJUI)
                ->where('tipe_transaksi', TipeTransaksi::TARIK)
                ->sum('jumlah');

        $totalPinjaman = PengajuanPinjaman::where('status', StatusPengajuan::DISETUJUI)
            ->orWhere('status', StatusPengajuan::LUNAS)
            ->sum('jumlah_disetujui');

        $pengajuanPending = PengajuanPinjaman::where('status', StatusPengajuan::PENDING)->count()
            + TransaksiSimpanan::where('status', StatusPengajuan::PENDING)->count();

        $transaksiTerbaru = TransaksiSimpanan::with(['anggota', 'jenisSimpanan'])
            ->latest('tgl_transaksi')
            ->take(5)
            ->get();

        $pinjamanTerbaru = PengajuanPinjaman::with(['anggota', 'jenisPinjaman'])
            ->latest('tgl_pengajuan')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalAnggota' => $totalAnggota,
            'totalSimpanan' => $totalSimpanan,
            'totalPinjaman' => $totalPinjaman,
            'pengajuanPending' => $pengajuanPending,
            'transaksiTerbaru' => $transaksiTerbaru,
            'pinjamanTerbaru' => $pinjamanTerbaru,
        ]);
    }
}
