<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\User;
use App\Support\Kalkulator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Admin Pinjam Sehat', 'username' => 'admin', 'password' => Hash::make('admin123'), 'peran' => 'admin']);
        User::create(['name' => 'Petugas Pinjam Sehat', 'username' => 'petugas', 'password' => Hash::make('petugas123'), 'peran' => 'petugas']);

        $orang = [
            ['Bagas Ardiansyah', '3312010101900001', '081234567811', 'Solo', 'Karyawan Swasta', 4500000],
            ['Citra Maharani', '3312010202920002', '081234567812', 'Solo', 'Pedagang', 3500000],
            ['Dimas Prasetyo', '3312020303930003', '081234567813', 'Pacitan', 'Wiraswasta', 6000000],
            ['Eka Wulandari', '3312030404940004', '081234567814', 'Wonogiri', 'Guru', 3800000],
            ['Fajar Nugroho', '3312040505950005', '081234567815', 'Wonogiri', 'Buruh', 3000000],
        ];

        foreach ($orang as $o) {
            Anggota::create([
                'nama' => $o[0], 'nik' => $o[1], 'no_hp' => $o[2],
                'alamat' => $o[3], 'pekerjaan' => $o[4], 'penghasilan' => $o[5],
            ]);
        }

        $pinjaman = [
            [1, 6000000, 12, 'Modal usaha', 'Aktif', 3],
            [2, 3000000, 6, 'Renovasi warung', 'Aktif', 1],
            [3, 12000000, 24, 'Beli motor', 'Aktif', 0],
            [4, 2500000, 3, 'Biaya sekolah', 'Lunas', 3],
            [5, 4000000, 12, 'Kebutuhan keluarga', 'Aktif', 2],
        ];

        foreach ($pinjaman as $i => $p) {
            [$anggotaId, $pokok, $tenor, $keperluan, $status, $sudahBayar] = $p;

            $mulai = Carbon::now()->subMonths($sudahBayar + 1)->startOfMonth();
            $hitung = Kalkulator::hitung($pokok, $tenor, 1.5);

            $row = Pinjaman::create([
                'anggota_id' => $anggotaId,
                'pokok' => $pokok,
                'tenor' => $tenor,
                'bunga_persen' => 1.5,
                'keperluan' => $keperluan,
                'status' => $status,
                'tanggal' => $mulai->toDateString(),
            ]);

            $sisa = $hitung['total_bayar'];
            for ($k = 1; $k <= $tenor; $k++) {
                $jumlah = ($k === $tenor) ? $sisa : $hitung['per_bulan'];
                $sisa -= $jumlah;
                $lunas = $k <= $sudahBayar;

                $row->angsuran()->create([
                    'angsuran_ke' => $k,
                    'jatuh_tempo' => $mulai->copy()->addMonths($k)->toDateString(),
                    'tanggal_bayar' => $lunas ? $mulai->copy()->addMonths($k)->toDateString() : null,
                    'jumlah' => $jumlah,
                    'status' => $lunas ? 'Lunas' : 'Belum bayar',
                ]);
            }
        }
    }
}
