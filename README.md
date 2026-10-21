# Pinjam Sehat

Sistem informasi pinjaman berbasis kelompok — mencatat anggota, pinjaman, angsuran, dan laporan. Dibuat dengan **Laravel 12** + **SQLite**, tampilan biru cerah, tanpa framework CSS tambahan.

Live demo: https://pinjamsehat.laura.web.id

## Akun demo

| Peran   | Username  | Password     | Bisa apa                                                                 |
| ------- | --------- | ------------ | ------------------------------------------------------------------------ |
| Admin   | `admin`   | `admin123`   | Semua: kelola anggota, buat pinjaman, terima angsuran, lihat laporan      |
| Petugas | `petugas` | `petugas123` | Operasional: lihat anggota, buat pinjaman, terima angsuran — tanpa laporan |

Pembatasan peran dijaga di dua tempat: middleware `admin` pada route, dan tampilan
(tombol/menu disembunyikan). Petugas yang memaksa buka `/laporan` atau `/anggota/tambah`
tetap kena **403**.

## Fitur

- **Kalkulator angsuran (publik)** — masukkan nominal dan bunga, bandingkan angsuran untuk tenor 3 / 6 / 12 / 18 / 24 bulan sekaligus, lengkap dengan rincian pokok, bunga, total bayar, dan tabel amortisasi.
- **Anggota** — data anggota kelompok (NIK, HP, alamat, pekerjaan, penghasilan), pencarian, dan paginasi.
- **Pinjaman** — pengajuan pinjaman dengan validasi nominal, tenor, dan bunga; filter status.
- **Angsuran** — jadwal cicilan otomatis per bulan, pencatatan pembayaran, dan progres pelunasan.
- **Laporan** — ringkasan portofolio dan rekap per anggota.
- **Login** — sesi berbasis guard `web` dengan middleware `cek`.

## Perhitungan

Bunga **flat per bulan** (bukan anuitas):

```
bunga_total  = pokok × bunga% × tenor
total_bayar  = pokok + bunga_total
angsuran     = total_bayar / tenor
```

Contoh: pinjam Rp5.000.000, bunga 1,5%/bulan, tenor 12 bulan → bunga Rp900.000, angsuran Rp491.667/bulan.

## Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Struktur singkat

```
app/Http/Controllers/   Auth, Beranda, Anggota, Pinjaman, Simulasi, Laporan
app/Support/Kalkulator  hitung() & bandingkan() bunga flat
app/Models/             Anggota, Pinjaman, Angsuran, User
resources/views/        layout, beranda, simulasi, login, anggota, pinjaman, laporan
public/css/             tokens.css (warna & tipografi) + style.css
```

## Lisensi

MIT
