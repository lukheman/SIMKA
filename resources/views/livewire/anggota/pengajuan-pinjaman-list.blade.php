<div>
    <x-admin.page-header title="Pengajuan Pinjaman" subtitle="Daftar pengajuan pinjaman Anda">
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
            style="border-radius: 10px; border: none; border-left: 4px solid var(--success-color); background: rgba(92,173,138,0.1); color: var(--text-primary);">
            <i class="fas fa-check-circle me-2" style="color: var(--success-color);"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="modern-card">
        {{-- Filter --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Riwayat Pengajuan</h5>
            <select class="form-control" wire:model.live="filterStatus" style="max-width: 200px;">
                <option value="">Semua Status</option>
                @foreach ($statusOptions as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Jenis Pinjaman</th>
                        <th>Jumlah Pengajuan</th>
                        <th>Tenor</th>
                        <th>Bunga Total</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuans as $p)
                        <tr wire:key="pengajuan-{{ $p->id }}">
                            <td class="fw-semibold">
                                {{ $p->jenisPinjaman->nama_pinjaman }}</td>
                            <td>Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                            <td>{{ $p->tenor_bulan }} bulan</td>
                            <td>Rp {{ number_format($p->bunga_total, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d M Y') }}</td>
                            <td>
                                @php $status = $p->status instanceof \App\Enum\StatusPengajuan ? $p->status : \App\Enum\StatusPengajuan::from($p->status); @endphp
                                <x-admin.badge :variant="$status->color()" :icon="$status->icon()">
                                    {{ $status->label() }}
                                </x-admin.badge>
                            </td>
                            <td>
                                @if ($p->status->value === 'disetujui' && $p->jumlah_disetujui)
                                    <small class="text-muted">Disetujui: Rp
                                        {{ number_format($p->jumlah_disetujui, 0, ',', '.') }}</small><br>
                                    <small class="text-muted">Cair:
                                        {{ \Carbon\Carbon::parse($p->tgl_cair)->format('d M Y') }}</small>
                                @elseif ($p->status->value === 'ditolak' && $p->alasan_tolak)
                                    <small style="color: var(--danger-color);">{{ $p->alasan_tolak }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
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
</div>
