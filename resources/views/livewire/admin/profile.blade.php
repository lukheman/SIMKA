<div>
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

    {{-- Profile Header Banner --}}
    <div class="modern-card mb-4" style="border-top: none; overflow: hidden;">
        <div class="position-relative"
            style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); margin: -1.75rem -1.75rem 0; padding: 2.5rem 2rem 4rem; border-radius: 14px 14px 0 0;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1" style="color: white; font-weight: 700;">Profil Saya</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">Kelola informasi akun Anda
                    </p>
                </div>
                <x-admin.badge variant="success" icon="fas fa-user-check">
                    {{ Auth::user()->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                </x-admin.badge>
            </div>
        </div>

        {{-- Avatar & User Info --}}
        <div class="d-flex flex-column flex-md-row align-items-center gap-4"
            style="margin-top: -3rem; padding: 0 1rem 1rem;">
            <div class="position-relative" style="flex-shrink: 0;">
                @if($currentAvatar)
                    <img src="{{ Storage::url($currentAvatar) }}" alt="Avatar" class="rounded-circle"
                        style="width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--bg-secondary); box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                @else
                    <div class="user-avatar"
                        style="width: 100px; height: 100px; font-size: 2.5rem; border: 4px solid var(--bg-secondary); box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                        {{ Auth::user()->initials() }}
                    </div>
                @endif
            </div>

            <div class="text-center text-md-start flex-grow-1">
                <h5 class="mb-1" style="color: var(--text-primary); font-weight: 700;">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-2" style="font-size: 0.9rem;">{{ Auth::user()->email }}</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <x-admin.badge variant="primary" icon="fas fa-user-shield">Administrator</x-admin.badge>
                    <span class="text-muted" style="font-size: 0.8rem; display: flex; align-items: center; gap: 4px;">
                        <i class="fas fa-calendar-alt"></i> Bergabung {{ Auth::user()->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>

            <div class="text-center text-md-end" style="flex-shrink: 0;">
                <small class="text-muted d-block" style="font-size: 0.75rem;">Terakhir diperbarui</small>
                <small
                    style="color: var(--text-primary); font-weight: 500;">{{ Auth::user()->updated_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-6">
            {{-- Avatar Upload --}}
            <div class="modern-card mb-4">
                <div class="preview-title d-flex align-items-center gap-2"
                    style="border-bottom-color: var(--border-light);">
                    <i class="fas fa-camera" style="color: var(--secondary-color);"></i>
                    Foto Profil
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div style="flex-shrink: 0;">
                        @if($avatar)
                            <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" class="rounded-circle"
                                style="width: 64px; height: 64px; object-fit: cover; border: 2px solid var(--primary-color);">
                        @elseif($currentAvatar)
                            <img src="{{ Storage::url($currentAvatar) }}" alt="Avatar" class="rounded-circle"
                                style="width: 64px; height: 64px; object-fit: cover; border: 2px solid var(--border-color);">
                        @else
                            <div class="user-avatar" style="width: 64px; height: 64px; font-size: 1.5rem;">
                                {{ Auth::user()->initials() }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow-1">
                        <input type="file" wire:model="avatar" id="avatar-upload" class="d-none" accept="image/*">

                        <div class="d-flex gap-2 flex-wrap">
                            <label for="avatar-upload" class="btn btn-modern"
                                style="background: var(--bg-tertiary); color: var(--text-primary); border: 1px solid var(--border-color); cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-upload me-1"></i>
                                <span wire:loading.remove wire:target="avatar">Pilih Foto</span>
                                <span wire:loading wire:target="avatar">Mengupload...</span>
                            </label>

                            @if($avatar)
                                <button type="button" wire:click="uploadAvatar" class="btn btn-modern"
                                    style="background: var(--success-color); color: white; font-size: 0.85rem;">
                                    <i class="fas fa-check me-1"></i>Simpan
                                </button>
                                <button type="button" wire:click="$set('avatar', null)" class="btn btn-modern"
                                    style="background: var(--bg-tertiary); color: var(--text-primary); font-size: 0.85rem;">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                            @endif

                            @if($currentAvatar && !$avatar)
                                <button type="button" wire:click="removeAvatar" class="btn btn-modern"
                                    style="background: var(--danger-color); color: white; font-size: 0.85rem;"
                                    onclick="return confirm('Hapus foto profil?')">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            @endif
                        </div>

                        @error('avatar')
                            <div class="text-danger mt-2" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror

                        <p class="text-muted mb-0 mt-2" style="font-size: 0.75rem;">
                            <i class="fas fa-info-circle me-1"></i>
                            Format: JPG, PNG, GIF. Maksimal 2MB.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Profile Information --}}
            <div class="modern-card">
                <div class="preview-title d-flex align-items-center gap-2"
                    style="border-bottom-color: var(--border-light);">
                    <i class="fas fa-user-edit" style="color: var(--primary-color);"></i>
                    Informasi Profil
                </div>

                <form wire:submit="updateProfile">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            wire:model="name" placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            wire:model="email" placeholder="Masukkan email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <x-admin.button type="submit" variant="primary" icon="fas fa-save">
                            Simpan Perubahan
                        </x-admin.button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-6">
            {{-- Change Password --}}
            <div class="modern-card">
                <div class="preview-title d-flex align-items-center justify-content-between"
                    style="border-bottom-color: var(--border-light);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-lock" style="color: var(--warning-color);"></i>
                        Ubah Password
                    </div>
                    <x-admin.button type="button" variant="{{ $showPasswordSection ? 'danger' : 'outline' }}" size="sm"
                        wire:click="togglePasswordSection">
                        {{ $showPasswordSection ? 'Batal' : 'Ubah Password' }}
                    </x-admin.button>
                </div>

                @if($showPasswordSection)
                    <form wire:submit="updatePassword" class="mt-3">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password" wire:model="current_password"
                                placeholder="Masukkan password saat ini">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" wire:model="password" placeholder="Masukkan password baru">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation"
                                wire:model="password_confirmation" placeholder="Konfirmasi password baru">
                        </div>

                        <x-admin.alert variant="info" class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Password harus minimal 8 karakter dan mengandung huruf dan angka.
                        </x-admin.alert>

                        <div class="d-flex justify-content-end">
                            <x-admin.button type="submit" variant="warning" icon="fas fa-key">
                                Perbarui Password
                            </x-admin.button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-4">
                        <div
                            style="width: 56px; height: 56px; border-radius: 14px; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <i class="fas fa-shield-alt" style="font-size: 1.5rem; color: var(--warning-color);"></i>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                            Klik tombol "Ubah Password" untuk memperbarui password Anda.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>