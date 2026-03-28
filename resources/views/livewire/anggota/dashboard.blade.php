u<div>
    <x-admin.page-header title="Dashboard" subtitle="Ringkasan data pinjaman Anda" />

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
            style="border-radius: 10px; border: none; border-left: 4px solid var(--success-color); background: rgba(92,173,138,0.1); color: var(--text-primary);">
            <i class="fas fa-check-circle me-2" style="color: var(--success-color);"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--primary-color);">
                <div class="stat-icon" style="background: rgba(74,127,181,0.12); color: var(--primary-color);">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="text-muted"
                    style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total
                    Pengajuan</div>
                <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary);">{{ $totalPengajuan }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--warning-color);">
                <div class="stat-icon" style="background: rgba(212,160,78,0.12); color: var(--warning-color);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="text-muted"
                    style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Menunggu Persetujuan</div>
                <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary);">{{ $pengajuanPending }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--success-color);">
                <div class="stat-icon" style="background: rgba(92,173,138,0.12); color: var(--success-color);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="text-muted"
                    style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Disetujui</div>
                <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary);">{{ $pengajuanDisetujui }}
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Applications --}}
    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Pengajuan Terbaru</h5>
        </div>

        @if ($recentPengajuan->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Jenis Pinjaman</th>
                            <th>Jumlah</th>
                            <th>Tenor</th>
                            <th>Tgl Pengajuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentPengajuan as $p)
                            <tr>
                                <td class="fw-semibold" style="color: var(--primary-color);">
                                    {{ $p->jenisPinjaman->nama_pinjaman }}</td>
                                <td>Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                                <td>{{ $p->tenor_bulan }} bulan</td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d M Y') }}</td>
                                <td>
                                    @php $status = $p->status instanceof \App\Enum\StatusPengajuan ? $p->status : \App\Enum\StatusPengajuan::from($p->status); @endphp
                                    <x-admin.badge :variant="$status->color()" :icon="$status->icon()">{{ $status->label() }}</x-admin.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-file-invoice mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0">Belum ada pengajuan pinjaman</p>
                </div>
            </div>
        @endif
    </div>
</div>
