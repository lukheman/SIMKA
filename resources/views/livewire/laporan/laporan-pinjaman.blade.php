<div>
    <x-admin.page-header title="Laporan Pinjaman" subtitle="Lihat dan cetak laporan pengajuan pinjaman">
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
            <div class="stat-card" style="--accent-color: var(--primary-color);">
                <div class="stat-icon" style="background: rgba(74,127,181,0.12); color: var(--primary-color);"><i
                        class="fas fa-file-invoice-dollar"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Pengajuan</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Rp
                    {{ number_format($totalPengajuan, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--success-color);">
                <div class="stat-icon" style="background: rgba(92,173,138,0.12); color: var(--success-color);"><i
                        class="fas fa-check-circle"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Disetujui</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Rp
                    {{ number_format($totalDisetujui, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--warning-color);">
                <div class="stat-icon" style="background: rgba(255,193,7,0.12); color: var(--warning-color);"><i
                        class="fas fa-clock"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Pending</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $totalPending }}</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Data Pengajuan Pinjaman</h5>
            <div class="d-flex gap-2">
                <select class="form-control" wire:model.live="filterStatus" style="min-width: 140px;">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
                <select class="form-control" wire:model.live="filterJenis" style="min-width: 160px;">
                    <option value="">Semua Jenis</option>
                    @foreach ($jenisPinjamans as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_pinjaman }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"
                    style="background: var(--input-bg); border-color: var(--border-color);"><i class="fas fa-search"
                        style="color: var(--text-muted);"></i></span>
                <input type="text" class="form-control" placeholder="Cari anggota..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Anggota</th>
                        <th>Jenis Pinjaman</th>
                        <th>Jumlah Pengajuan</th>
                        <th>Jumlah Disetujui</th>
                        <th>Tenor</th>
                        <th>Bunga Total</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuans as $index => $p)
                        <tr wire:key="pinjaman-{{ $p->id }}">
                            <td class="text-muted">{{ $pengajuans->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.85rem;">
                                    {{ $p->anggota->nama_lengkap }}</div>
                                <small class="text-muted">{{ $p->anggota->no_anggota }}</small>
                            </td>
                            <td class="fw-semibold">{{ $p->jenisPinjaman->nama_pinjaman }}</td>
                            <td>Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                            <td>
                                @if ($p->jumlah_disetujui)
                                    <span style="color: var(--success-color); font-weight: 600;">Rp
                                        {{ number_format($p->jumlah_disetujui, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $p->tenor_bulan }} bln</td>
                            <td>Rp {{ number_format($p->bunga_total, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d M Y') }}</td>
                            <td>
                                @php $status = $p->status instanceof \App\Enum\StatusPengajuan ? $p->status : \App\Enum\StatusPengajuan::from($p->status); @endphp
                                <x-admin.badge :variant="$status->color()"
                                    :icon="$status->icon()">{{ $status->label() }}</x-admin.badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-file-invoice mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Tidak ada data pengajuan pinjaman</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengajuans->hasPages())
            <div class="d-flex justify-content-end mt-4">{{ $pengajuans->links() }}</div>
        @endif
    </div>
</div>