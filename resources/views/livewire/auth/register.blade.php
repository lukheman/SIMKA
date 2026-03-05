<div>
    <section class="register-section">
        <div class="container">
            <div class="register-card">
                <!-- Brand -->
                <div class="register-brand">
                    <div class="brand-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1>Daftar Anggota</h1>
                    <p>Isi data diri Anda untuk mendaftar sebagai anggota CU Mentari Kasih TP Pomalaa</p>
                </div>

                <!-- Register Form -->
                <form wire:submit="submit">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" wire:model="nama_lengkap"
                                    class="form-input @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap"
                                    placeholder="Nama lengkap sesuai KTP">
                            </div>
                            @error('nama_lengkap')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nik" class="form-label">NIK</label>
                            <div class="input-wrapper">
                                <i class="fas fa-id-card"></i>
                                <input type="text" wire:model="nik"
                                    class="form-input @error('nik') is-invalid @enderror" id="nik"
                                    placeholder="16 digit NIK" maxlength="16">
                            </div>
                            @error('nik')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" wire:model="email"
                                    class="form-input @error('email') is-invalid @enderror" id="email"
                                    placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone"></i>
                                <input type="text" wire:model="no_telp"
                                    class="form-input @error('no_telp') is-invalid @enderror" id="no_telp"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                            @error('no_telp')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat" class="form-label">Alamat</label>
                        <div class="input-wrapper textarea-wrapper">
                            <i class="fas fa-map-marker-alt"></i>
                            <textarea wire:model="alamat"
                                class="form-input form-textarea @error('alamat') is-invalid @enderror" id="alamat"
                                placeholder="Alamat lengkap" rows="2"></textarea>
                        </div>
                        @error('alamat')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <div class="input-wrapper">
                            <i class="fas fa-briefcase"></i>
                            <input type="text" wire:model="pekerjaan"
                                class="form-input @error('pekerjaan') is-invalid @enderror" id="pekerjaan"
                                placeholder="Pekerjaan saat ini">
                        </div>
                        @error('pekerjaan')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" wire:model="password"
                                    class="form-input @error('password') is-invalid @enderror" id="password"
                                    placeholder="Minimal 8 karakter">
                            </div>
                            @error('password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" wire:model="password_confirmation" class="form-input"
                                    id="password_confirmation" placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" wire:loading.attr="disabled">
                        <span wire:loading.remove>Daftar Sekarang <i class="fas fa-arrow-right"></i></span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin me-1"></i> Memproses...
                        </span>
                    </button>
                </form>

                <div class="login-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </div>
        </div>
    </section>

    <x-slot:styles>
        <style>
            .register-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding-top: 80px;
                padding-bottom: 2rem;
            }

            .register-card {
                width: 100%;
                max-width: 560px;
                margin: 0 auto;
                background: var(--bg-white);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                padding: 2.5rem;
            }

            .register-brand {
                text-align: center;
                margin-bottom: 2rem;
            }

            .brand-icon {
                width: 56px;
                height: 56px;
                border-radius: 14px;
                background: rgba(212, 96, 138, 0.1);
                color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                margin: 0 auto 1rem;
            }

            .register-brand h1 {
                font-size: 1.4rem;
                font-weight: 700;
                margin-bottom: 0.25rem;
            }

            .register-brand p {
                font-size: 0.85rem;
                color: var(--text-muted);
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-label {
                display: block;
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 0.4rem;
            }

            .input-wrapper {
                position: relative;
                display: flex;
                align-items: center;
            }

            .textarea-wrapper {
                align-items: flex-start;
            }

            .textarea-wrapper>i:first-child {
                top: 14px;
            }

            .input-wrapper>i:first-child {
                position: absolute;
                left: 14px;
                color: var(--text-muted);
                font-size: 0.85rem;
                pointer-events: none;
            }

            .form-input {
                width: 100%;
                padding: 0.75rem 1rem 0.75rem 2.5rem;
                border: 1.5px solid var(--border-color);
                border-radius: 10px;
                font-size: 0.9rem;
                font-family: inherit;
                background: var(--bg-light);
                color: var(--text-primary);
                transition: border-color 0.2s, box-shadow 0.2s;
                outline: none;
            }

            .form-textarea {
                resize: vertical;
                min-height: 60px;
            }

            .form-input:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(212, 96, 138, 0.1);
                background: var(--bg-white);
            }

            .form-input.is-invalid {
                border-color: #d45d5d;
            }

            .form-input::placeholder {
                color: var(--text-muted);
            }

            .error-text {
                display: block;
                font-size: 0.8rem;
                color: #d45d5d;
                margin-top: 0.3rem;
            }

            .btn-submit {
                width: 100%;
                padding: 0.8rem;
                margin-top: 0.5rem;
                background: var(--primary-color);
                color: white;
                border: none;
                border-radius: 10px;
                font-size: 0.95rem;
                font-weight: 600;
                font-family: inherit;
                cursor: pointer;
                transition: background 0.25s, transform 0.25s, box-shadow 0.25s;
            }

            .btn-submit:hover {
                background: var(--primary-dark);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(212, 96, 138, 0.3);
            }

            .btn-submit:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                transform: none;
            }

            .login-link {
                text-align: center;
                margin-top: 1.5rem;
                font-size: 0.85rem;
                color: var(--text-secondary);
            }

            .login-link a {
                color: var(--primary-color);
                font-weight: 600;
                text-decoration: none;
            }

            .login-link a:hover {
                text-decoration: underline;
            }

            @media (max-width: 600px) {
                .form-row {
                    grid-template-columns: 1fr;
                }

                .register-card {
                    padding: 2rem 1.5rem;
                    border: none;
                    border-radius: 0;
                    background: transparent;
                }
            }
        </style>
    </x-slot:styles>
</div>