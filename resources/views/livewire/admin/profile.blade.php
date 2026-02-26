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

    <style>
        .profile-sidebar {
            position: sticky;
            top: calc(var(--topbar-height) + 2rem);
        }

        .profile-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .profile-nav-item:hover {
            background: var(--hover-bg);
            color: var(--primary-color);
        }

        .profile-nav-item.active {
            background: var(--primary-color);
            color: white;
        }

        .profile-nav-item i {
            width: 18px;
            text-align: center;
            font-size: 0.85rem;
        }

        .profile-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .profile-section-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
        }

        .profile-stat-box {
            background: var(--bg-tertiary);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            text-align: center;
        }

        .profile-stat-box .stat-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .profile-stat-box .stat-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .avatar-upload-zone {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }

        .avatar-upload-zone:hover {
            border-color: var(--primary-color);
            background: var(--hover-bg);
        }

        .divider {
            height: 1px;
            background: var(--border-light);
            margin: 1.25rem 0;
        }
    </style>

    <div class="row g-4">
        {{-- Left: Profile Sidebar Card --}}
        <div class="col-lg-3">
            <div class="profile-sidebar">
                <div class="modern-card text-center" style="border-top: none;">
                    {{-- Avatar --}}
                    <div class="mb-3">
                        @if($currentAvatar)
                            <img src="{{ Storage::url($currentAvatar) }}" alt="Avatar" class="rounded-circle"
                                style="width: 88px; height: 88px; object-fit: cover; border: 3px solid var(--primary-light);">
                        @else
                            <div class="user-avatar mx-auto" style="width: 88px; height: 88px; font-size: 2.2rem;">
                                {{ Auth::user()->initials() }}
                            </div>
                        @endif
                    </div>

                    <h6 style="color: var(--text-primary); font-weight: 700; margin-bottom: 0.15rem;">
                        {{ Auth::user()->name }}</h6>
                    <p class="text-muted mb-2" style="font-size: 0.8rem;">{{ Auth::user()->email }}</p>

                    <div class="d-flex justify-content-center gap-1 mb-3">
                        <x-admin.badge variant="primary" icon="fas fa-user-shield">Admin</x-admin.badge>
                        <x-admin.badge variant="{{ Auth::user()->email_verified_at ? 'success' : 'warning' }}">
                            {{ Auth::user()->email_verified_at ? 'Verified' : 'Unverified' }}
                        </x-admin.badge>
                    </div>

                    <div class="divider"></div>

                    {{-- Stats --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="profile-stat-box">
                                <div class="stat-value">{{ Auth::user()->created_at->format('d M Y') }}</div>
                                <div class="stat-label">Bergabung</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="profile-stat-box">
                                <div class="stat-value">{{ Auth::user()->updated_at->diffForHumans(null, true) }}</div>
                                <div class="stat-label">Diperbarui</div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- Navigation --}}
                    <nav class="d-flex flex-column gap-1">
                        <a href="#section-avatar" class="profile-nav-item active">
                            <i class="fas fa-camera"></i> Foto Profil
                        </a>
                        <a href="#section-info" class="profile-nav-item">
                            <i class="fas fa-user"></i> Informasi Akun
                        </a>
                        <a href="#section-password" class="profile-nav-item">
                            <i class="fas fa-lock"></i> Keamanan
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Right: Content Sections (stacked vertically) --}}
        <div class="col-lg-9">

            {{-- Section 1: Avatar Upload --}}
            <div class="modern-card mb-4" id="section-avatar">
                <div class="d-flex align-items-start justify-content-between mb-1">
                    <div>
                        <div class="profile-section-title">Foto Profil</div>
                        <div class="profile-section-desc">Unggah foto untuk mempersonalisasi akun Anda</div>
                    </div>
                </div>

                <div class="row align-items-center g-4">
                    {{-- Current Avatar Preview --}}
                    <div class="col-auto">
                        @if($avatar)
                            <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" class="rounded-circle"
                                style="width: 96px; height: 96px; object-fit: cover; border: 3px solid var(--primary-color); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        @elseif($currentAvatar)
                            <img src="{{ Storage::url($currentAvatar) }}" alt="Avatar" class="rounded-circle"
                                style="width: 96px; height: 96px; object-fit: cover; border: 3px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                        @else
                            <div class="user-avatar"
                                style="width: 96px; height: 96px; font-size: 2.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                                {{ Auth::user()->initials() }}
                            </div>
                        @endif
                    </div>

                    {{-- Upload Zone --}}
                    <div class="col">
                        <input type="file" wire:model="avatar" id="avatar-upload" class="d-none" accept="image/*">

                        <label for="avatar-upload" class="avatar-upload-zone d-block mb-2">
                            <i class="fas fa-cloud-upload-alt d-block mb-1"
                                style="font-size: 1.5rem; color: var(--text-muted);"></i>
                            <span wire:loading.remove wire:target="avatar"
                                style="font-size: 0.85rem; color: var(--text-secondary);">
                                Klik untuk memilih foto atau <strong
                                    style="color: var(--primary-color);">browse</strong>
                            </span>
                            <span wire:loading wire:target="avatar"
                                style="font-size: 0.85rem; color: var(--primary-color);">
                                <i class="fas fa-spinner fa-spin me-1"></i> Mengupload...
                            </span>
                            <br>
                            <small class="text-muted" style="font-size: 0.7rem;">JPG, PNG, GIF — Maks 2MB</small>
                        </label>

                        <div class="d-flex gap-2 flex-wrap">
                            @if($avatar)
                                <button type="button" wire:click="uploadAvatar" class="btn btn-modern"
                                    style="background: var(--success-color); color: white; font-size: 0.8rem; padding: 0.4rem 1rem;">
                                    <i class="fas fa-check me-1"></i>Simpan Foto
                                </button>
                                <button type="button" wire:click="$set('avatar', null)" class="btn btn-modern"
                                    style="background: var(--bg-tertiary); color: var(--text-primary); font-size: 0.8rem; padding: 0.4rem 1rem;">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                            @endif

                            @if($currentAvatar && !$avatar)
                                <button type="button" wire:click="removeAvatar" class="btn btn-modern"
                                    style="background: transparent; color: var(--danger-color); border: 1px solid var(--danger-color); font-size: 0.8rem; padding: 0.4rem 1rem;"
                                    onclick="return confirm('Hapus foto profil?')">
                                    <i class="fas fa-trash me-1"></i>Hapus Foto
                                </button>
                            @endif
                        </div>

                        @error('avatar')
                            <div class="text-danger mt-2" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Profile Information --}}
            <div class="modern-card mb-4" id="section-info">
                <div class="d-flex align-items-start justify-content-between mb-1">
                    <div>
                        <div class="profile-section-title">Informasi Akun</div>
                        <div class="profile-section-desc">Perbarui nama dan alamat email Anda</div>
                    </div>
                </div>

                <form wire:submit="updateProfile">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label" style="font-size: 0.85rem;">Nama Lengkap <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                wire:model="name" placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label" style="font-size: 0.85rem;">Email <span
                                    style="color: var(--danger-color);">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                wire:model="email" placeholder="Masukkan email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <x-admin.button type="submit" variant="primary" icon="fas fa-save">
                            Simpan Perubahan
                        </x-admin.button>
                    </div>
                </form>
            </div>

            {{-- Section 3: Change Password --}}
            <div class="modern-card" id="section-password">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div class="profile-section-title">Keamanan</div>
                        <div class="profile-section-desc">Kelola password akun Anda</div>
                    </div>
                    <x-admin.button type="button" variant="{{ $showPasswordSection ? 'danger' : 'outline' }}" size="sm"
                        wire:click="togglePasswordSection">
                        <i class="fas {{ $showPasswordSection ? 'fa-times' : 'fa-key' }} me-1"></i>
                        {{ $showPasswordSection ? 'Batal' : 'Ganti Password' }}
                    </x-admin.button>
                </div>

                @if($showPasswordSection)
                    <div class="divider"></div>

                    <form wire:submit="updatePassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label" style="font-size: 0.85rem;">Password Saat Ini
                                <span style="color: var(--danger-color);">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock" style="font-size: 0.8rem;"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" wire:model="current_password"
                                    placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label" style="font-size: 0.85rem;">Password Baru <span
                                        style="color: var(--danger-color);">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" wire:model="password" placeholder="Password baru">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label" style="font-size: 0.85rem;">Konfirmasi
                                    <span style="color: var(--danger-color);">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    wire:model="password_confirmation" placeholder="Konfirmasi password">
                            </div>
                        </div>

                        <x-admin.alert variant="info" class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Password minimal 8 karakter, mengandung huruf dan angka.
                        </x-admin.alert>

                        <div class="d-flex justify-content-end">
                            <x-admin.button type="submit" variant="warning" icon="fas fa-key">
                                Perbarui Password
                            </x-admin.button>
                        </div>
                    </form>
                @else
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-3 py-2">
                        <div
                            style="width: 44px; height: 44px; border-radius: 12px; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-shield-alt" style="color: var(--success-color);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">Password aktif
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">Terakhir diubah
                                {{ Auth::user()->updated_at->diffForHumans() }} — Klik "Ganti Password" untuk memperbarui.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>