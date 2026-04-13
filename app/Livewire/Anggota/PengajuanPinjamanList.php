<?php

namespace App\Livewire\Anggota;

use App\Enum\StatusPengajuan;
use App\Models\PengajuanPinjaman;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Pinjaman')]
class PengajuanPinjamanList extends Component
{
    use WithPagination;

    public string $filterStatus = '';

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $anggota = Auth::guard('anggota')->user();

        $pengajuans = PengajuanPinjaman::with('jenisPinjaman')
            ->where('anggota_id', $anggota->id)
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.anggota.pengajuan-pinjaman-list', [
            'pengajuans' => $pengajuans,
            'statusOptions' => StatusPengajuan::cases(),
        ]);
    }
}
