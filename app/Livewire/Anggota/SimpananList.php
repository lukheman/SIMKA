<?php

namespace App\Livewire\Anggota;

use App\Enum\StatusPengajuan;
use App\Enum\TipeTransaksi;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Simpanan Saya')]
class SimpananList extends Component
{
    use WithPagination;

    public string $filterJenis = '';
    public string $filterTipe = '';
    public string $filterStatus = '';

    // Form pengajuan
    public bool $showPengajuanModal = false;
    public $jenis_simpanan_id = '';
    public $tipe_transaksi = '';
    public $jumlah = '';
    public $keterangan = '';
    public ?float $minimalSetor = null;
    public ?float $saldoJenis = null;

    public function updatedFilterJenis()
    {
        $this->resetPage();
    }
    public function updatedFilterTipe()
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
        $this->updateSaldoJenis();
    }

    public function updatedTipeTransaksi()
    {
        $this->updateSaldoJenis();
    }

    private function updateSaldoJenis(): void
    {
        if ($this->jenis_simpanan_id) {
            $anggotaId = Auth::guard('anggota')->id();
            $setor = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $this->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::SETOR->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $tarik = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $this->jenis_simpanan_id)
                ->where('tipe_transaksi', TipeTransaksi::TARIK->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $this->saldoJenis = $setor - $tarik;
        } else {
            $this->saldoJenis = null;
        }
    }

    public function openPengajuanModal(): void
    {
        $this->resetValidation();
        $this->reset(['jenis_simpanan_id', 'tipe_transaksi', 'jumlah', 'keterangan', 'minimalSetor', 'saldoJenis']);
        $this->showPengajuanModal = true;
    }

    public function closePengajuanModal(): void
    {
        $this->showPengajuanModal = false;
    }

    public function submitPengajuan(): void
    {
        $this->validate([
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
            $this->updateSaldoJenis();
            if ($this->jumlah > $this->saldoJenis) {
                $this->addError('jumlah', 'Saldo tidak mencukupi. Saldo: Rp ' . number_format($this->saldoJenis, 0, ',', '.'));
                return;
            }
        }

        $kode = 'TRX-S-' . str_pad(TransaksiSimpanan::count() + 1, 6, '0', STR_PAD_LEFT);

        TransaksiSimpanan::create([
            'anggota_id' => Auth::guard('anggota')->id(),
            'jenis_simpanan_id' => $this->jenis_simpanan_id,
            'kode_transaksi' => $kode,
            'tipe_transaksi' => $this->tipe_transaksi,
            'jumlah' => $this->jumlah,
            'tgl_transaksi' => now()->toDateString(),
            'keterangan' => $this->keterangan,
            'status' => StatusPengajuan::PENDING->value,
        ]);

        $this->closePengajuanModal();
        session()->flash('success', 'Pengajuan simpanan berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function render()
    {
        $anggotaId = Auth::guard('anggota')->id();

        // Saldo per jenis (hanya dari disetujui)
        $jenisSimpanans = JenisSimpanan::all();
        $saldos = [];
        foreach ($jenisSimpanans as $jenis) {
            $setor = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $jenis->id)
                ->where('tipe_transaksi', TipeTransaksi::SETOR->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $tarik = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $jenis->id)
                ->where('tipe_transaksi', TipeTransaksi::TARIK->value)
                ->where('status', StatusPengajuan::DISETUJUI->value)
                ->sum('jumlah');
            $saldos[] = [
                'nama' => $jenis->nama_simpanan,
                'saldo' => $setor - $tarik,
            ];
        }

        // Riwayat transaksi
        $query = TransaksiSimpanan::with('jenisSimpanan')
            ->where('anggota_id', $anggotaId)
            ->latest('created_at');

        if ($this->filterJenis)
            $query->where('jenis_simpanan_id', $this->filterJenis);
        if ($this->filterTipe)
            $query->where('tipe_transaksi', $this->filterTipe);
        if ($this->filterStatus)
            $query->where('status', $this->filterStatus);

        $pendingCount = TransaksiSimpanan::where('anggota_id', $anggotaId)
            ->where('status', StatusPengajuan::PENDING->value)
            ->count();

        return view('livewire.anggota.simpanan-list', [
            'saldos' => $saldos,
            'transaksis' => $query->paginate(15),
            'jenisSimpanans' => $jenisSimpanans,
            'tipeOptions' => TipeTransaksi::cases(),
            'statusOptions' => [StatusPengajuan::PENDING, StatusPengajuan::DISETUJUI, StatusPengajuan::DITOLAK],
            'totalSaldo' => collect($saldos)->sum('saldo'),
            'pendingCount' => $pendingCount,
        ]);
    }
}
