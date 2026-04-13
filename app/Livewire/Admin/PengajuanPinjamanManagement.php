<?php

namespace App\Livewire\Admin;

use App\Enum\StatusPengajuan;
use App\Enum\TipeNotifikasi;
use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\JenisPinjaman;
use App\Models\Notifikasi;
use App\Models\PengajuanPinjaman;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Pinjaman')]
class PengajuanPinjamanManagement extends Component
{
    use WithPagination;

    #[Url(as: 'status')]
    public string $filterStatus = '';

    #[Url(as: 'q')]
    public string $search = '';

    // Create modal
    public bool $showCreateModal = false;
    public string $create_anggota_id = '';
    public string $create_jenis_pinjaman_id = '';
    public string $create_jumlah_pengajuan = '';
    public string $create_tenor_bulan = '';
    public float $create_bunga_persen = 0;
    public int $create_maks_tenor = 0;
    public float $create_estimasi_bunga = 0;

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

    // === Create loan methods ===

    public function openCreateModal(): void
    {
        $this->reset([
            'create_anggota_id',
            'create_jenis_pinjaman_id',
            'create_jumlah_pengajuan',
            'create_tenor_bulan',
            'create_bunga_persen',
            'create_maks_tenor',
            'create_estimasi_bunga',
        ]);
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function updatedCreateJenisPinjamanId($value): void
    {
        if ($value) {
            $jenis = JenisPinjaman::find($value);
            if ($jenis) {
                $this->create_bunga_persen = (float) $jenis->bunga_persen;
                $this->create_maks_tenor = (int) $jenis->maks_tenor_bulan;
            }
        } else {
            $this->create_bunga_persen = 0;
            $this->create_maks_tenor = 0;
        }
        $this->hitungEstimasi();
    }

    public function updatedCreateJumlahPengajuan(): void
    {
        $this->hitungEstimasi();
    }

    public function updatedCreateTenorBulan(): void
    {
        $this->hitungEstimasi();
    }

    protected function hitungEstimasi(): void
    {
        $jumlah = (float) $this->create_jumlah_pengajuan;
        $tenor = (int) $this->create_tenor_bulan;

        if ($jumlah > 0 && $tenor > 0 && $this->create_bunga_persen > 0) {
            $this->create_estimasi_bunga = $jumlah * ($this->create_bunga_persen / 100) * $tenor;
        } else {
            $this->create_estimasi_bunga = 0;
        }
    }

    public function createPinjaman(): void
    {
        $this->validate([
            'create_anggota_id' => ['required', 'exists:anggota,id'],
            'create_jenis_pinjaman_id' => ['required', 'exists:jenis_pinjaman,id'],
            'create_jumlah_pengajuan' => ['required', 'numeric', 'min:100000'],
            'create_tenor_bulan' => ['required', 'integer', 'min:1'],
        ], [
            'create_anggota_id.required' => 'Pilih anggota.',
            'create_jenis_pinjaman_id.required' => 'Pilih jenis pinjaman.',
            'create_jumlah_pengajuan.required' => 'Jumlah pinjaman wajib diisi.',
            'create_jumlah_pengajuan.min' => 'Jumlah pinjaman minimal Rp 100.000.',
            'create_tenor_bulan.required' => 'Tenor wajib diisi.',
            'create_tenor_bulan.min' => 'Tenor minimal 1 bulan.',
        ]);

        if ($this->create_maks_tenor > 0 && (int) $this->create_tenor_bulan > $this->create_maks_tenor) {
            $this->addError('create_tenor_bulan', "Tenor maksimal adalah {$this->create_maks_tenor} bulan.");
            return;
        }

        $jumlah = (float) $this->create_jumlah_pengajuan;
        $tenor = (int) $this->create_tenor_bulan;

        PengajuanPinjaman::create([
            'anggota_id' => $this->create_anggota_id,
            'jenis_pinjaman_id' => $this->create_jenis_pinjaman_id,
            'jumlah_pengajuan' => $jumlah,
            'tenor_bulan' => $tenor,
            'bunga_total' => $this->create_estimasi_bunga,
            'status' => 'pending',
            'tgl_pengajuan' => now()->toDateString(),
        ]);

        Notifikasi::create([
            'anggota_id' => $this->create_anggota_id,
            'judul' => 'Pengajuan Pinjaman Baru',
            'pesan' => 'Admin telah membuat pengajuan pinjaman sebesar Rp ' . number_format($jumlah, 0, ',', '.') . ' atas nama Anda.',
            'tipe' => TipeNotifikasi::INFO,
            'link' => route('anggota.pengajuan-pinjaman'),
        ]);

        $this->showCreateModal = false;
        session()->flash('success', 'Pengajuan pinjaman berhasil dibuat.');
    }

    // === Approve / Reject methods ===

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

        // Auto-generate angsuran records
        $jumlahDisetujui = (float) $this->jumlah_disetujui;
        $tenor = $pengajuan->tenor_bulan;
        $bungaTotal = (float) $pengajuan->bunga_total;
        $pokokPerBulan = round($jumlahDisetujui / $tenor, 2);
        $bungaPerBulan = round($bungaTotal / $tenor, 2);
        $tglCair = now();

        for ($i = 1; $i <= $tenor; $i++) {
            Angsuran::create([
                'pengajuan_pinjaman_id' => $pengajuan->id,
                'angsuran_ke' => $i,
                'tgl_jatuh_tempo' => $tglCair->copy()->addMonths($i)->toDateString(),
                'jumlah_pokok' => $pokokPerBulan,
                'jumlah_bunga' => $bungaPerBulan,
            ]);
        }

        Notifikasi::create([
            'anggota_id' => $pengajuan->anggota_id,
            'judul' => 'Pinjaman Disetujui',
            'pesan' => 'Pengajuan pinjaman Anda sebesar Rp ' . number_format($jumlahDisetujui, 0, ',', '.') . ' telah disetujui. Jadwal angsuran telah dibuat.',
            'tipe' => TipeNotifikasi::SUKSES,
            'link' => route('anggota.pengajuan-pinjaman'),
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

        Notifikasi::create([
            'anggota_id' => $pengajuan->anggota_id,
            'judul' => 'Pinjaman Ditolak',
            'pesan' => 'Pengajuan pinjaman Anda telah ditolak. Alasan: ' . $this->alasan_tolak,
            'tipe' => TipeNotifikasi::BAHAYA,
            'link' => route('anggota.pengajuan-pinjaman'),
        ]);

        $this->showRejectModal = false;
        $this->rejectingId = null;
        $this->alasan_tolak = '';
        session()->flash('success', 'Pengajuan pinjaman telah ditolak.');
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
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
            'anggotaList' => Anggota::orderBy('nama_lengkap')->get(),
            'jenisPinjamanList' => JenisPinjaman::all(),
        ]);
    }
}
