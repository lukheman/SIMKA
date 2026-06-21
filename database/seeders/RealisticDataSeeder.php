<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\JenisPinjaman;
use App\Models\TransaksiSimpanan;
use App\Models\PengajuanPinjaman;
use App\Models\Angsuran;
use Carbon\Carbon;
use Faker\Factory as Faker;

class RealisticDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Tambahkan 25 anggota dengan data yang realistis (orang Indonesia)
        $anggotas = [];
        $lastAnggota = Anggota::latest('id')->first();
        $startId = $lastAnggota ? $lastAnggota->id + 1 : 1;
        
        for ($i = 0; $i < 25; $i++) {
            $anggotas[] = Anggota::create([
                'no_anggota' => 'AGT-' . str_pad($startId + $i, 5, '0', STR_PAD_LEFT),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password123'),
                'nik' => $faker->unique()->numerify('1671##############'),
                'nama_lengkap' => $faker->name(),
                'alamat' => $faker->address(),
                'pekerjaan' => $faker->jobTitle(),
                'no_telp' => $faker->phoneNumber(),
                'tgl_bergabung' => $faker->dateTimeBetween('-18 months', '-1 months')->format('Y-m-d'),
                'status_aktif' => 'aktif',
            ]);
        }

        $jenisSimpananPokok = JenisSimpanan::where('nama_simpanan', 'Simpanan Pokok')->first();
        $jenisSimpananWajib = JenisSimpanan::where('nama_simpanan', 'Simpanan Wajib')->first();
        $jenisSimpananSukarela = JenisSimpanan::where('nama_simpanan', 'Simpanan Sukarela')->first();

        // Generate data simpanan yang masuk akal berdasarkan tanggal bergabung
        foreach ($anggotas as $anggota) {
            $tglBergabung = Carbon::parse($anggota->tgl_bergabung);
            
            // 1. Simpanan Pokok (Pasti dibayar sekali saat pertama kali bergabung)
            if ($jenisSimpananPokok) {
                TransaksiSimpanan::create([
                    'anggota_id' => $anggota->id,
                    'jenis_simpanan_id' => $jenisSimpananPokok->id,
                    'kode_transaksi' => 'TRX-S-' . str_pad(TransaksiSimpanan::count() + 1, 6, '0', STR_PAD_LEFT),
                    'tipe_transaksi' => 'setor',
                    'jumlah' => $jenisSimpananPokok->minimal_setor,
                    'tgl_transaksi' => $tglBergabung->toDateString(),
                    'keterangan' => 'Setoran pokok anggota baru otomatis',
                    'status' => 'disetujui',
                ]);
            }

            // 2. Simpanan Wajib (Dibayar setiap bulan setelah bergabung)
            if ($jenisSimpananWajib) {
                $monthsDiff = $tglBergabung->diffInMonths(now());
                for ($m = 1; $m <= $monthsDiff; $m++) {
                    $tglSetor = $tglBergabung->copy()->addMonths($m)->addDays($faker->numberBetween(0, 10)); // bayar dengan selisih bbrp hari
                    TransaksiSimpanan::create([
                        'anggota_id' => $anggota->id,
                        'jenis_simpanan_id' => $jenisSimpananWajib->id,
                        'kode_transaksi' => 'TRX-S-' . str_pad(TransaksiSimpanan::count() + 1, 6, '0', STR_PAD_LEFT),
                        'tipe_transaksi' => 'setor',
                        'jumlah' => $jenisSimpananWajib->minimal_setor,
                        'tgl_transaksi' => $tglSetor->toDateString(),
                        'keterangan' => 'Setoran wajib bulan ' . $tglSetor->format('M Y'),
                        'status' => 'disetujui',
                    ]);
                }
            }

            // 3. Simpanan Sukarela (Acak, tidak semua anggota)
            if ($jenisSimpananSukarela && $faker->boolean(60)) { 
                $jmlSukarela = $faker->randomElement([50000, 100000, 200000, 300000, 500000]);
                $tglSetor = $tglBergabung->copy()->addDays($faker->numberBetween(15, 100));
                TransaksiSimpanan::create([
                    'anggota_id' => $anggota->id,
                    'jenis_simpanan_id' => $jenisSimpananSukarela->id,
                    'kode_transaksi' => 'TRX-S-' . str_pad(TransaksiSimpanan::count() + 1, 6, '0', STR_PAD_LEFT),
                    'tipe_transaksi' => 'setor',
                    'jumlah' => $jmlSukarela,
                    'tgl_transaksi' => $tglSetor->toDateString(),
                    'keterangan' => 'Setoran sukarela tambahan',
                    'status' => 'disetujui',
                ]);
            }
        }

        // Generate Pengajuan Pinjaman & Angsuran
        $jenisPinjamans = JenisPinjaman::all();
        
        if ($jenisPinjamans->isNotEmpty()) {
            // Pilih 15 anggota secara acak yang mengajukan pinjaman
            $borrowers = $faker->randomElements($anggotas, 15);
            foreach ($borrowers as $anggota) {
                $jp = $faker->randomElement($jenisPinjamans);
                $jumlahPengajuan = $faker->randomElement([1500000, 2000000, 5000000, 10000000, 15000000]);
                $tenor = $faker->randomElement([6, 12, 18, 24]);
                if ($tenor > $jp->maks_tenor_bulan) {
                    $tenor = $jp->maks_tenor_bulan;
                }
                
                $bungaTotal = $jumlahPengajuan * ($jp->bunga_persen / 100) * $tenor;
                $tglPengajuan = Carbon::now()->subMonths($faker->numberBetween(1, 12))->subDays($faker->numberBetween(1, 28));
                
                // Distribusi status pengajuan
                $status = $faker->randomElement(['disetujui', 'disetujui', 'disetujui', 'disetujui', 'pending', 'ditolak']);
                
                $pinjaman = PengajuanPinjaman::create([
                    'anggota_id' => $anggota->id,
                    'jenis_pinjaman_id' => $jp->id,
                    'jumlah_pengajuan' => $jumlahPengajuan,
                    'tenor_bulan' => $tenor,
                    'bunga_total' => $bungaTotal,
                    'status' => $status,
                    'tgl_pengajuan' => $tglPengajuan->toDateString(),
                    'jumlah_disetujui' => $status === 'disetujui' ? $jumlahPengajuan : null,
                    'tgl_cair' => $status === 'disetujui' ? $tglPengajuan->copy()->addDays(random_int(1, 3))->toDateString() : null,
                    'alasan_tolak' => $status === 'ditolak' ? 'Analisa kredit kurang memenuhi syarat koperasi.' : null,
                ]);

                // Generate angsuran bulanan yang natural jika disetujui
                if ($status === 'disetujui') {
                    $pokokPerBulan = round($jumlahPengajuan / $tenor, 2);
                    $bungaPerBulan = round($bungaTotal / $tenor, 2);
                    $tglCair = Carbon::parse($pinjaman->tgl_cair);

                    for ($i = 1; $i <= $tenor; $i++) {
                        $tglJatuhTempo = $tglCair->copy()->addMonths($i);
                        $isPast = $tglJatuhTempo->isPast();
                        
                        // Kalau tanggal jatuh tempo sudah lewat, kemungkinan besar lunas. Atau belum lunas (telat).
                        $angsuranStatus = 'belum';
                        $tglBayar = null;
                        
                        if ($isPast) {
                            $angsuranStatus = $faker->boolean(90) ? 'lunas' : 'belum'; // 90% lunas jika sdh lewat
                            if ($angsuranStatus === 'lunas') {
                                // Dibayar beberapa hari sebelum atau sesudah jatuh tempo
                                $tglBayar = $tglJatuhTempo->copy()->addDays(random_int(-5, 2))->toDateString();
                            }
                        }

                        Angsuran::create([
                            'pengajuan_pinjaman_id' => $pinjaman->id,
                            'angsuran_ke' => $i,
                            'tgl_jatuh_tempo' => $tglJatuhTempo->toDateString(),
                            'jumlah_pokok' => $pokokPerBulan,
                            'jumlah_bunga' => $bungaPerBulan,
                            'status_bayar' => $angsuranStatus,
                            'total_bayar' => $angsuranStatus === 'lunas' ? ($pokokPerBulan + $bungaPerBulan) : null,
                            'tgl_bayar' => $tglBayar,
                        ]);
                    }
                }
            }
        }
    }
}
