<div>
    <x-admin.page-header title="Angsuran Pinjaman" subtitle="Jadwal dan status pembayaran angsuran Anda" />

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
            style="border-radius: 10px; border: none; border-left: 4px solid var(--success-color); background: rgba(16,185,129,0.1); color: var(--text-primary);">
            <i class="fas fa-check-circle me-2" style="color: var(--success-color);"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($pinjamans->isEmpty())
        <div class="modern-card text-center py-5">
            <div class="text-muted">
                <i class="fas fa-file-invoice-dollar mb-3" style="font-size: 2.5rem;"></i>
                <p class="mb-0">Belum ada pinjaman yang disetujui</p>
            </div>
        </div>
    @else
        {{-- Loan Selector --}}
        <div class="modern-card mb-4">
            <label class="form-label fw-semibold mb-2" style="font-size: 0.85rem;">Pilih Pinjaman</label>
            <select wire:model.live="selectedPinjamanId" class="form-select"
                style="border-radius: 10px; border: 1.5px solid var(--border-color); padding: 0.65rem 1rem;">
                @foreach($pinjamans as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->jenisPinjaman->nama_pinjaman }} — Rp {{ number_format($p->jumlah_disetujui, 0, ',', '.') }}
                        ({{ $p->tenor_bulan }} bulan)
                    </option>
                @endforeach
            </select>
        </div>

        @if($selectedPinjaman)
            {{-- Summary Cards --}}
            @php
                $totalAngsuran = $angsurans->sum(fn($a) => $a->jumlah_pokok + $a->jumlah_bunga);
                $totalDibayar = $angsurans->where('status_bayar', \App\Enum\StatusBayar::LUNAS)->sum('total_bayar');
                $sisaAngsuran = $angsurans->where('status_bayar', '!=', \App\Enum\StatusBayar::LUNAS)->sum(fn($a) => $a->jumlah_pokok + $a->jumlah_bunga);
                $sisaBelum = $angsurans->where('status_bayar', \App\Enum\StatusBayar::BELUM)->count();
                $menunggu = $angsurans->where('status_bayar', \App\Enum\StatusBayar::MENUNGGU)->count();
                $sudahBayar = $angsurans->where('status_bayar', \App\Enum\StatusBayar::LUNAS)->count();
            @endphp
            <div class="row g-3 mb-4">
                <div class="col-lg col-md-4 col-6">
                    <x-admin.stat-card icon="fas fa-money-bill-wave" label="Total Angsuran"
                        value="Rp {{ number_format($totalAngsuran, 0, ',', '.') }}" variant="primary" />
                </div>
                <div class="col-lg col-md-4 col-6">
                    <x-admin.stat-card icon="fas fa-wallet" label="Sisa Angsuran"
                        value="Rp {{ number_format($sisaAngsuran, 0, ',', '.') }}" variant="info" />
                </div>
                <div class="col-lg col-md-4 col-6">
                    <x-admin.stat-card icon="fas fa-check-circle" label="Lunas"
                        value="{{ $sudahBayar }}/{{ $angsurans->count() }}" variant="success" />
                </div>
                <div class="col-lg col-md-6 col-6">
                    <x-admin.stat-card icon="fas fa-clock" label="Menunggu Verifikasi" value="{{ $menunggu }}"
                        variant="primary" />
                </div>
                <div class="col-lg col-md-6 col-6">
                    <x-admin.stat-card icon="fas fa-hourglass-half" label="Belum Bayar" value="{{ $sisaBelum }}"
                        variant="warning" />
                </div>
            </div>

            {{-- Angsuran Table --}}
            <x-admin.table-card title="Jadwal Angsuran" :headers="['#', 'Jatuh Tempo', 'Pokok', 'Bunga', 'Denda', 'Total Bayar', 'Status', 'Aksi']">
                @foreach($angsurans as $a)
                    @php
                        $isOverdue = $a->status_bayar === \App\Enum\StatusBayar::BELUM && \Carbon\Carbon::parse($a->tgl_jatuh_tempo)->isPast();
                    @endphp
                    <tr style="{{ $isOverdue ? 'background: rgba(239, 68, 68, 0.05);' : '' }}">
                        <td class="fw-semibold">{{ $a->angsuran_ke }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($a->tgl_jatuh_tempo)->format('d M Y') }}
                            @if($isOverdue)
                                <br><small style="color: var(--danger-color); font-weight: 600;"><i
                                        class="fas fa-exclamation-triangle"></i> Jatuh tempo</small>
                            @endif
                        </td>
                        <td>Rp {{ number_format($a->jumlah_pokok, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($a->jumlah_bunga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($a->denda, 0, ',', '.') }}</td>
                        <td class="fw-semibold">
                            @if($a->total_bayar)
                                Rp {{ number_format($a->total_bayar, 0, ',', '.') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <x-admin.badge :variant="$a->status_bayar->color()" :icon="$a->status_bayar->icon()">
                                {{ $a->status_bayar->label() }}
                            </x-admin.badge>
                        </td>
                        <td>
                            @if($a->status_bayar === \App\Enum\StatusBayar::BELUM)
                                <button wire:click="openPaymentModal({{ $a->id }})" class="btn btn-sm btn-modern btn-primary-modern">
                                    <i class="fas fa-upload me-1"></i> Bayar
                                </button>
                            @elseif($a->status_bayar === \App\Enum\StatusBayar::MENUNGGU)
                                <span class="text-muted" style="font-size: 0.8rem;"><i class="fas fa-clock"></i> Menunggu</span>
                            @else
                                <span class="text-muted" style="font-size: 0.8rem;">
                                    {{ $a->tgl_bayar ? \Carbon\Carbon::parse($a->tgl_bayar)->format('d M Y') : '—' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-admin.table-card>
        @endif
    @endif

    {{-- Payment Upload Modal --}}
    @if($showPaymentModal)
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none;">
                    <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                        <h5 class="modal-title fw-bold"><i class="fas fa-upload me-2"></i>Kirim Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit="kirimBukti">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Bukti Transfer / Pembayaran</label>
                                <input type="file" wire:model="bukti_bayar" class="form-control" accept="image/*"
                                    style="border-radius: 10px; border: 1.5px solid var(--border-color);">
                                <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                                @error('bukti_bayar')
                                    <div class="mt-1"><small class="text-danger">{{ $message }}</small></div>
                                @enderror
                            </div>

                            @if($bukti_bayar)
                                <div class="mb-3 text-center">
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">Preview:</p>
                                    <img src="{{ $bukti_bayar->temporaryUrl() }}" alt="Preview"
                                        style="max-width: 100%; max-height: 250px; border-radius: 10px; border: 1px solid var(--border-color);">
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                            <button type="button" class="btn btn-modern" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-modern btn-primary-modern" wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="fas fa-paper-plane me-1"></i> Kirim Bukti</span>
                                <span wire:loading><i class="fas fa-spinner fa-spin me-1"></i> Mengunggah...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>