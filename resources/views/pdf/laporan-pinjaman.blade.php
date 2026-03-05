<x-pdf.layout :title="'LAPORAN PENGAJUAN PINJAMAN'" :tanggal="$tanggal">
    <div class="info">
        <p>Filter Status: <strong>{{ $filterStatus ? ucfirst($filterStatus) : 'Semua' }}</strong> |
            Filter Jenis: <strong>{{ $filterJenis ?: 'Semua' }}</strong></p>
        <p>Total Data: <strong>{{ $pengajuans->count() }} pengajuan</strong></p>
    </div>

    <div class="summary">
        <span class="summary-item summary-info"><strong>Total Pengajuan:</strong> Rp
            {{ number_format($totalPengajuan, 0, ',', '.') }}</span>
        <span class="summary-item summary-success"><strong>Total Disetujui:</strong> Rp
            {{ number_format($totalDisetujui, 0, ',', '.') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Anggota</th>
                <th>Jenis Pinjaman</th>
                <th class="text-right">Jumlah Pengajuan</th>
                <th class="text-right">Jumlah Disetujui</th>
                <th>Tenor</th>
                <th class="text-right">Bunga Total</th>
                <th>Tgl Pengajuan</th>
                <th>Tgl Cair</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuans as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $p->anggota->nama_lengkap }}</strong><br><small>{{ $p->anggota->no_anggota }}</small>
                    </td>
                    <td>{{ $p->jenisPinjaman->nama_pinjaman }}</td>
                    <td class="text-right">Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ $p->jumlah_disetujui ? 'Rp ' . number_format($p->jumlah_disetujui, 0, ',', '.') : '-' }}</td>
                    <td>{{ $p->tenor_bulan }} bln</td>
                    <td class="text-right">Rp {{ number_format($p->bunga_total, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d/m/Y') }}</td>
                    <td>{{ $p->tgl_cair ? \Carbon\Carbon::parse($p->tgl_cair)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @php $status = $p->status instanceof \App\Enum\StatusPengajuan ? $p->status : \App\Enum\StatusPengajuan::from($p->status); @endphp
                        <span class="badge badge-{{ $status->value }}">{{ $status->label() }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-pdf.layout>