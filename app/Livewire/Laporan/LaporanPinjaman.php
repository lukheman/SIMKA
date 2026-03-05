<?php

namespace App\Livewire\Laporan;

use App\Enum\StatusPengajuan;
use App\Models\JenisPinjaman;
use App\Models\PengajuanPinjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Laporan Pinjaman')]
class LaporanPinjaman extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterJenis = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }
    public function updatedFilterJenis()
    {
        $this->resetPage();
    }

    private function buildQuery()
    {
        $query = PengajuanPinjaman::with(['anggota', 'jenisPinjaman'])->latest('tgl_pengajuan');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('anggota', fn($q2) => $q2->where('nama_lengkap', 'like', "%{$this->search}%")
                    ->orWhere('no_anggota', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterStatus)
            $query->where('status', $this->filterStatus);
        if ($this->filterJenis)
            $query->where('jenis_pinjaman_id', $this->filterJenis);

        return $query;
    }

    public function cetakPdf()
    {
        $pengajuans = $this->buildQuery()->get();

        $pdf = Pdf::loadView('pdf.laporan-pinjaman', [
            'pengajuans' => $pengajuans,
            'tanggal' => now()->format('d F Y'),
            'totalPengajuan' => $pengajuans->sum('jumlah_pengajuan'),
            'totalDisetujui' => $pengajuans->where('status', StatusPengajuan::DISETUJUI)->sum('jumlah_disetujui'),
            'filterStatus' => $this->filterStatus,
            'filterJenis' => $this->filterJenis ? JenisPinjaman::find($this->filterJenis)?->nama_pinjaman : '',
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-pinjaman-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        $totalPengajuan = PengajuanPinjaman::sum('jumlah_pengajuan');
        $totalDisetujui = PengajuanPinjaman::where('status', StatusPengajuan::DISETUJUI->value)->sum('jumlah_disetujui');
        $totalPending = PengajuanPinjaman::where('status', StatusPengajuan::PENDING->value)->count();

        return view('livewire.laporan.laporan-pinjaman', [
            'pengajuans' => $this->buildQuery()->paginate(15),
            'totalPengajuan' => $totalPengajuan,
            'totalDisetujui' => $totalDisetujui,
            'totalPending' => $totalPending,
            'jenisPinjamans' => JenisPinjaman::all(),
            'statusOptions' => StatusPengajuan::cases(),
        ]);
    }
}
