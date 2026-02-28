<div>
    <x-admin.page-header title="Pengajuan Pinjaman" subtitle="Kelola pengajuan pinjaman anggota" />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    {{-- Table Card --}}
    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Pengajuan</h5>
            <div class="d-flex gap-2">
                <select class="form-control" wire:model.live="filterStatus" style="min-width: 160px;">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari anggota..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Jenis Pinjaman</th>
                        <th>Jumlah</th>
                        <th>Tenor</th>
                        <th>Bunga Total</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuans as $p)
                        <tr wire:key="pengajuan-{{ $p->id }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">{{ strtoupper(substr($p->anggota->nama_lengkap, 0, 2)) }}</div>
                                    <div>
                                        <div class="fw-semibold" style="color: var(--text-primary);">
                                            {{ $p->anggota->nama_lengkap }}
                                        </div>
                                        <small class="text-muted">{{ $p->anggota->no_anggota }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold">
                                {{ $p->jenisPinjaman->nama_pinjaman }}
                            </td>
                            <td>Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                            <td>{{ $p->tenor_bulan }} bln</td>
                            <td>Rp {{ number_format($p->bunga_total, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d M Y') }}</td>
                            <td>
                                @php $status = $p->status instanceof \App\Enum\StatusPengajuan ? $p->status : \App\Enum\StatusPengajuan::from($p->status); @endphp
                                <x-admin.badge :variant="$status->color()" :icon="$status->icon()">
                                    {{ $status->label() }}
                                </x-admin.badge>
                            </td>
                            <td>
                                @if ($status === \App\Enum\StatusPengajuan::PENDING)
                                    <div class="d-flex gap-1">
                                        <button class="action-btn" style="color: var(--success-color);"
                                            wire:click="openApproveModal({{ $p->id }})" title="Setujui">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button class="action-btn" style="color: var(--danger-color);"
                                            wire:click="openRejectModal({{ $p->id }})" title="Tolak">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                @elseif ($status === \App\Enum\StatusPengajuan::DISETUJUI)
                                    <small class="text-muted">
                                        Cair:
                                        {{ $p->tgl_cair ? \Carbon\Carbon::parse($p->tgl_cair)->format('d M Y') : '-' }}<br>
                                        Rp {{ number_format($p->jumlah_disetujui, 0, ',', '.') }}
                                    </small>
                                @elseif ($status === \App\Enum\StatusPengajuan::DITOLAK)
                                    <small style="color: var(--danger-color);">{{ $p->alasan_tolak }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-file-invoice mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada pengajuan pinjaman</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengajuans->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>

    {{-- Approve Modal --}}
    @if ($showApproveModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        <i class="fas fa-check-circle me-2" style="color: var(--success-color);"></i>Setujui Pengajuan
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form wire:submit="approve">
                    <div class="mb-3">
                        <label for="jumlah_disetujui" class="form-label">Jumlah Disetujui (Rp) <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" class="form-control @error('jumlah_disetujui') is-invalid @enderror"
                            id="jumlah_disetujui" wire:model="jumlah_disetujui"
                            placeholder="Masukkan jumlah yang disetujui">
                        @error('jumlah_disetujui')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tanggal pencairan akan otomatis diisi hari ini.</small>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-modern"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);"
                            wire:click="closeModal">Batal</button>
                        <button type="submit" class="btn btn-modern"
                            style="background: var(--success-color); color: white; border: none;">
                            <i class="fas fa-check me-2"></i>Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    @if ($showRejectModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        <i class="fas fa-times-circle me-2" style="color: var(--danger-color);"></i>Tolak Pengajuan
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form wire:submit="reject">
                    <div class="mb-3">
                        <label for="alasan_tolak" class="form-label">Alasan Penolakan <span
                                style="color: var(--danger-color);">*</span></label>
                        <textarea class="form-control @error('alasan_tolak') is-invalid @enderror" id="alasan_tolak"
                            wire:model="alasan_tolak" placeholder="Jelaskan alasan penolakan" rows="3"></textarea>
                        @error('alasan_tolak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-modern"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);"
                            wire:click="closeModal">Batal</button>
                        <button type="submit" class="btn btn-modern"
                            style="background: var(--danger-color); color: white; border: none;">
                            <i class="fas fa-times me-2"></i>Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
