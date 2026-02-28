<?php

namespace App\Livewire\Admin;

use App\Enum\StatusPengajuan;
use App\Models\PengajuanPinjaman;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Pengajuan Pinjaman')]
class PengajuanPinjamanManagement extends Component
{
    use WithPagination;

    #[Url(as: 'status')]
    public string $filterStatus = '';

    #[Url(as: 'q')]
    public string $search = '';

    // Approval modal
    public bool $showApproveModal = false;
    public ?int $approvingId = null;
    public string $jumlah_disetujui = '';

    // Reject modal
    public bool $showRejectModal = false;
    public ?int $rejectingId = null;
    public string $alasan_tolak = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openApproveModal(int $id): void
    {
        $pengajuan = PengajuanPinjaman::findOrFail($id);
        $this->approvingId = $id;
        $this->jumlah_disetujui = (string) $pengajuan->jumlah_pengajuan;
        $this->showApproveModal = true;
    }

    public function approve(): void
    {
        $this->validate([
            'jumlah_disetujui' => ['required', 'numeric', 'min:1'],
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($this->approvingId);
        $pengajuan->update([
            'jumlah_disetujui' => (float) $this->jumlah_disetujui,
            'status' => StatusPengajuan::DISETUJUI->value,
            'tgl_cair' => now()->toDateString(),
        ]);

        $this->showApproveModal = false;
        $this->approvingId = null;
        $this->jumlah_disetujui = '';
        session()->flash('success', 'Pengajuan pinjaman berhasil disetujui.');
    }

    public function openRejectModal(int $id): void
    {
        $this->rejectingId = $id;
        $this->alasan_tolak = '';
        $this->showRejectModal = true;
    }

    public function reject(): void
    {
        $this->validate([
            'alasan_tolak' => ['required', 'string', 'min:10'],
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($this->rejectingId);
        $pengajuan->update([
            'status' => StatusPengajuan::DITOLAK->value,
            'alasan_tolak' => $this->alasan_tolak,
        ]);

        $this->showRejectModal = false;
        $this->rejectingId = null;
        $this->alasan_tolak = '';
        session()->flash('success', 'Pengajuan pinjaman telah ditolak.');
    }

    public function closeModal(): void
    {
        $this->showApproveModal = false;
        $this->showRejectModal = false;
        $this->approvingId = null;
        $this->rejectingId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $pengajuans = PengajuanPinjaman::with(['anggota', 'jenisPinjaman'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, function ($q) {
                $q->whereHas('anggota', function ($q2) {
                    $q2->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('no_anggota', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.pengajuan-pinjaman-management', [
            'pengajuans' => $pengajuans,
            'statusOptions' => StatusPengajuan::cases(),
        ]);
    }
}
