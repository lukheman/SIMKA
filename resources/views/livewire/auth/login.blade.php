<div>
    <section class="login-section">
        <div class="container">
            <div class="login-card">
                <!-- Brand -->
                <div class="login-brand">
                    <div class="brand-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h1>Masuk ke SIMKA</h1>
                    <p>Sistem Informasi Pengelolaan Simpan Pinjam Kredit Union (CU) Mentari Kasih TP Pomalaa</p>
                </div>

                <!-- Login Form -->
                <form wire:submit="submit">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" wire:model="email"
                                class="form-input @error('email') is-invalid @enderror" id="email"
                                placeholder="nama@email.com" autofocus>
                        </div>
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" wire:model="password"
                                class="form-input @error('password') is-invalid @enderror" id="password"
                                placeholder="••••••••">
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" wire:model="remember">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit" wire:loading.attr="disabled">
                        <span wire:loading.remove>Masuk <i class="fas fa-arrow-right"></i></span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin me-1"></i> Memproses...
                        </span>
                    </button>
                </form>

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai anggota</a>
                </div>
            </div>
        </div>
    </section>

    <x-slot:styles>
        <style>
            .login-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding-top: 80px;
                padding-bottom: 2rem;
            }

            .login-card {
                width: 100%;
                max-width: 420px;
                margin: 0 auto;
                background: var(--bg-white);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                padding: 2.5rem;
            }

            .login-brand {
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

            .login-brand h1 {
                font-size: 1.4rem;
                font-weight: 700;
                margin-bottom: 0.25rem;
            }

            .login-brand p {
                font-size: 0.85rem;
                color: var(--text-muted);
            }

            .form-group {
                margin-bottom: 1.25rem;
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

            .toggle-password {
                position: absolute;
                right: 12px;
                background: transparent;
                border: none;
                color: var(--text-muted);
                cursor: pointer;
                padding: 4px;
                font-size: 0.9rem;
                transition: color 0.2s;
            }

            .toggle-password:hover {
                color: var(--primary-color);
            }

            .error-text {
                display: block;
                font-size: 0.8rem;
                color: #d45d5d;
                margin-top: 0.3rem;
            }

            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .checkbox-label {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 0.85rem;
                color: var(--text-secondary);
                cursor: pointer;
            }

            .checkbox-label input {
                accent-color: var(--primary-color);
                width: 16px;
                height: 16px;
            }

            .btn-submit {
                width: 100%;
                padding: 0.8rem;
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

            @media (max-width: 480px) {
                .login-card {
                    padding: 2rem 1.5rem;
                    border: none;
                    border-radius: 0;
                    background: transparent;
                }
            }

            .register-link {
                text-align: center;
                margin-top: 1.5rem;
                font-size: 0.85rem;
                color: var(--text-secondary);
            }

            .register-link a {
                color: var(--primary-color);
                font-weight: 600;
                text-decoration: none;
            }

            .register-link a:hover {
                text-decoration: underline;
            }
        </style>
    </x-slot:styles>

    <x-slot:scripts>
        <script>
            function togglePassword() {
                const input = document.getElementById('password');
                const icon = document.getElementById('toggleIcon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            }
        </script>
    </x-slot:scripts>
</div>