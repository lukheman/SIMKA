<div>
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <p class="hero-label">CU Mentari Kasih TP Pomalaa</p>
                <h1 class="hero-title">
                    Sistem Informasi Pengelolaan <span class="highlight">Simpan Pinjam</span> Kredit Union (CU) Mentari
                    Kasih TP Pomalaa
                </h1>
                <p class="hero-desc">
                    Kelola simpanan dan pinjaman anggota dengan mudah, cepat, dan transparan.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-right"></i> Masuk ke Sistem
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section" id="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Layanan Kami</h2>
                <p class="section-desc">Fitur utama yang tersedia dalam sistem koperasi</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <h3>Simpanan</h3>
                    <p>Kelola simpanan pokok, wajib, dan sukarela anggota secara digital dengan pencatatan otomatis.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3>Pinjaman</h3>
                    <p>Ajukan pinjaman secara online dengan proses persetujuan yang cepat dan transparan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Laporan</h3>
                    <p>Pantau laporan keuangan, simpanan, dan pinjaman secara real-time melalui dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Syarat Menjadi Anggota Section -->
    <section class="section" id="syarat-anggota">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Syarat Menjadi Anggota CU Mentari Kasih</h2>
                <p class="section-desc">Berikut adalah persyaratan untuk menjadi anggota koperasi</p>
            </div>
            <div class="requirements-card">
                <ol class="requirements-list">
                    <li>
                        <div class="req-icon"><i class="fas fa-user-clock"></i></div>
                        <div class="req-text">Umur maksimal 50 tahun.</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-heartbeat"></i></div>
                        <div class="req-text">Sehat jasmani dan tidak cacat total tetap.</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="req-text">Berdomisili tetap minimal 6 (enam) bulan di wilayah pengembangan CU
                            Mentari Kasih.</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-file-signature"></i></div>
                        <div class="req-text">Menandatangani surat pernyataan untuk mengikuti dan mematuhi AD, ART dan
                            Kebijakan CUMKS.</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="req-text">Mengisi formulir Surat Permohonan Menjadi Anggota (SPMA).</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-id-card"></i></div>
                        <div class="req-text">Menyerahkan fotocopy KK dan KTP (masing-masing 1 lembar) yang masih
                            berlaku.</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-child"></i></div>
                        <div class="req-text">Anggota Luar Biasa didaftarkan dan masuk dengan cara PMT oleh orang tua
                            atau wali yang bersangkutan. Wajib menyertakan fotocopy Akte Kelahiran 1 (satu) lembar.
                        </div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-camera"></i></div>
                        <div class="req-text">Menyerahkan pasphoto 2x3 terbaru (2 lembar).</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-money-check-alt"></i></div>
                        <div class="req-text">Anggota baru masuk dengan cara Pinjaman Modal Tabungan (PMT).</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="req-text">Telah mengikuti Edukasi Dasar secara penuh selama dua hari.</div>
                    </li>
                    <li>
                        <div class="req-icon"><i class="fas fa-receipt"></i></div>
                        <div class="req-text">Melunasi pembayaran biaya administrasi.</div>
                    </li>
                </ol>
            </div>
        </div>
    </section>


    <!-- Stats Section -->
    <section class="section stats-section" id="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalAnggota }}</div>
                    <div class="stat-label">Anggota Terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Akses Sistem</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Digital & Transparan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-card">
                <h2>Sudah menjadi anggota?</h2>
                <p>Masuk ke sistem untuk melihat simpanan, mengajukan pinjaman, dan memantau transaksi Anda.</p>
                <a href="{{ route('login') }}" class="btn btn-white btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </a>
            </div>
        </div>
    </section>

    <x-slot:styles>
        <style>
            /* Hero */
            .hero {
                min-height: 100vh;
                display: flex;
                align-items: center;
                padding-top: 80px;
            }

            .hero-content {
                max-width: 640px;
            }

            .hero-label {
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--primary-color);
                text-transform: uppercase;
                letter-spacing: 1.5px;
                margin-bottom: 1rem;
            }

            .hero-title {
                font-size: 3.2rem;
                font-weight: 800;
                line-height: 1.15;
                margin-bottom: 1.25rem;
                color: var(--text-primary);
            }

            .highlight {
                color: var(--primary-color);
            }

            .hero-desc {
                font-size: 1.15rem;
                color: var(--text-secondary);
                margin-bottom: 2rem;
                line-height: 1.7;
                max-width: 480px;
            }

            .hero-actions {
                display: flex;
                gap: 1rem;
            }

            .btn-lg {
                padding: 0.85rem 1.75rem;
                font-size: 1rem;
            }

            /* Section Header */
            .section-header {
                margin-bottom: 3rem;
            }

            .section-title {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .section-desc {
                font-size: 1rem;
                color: var(--text-secondary);
            }

            /* Features */
            .features-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .feature-card {
                background: var(--bg-white);
                padding: 2rem;
                border-radius: 14px;
                border: 1px solid var(--border-color);
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .feature-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            }

            [data-theme="dark"] .feature-card:hover {
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            }

            .feature-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                background: rgba(212, 96, 138, 0.1);
                color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                margin-bottom: 1.25rem;
            }

            .feature-card h3 {
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .feature-card p {
                font-size: 0.9rem;
                color: var(--text-secondary);
                line-height: 1.6;
            }

            /* Requirements */
            .requirements-card {
                background: var(--bg-white);
                border-radius: 14px;
                border: 1px solid var(--border-color);
                padding: 2rem;
            }

            .requirements-list {
                list-style: none;
                counter-reset: req-counter;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .requirements-list li {
                counter-increment: req-counter;
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem 1.25rem;
                border-radius: 10px;
                background: var(--bg-color);
                border: 1px solid var(--border-color);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .requirements-list li:hover {
                transform: translateX(4px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            [data-theme="dark"] .requirements-list li:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            }

            .requirements-list li::before {
                content: counter(req-counter);
                min-width: 28px;
                height: 28px;
                border-radius: 50%;
                background: var(--primary-color);
                color: white;
                font-size: 0.8rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                margin-top: 1px;
            }

            .req-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                background: rgba(212, 96, 138, 0.1);
                color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.9rem;
                flex-shrink: 0;
            }

            .req-text {
                font-size: 0.95rem;
                color: var(--text-primary);
                line-height: 1.6;
                padding-top: 2px;
            }

            /* Stats */
            .stats-section {
                background: var(--bg-white);
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
                text-align: center;
            }

            .stat-card {
                padding: 2rem 1rem;
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: 800;
                color: var(--primary-color);
                margin-bottom: 0.25rem;
            }

            .stat-label {
                font-size: 0.9rem;
                color: var(--text-secondary);
                font-weight: 500;
            }

            /* CTA */
            .cta-card {
                background: var(--primary-color);
                border-radius: 18px;
                padding: 3.5rem;
                text-align: center;
            }

            .cta-card h2 {
                font-size: 1.75rem;
                font-weight: 700;
                color: white;
                margin-bottom: 0.75rem;
            }

            .cta-card p {
                font-size: 1rem;
                color: rgba(255, 255, 255, 0.85);
                margin-bottom: 1.5rem;
                max-width: 480px;
                margin-left: auto;
                margin-right: auto;
            }

            .btn-white {
                background: white;
                color: var(--primary-color);
                font-weight: 600;
            }

            .btn-white:hover {
                background: #faf8f9;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.2rem;
                }

                .hero-desc {
                    font-size: 1rem;
                }

                .features-grid,
                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .cta-card {
                    padding: 2.5rem 1.5rem;
                }

                .cta-card h2 {
                    font-size: 1.4rem;
                }
            }
        </style>
    </x-slot:styles>
</div>