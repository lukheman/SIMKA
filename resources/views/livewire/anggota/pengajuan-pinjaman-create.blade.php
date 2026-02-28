<div>
    <x-admin.page-header title="Ajukan Pinjaman" subtitle="Isi formulir pengajuan pinjaman">
        <x-slot:actions>
            <a href="{{ route('anggota.pengajuan-pinjaman') }}" wire:navigate class="btn btn-modern"
                style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="row g-4">
        {{-- Form --}}
        <div class="col-md-7">
            <div class="modern-card">
                <h6 class="mb-3" style="color: var(--text-primary); font-weight: 600;">
                    <i class="fas fa-file-invoice-dollar me-2" style="color: var(--primary-color);"></i>Formulir
                    Pengajuan
                </h6>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="jenis_pinjaman_id" class="form-label">Jenis Pinjaman <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-control @error('jenis_pinjaman_id') is-invalid @enderror"
                            id="jenis_pinjaman_id" wire:model.live="jenis_pinjaman_id">
                            <option value="">-- Pilih Jenis Pinjaman --</option>
                            @foreach ($jenisPinjamans as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_pinjaman }} (Bunga:
                                    {{ $jenis->bunga_persen }}%/bulan, Maks: {{ $jenis->maks_tenor_bulan }} bulan)</option>
                            @endforeach
                        </select>
                        @error('jenis_pinjaman_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_pengajuan" class="form-label">Jumlah Pinjaman (Rp) <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" class="form-control @error('jumlah_pengajuan') is-invalid @enderror"
                            id="jumlah_pengajuan" wire:model.live.debounce.300ms="jumlah_pengajuan"
                            placeholder="Masukkan jumlah pinjaman" min="100000" step="50000">
                        @error('jumlah_pengajuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tenor_bulan" class="form-label">
                            Tenor (Bulan) <span style="color: var(--danger-color);">*</span>
                            @if ($maks_tenor > 0)
                                <small class="text-muted">(Maks: {{ $maks_tenor }} bulan)</small>
                            @endif
                        </label>
                        <input type="number" class="form-control @error('tenor_bulan') is-invalid @enderror"
                            id="tenor_bulan" wire:model.live.debounce.300ms="tenor_bulan" placeholder="Masukkan tenor"
                            min="1" @if($maks_tenor > 0) max="{{ $maks_tenor }}" @endif>
                        @error('tenor_bulan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr style="border-color: var(--border-color); margin: 1.5rem 0;">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('anggota.pengajuan-pinjaman') }}" wire:navigate class="btn btn-modern"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-modern btn-primary-modern">
                            <span wire:loading.remove wire:target="save">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Pinjaman
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fas fa-spinner fa-spin me-2"></i>Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Estimasi --}}
        <div class="col-md-5">
            <div class="modern-card" style="position: sticky; top: calc(var(--topbar-height) + 2rem);">
                <h6 class="mb-3" style="color: var(--text-primary); font-weight: 600;">
                    <i class="fas fa-calculator me-2" style="color: var(--primary-color);"></i>Estimasi Pinjaman
                </h6>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Jumlah Pinjaman</span>
                        <strong style="color: var(--text-primary);">Rp
                            {{ number_format((float) $jumlah_pengajuan, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Bunga per Bulan</span>
                        <strong style="color: var(--text-primary);">{{ $bunga_persen }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tenor</span>
                        <strong style="color: var(--text-primary);">{{ $tenor_bulan ?: 0 }} bulan</strong>
                    </div>
                    <hr style="border-color: var(--border-color); margin: 0.5rem 0;">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Estimasi Total Bunga</span>
                        <strong style="color: var(--warning-color);">Rp
                            {{ number_format($estimasi_bunga, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size: 1.1rem;">
                        <span class="fw-semibold" style="color: var(--text-primary);">Total Pengembalian</span>
                        <strong style="color: var(--primary-color);">Rp
                            {{ number_format((float) $jumlah_pengajuan + $estimasi_bunga, 0, ',', '.') }}</strong>
                    </div>
                    @if ((float) $jumlah_pengajuan > 0 && (int) $tenor_bulan > 0)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Estimasi Angsuran/Bulan</span>
                            <strong style="color: var(--success-color);">Rp
                                {{ number_format(((float) $jumlah_pengajuan + $estimasi_bunga) / (int) $tenor_bulan, 0, ',', '.') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>