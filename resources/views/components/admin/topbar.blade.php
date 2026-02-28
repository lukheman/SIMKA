<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="mobile-toggle btn btn-sm" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <span style="color: var(--text-muted); font-size: 0.9rem;">Selamat datang, <strong
                style="color: var(--text-primary);">{{ Auth::guard('web')->user()?->name ?? Auth::guard('anggota')->user()?->nama_lengkap ?? 'Guest' }}</strong></span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button class="theme-toggle" onclick="toggleTheme()">
            <i class="fas fa-moon" id="theme-icon"></i>
        </button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="action-btn" style="color: var(--danger-color);">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</div>
