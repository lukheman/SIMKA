<div>
    <x-admin.page-header title="Laporan Simpanan" subtitle="Lihat dan cetak laporan transaksi simpanan">
        <x-slot:actions>
            <button wire:click="cetakPdf" class="btn btn-modern btn-primary-modern">
                <span wire:loading.remove wire:target="cetakPdf"><i class="fas fa-file-pdf me-2"></i>Cetak PDF</span>
                <span wire:loading wire:target="cetakPdf"><i class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--success-color);">
                <div class="stat-icon" style="background: rgba(92,173,138,0.12); color: var(--success-color);"><i
                        class="fas fa-arrow-down"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Setor</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Rp
                    {{ number_format($totalSetor, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--danger-color);">
                <div class="stat-icon" style="background: rgba(212,93,93,0.12); color: var(--danger-color);"><i
                        class="fas fa-arrow-up"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Tarik</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Rp
                    {{ number_format($totalTarik, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--primary-color);">
                <div class="stat-icon" style="background: rgba(74,127,181,0.12); color: var(--primary-color);"><i
                        class="fas fa-wallet"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Saldo</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Rp
                    {{ number_format($totalSaldo, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Data Transaksi Simpanan</h5>
            <div class="d-flex gap-2">
                <select class="form-control" wire:model.live="filterStatus" style="min-width: 140px;">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
                <select class="form-control" wire:model.live="filterTipe" style="min-width: 130px;">
                    <option value="">Semua Tipe</option>
                    @foreach ($tipeOptions as $tipe)
                        <option value="{{ $tipe->value }}">{{ $tipe->label() }}</option>
                    @endforeach
                </select>
                <select class="form-control" wire:model.live="filterJenis" style="min-width: 160px;">
                    <option value="">Semua Jenis</option>
                    @foreach ($jenisSimpanans as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_simpanan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"
                    style="background: var(--input-bg); border-color: var(--border-color);"><i class="fas fa-search"
                        style="color: var(--text-muted);"></i></span>
                <input type="text" class="form-control" placeholder="Cari anggota / kode transaksi..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Anggota</th>
                        <th>Jenis</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $index => $t)
                        <tr wire:key="trx-{{ $t->id }}">
                            <td class="text-muted">{{ $transaksis->firstItem() + $index }}</td>
                            <td><code style="color: var(--primary-color);">{{ $t->kode_transaksi }}</code></td>
                            <td>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.85rem;">
                                    {{ $t->anggota->nama_lengkap }}</div>
                                <small class="text-muted">{{ $t->anggota->no_anggota }}</small>
                            </td>
                            <td class="fw-semibold">{{ $t->jenisSimpanan->nama_simpanan }}</td>
                            <td>
                                @php $tipe = $t->tipe_transaksi instanceof \App\Enum\TipeTransaksi ? $t->tipe_transaksi : \App\Enum\TipeTransaksi::from($t->tipe_transaksi); @endphp
                                <x-admin.badge :variant="$tipe->color()"
                                    :icon="$tipe->icon()">{{ $tipe->label() }}</x-admin.badge>
                            </td>
                            <td class="fw-semibold"
                                style="color: {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? '+' : '-' }} Rp
                                {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($t->tgl_transaksi)->format('d M Y') }}</td>
                            <td>
                                @php $status = $t->status instanceof \App\Enum\StatusPengajuan ? $t->status : \App\Enum\StatusPengajuan::from($t->status); @endphp
                                <x-admin.badge :variant="$status->color()"
                                    :icon="$status->icon()">{{ $status->label() }}</x-admin.badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-piggy-bank mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Tidak ada data transaksi simpanan</p>
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
</div>