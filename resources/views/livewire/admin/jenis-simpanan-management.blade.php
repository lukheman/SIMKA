<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Jenis Simpanan" subtitle="Kelola jenis-jenis simpanan koperasi">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Jenis Simpanan
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
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Jenis Simpanan</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari jenis simpanan..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Simpanan</th>
                        <th>Minimal Setor</th>
                        <th>Dibuat</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jenisSimpanans as $index => $jenis)
                        <tr wire:key="jenis-simpanan-{{ $jenis->id }}">
                            <td class="text-muted">{{ $jenisSimpanans->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar" style="background: var(--success-color);">
                                        <i class="fas fa-piggy-bank" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <div class="fw-semibold" style="color: var(--text-primary);">{{ $jenis->nama_simpanan }}
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary);">Rp
                                {{ number_format($jenis->minimal_setor, 0, ',', '.') }}</td>
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
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-piggy-bank mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data jenis simpanan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($jenisSimpanans->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $jenisSimpanans->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Jenis Simpanan' : 'Tambah Jenis Simpanan Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="nama_simpanan" class="form-label">Nama Simpanan <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nama_simpanan') is-invalid @enderror"
                            id="nama_simpanan" wire:model="nama_simpanan" placeholder="Contoh: Simpanan Pokok">
                        @error('nama_simpanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="minimal_setor" class="form-label">Minimal Setor (Rp) <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" class="form-control @error('minimal_setor') is-invalid @enderror"
                            id="minimal_setor" wire:model="minimal_setor" placeholder="Contoh: 50000" min="0" step="1000">
                        @error('minimal_setor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
        message="Apakah Anda yakin ingin menghapus jenis simpanan ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="deleteJenisSimpanan" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Jenis Simpanan
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>