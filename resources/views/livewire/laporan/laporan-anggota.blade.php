<div>
    <x-admin.page-header title="Laporan Anggota" subtitle="Lihat dan cetak laporan data anggota">
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
                        class="fas fa-users"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Total Anggota</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $totalAnggota }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--success-color);">
                <div class="stat-icon" style="background: rgba(92,173,138,0.12); color: var(--success-color);"><i
                        class="fas fa-user-check"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Aktif</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $totalAktif }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--danger-color);">
                <div class="stat-icon" style="background: rgba(212,93,93,0.12); color: var(--danger-color);"><i
                        class="fas fa-user-times"></i></div>
                <div class="text-muted"
                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Tidak Aktif</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $totalNonaktif }}</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Data Anggota</h5>
            <div class="d-flex gap-2">
                <select class="form-control" wire:model.live="filterStatus" style="min-width: 140px;">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"
                    style="background: var(--input-bg); border-color: var(--border-color);"><i class="fas fa-search"
                        style="color: var(--text-muted);"></i></span>
                <input type="text" class="form-control" placeholder="Cari nama, no anggota, NIK..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Anggota</th>
                        <th>Nama Lengkap</th>
                        <th>NIK</th>
                        <th>Pekerjaan</th>
                        <th>No Telp</th>
                        <th>Tgl Bergabung</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggotas as $index => $a)
                        <tr wire:key="anggota-{{ $a->id }}">
                            <td class="text-muted">{{ $anggotas->firstItem() + $index }}</td>
                            <td><code style="color: var(--primary-color);">{{ $a->no_anggota }}</code></td>
                            <td class="fw-semibold" style="color: var(--text-primary);">{{ $a->nama_lengkap }}</td>
                            <td class="text-muted">{{ $a->nik }}</td>
                            <td>{{ $a->pekerjaan }}</td>
                            <td>{{ $a->no_telp }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($a->tgl_bergabung)->format('d M Y') }}</td>
                            <td>
                                @php $status = $a->status_aktif instanceof \App\Enum\StatusAktif ? $a->status_aktif : \App\Enum\StatusAktif::from($a->status_aktif); @endphp
                                <x-admin.badge :variant="$status->color()" :icon="$status->icon()">
                                    {{ $status->label() }}
                                </x-admin.badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Tidak ada data anggota</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($anggotas->hasPages())
            <div class="d-flex justify-content-end mt-4">{{ $anggotas->links() }}</div>
        @endif
    </div>
</div>