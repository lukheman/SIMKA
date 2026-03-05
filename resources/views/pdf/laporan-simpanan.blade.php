<x-pdf.layout :title="'LAPORAN TRANSAKSI SIMPANAN'" :tanggal="$tanggal">
    <div class="info">
        <p>Filter Tipe: <strong>{{ $filterTipe ? ucfirst($filterTipe) : 'Semua' }}</strong> |
            Filter Jenis: <strong>{{ $filterJenis ?: 'Semua' }}</strong> |
            Filter Status: <strong>{{ $filterStatus ? ucfirst($filterStatus) : 'Semua' }}</strong></p>
        <p>Total Data: <strong>{{ $transaksis->count() }} transaksi</strong></p>
    </div>

    <div class="summary">
        <span class="summary-item summary-success"><strong>Total Setor:</strong> Rp
            {{ number_format($totalSetor, 0, ',', '.') }}</span>
        <span class="summary-item summary-danger"><strong>Total Tarik:</strong> Rp
            {{ number_format($totalTarik, 0, ',', '.') }}</span>
        <span class="summary-item summary-info"><strong>Saldo:</strong> Rp
            {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Kode</th>
                <th>Anggota</th>
                <th>Jenis</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->kode_transaksi }}</td>
                    <td><strong>{{ $t->anggota->nama_lengkap }}</strong><br><small>{{ $t->anggota->no_anggota }}</small>
                    </td>
                    <td>{{ $t->jenisSimpanan->nama_simpanan }}</td>
                    <td>
                        @php $tipe = $t->tipe_transaksi instanceof \App\Enum\TipeTransaksi ? $t->tipe_transaksi : \App\Enum\TipeTransaksi::from($t->tipe_transaksi); @endphp
                        <span class="badge badge-{{ $tipe->value }}">{{ $tipe->label() }}</span>
                    </td>
                    <td class="text-right">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_transaksi)->format('d/m/Y') }}</td>
                    <td>
                        @php $status = $t->status instanceof \App\Enum\StatusPengajuan ? $t->status : \App\Enum\StatusPengajuan::from($t->status); @endphp
                        <span class="badge badge-{{ $status->value }}">{{ $status->label() }}</span>
                    </td>
                    <td>{{ $t->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-pdf.layout>