<div>
    <x-admin.page-header title="Notifikasi" subtitle="Daftar notifikasi Anda" />

    {{-- Summary Bar --}}
    <div class="notif-summary-bar mb-4">
        <div class="notif-summary-left">
            <div class="notif-summary-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <span class="notif-summary-count">{{ $belumDibaca }}</span>
                <span class="notif-summary-label">notifikasi belum dibaca</span>
            </div>
        </div>
        @if ($belumDibaca > 0)
            <button wire:click="tandaiSemuaBaca" class="notif-mark-all-btn">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </button>
        @endif
    </div>

    {{-- Notification Cards --}}
    <div class="notif-cards">
        @forelse ($notifikasis as $n)
            <div class="notif-card {{ !$n->dibaca ? 'notif-unread' : '' }}" wire:click="tandaiBaca({{ $n->id }})"
                style="cursor: pointer;" data-type="{{ $n->tipe->color() }}">

                {{-- Accent Strip --}}
                <div class="notif-accent notif-accent-{{ $n->tipe->color() }}"></div>

                <div class="notif-card-body">
                    {{-- Icon --}}
                    <div class="notif-icon-wrapper notif-icon-{{ $n->tipe->color() }}">
                        <i class="{{ $n->tipe->icon() }}"></i>
                    </div>

                    {{-- Content --}}
                    <div class="notif-card-content">
                        <div class="notif-card-top">
                            <div class="notif-card-title-row">
                                <h6 class="notif-card-title">
                                    @if (!$n->dibaca)
                                        <span class="notif-unread-dot"></span>
                                    @endif
                                    {{ $n->judul }}
                                </h6>
                                <x-admin.badge :variant="$n->tipe->color()" :icon="$n->tipe->icon()">
                                    {{ $n->tipe->label() }}
                                </x-admin.badge>
                            </div>
                            <p class="notif-card-pesan">{{ $n->pesan }}</p>
                        </div>
                        <div class="notif-card-footer">
                            <span class="notif-card-time">
                                <i class="far fa-clock me-1"></i>{{ $n->created_at->diffForHumans() }}
                            </span>
                            @if (!$n->dibaca)
                                <span class="notif-card-status">Belum dibaca</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="notif-empty-state">
                <div class="notif-empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h6 class="notif-empty-title">Tidak Ada Notifikasi</h6>
                <p class="notif-empty-text">Semua notifikasi akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($notifikasis->hasPages())
        <div class="mt-4">
            {{ $notifikasis->links() }}
        </div>
    @endif

    <style>
        /* ── Summary Bar ── */
        .notif-summary-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 1rem 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .notif-summary-left {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .notif-summary-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .notif-summary-count {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-right: 0.35rem;
        }

        .notif-summary-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .notif-mark-all-btn {
            background: rgba(212, 96, 138, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(212, 96, 138, 0.2);
            border-radius: 10px;
            padding: 0.5rem 1.125rem;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .notif-mark-all-btn:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(212, 96, 138, 0.3);
        }

        /* ── Card List ── */
        .notif-cards {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        /* ── Individual Card ── */
        .notif-card {
            position: relative;
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.25s ease;
        }

        .notif-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(180, 100, 130, 0.1);
        }

        [data-theme="dark"] .notif-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        .notif-card.notif-unread {
            border-color: rgba(212, 96, 138, 0.25);
            background: linear-gradient(135deg, rgba(212, 96, 138, 0.03) 0%, var(--bg-secondary) 100%);
        }

        /* ── Accent Strip ── */
        .notif-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            border-radius: 4px 0 0 4px;
        }

        .notif-accent-primary {
            background: linear-gradient(180deg, var(--primary-color), var(--primary-light));
        }

        .notif-accent-success {
            background: linear-gradient(180deg, #5cad8a, #7cc4a5);
        }

        .notif-accent-warning {
            background: linear-gradient(180deg, #d4a04e, #e0bc78);
        }

        .notif-accent-danger {
            background: linear-gradient(180deg, #d45d5d, #e08080);
        }

        /* ── Card Body ── */
        .notif-card-body {
            display: flex;
            gap: 1rem;
            padding: 1.25rem 1.25rem 1.25rem 1.5rem;
        }

        /* ── Icon ── */
        .notif-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.15rem;
            transition: transform 0.2s ease;
        }

        .notif-card:hover .notif-icon-wrapper {
            transform: scale(1.08);
        }

        .notif-icon-primary {
            background: rgba(212, 96, 138, 0.1);
            color: var(--primary-color);
        }

        .notif-icon-success {
            background: rgba(92, 173, 138, 0.1);
            color: var(--success-color);
        }

        .notif-icon-warning {
            background: rgba(212, 160, 78, 0.1);
            color: var(--warning-color);
        }

        .notif-icon-danger {
            background: rgba(212, 93, 93, 0.1);
            color: var(--danger-color);
        }

        /* ── Content ── */
        .notif-card-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .notif-card-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.35rem;
        }

        .notif-card-title {
            font-size: 0.95rem;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notif-unread-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary-color);
            display: inline-block;
            flex-shrink: 0;
            animation: notif-pulse 2s ease-in-out infinite;
        }

        @keyframes notif-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.85);
            }
        }

        .notif-card-pesan {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0 0 0.625rem 0;
            line-height: 1.6;
        }

        .notif-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .notif-card-time {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .notif-card-status {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--primary-color);
            background: rgba(212, 96, 138, 0.08);
            padding: 0.2rem 0.625rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* ── Empty State ── */
        .notif-empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-secondary);
            border: 1px dashed var(--border-color);
            border-radius: 16px;
        }

        .notif-empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--hover-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            color: var(--text-muted);
        }

        .notif-empty-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.35rem;
        }

        .notif-empty-text {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .notif-summary-bar {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .notif-mark-all-btn {
                width: 100%;
                text-align: center;
            }

            .notif-card-title-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.35rem;
            }

            .notif-card-body {
                padding: 1rem 1rem 1rem 1.25rem;
            }

            .notif-icon-wrapper {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                font-size: 1rem;
            }

            .notif-card-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.35rem;
            }
        }
    </style>
</div>