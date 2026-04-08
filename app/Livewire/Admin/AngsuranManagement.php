<?php

namespace App\Livewire\Admin;

use App\Enum\StatusBayar;
use App\Enum\StatusPengajuan;
use App\Enum\TipeNotifikasi;
use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\Notifikasi;
use App\Models\PengajuanPinjaman;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Angsuran')]
class AngsuranManagement extends Component
{
    use WithPagination, WithFileUploads;

    // View state: 'anggota' | 'pinjaman' | 'angsuran' | 'pending'
    public string $view = 'anggota';

    #[Url(as: 'q')]
    public string $search = '';

    // Selected anggota
    public ?int $selectedAnggotaId = null;
    public ?string $selectedAnggotaNama = null;

    // Selected pinjaman
    public ?int $selectedPinjamanId = null;

    // Verify modal
    public bool $showVerifyModal = false;
    public ?int $verifyingId = null;
    public ?Angsuran $verifyingAngsuran = null;
    public string $denda = '0';

    // Reject modal
    public bool $showRejectModal = false;
    public ?int $rejectingId = null;

    // Bayar langsung modal
    public bool $showBayarModal = false;
    public ?int $bayarAngsuranId = null;
    public ?Angsuran $bayarAngsuran = null;
    public string $bayarDenda = '0';
    public $bayarBuktiBayar;

    // Preview modal
    public bool $showPreviewModal = false;
    public ?string $previewImage = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ─── Navigation ───

    public function lihatPinjaman(int $anggotaId): void
    {
        $anggota = Anggota::findOrFail($anggotaId);
        $this->selectedAnggotaId = $anggotaId;
        $this->selectedAnggotaNama = $anggota->nama_lengkap;
        $this->view = 'pinjaman';
        $this->search = '';
        $this->resetPage();
    }

    public function lihatAngsuran(int $pinjamanId): void
    {
        $this->selectedPinjamanId = $pinjamanId;
        $this->view = 'angsuran';
    }

    public function kembaliKeAnggota(): void
    {
        $this->view = 'anggota';
        $this->selectedAnggotaId = null;
        $this->selectedAnggotaNama = null;
        $this->selectedPinjamanId = null;
        $this->search = '';
        $this->resetPage();
    }

    public function kembaliKePinjaman(): void
    {
        $this->view = 'pinjaman';
        $this->selectedPinjamanId = null;
    }

    public function lihatPending(): void
    {
        $this->view = 'pending';
        $this->search = '';
        $this->resetPage();
    }

    // ─── Bukti Preview ───

    public function previewBukti(int $id): void
    {
        $angsuran = Angsuran::findOrFail($id);
        $this->previewImage = $angsuran->bukti_bayar;
        $this->showPreviewModal = true;
    }

    public function closePreview(): void
    {
        $this->showPreviewModal = false;
        $this->previewImage = null;
    }

    // ─── Verify Payment ───

    public function openVerifyModal(int $id): void
    {
        $this->verifyingAngsuran = Angsuran::with('pengajuanPinjaman.anggota')->findOrFail($id);
        $this->verifyingId = $id;
        $this->denda = '0';
        $this->showVerifyModal = true;
    }

