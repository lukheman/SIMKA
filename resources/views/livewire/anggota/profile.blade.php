<div>
    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    <style>
        .avatar-upload-zone {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .avatar-upload-zone:hover {
            border-color: var(--primary-color);
            background: var(--hover-bg);
        }
    </style>

    <div class="row g-4">
        {{-- Left: Profile Sidebar --}}
        <div class="col-lg-3">
            <div style="position: sticky; top: calc(var(--topbar-height) + 2rem);">
                <div class="modern-card text-center" style="border-top: none;">
                    {{-- Avatar --}}
                    <div class="mb-3">
                        @if($currentAvatar)
                            <img src="{{ Storage::url($currentAvatar) }}" alt="Avatar" class="rounded-circle"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 1.5rem;">
                                {{ Auth::guard('anggota')->user()->initials() }}
                            </div>
                        @endif
                    </div>

                    <h6 class="fw-bold mb-0" style="color: var(--text-primary);">{{ $nama_lengkap }}</h6>
                    <small class="text-muted">{{ $email }}</small>

                    <div style="height: 1px; background: var(--border-light); margin: 1.25rem 0;"></div>

                    {{-- Info --}}
                    <div class="text-start" style="font-size: 0.85rem;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-id-card text-muted" style="width: 16px;"></i>
                            <span style="color: var(--text-secondary);">{{ Auth::guard('anggota')->user()->no_anggota }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-briefcase text-muted" style="width: 16px;"></i>
                            <span style="color: var(--text-secondary);">{{ $pekerjaan }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-phone text-muted" style="width: 16px;"></i>
                            <span style="color: var(--text-secondary);">{{ $no_telp }}</span>
                        </div>
                    </div>

                    <div style="height: 1px; background: var(--border-light); margin: 1.25rem 0;"></div>

                    {{-- Navigation --}}
                    <nav class="d-flex flex-column gap-1">
                        <a href="#section-avatar" class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-decoration-none" style="color: var(--primary-color); background: rgba(74,127,181,0.08);">
                            <i class="fas fa-camera"></i> Foto Profil
                        </a>
                        <a href="#section-profile" class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-decoration-none" style="color: var(--text-secondary);">
                            <i class="fas fa-user"></i> Informasi Pribadi
                        </a>
                        <a href="#section-password" class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 text-decoration-none" style="color: var(--text-secondary);">
                            <i class="fas fa-lock"></i> Keamanan
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Right: Content --}}
        <div class="col-lg-9">

            {{-- Section 1: Avatar Upload --}}
            <div class="modern-card mb-4" id="section-avatar">
                <div class="mb-1">
                    <div class="fw-bold" style="color: var(--text-primary); font-size: 1rem;">Foto Profil</div>
                    <small class="text-muted">Unggah foto untuk mempersonalisasi akun Anda</small>
                </div>

                <div style="height: 1px; background: var(--border-light); margin: 1rem 0;"></div>

                <div class="row align-items-center g-4">
                    {{-- Current Avatar Preview --}}
                    <div class="col-auto">
                        @if($avatar)
                            <img src="{{ $avatar->temporaryUrl() }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="Preview">
                        @elseif($currentAvatar)
                            <img src="{{ Storage::url($currentAvatar) }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="Avatar">
                        @else
                            <div class="user-avatar" style="width: 80px; height: 80px; font-size: 1.5rem;">
                                {{ Auth::guard('anggota')->user()->initials() }}
                            </div>
                        @endif
                    </div>

                    {{-- Upload Zone --}}
                    <div class="col">
                        <input type="file" wire:model="avatar" id="avatar-upload" class="d-none" accept="image/*">

                        <label for="avatar-upload" class="avatar-upload-zone d-block mb-2">
                            <span wire:loading.remove wire:target="avatar">
                                <i class="fas fa-cloud-upload-alt me-2" style="color: var(--primary-color);"></i>
                                <span style="color: var(--text-secondary); font-size: 0.85rem;">Klik untuk memilih foto</span>
                            </span>
                            <span wire:loading wire:target="avatar">
                                <i class="fas fa-spinner fa-spin me-2" style="color: var(--primary-color);"></i>
                                <span style="color: var(--text-secondary); font-size: 0.85rem;">Mengunggah...</span>
                            </span>
                            <br>
                            <small class="text-muted" style="font-size: 0.7rem;">JPG, PNG, GIF — Maks 2MB</small>
                        </label>

                        @error('avatar')
                            <div class="text-danger" style="font-size: 0.85rem;">{{ $message }}</div>
                        @enderror

                        <div class="d-flex gap-2 flex-wrap">
                            @if($avatar)
                                <button wire:click="uploadAvatar" class="btn btn-sm btn-modern btn-primary-modern">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                                <button wire:click="$set('avatar', null)" class="btn btn-sm btn-modern" style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);">
                                    Batal
                                </button>
                            @endif
                            @if($currentAvatar && !$avatar)
                                <button wire:click="removeAvatar" wire:confirm="Yakin ingin menghapus foto profil?" class="btn btn-sm btn-modern" style="color: var(--danger-color); background: rgba(212,93,93,0.08); border: 1px solid rgba(212,93,93,0.2);">
                                    <i class="fas fa-trash me-1"></i> Hapus Foto
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Personal Info --}}
            <div class="modern-card mb-4" id="section-profile">
                <div class="mb-1">
                    <div class="fw-bold" style="color: var(--text-primary); font-size: 1rem;">Informasi Pribadi</div>
                    <small class="text-muted">Perbarui data diri Anda</small>
                </div>

                <div style="height: 1px; background: var(--border-light); margin: 1rem 0;"></div>

                <form wire:submit="updateProfile">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap"
                            wire:model="nama_lengkap">
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span style="color: var(--danger-color);">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            wire:model="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span style="color: var(--danger-color);">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                            wire:model="alamat" rows="2"></textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan <span style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" id="pekerjaan"
                                wire:model="pekerjaan">
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_telp" class="form-label">No. Telp <span style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                wire:model="no_telp">
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-modern btn-primary-modern">
                            <span wire:loading.remove wire:target="updateProfile">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </span>
                            <span wire:loading wire:target="updateProfile">
                                <i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Section 3: Password --}}
            <div class="modern-card" id="section-password">
                <div class="d-flex align-items-start justify-content-between mb-1">
                    <div>
                        <div class="fw-bold" style="color: var(--text-primary); font-size: 1rem;">Keamanan</div>
                        <small class="text-muted">Ubah password akun Anda</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-modern" wire:click="togglePasswordSection"
                        style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);">
                        <i class="fas {{ $showPasswordSection ? 'fa-times' : 'fa-key' }} me-1"></i>
                        {{ $showPasswordSection ? 'Batal' : 'Ubah Password' }}
                    </button>
                </div>

                @if ($showPasswordSection)
                    <div style="height: 1px; background: var(--border-light); margin: 1rem 0;"></div>

                    <form wire:submit="updatePassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini <span style="color: var(--danger-color);">*</span></label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password"
                                wire:model="current_password" placeholder="Masukkan password saat ini">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password Baru <span style="color: var(--danger-color);">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                    wire:model="password" placeholder="Masukkan password baru">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    wire:model="password_confirmation" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-modern btn-primary-modern">
                                <span wire:loading.remove wire:target="updatePassword">
                                    <i class="fas fa-lock me-2"></i>Perbarui Password
                                </span>
                                <span wire:loading wire:target="updatePassword">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                @else
                    <div style="height: 1px; background: var(--border-light); margin: 1rem 0;"></div>
                    <div class="d-flex align-items-center gap-3 py-2">
                        <div class="stat-icon" style="background: rgba(92,173,138,0.12); color: var(--success-color); width: 40px; height: 40px; font-size: 1rem; margin-bottom: 0;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.9rem;">Password Aman</div>
                            <small class="text-muted">Klik "Ubah Password" untuk mengganti password Anda</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
