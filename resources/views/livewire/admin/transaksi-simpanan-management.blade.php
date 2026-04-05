<div>
    <x-admin.page-header title="Transaksi Simpanan" subtitle="Kelola transaksi simpanan anggota">
        <x-slot:actions>
            <button wire:click="openCreateModal" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-plus me-2"></i>Transaksi Baru
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert variant="danger" title="Error!" class="mb-4">{{ session('error') }}</x-admin.alert>
    @endif

    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Transaksi</h5>
                @if ($pendingCount > 0)
                    <x-admin.badge variant="warning" icon="fas fa-clock">{{ $pendingCount }} menunggu</x-admin.badge>
                @endif
            </div>
            <div class="d-flex gap-2">
                <select class="form-control" wire:model.live="filterStatus" style="min-width: 140px;">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
                <select class="form-control" wire:model.live="filterTipe" style="min-width: 130px;">
                    <option value="">Semua Tipe</option>
                    @foreach ($tipeOptions as $tipe)
                        <option value="{{ $tipe->value }}">{{ $tipe->label() }}</option>
                    @endforeach
                </select>
                <select class="form-control" wire:model.live="filterJenis" style="min-width: 160px;">
                    <option value="">Semua Jenis</option>
                    @foreach ($jenisSimpanans as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_simpanan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"
                    style="background: var(--input-bg); border-color: var(--border-color);"><i class="fas fa-search"
                        style="color: var(--text-muted);"></i></span>
                <input type="text" class="form-control" placeholder="Cari anggota / kode..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Anggota</th>
                        <th>Jenis</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $t)
                        <tr wire:key="trx-{{ $t->id }}">
                            <td><code style="color: var(--primary-color);">{{ $t->kode_transaksi }}</code></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                        {{ strtoupper(substr($t->anggota->nama_lengkap, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.85rem;">
                                            {{ $t->anggota->nama_lengkap }}
                                        </div>
                                        <small class="text-muted">{{ $t->anggota->no_anggota }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold">{{ $t->jenisSimpanan->nama_simpanan }}</td>
                            <td>
                                @php $tipe = $t->tipe_transaksi instanceof \App\Enum\TipeTransaksi ? $t->tipe_transaksi : \App\Enum\TipeTransaksi::from($t->tipe_transaksi); @endphp
                                <x-admin.badge :variant="$tipe->color()"
                                    :icon="$tipe->icon()">{{ $tipe->label() }}</x-admin.badge>
                            </td>
                            <td class="fw-semibold"
                                style="color: {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                {{ $tipe === \App\Enum\TipeTransaksi::SETOR ? '+' : '-' }} Rp
                                {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($t->tgl_transaksi)->format('d M Y') }}</td>
                            <td>
                                @php $status = $t->status instanceof \App\Enum\StatusPengajuan ? $t->status : \App\Enum\StatusPengajuan::from($t->status); @endphp
                                <x-admin.badge :variant="$status->color()" :icon="$status->icon()">
                                    {{ $status->label() }}
                                </x-admin.badge>
                                @if ($t->alasan_tolak)
                                    <br><small class="text-muted" title="{{ $t->alasan_tolak }}"><i
                                            class="fas fa-info-circle"></i> {{ Str::limit($t->alasan_tolak, 20) }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($status === \App\Enum\StatusPengajuan::PENDING)
                                    <div class="d-flex gap-1">
                                        <button class="action-btn" style="color: var(--success-color);"
                                            wire:click="openApproveModal({{ $t->id }})" title="Setujui">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button class="action-btn" style="color: var(--danger-color);"
                                            wire:click="openRejectModal({{ $t->id }})" title="Tolak">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-piggy-bank mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada transaksi simpanan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transaksis->hasPages())
            <div class="d-flex justify-content-end mt-4">{{ $transaksis->links() }}</div>
        @endif
    </div>

    {{-- Create Modal --}}
    @if ($showCreateModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 500px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom"><i class="fas fa-plus-circle me-2"
                            style="color: var(--primary-color);"></i>Transaksi Baru (Admin)</h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="mb-2 p-2 rounded-2"
                    style="background: rgba(92,173,138,0.08); border: 1px solid rgba(92,173,138,0.2); font-size: 0.8rem; color: var(--success-color);">
                    <i class="fas fa-info-circle me-1"></i> Transaksi dari admin otomatis disetujui
                </div>
                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="anggota_id" class="form-label">Anggota <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-control @error('anggota_id') is-invalid @enderror" id="anggota_id"
                            wire:model.live="anggota_id">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach ($anggotas as $anggota)
                                <option value="{{ $anggota->id }}">{{ $anggota->no_anggota }} - {{ $anggota->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="jenis_simpanan_id" class="form-label">Jenis Simpanan <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-control @error('jenis_simpanan_id') is-invalid @enderror" id="jenis_simpanan_id"
                            wire:model.live="jenis_simpanan_id">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($jenisSimpanans as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_simpanan }} (Min: Rp
                                    {{ number_format($jenis->minimal_setor, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_simpanan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="tipe_transaksi" class="form-label">Tipe <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-control @error('tipe_transaksi') is-invalid @enderror" id="tipe_transaksi"
                            wire:model.live="tipe_transaksi">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="setor">Setor</option>
                            <option value="tarik">Tarik</option>
                        </select>
                        @error('tipe_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @if ($saldoAnggota !== null && $anggota_id && $jenis_simpanan_id)
                        <div class="mb-3 p-3 rounded-3"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color);">
                            <div class="d-flex justify-content-between" style="font-size: 0.85rem;">
                                <span class="text-muted">Saldo saat ini:</span>
                                <strong style="color: var(--primary-color);">Rp
                                    {{ number_format($saldoAnggota, 0, ',', '.') }}</strong>
                            </div>
                            @if ($minimalSetor && $tipe_transaksi === 'setor')
                                <div class="d-flex justify-content-between mt-1" style="font-size: 0.85rem;">
                                    <span class="text-muted">Minimal setor:</span>
                                    <strong style="color: var(--warning-color);">Rp
                                        {{ number_format($minimalSetor, 0, ',', '.') }}</strong>
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah (Rp) <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                            wire:model="jumlah">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" wire:model="keterangan" rows="2"
                            placeholder="Opsional"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-modern"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);"
                            wire:click="closeModal">Batal</button>
                        <button type="submit" class="btn btn-modern btn-primary-modern">
                            <span wire:loading.remove wire:target="save"><i class="fas fa-save me-2"></i>Simpan</span>
                            <span wire:loading wire:target="save"><i
                                    class="fas fa-spinner fa-spin me-2"></i>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Approve Modal --}}
    @if ($showApproveModal)
        <div class="modal-backdrop-custom" wire:click.self="closeApproveModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 450px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom"><i class="fas fa-check-circle me-2"
                            style="color: var(--success-color);"></i>Setujui Pengajuan</h5>
                    <button type="button" class="modal-close-btn" wire:click="closeApproveModal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="p-3">
                    <p style="font-size: 1.05rem; color: var(--text-primary);">Apakah Anda yakin
                        ingin menyetujui pengajuan ini?</p>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-modern"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);"
                            wire:click="closeApproveModal">Batal</button>
                        <button type="button" class="btn btn-modern" style="background: var(--success-color); color: white;"
                            wire:click="approve">
                            <span wire:loading.remove wire:target="approve"><i class="fas fa-check me-2"></i>Setujui</span>
                            <span wire:loading wire:target="approve"><i
                                    class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    @if ($showRejectModal)
        <div class="modal-backdrop-custom" wire:click.self="closeRejectModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 450px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom"><i class="fas fa-times-circle me-2"
                            style="color: var(--danger-color);"></i>Tolak Pengajuan</h5>
                    <button type="button" class="modal-close-btn" wire:click="closeRejectModal"><i
                            class="fas fa-times"></i></button>
                </div>
                <form wire:submit="reject">
                    <div class="mb-3">
                        <label for="alasan_tolak" class="form-label">Alasan Penolakan <span
                                style="color: var(--danger-color);">*</span></label>
                        <textarea class="form-control @error('alasan_tolak') is-invalid @enderror" id="alasan_tolak"
                            wire:model="alasan_tolak" rows="3" placeholder="Jelaskan alasan penolakan..."></textarea>
                        @error('alasan_tolak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-modern"
                            style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary);"
                            wire:click="closeRejectModal">Batal</button>
                        <button type="submit" class="btn btn-modern" style="background: var(--danger-color); color: white;">
                            <span wire:loading.remove wire:target="reject"><i class="fas fa-times me-2"></i>Tolak</span>
                            <span wire:loading wire:target="reject"><i
                                    class="fas fa-spinner fa-spin me-2"></i>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
