<?php

namespace App\Livewire\Anggota;

use App\Enum\StatusBayar;
use App\Enum\StatusPengajuan;
use App\Enum\TipeNotifikasi;
use App\Models\Angsuran;
use App\Models\Notifikasi;
use App\Models\PengajuanPinjaman;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.admin.livewire-layout')]
#[Title('Angsuran Pinjaman')]
class AngsuranList extends Component
{
    use WithFileUploads;

    public ?int $selectedPinjamanId = null;

    // Payment modal
    public bool $showPaymentModal = false;
    public ?int $payingId = null;
    public $bukti_bayar;

    public function openPaymentModal(int $id): void
    {
        $anggotaId = Auth::guard('anggota')->id();
        $angsuran = Angsuran::whereHas('pengajuanPinjaman', fn($q) => $q->where('anggota_id', $anggotaId))
            ->findOrFail($id);

        $this->payingId = $id;
        $this->bukti_bayar = null;
        $this->resetValidation();
        $this->showPaymentModal = true;
    }

    public function kirimBukti(): void
    {
        $this->validate([
            'bukti_bayar' => ['required', 'image', 'max:2048'],
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_bayar.image' => 'File harus berupa gambar (JPG, PNG, dll).',
            'bukti_bayar.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $anggotaId = Auth::guard('anggota')->id();
        $angsuran = Angsuran::whereHas('pengajuanPinjaman', fn($q) => $q->where('anggota_id', $anggotaId))
            ->findOrFail($this->payingId);

        $path = $this->bukti_bayar->store('bukti-angsuran', 'public');

        $angsuran->update([
            'bukti_bayar' => $path,
            'tgl_bayar' => now()->toDateString(),
            'status_bayar' => StatusBayar::MENUNGGU,
        ]);

        $this->showPaymentModal = false;
        $this->payingId = null;
        $this->bukti_bayar = null;
        session()->flash('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function closeModal(): void
    {
        $this->showPaymentModal = false;
        $this->payingId = null;
        $this->bukti_bayar = null;
        $this->resetValidation();
    }

    public function render()
    {
        $anggotaId = Auth::guard('anggota')->id();

        $pinjamans = PengajuanPinjaman::with('jenisPinjaman')
            ->where('anggota_id', $anggotaId)
            ->whereIn('status', [StatusPengajuan::DISETUJUI, StatusPengajuan::LUNAS])
            ->orderBy('tgl_cair', 'desc')
            ->get();

        $angsurans = collect();
        $selectedPinjaman = null;

        if ($this->selectedPinjamanId) {
            $selectedPinjaman = PengajuanPinjaman::with('jenisPinjaman')
                ->where('anggota_id', $anggotaId)
                ->find($this->selectedPinjamanId);
        }

        if (!$selectedPinjaman && $pinjamans->isNotEmpty()) {
            $this->selectedPinjamanId = $pinjamans->first()->id;
            $selectedPinjaman = $pinjamans->first();
        }

        if ($selectedPinjaman) {
            $angsurans = Angsuran::where('pengajuan_pinjaman_id', $this->selectedPinjamanId)
                ->orderBy('angsuran_ke')
                ->get();
        }

        return view('livewire.anggota.angsuran-list', [
            'pinjamans' => $pinjamans,
            'angsurans' => $angsurans,
            'selectedPinjaman' => $selectedPinjaman,
        ]);
    }
}
