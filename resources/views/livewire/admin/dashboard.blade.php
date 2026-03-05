<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Dashboard" subtitle="Ringkasan data pengelolaan simpan pinjam" />

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-users" label="Total Anggota" :value="number_format($totalAnggota)"
                variant="primary" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-piggy-bank" label="Total Simpanan"
                :value="'Rp ' . number_format($totalSimpanan, 0, ',', '.')" variant="success" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-hand-holding-usd" label="Total Pinjaman"
                :value="'Rp ' . number_format($totalPinjaman, 0, ',', '.')" variant="warning" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-clock" label="Menunggu Persetujuan"
                :value="number_format($pengajuanPending)" variant="danger" />
        </div>
    </div>

    {{-- Transaksi Simpanan Terbaru --}}
    <div class="mb-4">
        <x-admin.table-card title="Transaksi Simpanan Terbaru"
            :view-all-href="route('admin.transaksi-simpanan')" view-all-text="Lihat Semua"
            :headers="['Kode', 'Anggota', 'Jenis Simpanan', 'Tipe', 'Jumlah', 'Tanggal', 'Status']">
            @forelse ($transaksiTerbaru as $t)
                <tr>
                    <td><code style="color: var(--primary-color);">{{ $t->kode_transaksi }}</code></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                {{ strtoupper(substr($t->anggota->nama_lengkap, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold"
                                    style="color: var(--text-primary); font-size: 0.85rem;">
                                    {{ $t->anggota->nama_lengkap }}
                                </div>
                                <small class="text-muted">{{ $t->anggota->no_anggota }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-semibold">{{ $t->jenisSimpanan->nama_simpanan }}</td>
                    <td>
                        @php $tipe = $t->tipe_transaksi instanceof \App\Enum\TipeTransaksi ? $t->tipe_transaksi : \App\Enum\TipeTransaksi::from($t->tipe_transaksi); @endphp
                        <x-admin.badge :variant="$tipe->color()" :icon="$tipe->icon()">{{ $tipe->label() }}</x-admin.badge>
                    </td>
                    <td class="fw-semibold"
                        style="color: {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? 'var(--success-color)' : 'var(--danger-color)' }};">
                        {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? '+' : '-' }} Rp
                        {{ number_format($t->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="text-muted">{{ \Carbon\Carbon::parse($t->tgl_transaksi)->format('d M Y') }}</td>
                    <td>
                        @php $status = $t->status instanceof \App\Enum\StatusPengajuan ? $t->status : \App\Enum\StatusPengajuan::from($t->status); @endphp
                        <x-admin.badge :variant="$status->color()" :icon="$status->icon()">{{ $status->label() }}</x-admin.badge>
                    </td>
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
        </x-admin.table-card>
    </div>

    {{-- Pengajuan Pinjaman Terbaru --}}
    <x-admin.table-card title="Pengajuan Pinjaman Terbaru"
        :view-all-href="route('admin.pengajuan-pinjaman')" view-all-text="Lihat Semua"
        :headers="['Anggota', 'Jenis Pinjaman', 'Jumlah', 'Tenor', 'Tanggal', 'Status']">
        @forelse ($pinjamanTerbaru as $p)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.7rem;">
                            {{ strtoupper(substr($p->anggota->nama_lengkap, 0, 2)) }}
                        </div>
                        <div>
                            <div class="fw-semibold"
                                style="color: var(--text-primary); font-size: 0.85rem;">
                                {{ $p->anggota->nama_lengkap }}
                            </div>
                            <small class="text-muted">{{ $p->anggota->no_anggota }}</small>
                        </div>
                    </div>
                </td>
                <td class="fw-semibold">
                    {{ $p->jenisPinjaman->nama_pinjaman }}</td>
                <td class="fw-semibold">Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                <td>{{ $p->tenor_bulan }} bulan</td>
                <td class="text-muted">{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d M Y') }}</td>
                <td>
                    @php $status = $p->status instanceof \App\Enum\StatusPengajuan ? $p->status : \App\Enum\StatusPengajuan::from($p->status); @endphp
                    <x-admin.badge :variant="$status->color()" :icon="$status->icon()">{{ $status->label() }}</x-admin.badge>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-file-invoice-dollar mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Belum ada pengajuan pinjaman</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-admin.table-card>
</div>
