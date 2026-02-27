<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Anggota" subtitle="Kelola data anggota koperasi">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" href="{{ route('admin.anggota.create') }}" wire:navigate>
                Tambah Anggota
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    @if (session('error'))
        <x-admin.alert variant="danger" title="Error!" class="mb-4">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    {{-- Table Card --}}
    <div class="modern-card">
        {{-- Search --}}
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

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>No. Anggota</th>
                        <th>Nama Lengkap</th>
                        <th>NIK</th>
                        <th>No. Telp</th>
                        <th>Tgl Bergabung</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggotas as $anggota)
                        <tr wire:key="anggota-{{ $anggota->id }}">
                            <td>
                                <span class="fw-semibold" style="color: var(--primary-color);">{{ $anggota->no_anggota }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">{{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}</div>
                                    <div>
                                        <div class="fw-semibold" style="color: var(--text-primary);">{{ $anggota->nama_lengkap }}</div>
                                        <small class="text-muted">{{ $anggota->pekerjaan }}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary);">{{ $anggota->nik }}</td>
                            <td style="color: var(--text-secondary);">{{ $anggota->no_telp }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($anggota->tgl_bergabung)->format('d M Y') }}</td>
                            <td>
                                @php
                                    $status = $anggota->status_aktif instanceof \App\Enum\StatusAktif
                                        ? $anggota->status_aktif
                                        : \App\Enum\StatusAktif::from($anggota->status_aktif);
                                @endphp
                                <x-admin.badge :variant="$status->color()" :icon="$status->icon()">
                                    {{ $status->label() }}
                                </x-admin.badge>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <x-admin.action-btn-edit :href="route('admin.anggota.edit', $anggota->id)" wire:navigate />
                                    <x-admin.action-btn-delete wire:click="confirmDelete({{ $anggota->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data anggota</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($anggotas->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $anggotas->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-admin.confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus data anggota ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="deleteAnggota" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Anggota
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>