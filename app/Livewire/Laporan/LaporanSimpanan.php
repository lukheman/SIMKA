<?php

namespace App\Livewire\Laporan;

use App\Enum\StatusPengajuan;
use App\Enum\TipeTransaksi;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Laporan Simpanan')]
class LaporanSimpanan extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterTipe = '';
    public string $filterJenis = '';
    public string $filterStatus = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedFilterTipe()
    {
        $this->resetPage();
    }
    public function updatedFilterJenis()
    {
        $this->resetPage();
    }
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    private function buildQuery()
    {
        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan'])->latest('tgl_transaksi');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('anggota', fn($q2) => $q2->where('nama_lengkap', 'like', "%{$this->search}%")
                    ->orWhere('no_anggota', 'like', "%{$this->search}%"))
                    ->orWhere('kode_transaksi', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterTipe)
            $query->where('tipe_transaksi', $this->filterTipe);
        if ($this->filterJenis)
            $query->where('jenis_simpanan_id', $this->filterJenis);
        if ($this->filterStatus)
            $query->where('status', $this->filterStatus);

        return $query;
    }

    public function cetakPdf()
    {
        $transaksis = $this->buildQuery()->get();

        $totalSetor = $transaksis->where('tipe_transaksi', TipeTransaksi::SETOR)->where('status', StatusPengajuan::DISETUJUI)->sum('jumlah');
        $totalTarik = $transaksis->where('tipe_transaksi', TipeTransaksi::TARIK)->where('status', StatusPengajuan::DISETUJUI)->sum('jumlah');

        $pdf = Pdf::loadView('pdf.laporan-simpanan', [
            'transaksis' => $transaksis,
            'tanggal' => now()->format('d F Y'),
            'totalSetor' => $totalSetor,
            'totalTarik' => $totalTarik,
            'filterTipe' => $this->filterTipe,
            'filterJenis' => $this->filterJenis ? JenisSimpanan::find($this->filterJenis)?->nama_simpanan : '',
            'filterStatus' => $this->filterStatus,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-simpanan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        $disetujui = TransaksiSimpanan::where('status', StatusPengajuan::DISETUJUI->value);
        $totalSetor = (clone $disetujui)->where('tipe_transaksi', TipeTransaksi::SETOR->value)->sum('jumlah');
        $totalTarik = (clone $disetujui)->where('tipe_transaksi', TipeTransaksi::TARIK->value)->sum('jumlah');

        return view('livewire.laporan.laporan-simpanan', [
            'transaksis' => $this->buildQuery()->paginate(15),
            'totalSetor' => $totalSetor,
            'totalTarik' => $totalTarik,
            'totalSaldo' => $totalSetor - $totalTarik,
            'jenisSimpanans' => JenisSimpanan::all(),
            'tipeOptions' => TipeTransaksi::cases(),
            'statusOptions' => [StatusPengajuan::PENDING, StatusPengajuan::DISETUJUI, StatusPengajuan::DITOLAK],
        ]);
    }
}