    public function verify(): void
    {
        $this->validate([
            'denda' => ['required', 'numeric', 'min:0'],
        ]);

        $angsuran = Angsuran::with('pengajuanPinjaman.anggota')->findOrFail($this->verifyingId);
        $totalBayar = $angsuran->jumlah_pokok + $angsuran->jumlah_bunga + (float) $this->denda;

        $angsuran->update([
            'denda' => (float) $this->denda,
            'total_bayar' => $totalBayar,
            'status_bayar' => StatusBayar::LUNAS,
        ]);

        $pinjamanId = $angsuran->pengajuan_pinjaman_id;
        $belumLunas = Angsuran::where('pengajuan_pinjaman_id', $pinjamanId)
            ->where('status_bayar', '!=', StatusBayar::LUNAS)
            ->count();

        if ($belumLunas === 0) {
            PengajuanPinjaman::where('id', $pinjamanId)
                ->update(['status' => StatusPengajuan::LUNAS->value]);

            Notifikasi::create([
                'anggota_id' => $angsuran->pengajuanPinjaman->anggota_id,
                'judul' => 'Pinjaman Lunas',
                'pesan' => 'Selamat! Pinjaman Anda telah lunas. Terima kasih atas pembayaran Anda.',
                'tipe' => TipeNotifikasi::SUKSES,
                'link' => route('anggota.angsuran'),
            ]);
        }

        Notifikasi::create([
            'anggota_id' => $angsuran->pengajuanPinjaman->anggota_id,
            'judul' => 'Pembayaran Angsuran Ke-' . $angsuran->angsuran_ke . ' Diterima',
            'pesan' => 'Pembayaran angsuran ke-' . $angsuran->angsuran_ke . ' sebesar Rp ' . number_format($totalBayar, 0, ',', '.') . ' telah diverifikasi.',
            'tipe' => TipeNotifikasi::SUKSES,
            'link' => route('anggota.angsuran'),
        ]);

        $this->showVerifyModal = false;
        $this->verifyingId = null;
        $this->verifyingAngsuran = null;
        session()->flash('success', 'Pembayaran angsuran berhasil diverifikasi.');
    }

    // ─── Reject Payment ───

    public function openRejectModal(int $id): void
    {
        $this->rejectingId = $id;
        $this->showRejectModal = true;
    }

    public function rejectPayment(): void
    {
        $angsuran = Angsuran::with('pengajuanPinjaman.anggota')->findOrFail($this->rejectingId);

        $angsuran->update([
            'status_bayar' => StatusBayar::BELUM,
            'bukti_bayar' => null,
            'tgl_bayar' => null,
        ]);

        Notifikasi::create([
            'anggota_id' => $angsuran->pengajuanPinjaman->anggota_id,
            'judul' => 'Pembayaran Angsuran Ke-' . $angsuran->angsuran_ke . ' Ditolak',
            'pesan' => 'Bukti pembayaran angsuran ke-' . $angsuran->angsuran_ke . ' ditolak. Silakan kirim ulang bukti yang valid.',
            'tipe' => TipeNotifikasi::BAHAYA,
            'link' => route('anggota.angsuran'),
        ]);

        $this->showRejectModal = false;
        $this->rejectingId = null;
        session()->flash('success', 'Bukti pembayaran ditolak.');
    }

    public function closeModal(): void
    {
        $this->showVerifyModal = false;
        $this->showRejectModal = false;
        $this->verifyingId = null;
        $this->rejectingId = null;
        $this->verifyingAngsuran = null;
    }

    // ─── Bayar Langsung (Admin Offline) ───

    public function openBayarModal(int $id): void
    {
        $this->bayarAngsuran = Angsuran::with('pengajuanPinjaman.anggota')->findOrFail($id);
        $this->bayarAngsuranId = $id;
        $this->bayarDenda = '0';
        $this->bayarBuktiBayar = null;
        $this->resetValidation();
        $this->showBayarModal = true;
    }

