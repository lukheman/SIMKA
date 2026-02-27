<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Anggota" subtitle="Kelola data anggota koperasi">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
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
                                            <span class="fw-semibold"
                                                style="color: var(--primary-color);">{{ $anggota->no_anggota }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="user-avatar">{{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}</div>
                                                <div>
                                                    <div class="fw-semibold" style="color: var(--text-primary);">
                                                        {{ $anggota->nama_lengkap }}</div>
                                                    <small class="text-muted">{{ $anggota->pekerjaan }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="color: var(--text-secondary);">{{ $anggota->nik }}</td>
                                        <td style="color: var(--text-secondary);">{{ $anggota->no_telp }}</td>
                                        <td class="text-muted">{{ \Carbon\Carbon::parse($anggota->tgl_bergabung)->format('d M Y') }}
                                        </td>
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
                                                <x-admin.action-btn-edit wire:click="openEditModal({{ $anggota->id }})" />
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

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Anggota' : 'Tambah Anggota Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="no_anggota" class="form-label">No. Anggota <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('no_anggota') is-invalid @enderror"
                                id="no_anggota" wire:model="no_anggota" placeholder="Contoh: AGT-00001">
                            @error('no_anggota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                                wire:model="nik" placeholder="Masukkan NIK">
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            id="nama_lengkap" wire:model="nama_lengkap" placeholder="Masukkan nama lengkap">
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span
                                style="color: var(--danger-color);">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" wire:model="alamat"
                            placeholder="Masukkan alamat lengkap" rows="2"></textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" id="pekerjaan"
                                wire:model="pekerjaan" placeholder="Masukkan pekerjaan">
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="no_telp" class="form-label">No. Telp <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                wire:model="no_telp" placeholder="Masukkan no. telp">
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl_bergabung" class="form-label">Tgl Bergabung <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="date" class="form-control @error('tgl_bergabung') is-invalid @enderror"
                                id="tgl_bergabung" wire:model="tgl_bergabung">
                            @error('tgl_bergabung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status_aktif" class="form-label">Status <span
                                    style="color: var(--danger-color);">*</span></label>
                            <select class="form-control @error('status_aktif') is-invalid @enderror" id="status_aktif"
                                wire:model="status_aktif">
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                            @error('status_aktif')
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
        message="Apakah Anda yakin ingin menghapus data anggota ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="deleteAnggota" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Anggota
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>