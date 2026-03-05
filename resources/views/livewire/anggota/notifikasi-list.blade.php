<div>
    <x-admin.page-header title="Notifikasi" subtitle="Daftar notifikasi Anda" />

    {{-- Actions --}}
    @if ($belumDibaca > 0)
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <span class="text-muted" style="font-size: 0.9rem;">
                <i class="fas fa-bell me-1"></i> {{ $belumDibaca }} notifikasi belum dibaca
            </span>
            <button wire:click="tandaiSemuaBaca" class="btn btn-modern btn-sm"
                style="color: var(--primary-color); background: rgba(212, 96, 138, 0.1); border: none; border-radius: 8px; font-weight: 500;">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </button>
        </div>
    @endif

    {{-- Notification List --}}
    <div class="notifikasi-list">
        @forelse ($notifikasis as $n)
            <div class="notifikasi-item {{ !$n->dibaca ? 'belum-dibaca' : '' }}" wire:click="tandaiBaca({{ $n->id }})"
                style="cursor: pointer;">
                <div class="notifikasi-icon notifikasi-{{ $n->tipe->color() }}">
                    <i class="{{ $n->tipe->icon() }}"></i>
                </div>
                <div class="notifikasi-content">
                    <div class="notifikasi-header">
                        <h6 class="notifikasi-judul">{{ $n->judul }}</h6>
                        <small class="notifikasi-waktu">
                            {{ $n->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <p class="notifikasi-pesan">{{ $n->pesan }}</p>
                    <div class="d-flex align-items-center gap-2">
                        <x-admin.badge :variant="$n->tipe->color()" :icon="$n->tipe->icon()">
                            {{ $n->tipe->label() }}
                        </x-admin.badge>
                        @if (!$n->dibaca)
                            <span
                                style="width: 8px; height: 8px; border-radius: 50%; background: var(--primary-color); display: inline-block;"></span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="modern-card text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-bell-slash mb-3" style="font-size: 2.5rem;"></i>
                    <p class="mb-0">Belum ada notifikasi</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($notifikasis->hasPages())
        <div class="mt-3">
            {{ $notifikasis->links() }}
        </div>
    @endif

    <x-slot:styles>
        <style>
            .notifikasi-list {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .notifikasi-item {
                display: flex;
                gap: 1rem;
                padding: 1.25rem;
                background: var(--bg-white);
                border: 1px solid var(--border-color);
                border-radius: 12px;
                transition: all 0.2s ease;
            }

            .notifikasi-item:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            }

            .notifikasi-item.belum-dibaca {
                border-left: 3px solid var(--primary-color);
                background: rgba(212, 96, 138, 0.03);
            }

            .notifikasi-icon {
                width: 42px;
                height: 42px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 1rem;
            }

            .notifikasi-primary {
                background: rgba(99, 102, 241, 0.1);
                color: var(--primary-color);
            }

            .notifikasi-success {
                background: rgba(16, 185, 129, 0.1);
                color: var(--success-color);
            }

            .notifikasi-warning {
                background: rgba(245, 158, 11, 0.1);
                color: var(--warning-color);
            }

            .notifikasi-danger {
                background: rgba(239, 68, 68, 0.1);
                color: var(--danger-color);
            }

            .notifikasi-content {
                flex: 1;
                min-width: 0;
            }

            .notifikasi-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 0.5rem;
                margin-bottom: 0.25rem;
            }

            .notifikasi-judul {
                font-size: 0.95rem;
                font-weight: 600;
                color: var(--text-primary);
                margin: 0;
            }

            .notifikasi-waktu {
                font-size: 0.8rem;
                color: var(--text-muted);
                white-space: nowrap;
            }

            .notifikasi-pesan {
                font-size: 0.85rem;
                color: var(--text-secondary);
                margin-bottom: 0.5rem;
                line-height: 1.5;
            }

            @media (max-width: 576px) {
                .notifikasi-header {
                    flex-direction: column;
                }
            }
        </style>
    </x-slot:styles>
</div>