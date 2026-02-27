<div>
    {{-- Page Header --}}
    <x-admin.page-header :title="$this->getTitle()" subtitle="Isi data lengkap anggota koperasi">
        <x-slot:actions>
            <x-admin.button variant="outline" icon="fas fa-arrow-left" href="{{ route('admin.anggota') }}"
                wire:navigate>
                Kembali
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Form Card --}}
    <div class="modern-card">
        <form wire:submit="save">
            {{-- Data Anggota Section --}}
            <h6 class="mb-3" style="color: var(--text-primary); font-weight: 600;">
                <i class="fas fa-id-card me-2" style="color: var(--primary-color);"></i>Data Anggota
            </h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="no_anggota" class="form-label">No. Anggota <span
                            style="color: var(--danger-color);">*</span></label>
                    <input type="text" class="form-control @error('no_anggota') is-invalid @enderror" id="no_anggota"
                        wire:model="no_anggota" placeholder="Contoh: AGT-00001">
                    @error('no_anggota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nik" class="form-label">NIK <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" wire:model="nik"
                        placeholder="Masukkan NIK">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap <span
                        style="color: var(--danger-color);">*</span></label>
                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap"
                    wire:model="nama_lengkap" placeholder="Masukkan nama lengkap">
                @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat <span style="color: var(--danger-color);">*</span></label>
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

            {{-- Login Credentials Section --}}
            <hr style="border-color: var(--border-color); margin: 1.5rem 0;">
            <h6 class="mb-3" style="color: var(--text-primary); font-weight: 600;">
                <i class="fas fa-key me-2" style="color: var(--primary-color);"></i>Akun Login
            </h6>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span style="color: var(--danger-color);">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    wire:model="email" placeholder="Masukkan email untuk login">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">
                        Password
                        @if (!$anggotaId)
                            <span style="color: var(--danger-color);">*</span>
                        @else
                            <small class="text-muted">(kosongkan jika tidak diubah)</small>
                        @endif
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        wire:model="password" placeholder="{{ $anggotaId ? 'Password baru' : 'Masukkan password' }}">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation"
                        wire:model="password_confirmation" placeholder="Ulangi password">
                </div>
            </div>

            {{-- Action Buttons --}}
            <hr style="border-color: var(--border-color); margin: 1.5rem 0;">
            <div class="d-flex justify-content-end gap-2">
                <x-admin.button type="button" variant="outline" href="{{ route('admin.anggota') }}" wire:navigate>
                    Batal
                </x-admin.button>
                <x-admin.button type="submit" variant="primary">
                    <span wire:loading.remove wire:target="save">
                        <i class="fas fa-save me-2"></i>{{ $anggotaId ? 'Perbarui' : 'Simpan' }}
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...
                    </span>
                </x-admin.button>
            </div>
        </form>
    </div>
</div>