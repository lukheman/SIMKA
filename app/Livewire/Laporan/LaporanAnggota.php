<?php

namespace App\Livewire\Laporan;

use App\Models\Anggota;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Laporan Anggota')]
class LaporanAnggota extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function cetakPdf()
    {
        $query = Anggota::query()->orderBy('no_anggota');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                    ->orWhere('no_anggota', 'like', "%{$this->search}%")
                    ->orWhere('nik', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus) {
            $query->where('status_aktif', $this->filterStatus);
        }

        $anggotas = $query->get();

        $pdf = Pdf::loadView('pdf.laporan-anggota', [
            'anggotas' => $anggotas,
            'tanggal' => now()->format('d F Y'),
            'filterStatus' => $this->filterStatus,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-anggota-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        $query = Anggota::query()->orderBy('no_anggota');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                    ->orWhere('no_anggota', 'like', "%{$this->search}%")
                    ->orWhere('nik', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus) {
            $query->where('status_aktif', $this->filterStatus);
        }

        return view('livewire.laporan.laporan-anggota', [
            'anggotas' => $query->paginate(15),
            'totalAnggota' => Anggota::count(),
            'totalAktif' => Anggota::where('status_aktif', \App\Enum\StatusAktif::AKTIF->value)->count(),
            'totalNonaktif' => Anggota::where('status_aktif', '!=', \App\Enum\StatusAktif::AKTIF->value)->count(),
            'statusOptions' => \App\Enum\StatusAktif::cases(),
        ]);
    }
}
