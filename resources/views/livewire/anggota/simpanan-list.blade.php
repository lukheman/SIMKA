<div>
    <x-admin.page-header title="Simpanan Saya" subtitle="Lihat saldo dan ajukan simpanan">
        <x-slot:actions>
            <button wire:click="openPengajuanModal" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-plus me-2"></i>Ajukan Simpanan
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">{{ session('success') }}</x-admin.alert>
    @endif

    {{-- Saldo Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg-3">
            <div class="stat-card" style="--accent-color: var(--primary-color);">
                <div class="stat-icon" style="background: rgba(74,127,181,0.12); color: var(--primary-color);"><i
                        class="fas fa-wallet"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Saldo</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">Rp
                    {{ number_format($totalSaldo, 0, ',', '.') }}</div>
            </div>
        </div>
        @foreach ($saldos as $saldo)
            <div class="col-md-4 col-lg-3">
                <div class="stat-card" style="--accent-color: var(--success-color);">
                    <div class="stat-icon" style="background: rgba(92,173,138,0.12); color: var(--success-color);"><i
                            class="fas fa-piggy-bank"></i></div>
                    <div class="text-muted"
                        style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ $saldo['nama'] }}</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Rp
                        {{ number_format($saldo['saldo'], 0, ',', '.') }}</div>
                </div>
            </div>
        @endforeach
        @if ($pendingCount > 0)
            <div class="col-md-4 col-lg-3">
                <div class="stat-card" style="--accent-color: var(--warning-color);">
                    <div class="stat-icon" style="background: rgba(255,193,7,0.12); color: var(--warning-color);"><i
                            class="fas fa-clock"></i></div>
                    <div class="text-muted"
                        style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Menunggu</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">{{ $pendingCount }}
                        pengajuan</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Transaction History --}}
    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Riwayat Transaksi</h5>
            <div class="d-flex gap-2">
                <select class="form-control" wire:model.live="filterJenis" style="min-width: 150px;">
                    <option value="">Semua Jenis</option>
                    @foreach ($jenisSimpanans as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_simpanan }}</option>
                    @endforeach
                </select>
                <select class="form-control" wire:model.live="filterTipe" style="min-width: 120px;">
                    <option value="">Semua Tipe</option>
                    @foreach ($tipeOptions as $tipe)
                        <option value="{{ $tipe->value }}">{{ $tipe->label() }}</option>
                    @endforeach
                </select>
                <select class="form-control" wire:model.live="filterStatus" style="min-width: 130px;">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Jenis Simpanan</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $t)
                        <tr wire:key="trx-{{ $t->id }}">
                            <td><code style="color: var(--primary-color);">{{ $t->kode_transaksi }}</code></td>
                            <td class="fw-semibold" style="color: var(--text-primary);">
                                {{ $t->jenisSimpanan->nama_simpanan }}</td>
                            <td>
                                @php $tipe = $t->tipe_transaksi instanceof \App\Enum\TipeTransaksi ? $t->tipe_transaksi : \App\Enum\TipeTransaksi::from($t->tipe_transaksi); @endphp
                                <span class="badge-modern"
                                    style="color: var(--{{ $tipe->color() }}-color); background: rgba(var(--badge-rgb), 0.12);">
                                    <i class="{{ $tipe->icon() }}"></i> {{ $tipe->label() }}
                                </span>
                            </td>
                            <td class="fw-semibold"
                                style="color: {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? '+' : '-' }} Rp
                                {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($t->tgl_transaksi)->format('d M Y') }}</td>
                            <td>
                                @php $status = $t->status instanceof \App\Enum\StatusPengajuan ? $t->status : \App\Enum\StatusPengajuan::from($t->status); @endphp
                                <x-admin.badge :variant="$status->color()" :icon="$status->icon()">
                                    {{ $status->label() }}
                                </x-admin.badge>
                                @if ($t->alasan_tolak)
                                    <br><small class="text-danger"><i class="fas fa-info-circle"></i>
                                        {{ $t->alasan_tolak }}</small>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $t->keterangan ?? '-' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-piggy-bank mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada transaksi simpanan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transaksis->hasPages())
            <div class="d-flex justify-content-end mt-4">{{ $transaksis->links() }}</div>
        @endif
    </div>

    {{-- Pengajuan Modal --}}
    @if ($showPengajuanModal)
        <div class="modal-backdrop-custom" wire:click.self="closePengajuanModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 500px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom"><i class="fas fa-plus-circle me-2"
                            style="color: var(--primary-color);"></i>Ajukan Simpanan</h5>
                    <button type="button" class="modal-close-btn" wire:click="closePengajuanModal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="mb-2 p-2 rounded-2"
                    style="background: rgba(255,193,7,0.08); border: 1px solid rgba(255,193,7,0.2); font-size: 0.8rem; color: var(--warning-color);">
                    <i class="fas fa-info-circle me-1"></i> Pengajuan akan diproses setelah disetujui admin
                </div>
                <form wire:submit="submitPengajuan">
                    <div class="mb-3">
                        <label for="jenis_simpanan_id" class="form-label">Jenis Simpanan <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-control @error('jenis_simpanan_id') is-invalid @enderror" id="jenis_simpanan_id"
                            wire:model.live="jenis_simpanan_id">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($jenisSimpanans as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_simpanan }}</option>
                            @endforeach
                        </select>
                        @error('jenis_simpanan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="tipe_transaksi" class="form-label">Tipe <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-control @error('tipe_transaksi') is-invalid @enderror" id="tipe_transaksi"
                            wire:model.live="tipe_transaksi">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="setor">Setor</option>
                            <option value="tarik">Tarik</option>
                        </select>
                        @error('tipe_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @if ($saldoJenis !== null && $jenis_simpanan_id)
                        <div class="mb-3 p-3 rounded-3"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color);">
                            <div class="d-flex justify-content-between" style="font-size: 0.85rem;">
                                <span class="text-muted">Saldo saat ini:</span>
                                <strong style="color: var(--primary-color);">Rp
                                    {{ number_format($saldoJenis, 0, ',', '.') }}</strong>
                            </div>
                            @if ($minimalSetor && $tipe_transaksi === 'setor')
                                <div class="d-flex justify-content-between mt-1" style="font-size: 0.85rem;">
                                    <span class="text-muted">Minimal setor:</span>
                                    <strong style="color: var(--warning-color);">Rp
                                        {{ number_format($minimalSetor, 0, ',', '.') }}</strong>
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah (Rp) <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                            wire:model="jumlah" min="1" step="1000">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" wire:model="keterangan" rows="2"
                            placeholder="Opsional"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-modern"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);"
                            wire:click="closePengajuanModal">Batal</button>
                        <button type="submit" class="btn btn-modern btn-primary-modern">
                            <span wire:loading.remove wire:target="submitPengajuan"><i
                                    class="fas fa-paper-plane me-2"></i>Ajukan</span>
                            <span wire:loading wire:target="submitPengajuan"><i
                                    class="fas fa-spinner fa-spin me-2"></i>Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
