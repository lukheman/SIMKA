<?php

namespace App\Livewire\Admin;

use App\Enum\StatusPengajuan;
use App\Enum\TipeNotifikasi;
use App\Enum\TipeTransaksi;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\Notifikasi;
use App\Models\TransaksiSimpanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Transaksi Simpanan')]
class TransaksiSimpananManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterTipe = '';
    public string $filterJenis = '';
    public string $filterStatus = '';

    // Create/Edit form
    public bool $showCreateModal = false;
    public bool $isEditing = false;
    public ?int $editId = null;
    public $anggota_id = '';
    public $jenis_simpanan_id = '';
    public $tipe_transaksi = '';
    public $jumlah = '';
    public $keterangan = '';
    public ?float $minimalSetor = null;
    public ?float $saldoAnggota = null;

    // Approve form
    public bool $showApproveModal = false;
    public ?int $approveId = null;

    // Reject form
    public bool $showRejectModal = false;
    public ?int $rejectId = null;
    public string $alasan_tolak = '';

    // Delete form
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

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

    public function updatedJenisSimpananId($value)
    {
        $this->minimalSetor = $value ? JenisSimpanan::find($value)?->minimal_setor : null;
        $this->updateSaldo();
    }

    public function updatedAnggotaId()
    {
        $this->updateSaldo();
    }
    public function updatedTipeTransaksi()
    {
        $this->updateSaldo();
    }

    private function updateSaldo(): void
    {
        if ($this->anggota_id && $this->jenis_simpanan_id) {
            $setor = TransaksiSimpanan::where('anggota_id', $this->anggota_id)
                ->where('jenis_simpanan_id', $this->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::SETOR->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $tarik = TransaksiSimpanan::where('anggota_id', $this->anggota_id)
                ->where('jenis_simpanan_id', $this->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::TARIK->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $this->saldoAnggota = $setor - $tarik;
        } else {
            $this->saldoAnggota = null;
        }
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['anggota_id', 'jenis_simpanan_id', 'tipe_transaksi', 'jumlah', 'keterangan', 'minimalSetor', 'saldoAnggota', 'isEditing', 'editId']);
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $trx = TransaksiSimpanan::findOrFail($id);

        $this->editId = $id;
        $this->isEditing = true;
        $this->anggota_id = $trx->anggota_id;
        $this->jenis_simpanan_id = $trx->jenis_simpanan_id;
        $this->tipe_transaksi = $trx->tipe_transaksi instanceof \App\Enum\TipeTransaksi ? $trx->tipe_transaksi->value : $trx->tipe_transaksi;
        $this->jumlah = $trx->jumlah;
        $this->keterangan = $trx->keterangan;
        $this->minimalSetor = $trx->jenisSimpanan?->minimal_setor;
        $this->updateSaldo();
        $this->showCreateModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->isEditing = false;
        $this->editId = null;
    }

    public function save(): void
    {
        $this->validate([
            'anggota_id' => ['required', 'exists:anggota,id'],
            'jenis_simpanan_id' => ['required', 'exists:jenis_simpanan,id'],
            'tipe_transaksi' => ['required', 'in:setor,tarik'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $jenis = JenisSimpanan::find($this->jenis_simpanan_id);

        if ($this->tipe_transaksi === 'setor' && $this->jumlah < $jenis->minimal_setor) {
            $this->addError('jumlah', 'Jumlah setor minimal Rp ' . number_format($jenis->minimal_setor, 0, ',', '.'));
            return;
        }

        if ($this->tipe_transaksi === 'tarik') {
            $this->updateSaldo();
            if ($this->jumlah > $this->saldoAnggota) {
                $this->addError('jumlah', 'Saldo tidak mencukupi. Saldo: Rp ' . number_format($this->saldoAnggota, 0, ',', '.'));
                return;
            }
        }

        $kode = 'TRX-S-' . str_pad(TransaksiSimpanan::count() + 1, 6, '0', STR_PAD_LEFT);

        TransaksiSimpanan::create([
            'anggota_id' => $this->anggota_id,
            'jenis_simpanan_id' => $this->jenis_simpanan_id,
            'kode_transaksi' => $kode,
            'tipe_transaksi' => $this->tipe_transaksi,
            'jumlah' => $this->jumlah,
            'tgl_transaksi' => now()->toDateString(),
            'keterangan' => $this->keterangan,
            'status' => StatusPengajuan::DISETUJUI->value,
        ]);

        $this->closeModal();
        session()->flash('success', 'Transaksi simpanan berhasil ditambahkan.');
    }

    public function update(): void
    {
        if (!$this->editId) return;

        $this->validate([
            'anggota_id' => ['required', 'exists:anggota,id'],
            'jenis_simpanan_id' => ['required', 'exists:jenis_simpanan,id'],
            'tipe_transaksi' => ['required', 'in:setor,tarik'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $jenis = JenisSimpanan::find($this->jenis_simpanan_id);

        if ($this->tipe_transaksi === 'setor' && $this->jumlah < $jenis->minimal_setor) {
            $this->addError('jumlah', 'Jumlah setor minimal Rp ' . number_format($jenis->minimal_setor, 0, ',', '.'));
            return;
        }

        if ($this->tipe_transaksi === 'tarik') {
            // Calculate saldo excluding current transaction
            $setor = TransaksiSimpanan::where('anggota_id', $this->anggota_id)
                ->where('jenis_simpanan_id', $this->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::SETOR->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->where('id', '!=', $this->editId)
                ->sum('jumlah');
            $tarik = TransaksiSimpanan::where('anggota_id', $this->anggota_id)
                ->where('jenis_simpanan_id', $this->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::TARIK->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->where('id', '!=', $this->editId)
                ->sum('jumlah');
            $saldo = $setor - $tarik;

            if ($this->jumlah > $saldo) {
                $this->addError('jumlah', 'Saldo tidak mencukupi. Saldo: Rp ' . number_format($saldo, 0, ',', '.'));
                return;
            }
        }

        $trx = TransaksiSimpanan::findOrFail($this->editId);
        $trx->update([
            'anggota_id' => $this->anggota_id,
            'jenis_simpanan_id' => $this->jenis_simpanan_id,
            'tipe_transaksi' => $this->tipe_transaksi,
            'jumlah' => $this->jumlah,
            'keterangan' => $this->keterangan,
        ]);

        $this->closeModal();
        session()->flash('success', 'Transaksi simpanan berhasil diperbarui.');
    }

    public function openApproveModal(int $id): void
    {
        $this->approveId = $id;
        $this->showApproveModal = true;
    }

    public function closeApproveModal(): void
    {
        $this->showApproveModal = false;
        $this->approveId = null;
    }

    public function approve(): void
    {
        if (!$this->approveId)
            return;

        $trx = TransaksiSimpanan::findOrFail($this->approveId);

        if ($trx->status !== StatusPengajuan::PENDING) {
            $this->closeApproveModal();
            return;
        }

        // Validate saldo for tarik
        if ($trx->tipe_transaksi === TipeTransaksi::TARIK) {
            $setor = TransaksiSimpanan::where('anggota_id', $trx->anggota_id)
                ->where('jenis_simpanan_id', $trx->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::SETOR->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $tarik = TransaksiSimpanan::where('anggota_id', $trx->anggota_id)
                ->where('jenis_simpanan_id', $trx->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::TARIK->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $saldo = $setor - $tarik;

            if ($trx->jumlah > $saldo) {
                session()->flash('error', 'Saldo anggota tidak mencukupi untuk penarikan ini.');
                $this->closeApproveModal();
                return;
            }
        }

        $trx->update([
            'status' => StatusPengajuan::DISETUJUI->value,
        ]);

        $tipeLabel = $trx->tipe_transaksi === TipeTransaksi::SETOR ? 'setoran' : 'penarikan';
        Notifikasi::create([
            'anggota_id' => $trx->anggota_id,
            'judul' => 'Transaksi Simpanan Disetujui',
            'pesan' => 'Transaksi ' . $tipeLabel . ' sebesar Rp ' . number_format($trx->jumlah, 0, ',', '.') . ' telah disetujui.',
            'tipe' => TipeNotifikasi::SUKSES,
            'link' => route('anggota.simpanan'),
        ]);

        $this->closeApproveModal();
        session()->flash('success', 'Pengajuan simpanan berhasil disetujui.');
    }

    public function openRejectModal(int $id): void
    {
        $this->rejectId = $id;
        $this->alasan_tolak = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->rejectId = null;
        $this->alasan_tolak = '';
    }

    public function reject(): void
    {
        $this->validate(['alasan_tolak' => ['required', 'string']]);

        $trx = TransaksiSimpanan::findOrFail($this->rejectId);

        if ($trx->status !== StatusPengajuan::PENDING)
            return;

        $trx->update([
            'status' => StatusPengajuan::DITOLAK->value,
            'alasan_tolak' => $this->alasan_tolak,
        ]);

        $tipeLabel = $trx->tipe_transaksi === TipeTransaksi::SETOR ? 'setoran' : 'penarikan';
        Notifikasi::create([
            'anggota_id' => $trx->anggota_id,
            'judul' => 'Transaksi Simpanan Ditolak',
            'pesan' => 'Transaksi ' . $tipeLabel . ' Anda ditolak. Alasan: ' . $this->alasan_tolak,
            'tipe' => TipeNotifikasi::BAHAYA,
            'link' => route('anggota.simpanan'),
        ]);

        $this->closeRejectModal();
        session()->flash('success', 'Pengajuan simpanan ditolak.');
    }

    public function openDeleteModal(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function delete(): void
    {
        if (!$this->deleteId) return;

        $trx = TransaksiSimpanan::findOrFail($this->deleteId);
        $trx->delete();

        $this->closeDeleteModal();
        session()->flash('success', 'Transaksi simpanan berhasil dihapus.');
    }

    public function render()
    {
        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan'])
            ->latest('created_at');

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

        return view('livewire.admin.transaksi-simpanan-management', [
            'transaksis' => $query->paginate(15),
            'anggotas' => Anggota::orderBy('nama_lengkap')->get(),
            'jenisSimpanans' => JenisSimpanan::all(),
            'tipeOptions' => TipeTransaksi::cases(),
            'statusOptions' => [StatusPengajuan::PENDING, StatusPengajuan::DISETUJUI, StatusPengajuan::DITOLAK],
            'pendingCount' => TransaksiSimpanan::where('status', StatusPengajuan::PENDING->value)->count(),
        ]);
    }
}
