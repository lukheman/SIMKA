@props([
    'title' => 'SIMKA - Sistem Informasi Pengelolaan Simpan Pinjam Kredit Union (CU) Mentari Kasih TP Pomalaa',
    'description' => 'Sistem Informasi Pengelolaan Simpan Pinjam Kredit Union (CU) Mentari Kasih TP Pomalaa',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #d4608a;
            --primary-dark: #b8496f;
            --primary-light: #e8a0b8;
            --text-primary: #2d2832;
            --text-secondary: #6b6370;
            --text-muted: #9e95a3;
            --border-color: #ebe5e8;
            --bg-light: #faf8f9;
            --bg-white: #ffffff;
        }

        [data-theme="dark"] {
            --primary-color: #e8a0b8;
            --primary-dark: #d4608a;
            --primary-light: #f0c0d0;
            --text-primary: #e8e6ec;
            --text-secondary: #b3b0ba;
            --text-muted: #807c88;
            --border-color: #2e2d36;
            --bg-light: #141318;
            --bg-white: #1c1b22;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-light);
            color: var(--text-primary);
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        }

        [data-theme="dark"] .navbar {
            background: rgba(20, 19, 24, 0.9);
        }

        [data-theme="dark"] .navbar.scrolled {
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        .navbar-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--primary-color);
            font-size: 1.4rem;
            font-weight: 700;
        }

        .navbar-brand i {
            font-size: 1.5rem;
        }

        /* Nav Menu */
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar-menu a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .navbar-menu a:hover {
            color: var(--primary-color);
            background: rgba(212, 96, 138, 0.08);
        }

        .navbar-menu a.active {
            color: var(--primary-color);
            background: rgba(212, 96, 138, 0.1);
            font-weight: 600;
        }

        .navbar-menu a i {
            font-size: 0.8rem;
        }

        /* Hamburger */
        .navbar-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.25s ease;
        }

        .navbar-toggle:hover {
            background: var(--border-color);
            color: var(--primary-color);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.65rem 1.4rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(212, 96, 138, 0.3);
        }

        /* Theme Toggle */
        .theme-toggle {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            background: var(--border-color);
            color: var(--primary-color);
        }

        .theme-toggle i {
            font-size: 1.15rem;
        }

        /* Container */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Section */
        .section {
            padding: 5rem 0;
        }

        /* Footer */
        .footer {
            background: var(--bg-white);
            border-top: 1px solid var(--border-color);
            padding: 2.5rem 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
        }

        .footer-brand i {
            font-size: 1.2rem;
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .navbar-toggle {
                display: block;
            }

            .navbar-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                flex-direction: column;
                background: var(--bg-white);
                border-bottom: 1px solid var(--border-color);
                padding: 0.75rem 1.5rem 1rem;
                gap: 0.25rem;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            }

            [data-theme="dark"] .navbar-menu {
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            }

            .navbar-menu.open {
                display: flex;
            }

            .navbar-menu a {
                width: 100%;
                padding: 0.65rem 1rem;
            }

            .navbar-actions .btn-outline {
                display: none;
            }
        }
    </style>
    {{ $styles ?? '' }}
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="main-navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">
                <i class="fas fa-university"></i>
                <span>SIMKA</span>
            </a>

            <ul class="navbar-menu" id="navbar-menu">
                <li><a href="{{ route('home')}}#home" class="active"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="{{ route('home')}}#features"><i class="fas fa-concierge-bell"></i> Layanan</a></li>
                <li><a href="{{ route('home')}}#syarat-anggota"><i class="fas fa-clipboard-list"></i> Syarat Anggota</a></li>
                <!-- <li><a href="#stats"><i class="fas fa-chart-bar"></i> Statistik</a></li> -->
            </ul>

            <div class="navbar-actions">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
                <button class="navbar-toggle" id="navbar-toggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <a href="/" class="footer-brand">
                    <i class="fas fa-university"></i>
                    SIMKA
                </a>
                <p class="footer-text">&copy; {{ date('Y') }} CU Mentari Kasih TP Pomalaa. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Theme
        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (prefersDark) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }

            updateThemeIcon();
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const themeIcon = document.getElementById('theme-icon');
            if (themeIcon) {
                themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
        }

        initTheme();

        // Hamburger Toggle
        const navbarToggle = document.getElementById('navbar-toggle');
        const navbarMenu = document.getElementById('navbar-menu');

        if (navbarToggle && navbarMenu) {
            navbarToggle.addEventListener('click', () => {
                navbarMenu.classList.toggle('open');
                const icon = navbarToggle.querySelector('i');
                icon.className = navbarMenu.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
            });
        }

        // Smooth scroll & close mobile menu on click
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu
                if (navbarMenu) {
                    navbarMenu.classList.remove('open');
                    const icon = navbarToggle?.querySelector('i');
                    if (icon) icon.className = 'fas fa-bars';
                }
            });
        });

        // Navbar shadow on scroll
        const navbar = document.getElementById('main-navbar');
        window.addEventListener('scroll', () => {
            if (navbar) {
                navbar.classList.toggle('scrolled', window.scrollY > 10);
            }
        });

        // Active link on scroll (Intersection Observer)
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.navbar-menu a');

        if (sections.length && navLinks.length) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        navLinks.forEach(link => {
                            link.classList.toggle('active', link.getAttribute('href') === '#' + id);
                        });
                    }
                });
            }, {
                rootMargin: '-20% 0px -60% 0px',
                threshold: 0
            });

            sections.forEach(section => observer.observe(section));
        }
    </script>
    {{ $scripts ?? '' }}
</body>

</html>
