<div>
    <x-admin.page-header title="Manajemen Angsuran" subtitle="Kelola pembayaran angsuran pinjaman anggota" />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    @if($pendingCount > 0)
        <div class="modern-card mb-4" style="border-left: 4px solid #f59e0b;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock" style="font-size: 1.25rem; color: #d97706;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="color: var(--text-primary);">Menunggu Verifikasi</h6>
                        <small class="text-muted"><strong style="color: #d97706;">{{ $pendingCount }}</strong> pembayaran angsuran perlu diverifikasi</small>
                    </div>
                </div>
                <button wire:click="lihatPending" class="btn btn-sm px-3 py-2"
                    style="background: rgba(245, 158, 11, 0.12); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; font-weight: 600; font-size: 0.85rem;">
                    <i class="fas fa-eye me-1"></i> Lihat
                </button>
            </div>
        </div>
    @endif

    {{-- ═══════════════ VIEW 1: DAFTAR ANGGOTA ═══════════════ --}}
    @if($view === 'anggota')
        <div class="modern-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Anggota</h5>
                <div class="input-group" style="max-width: 300px;">
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
                            <th>No. Anggota</th>
                            <th>Nama Lengkap</th>
                            <th>Jumlah Pinjaman Aktif</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotas as $anggota)
                            <tr wire:key="anggota-{{ $anggota->id }}">
                                <td>
                                    <code style="color: var(--primary-color);">{{ $anggota->no_anggota }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar">{{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}</div>
                                        <div>
                                            <div class="fw-semibold" style="color: var(--text-primary);">
                                                {{ $anggota->nama_lengkap }}</div>
                                            <small class="text-muted">{{ $anggota->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($anggota->pengajuan_pinjaman_count > 0)
                                        <x-admin.badge variant="primary" icon="fas fa-file-invoice-dollar">
                                            {{ $anggota->pengajuan_pinjaman_count }} pinjaman
                                        </x-admin.badge>
                                    @else
                                        <span class="text-muted" style="font-size: 0.85rem;">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($anggota->pengajuan_pinjaman_count > 0)
                                        <x-admin.action-btn-view wire:click="lihatPinjaman({{ $anggota->id }})" label="Pinjaman"
                                            icon="fas fa-eye" />
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-0">Tidak ada anggota ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($anggotas->hasPages())
                <div class="d-flex justify-content-end mt-4">
                    {{ $anggotas->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- ═══════════════ VIEW 2: DAFTAR PINJAMAN ANGGOTA ═══════════════ --}}
    @if($view === 'pinjaman')
        {{-- Breadcrumb --}}
        <div class="mb-3">
            <button wire:click="kembaliKeAnggota" class="btn btn-sm"
                style="border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-secondary); background: var(--bg-white);">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Anggota
            </button>
        </div>

        <div class="modern-card">
            <div class="mb-4">
                <h5 class="mb-1" style="color: var(--text-primary); font-weight: 600;">
                    Pinjaman — {{ $selectedAnggotaNama }}
                </h5>
                <small class="text-muted">Daftar pinjaman yang disetujui atau sudah lunas</small>
            </div>

            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Jenis Pinjaman</th>
                            <th>Jumlah Disetujui</th>
                            <th>Tenor</th>
                            <th>Tgl Cair</th>
                            <th>Progress Angsuran</th>
                            <th>Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pinjamans as $p)
                            <tr wire:key="pinjaman-{{ $p->id }}">
                                <td class="fw-semibold">{{ $p->jenisPinjaman->nama_pinjaman }}</td>
                                <td class="fw-semibold">Rp {{ number_format($p->jumlah_disetujui, 0, ',', '.') }}</td>
                                <td>{{ $p->tenor_bulan }} bulan</td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($p->tgl_cair)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $persen = $p->angsuran_count > 0 ? round(($p->angsuran_lunas_count / $p->angsuran_count) * 100) : 0;
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            style="flex: 1; height: 6px; background: var(--border-color); border-radius: 3px; overflow: hidden;">
                                            <div
                                                style="width: {{ $persen }}%; height: 100%; background: var(--success-color); border-radius: 3px;">
                                            </div>
                                        </div>
                                        <small class="text-muted fw-semibold"
                                            style="white-space: nowrap;">{{ $p->angsuran_lunas_count }}/{{ $p->angsuran_count }}</small>
                                    </div>
                                    @if($p->angsuran_menunggu_count > 0)
                                        <small style="color: var(--primary-color); font-weight: 600;">
                                            <i class="fas fa-clock"></i> {{ $p->angsuran_menunggu_count }} menunggu
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <x-admin.badge :variant="$p->status->color()" :icon="$p->status->icon()">
                                        {{ $p->status->label() }}
                                    </x-admin.badge>
                                </td>
                                <td>
                                    <x-admin.action-btn-view wire:click="lihatAngsuran({{ $p->id }})" label="Angsuran"
                                        icon="fas fa-list" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-file-invoice-dollar mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-0">Tidak ada pinjaman yang disetujui</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ═══════════════ VIEW 3: RIWAYAT ANGSURAN ═══════════════ --}}
    @if($view === 'angsuran')
        {{-- Breadcrumb --}}
        <div class="mb-3 d-flex gap-2">
            <button wire:click="kembaliKeAnggota" class="btn btn-sm"
                style="border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-secondary); background: var(--bg-white);">
                <i class="fas fa-arrow-left me-1"></i> Anggota
            </button>
            <button wire:click="kembaliKePinjaman" class="btn btn-sm"
                style="border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-secondary); background: var(--bg-white);">
                <i class="fas fa-arrow-left me-1"></i> Pinjaman
            </button>
        </div>

        {{-- Info Pinjaman --}}
        <div class="modern-card mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-1" style="color: var(--text-primary); font-weight: 600;">
                        Angsuran — {{ $pinjaman->anggota->nama_lengkap }}
                    </h5>
                    <div class="d-flex flex-wrap gap-3 text-muted" style="font-size: 0.85rem;">
                        <span><i class="fas fa-tag me-1"></i> {{ $pinjaman->jenisPinjaman->nama_pinjaman }}</span>
                        <span><i class="fas fa-money-bill-wave me-1"></i> Rp
                            {{ number_format($pinjaman->jumlah_disetujui, 0, ',', '.') }}</span>
                        <span><i class="fas fa-calendar me-1"></i> {{ $pinjaman->tenor_bulan }} bulan</span>
                        <span><i class="fas fa-calendar-check me-1"></i> Cair:
                            {{ \Carbon\Carbon::parse($pinjaman->tgl_cair)->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <x-admin.badge :variant="$pinjaman->status->color()" :icon="$pinjaman->status->icon()">
                        {{ $pinjaman->status->label() }}
                    </x-admin.badge>
                </div>
            </div>
        </div>

        {{-- Summary Stats --}}
        @php
            $totalAngsuran = $angsurans->sum(fn($a) => $a->jumlah_pokok + $a->jumlah_bunga);
            $totalDibayar = $angsurans->where('status_bayar', \App\Enum\StatusBayar::LUNAS)->sum('total_bayar');
            $lunas = $angsurans->where('status_bayar', \App\Enum\StatusBayar::LUNAS)->count();
            $menunggu = $angsurans->where('status_bayar', \App\Enum\StatusBayar::MENUNGGU)->count();
            $belum = $angsurans->where('status_bayar', \App\Enum\StatusBayar::BELUM)->count();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <x-admin.stat-card icon="fas fa-money-bill-wave" label="Total Angsuran"
                    value="Rp {{ number_format($totalAngsuran, 0, ',', '.') }}" variant="primary" />
            </div>
            <div class="col-md-3 col-6">
                <x-admin.stat-card icon="fas fa-check-circle" label="Lunas" value="{{ $lunas }}/{{ $angsurans->count() }}"
                    variant="success" />
            </div>
            <div class="col-md-3 col-6">
                <x-admin.stat-card icon="fas fa-clock" label="Menunggu" value="{{ $menunggu }}" variant="primary" />
            </div>
            <div class="col-md-3 col-6">
                <x-admin.stat-card icon="fas fa-hourglass-half" label="Belum Bayar" value="{{ $belum }}"
                    variant="warning" />
            </div>
        </div>

        {{-- Angsuran Table --}}
        <x-admin.table-card title="Riwayat Angsuran" :headers="['Ke-', 'Jatuh Tempo', 'Pokok', 'Bunga', 'Denda', 'Total Bayar', 'Bukti', 'Status', 'Aksi']">
            @foreach($angsurans as $a)
                @php
                    $isOverdue = $a->status_bayar === \App\Enum\StatusBayar::BELUM && \Carbon\Carbon::parse($a->tgl_jatuh_tempo)->isPast();
                    $isPending = $a->status_bayar === \App\Enum\StatusBayar::MENUNGGU;
                @endphp
                <tr
                    style="{{ $isOverdue ? 'background: rgba(239, 68, 68, 0.05);' : ($isPending ? 'background: rgba(99, 102, 241, 0.04);' : '') }}">
                    <td class="fw-semibold">{{ $a->angsuran_ke }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($a->tgl_jatuh_tempo)->format('d M Y') }}
                        @if($isOverdue)
                            <br><small style="color: var(--danger-color); font-weight: 600;"><i
                                    class="fas fa-exclamation-triangle"></i> Terlambat</small>
                        @endif
                    </td>
                    <td>Rp {{ number_format($a->jumlah_pokok, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($a->jumlah_bunga, 0, ',', '.') }}</td>
                    <td>{{ $a->denda ? 'Rp ' . number_format($a->denda, 0, ',', '.') : '—' }}</td>
                    <td class="fw-semibold">
                        @if($a->total_bayar)
                            Rp {{ number_format($a->total_bayar, 0, ',', '.') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($a->bukti_bayar)
                            <button wire:click="previewBukti({{ $a->id }})" class="action-btn action-btn-view" title="Lihat Bukti">
                                <i class="fas fa-image"></i> Bukti
                            </button>
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
                        @if($a->status_bayar === \App\Enum\StatusBayar::MENUNGGU)
                            <div class="d-flex gap-2">
                                <x-admin.action-btn-edit wire:click="openVerifyModal({{ $a->id }})" label="Verifikasi"
                                    icon="fas fa-check" />
                                <x-admin.action-btn-delete wire:click="openRejectModal({{ $a->id }})" label="Tolak"
                                    icon="fas fa-times" />
                            </div>
                        @elseif($a->status_bayar === \App\Enum\StatusBayar::LUNAS)
                            <span class="text-muted" style="font-size: 0.8rem;">
                                {{ $a->tgl_bayar ? \Carbon\Carbon::parse($a->tgl_bayar)->format('d M Y') : '—' }}
                            </span>
                        @elseif($a->status_bayar === \App\Enum\StatusBayar::BELUM)
                            <button wire:click="openBayarModal({{ $a->id }})" class="btn btn-sm btn-modern btn-primary-modern">
                                <i class="fas fa-cash-register me-1"></i> Bayar
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-admin.table-card>
    @endif

    {{-- ═══════════════ VIEW 4: DAFTAR PENDING VERIFIKASI ═══════════════ --}}
    @if($view === 'pending')
        <div class="mb-3">
            <button wire:click="kembaliKeAnggota" class="btn btn-sm"
                style="border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-secondary); background: var(--bg-white);">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Anggota
            </button>
        </div>

        <div class="modern-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1" style="color: var(--text-primary); font-weight: 600;">Pembayaran Menunggu Verifikasi</h5>
                    <small class="text-muted">Daftar angsuran yang sudah dibayar dan perlu diverifikasi</small>
                </div>
                <span class="d-inline-flex align-items-center gap-1 px-3 py-1"
                    style="background: rgba(245, 158, 11, 0.12); color: #d97706; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-clock"></i> {{ $pendingCount }} menunggu
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Jenis Pinjaman</th>
                            <th>Angsuran Ke-</th>
                            <th>Jatuh Tempo</th>
                            <th>Total</th>
                            <th>Tgl Bayar</th>
                            <th>Bukti</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingAngsurans as $a)
                            <tr wire:key="pending-{{ $a->id }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                            {{ strtoupper(substr($a->pengajuanPinjaman->anggota->nama_lengkap, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size: 0.9rem;">{{ $a->pengajuanPinjaman->anggota->nama_lengkap }}</div>
                                            <small class="text-muted">{{ $a->pengajuanPinjaman->anggota->no_anggota }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $a->pengajuanPinjaman->jenisPinjaman->nama_pinjaman }}</td>
                                <td class="fw-semibold">{{ $a->angsuran_ke }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->tgl_jatuh_tempo)->format('d M Y') }}</td>
                                <td class="fw-semibold">Rp {{ number_format($a->jumlah_pokok + $a->jumlah_bunga, 0, ',', '.') }}</td>
                                <td class="text-muted">{{ $a->tgl_bayar ? \Carbon\Carbon::parse($a->tgl_bayar)->format('d M Y') : '—' }}</td>
                                <td>
                                    @if($a->bukti_bayar)
                                        <button wire:click="previewBukti({{ $a->id }})" class="action-btn action-btn-view" title="Lihat Bukti">
                                            <i class="fas fa-image"></i> Bukti
                                        </button>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <x-admin.action-btn-edit wire:click="openVerifyModal({{ $a->id }})" label="Verifikasi"
                                            icon="fas fa-check" />
                                        <x-admin.action-btn-delete wire:click="openRejectModal({{ $a->id }})" label="Tolak"
                                            icon="fas fa-times" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-check-circle mb-2" style="font-size: 2rem; color: var(--success-color);"></i>
                                        <p class="mb-0">Tidak ada pembayaran yang menunggu verifikasi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pendingAngsurans->hasPages())
                <div class="d-flex justify-content-end mt-4">
                    {{ $pendingAngsurans->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- ═══════════════ MODALS ═══════════════ --}}

    {{-- Verify Modal --}}
    @if($showVerifyModal && $verifyingAngsuran)
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none;">
                    <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                        <h5 class="modal-title fw-bold">Verifikasi Pembayaran</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit="verify">
                        <div class="modal-body">
                            <div class="mb-3 p-3" style="background: var(--bg-light); border-radius: 10px;">
                                <div class="row g-2" style="font-size: 0.9rem;">
                                    <div class="col-6">
                                        <span class="text-muted">Angsuran Ke:</span><br>
                                        <span class="fw-semibold">{{ $verifyingAngsuran->angsuran_ke }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">Jatuh Tempo:</span><br>
                                        <span
                                            class="fw-semibold">{{ \Carbon\Carbon::parse($verifyingAngsuran->tgl_jatuh_tempo)->format('d M Y') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">Pokok:</span><br>
                                        <span class="fw-semibold">Rp
                                            {{ number_format($verifyingAngsuran->jumlah_pokok, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">Bunga:</span><br>
                                        <span class="fw-semibold">Rp
                                            {{ number_format($verifyingAngsuran->jumlah_bunga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($verifyingAngsuran->bukti_bayar)
                                <div class="mb-3 text-center">
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">Bukti Pembayaran:</p>
                                    <img src="{{ Storage::url($verifyingAngsuran->bukti_bayar) }}" alt="Bukti"
                                        style="max-width: 100%; max-height: 200px; border-radius: 10px; border: 1px solid var(--border-color);">
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Denda (Rp)</label>
                                <input type="number" wire:model="denda" class="form-control"
                                    style="border-radius: 10px; border: 1.5px solid var(--border-color);" min="0"
                                    step="1000">
                                @error('denda')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="p-3" style="background: rgba(16,185,129,0.08); border-radius: 10px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Total Bayar:</span>
                                    <span class="fw-bold" style="font-size: 1.15rem; color: var(--success-color);">
                                        Rp
                                        {{ number_format($verifyingAngsuran->jumlah_pokok + $verifyingAngsuran->jumlah_bunga + (float) $denda, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                            <button type="button" class="btn btn-modern" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-modern btn-success-modern">
                                <i class="fas fa-check me-1"></i> Verifikasi & Terima
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Reject Confirmation Modal --}}
    @if($showRejectModal)
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content" style="border-radius: 16px; border: none;">
                    <div class="modal-body text-center py-4">
                        <div
                            style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239,68,68,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <i class="fas fa-times" style="font-size: 1.5rem; color: var(--danger-color);"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Tolak Pembayaran?</h6>
                        <p class="text-muted mb-3" style="font-size: 0.85rem;">Bukti akan dihapus dan anggota diminta kirim
                            ulang.</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-modern" wire:click="closeModal">Batal</button>
                            <button class="btn btn-modern btn-danger-modern" wire:click="rejectPayment">Tolak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Preview Modal --}}
    @if($showPreviewModal && $previewImage)
        <div class="modal-backdrop fade show" wire:click="closePreview"></div>
        <div class="modal fade show d-block" tabindex="-1" wire:click="closePreview">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content"
                    style="border-radius: 16px; border: none; background: transparent; box-shadow: none;">
                    <div class="text-center">
                        <img src="{{ Storage::url($previewImage) }}" alt="Bukti Pembayaran"
                            style="max-width: 100%; max-height: 80vh; border-radius: 12px;" wire:click.stop>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Bayar Langsung Modal --}}
    @if($showBayarModal && $bayarAngsuran)
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none;">
                    <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                        <h5 class="modal-title fw-bold"><i class="fas fa-cash-register me-2"></i>Bayar Angsuran (Offline)</h5>
                        <button type="button" class="btn-close" wire:click="closeBayarModal"></button>
                    </div>
                    <form wire:submit="bayarLangsung">
                        <div class="modal-body">
                            {{-- Info Angsuran --}}
                            <div class="mb-3 p-3" style="background: var(--bg-light); border-radius: 10px;">
                                <div class="row g-2" style="font-size: 0.9rem;">
                                    <div class="col-6">
                                        <span class="text-muted">Anggota:</span><br>
                                        <span class="fw-semibold">{{ $bayarAngsuran->pengajuanPinjaman->anggota->nama_lengkap }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">Angsuran Ke:</span><br>
                                        <span class="fw-semibold">{{ $bayarAngsuran->angsuran_ke }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">Jatuh Tempo:</span><br>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($bayarAngsuran->tgl_jatuh_tempo)->format('d M Y') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">Pokok + Bunga:</span><br>
                                        <span class="fw-semibold">Rp {{ number_format($bayarAngsuran->jumlah_pokok + $bayarAngsuran->jumlah_bunga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Bukti Bayar (Optional) --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Bukti Pembayaran <small class="text-muted fw-normal">(opsional)</small></label>
                                <input type="file" wire:model="bayarBuktiBayar" class="form-control" accept="image/*"
                                    style="border-radius: 10px; border: 1.5px solid var(--border-color);">
                                <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                                @error('bayarBuktiBayar')
                                    <div class="mt-1"><small class="text-danger">{{ $message }}</small></div>
                                @enderror
                            </div>

                            @if($bayarBuktiBayar)
                                <div class="mb-3 text-center">
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">Preview:</p>
                                    <img src="{{ $bayarBuktiBayar->temporaryUrl() }}" alt="Preview"
                                        style="max-width: 100%; max-height: 200px; border-radius: 10px; border: 1px solid var(--border-color);">
                                </div>
                            @endif

                            {{-- Denda --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Denda (Rp)</label>
                                <input type="number" wire:model="bayarDenda" class="form-control"
                                    style="border-radius: 10px; border: 1.5px solid var(--border-color);" min="0" step="1000">
                                @error('bayarDenda')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Total --}}
                            <div class="p-3" style="background: rgba(16,185,129,0.08); border-radius: 10px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Total Bayar:</span>
                                    <span class="fw-bold" style="font-size: 1.15rem; color: var(--success-color);">
                                        Rp {{ number_format($bayarAngsuran->jumlah_pokok + $bayarAngsuran->jumlah_bunga + (float) $bayarDenda, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                            <button type="button" class="btn btn-modern" wire:click="closeBayarModal">Batal</button>
                            <button type="submit" class="btn btn-modern btn-success-modern" wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="fas fa-check me-1"></i> Bayar & Lunaskan</span>
                                <span wire:loading><i class="fas fa-spinner fa-spin me-1"></i> Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>