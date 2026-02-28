<?php

namespace App\Livewire\Anggota;

use App\Models\JenisPinjaman;
use App\Models\PengajuanPinjaman;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.admin.livewire-layout')]
#[Title('Buat Pengajuan Pinjaman')]
class PengajuanPinjamanCreate extends Component
{
    public string $jenis_pinjaman_id = '';
    public string $jumlah_pengajuan = '';
    public string $tenor_bulan = '';

    // Computed from jenis pinjaman
    public float $bunga_persen = 0;
    public int $maks_tenor = 0;
    public float $estimasi_bunga = 0;

    protected function rules(): array
    {
        return [
            'jenis_pinjaman_id' => ['required', 'exists:jenis_pinjaman,id'],
            'jumlah_pengajuan' => ['required', 'numeric', 'min:100000'],
            'tenor_bulan' => ['required', 'integer', 'min:1'],
        ];
    }

    public function updatedJenisPinjamanId($value): void
    {
        if ($value) {
            $jenis = JenisPinjaman::find($value);
            if ($jenis) {
                $this->bunga_persen = (float) $jenis->bunga_persen;
                $this->maks_tenor = (int) $jenis->maks_tenor_bulan;
            }
        } else {
            $this->bunga_persen = 0;
            $this->maks_tenor = 0;
        }
        $this->hitungEstimasi();
    }

    public function updatedJumlahPengajuan(): void
    {
        $this->hitungEstimasi();
    }

    public function updatedTenorBulan(): void
    {
        $this->hitungEstimasi();
    }

    protected function hitungEstimasi(): void
    {
        $jumlah = (float) $this->jumlah_pengajuan;
        $tenor = (int) $this->tenor_bulan;

        if ($jumlah > 0 && $tenor > 0 && $this->bunga_persen > 0) {
            $this->estimasi_bunga = $jumlah * ($this->bunga_persen / 100) * $tenor;
        } else {
            $this->estimasi_bunga = 0;
        }
    }

    public function save(): void
    {
        $this->validate();

        // Additional validation: tenor must not exceed max
        if ($this->maks_tenor > 0 && (int) $this->tenor_bulan > $this->maks_tenor) {
            $this->addError('tenor_bulan', "Tenor maksimal adalah {$this->maks_tenor} bulan.");
            return;
        }

        $anggota = Auth::guard('anggota')->user();
        $jumlah = (float) $this->jumlah_pengajuan;
        $tenor = (int) $this->tenor_bulan;

        PengajuanPinjaman::create([
            'anggota_id' => $anggota->id,
            'jenis_pinjaman_id' => $this->jenis_pinjaman_id,
            'jumlah_pengajuan' => $jumlah,
            'tenor_bulan' => $tenor,
            'bunga_total' => $this->estimasi_bunga,
            'status' => 'pending',
            'tgl_pengajuan' => now()->toDateString(),
        ]);

        session()->flash('success', 'Pengajuan pinjaman berhasil diajukan. Silakan tunggu persetujuan admin.');
        $this->redirect(route('anggota.pengajuan-pinjaman'), navigate: true);
    }

    public function render()
    {
        return view('livewire.anggota.pengajuan-pinjaman-create', [
            'jenisPinjamans' => JenisPinjaman::all(),
        ]);
    }
}