    public function bayarLangsung(): void
    {
        $this->validate([
            'bayarDenda' => ['required', 'numeric', 'min:0'],
            'bayarBuktiBayar' => ['nullable', 'image', 'max:2048'],
        ]);

        $angsuran = Angsuran::with('pengajuanPinjaman.anggota')->findOrFail($this->bayarAngsuranId);
        $totalBayar = $angsuran->jumlah_pokok + $angsuran->jumlah_bunga + (float) $this->bayarDenda;

        $path = null;
        if ($this->bayarBuktiBayar) {
            $path = $this->bayarBuktiBayar->store('bukti-angsuran', 'public');
        }

        $angsuran->update([
            'denda' => (float) $this->bayarDenda,
            'total_bayar' => $totalBayar,
            'tgl_bayar' => now()->toDateString(),
            'status_bayar' => StatusBayar::LUNAS,
            'bukti_bayar' => $path,
        ]);

        // Check if all installments are paid
        $pinjamanId = $angsuran->pengajuan_pinjaman_id;
        $belumLunas = Angsuran::where('pengajuan_pinjaman_id', $pinjamanId)
            ->where('status_bayar', '!=', StatusBayar::LUNAS)
            ->count();

        if ($belumLunas === 0) {
            PengajuanPinjaman::where('id', $pinjamanId)
                ->update(['status' => StatusPengajuan::LUNAS->value]);

            Notifikasi::create([
                'anggota_id' => $angsuran->pengajuanPinjaman->anggota_id,
                'judul' => 'Pinjaman Lunas',
                'pesan' => 'Selamat! Pinjaman Anda telah lunas. Terima kasih atas pembayaran Anda.',
                'tipe' => TipeNotifikasi::SUKSES,
                'link' => route('anggota.angsuran'),
            ]);
        }

        Notifikasi::create([
            'anggota_id' => $angsuran->pengajuanPinjaman->anggota_id,
            'judul' => 'Pembayaran Angsuran Ke-' . $angsuran->angsuran_ke . ' Diterima',
            'pesan' => 'Pembayaran angsuran ke-' . $angsuran->angsuran_ke . ' sebesar Rp ' . number_format($totalBayar, 0, ',', '.') . ' telah dibayarkan melalui admin.',
            'tipe' => TipeNotifikasi::SUKSES,
            'link' => route('anggota.angsuran'),
        ]);

        $this->closeBayarModal();
        session()->flash('success', 'Pembayaran angsuran berhasil dicatat.');
    }

    public function closeBayarModal(): void
    {
        $this->showBayarModal = false;
        $this->bayarAngsuranId = null;
        $this->bayarAngsuran = null;
        $this->bayarDenda = '0';
        $this->bayarBuktiBayar = null;
    }

    // ─── Render ───

    public function render()
    {
        $data = [];

        if ($this->view === 'anggota') {
            $data['anggotas'] = Anggota::query()
                ->when($this->search, function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('no_anggota', 'like', '%' . $this->search . '%');
                })
                ->withCount([
                    'pengajuanPinjaman' => fn($q) => $q->whereIn('status', [
                        StatusPengajuan::DISETUJUI->value,
                        StatusPengajuan::LUNAS->value,
                    ])
                ])
                ->orderBy('nama_lengkap')
                ->paginate(15);
        } elseif ($this->view === 'pinjaman') {
            $data['pinjamans'] = PengajuanPinjaman::with('jenisPinjaman')
                ->withCount('angsuran')
                ->withCount(['angsuran as angsuran_lunas_count' => fn($q) => $q->where('status_bayar', StatusBayar::LUNAS)])
                ->withCount(['angsuran as angsuran_menunggu_count' => fn($q) => $q->where('status_bayar', StatusBayar::MENUNGGU)])
                ->where('anggota_id', $this->selectedAnggotaId)
                ->whereIn('status', [StatusPengajuan::DISETUJUI->value, StatusPengajuan::LUNAS->value])
                ->orderBy('tgl_cair', 'desc')
                ->get();
        } elseif ($this->view === 'angsuran') {
            $data['angsurans'] = Angsuran::where('pengajuan_pinjaman_id', $this->selectedPinjamanId)
                ->orderBy('angsuran_ke')
                ->get();
            $data['pinjaman'] = PengajuanPinjaman::with(['jenisPinjaman', 'anggota'])
                ->findOrFail($this->selectedPinjamanId);
        } elseif ($this->view === 'pending') {
            $data['pendingAngsurans'] = Angsuran::with(['pengajuanPinjaman.anggota', 'pengajuanPinjaman.jenisPinjaman'])
                ->where('status_bayar', StatusBayar::MENUNGGU)
                ->orderBy('tgl_bayar', 'desc')
                ->paginate(15);
        }

        $data['pendingCount'] = Angsuran::where('status_bayar', StatusBayar::MENUNGGU)->count();

        return view('livewire.admin.angsuran-management', $data);
    }
}
