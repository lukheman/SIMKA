<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Jenis Pinjaman" subtitle="Kelola jenis-jenis pinjaman koperasi">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Jenis Pinjaman
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
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Jenis Pinjaman</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari jenis pinjaman..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pinjaman</th>
                        <th>Bunga (%)</th>
                        <th>Maks. Tenor</th>
                        <th>Dibuat</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jenisPinjamans as $index => $jenis)
                        <tr wire:key="jenis-pinjaman-{{ $jenis->id }}">
                            <td class="text-muted">{{ $jenisPinjamans->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fw-semibold" style="color: var(--text-primary);">{{ $jenis->nama_pinjaman }}</div>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary);">{{ number_format($jenis->bunga_persen, 2) }}%</td>
                            <td style="color: var(--text-secondary);">{{ $jenis->maks_tenor_bulan }} bulan</td>
                            <td class="text-muted">{{ $jenis->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <x-admin.action-btn-edit wire:click="openEditModal({{ $jenis->id }})" />
                                    <x-admin.action-btn-delete wire:click="confirmDelete({{ $jenis->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-hand-holding-usd mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data jenis pinjaman</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($jenisPinjamans->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $jenisPinjamans->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Jenis Pinjaman' : 'Tambah Jenis Pinjaman Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="nama_pinjaman" class="form-label">Nama Pinjaman <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nama_pinjaman') is-invalid @enderror" id="nama_pinjaman"
                            wire:model="nama_pinjaman" placeholder="Contoh: Pinjaman Modal Usaha">
                        @error('nama_pinjaman')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bunga_persen" class="form-label">Bunga (%) <span style="color: var(--danger-color);">*</span></label>
                            <input type="number" class="form-control @error('bunga_persen') is-invalid @enderror" id="bunga_persen"
                                wire:model="bunga_persen" placeholder="Contoh: 2.5" min="0" max="100" step="0.01">
                            @error('bunga_persen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="maks_tenor_bulan" class="form-label">Maks. Tenor (bulan) <span style="color: var(--danger-color);">*</span></label>
                            <input type="number" class="form-control @error('maks_tenor_bulan') is-invalid @enderror" id="maks_tenor_bulan"
                                wire:model="maks_tenor_bulan" placeholder="Contoh: 24" min="1">
                            @error('maks_tenor_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <x-admin.button type="button" variant="outline" wire:click="closeModal">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" variant="primary">
                            {{ $editingId ? 'Perbarui' : 'Simpan' }}
                        </x-admin.button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-admin.confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus jenis pinjaman ini? Tindakan ini tidak dapat dibatalkan." on-confirm="deleteJenisPinjaman"
        on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Jenis Pinjaman
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>
