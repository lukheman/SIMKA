<x-pdf.layout :title="'LAPORAN DATA ANGGOTA'" :tanggal="$tanggal">
    <div class="info">
        <p>Filter Status: <strong>{{ $filterStatus ? ucfirst($filterStatus) : 'Semua' }}</strong></p>
        <p>Total Data: <strong>{{ $anggotas->count() }} anggota</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>No Anggota</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>Pekerjaan</th>
                <th>No Telp</th>
                <th>Tgl Bergabung</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anggotas as $index => $a)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $a->no_anggota }}</td>
                    <td><strong>{{ $a->nama_lengkap }}</strong></td>
                    <td>{{ $a->nik }}</td>
                    <td>{{ $a->alamat }}</td>
                    <td>{{ $a->pekerjaan }}</td>
                    <td>{{ $a->no_telp }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->tgl_bergabung)->format('d/m/Y') }}</td>
                    <td>
                        @php $status = $a->status_aktif instanceof \App\Enum\StatusAktif ? $a->status_aktif : \App\Enum\StatusAktif::from($a->status_aktif); @endphp
                        <span class="badge badge-{{ $status->value }}">{{ $status->label() }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-pdf.layout>