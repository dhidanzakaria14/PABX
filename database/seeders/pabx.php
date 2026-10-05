<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class pabx extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily during seed
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // 1. Profil Perusahaan
        DB::table('tbm_profil')->updateOrInsert(['id' => 1], [
            'pemilik' => 'PT Angkasa Pura Indonesia',
            'namaperusahaan' => 'ANGKASA PURA',
            'alamat' => 'Bandara Internasional Juanda & Soekarno-Hatta',
            'telp' => '021-5505070',
            'hp' => '08113866479',
            'email' => 'contact@angkasapura.co.id',
            'url' => 'https://angkasapura.co.id',
            'tgldaftar' => '2024-01-01',
            'totalhari' => 365,
            'expire' => '2027-12-31',
            'ppn' => 11,
            'service' => 0,
        ]);

        // 2. Setting PABX / Mesin Server
        DB::table('tbm_setting')->updateOrInsert(['id' => 1], [
            'ip' => '192.168.1.100',
            'port' => 5000,
            'keterangan' => 'Panasonic KX-TDE600 Server Utama',
            'st' => '1',
            'tgltambah' => now(),
            'optambah' => 'admin',
        ]);
        DB::table('tbm_setting')->updateOrInsert(['id' => 2], [
            'ip' => '192.168.1.201',
            'port' => 5060,
            'keterangan' => 'Asterisk IP-PBX Gateway Cabang',
            'st' => '1',
            'tgltambah' => now(),
            'optambah' => 'admin',
        ]);

        // 3. User Roles / Level
        DB::table('tbm_level')->updateOrInsert(['id' => 1], ['idcabang' => 1, 'level' => 'Administrator System', 'valid' => 'Ya']);
        DB::table('tbm_level')->updateOrInsert(['id' => 2], ['idcabang' => 1, 'level' => 'Supervisor Billing', 'valid' => 'Ya']);
        DB::table('tbm_level')->updateOrInsert(['id' => 3], ['idcabang' => 1, 'level' => 'Operator PABX', 'valid' => 'Ya']);
        DB::table('tbm_level')->updateOrInsert(['id' => 4], ['idcabang' => 1, 'level' => 'Finance & Accounting', 'valid' => 'Ya']);

        // 4. Users (tbl_user)
        $users = [
            [
                'iduser' => 1,
                'idcabang' => '1',
                'idlevel' => 1,
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'nama' => 'Super Administrator',
                'telp' => '08123456789',
                'leveluser' => 'Administrator System',
                'blokir' => 'N',
                'aktif' => 1,
            ],
            [
                'iduser' => 2,
                'idcabang' => '1',
                'idlevel' => 2,
                'username' => 'supervisor',
                'password' => Hash::make('admin123'),
                'nama' => 'Supervisor Billing',
                'telp' => '08129876543',
                'leveluser' => 'Supervisor Billing',
                'blokir' => 'N',
                'aktif' => 1,
            ],
            [
                'iduser' => 3,
                'idcabang' => '1',
                'idlevel' => 4,
                'username' => 'finance',
                'password' => Hash::make('admin123'),
                'nama' => 'Finance Dept',
                'telp' => '08131122334',
                'leveluser' => 'Finance & Accounting',
                'blokir' => 'N',
                'aktif' => 1,
            ],
        ];
        foreach ($users as $u) {
            DB::table('tbl_user')->updateOrInsert(['username' => $u['username']], $u);
        }

        // 5. Group Department / Divisi
        $groups = [
            ['idgroup' => 1, 'groupcode' => 'DIR', 'namagroup' => 'Direksi', 'namadivisi' => 'Executive Management', 'st' => '1'],
            ['idgroup' => 2, 'groupcode' => 'FIN', 'namagroup' => 'Finance & Accounting', 'namadivisi' => 'Keuangan & Akuntansi', 'st' => '1'],
            ['idgroup' => 3, 'groupcode' => 'IT', 'namagroup' => 'Information Technology', 'namadivisi' => 'Teknologi Informasi & Jaringan', 'st' => '1'],
            ['idgroup' => 4, 'groupcode' => 'HRD', 'namagroup' => 'Human Resources & General Affairs', 'namadivisi' => 'SDM & Umum', 'st' => '1'],
            ['idgroup' => 5, 'groupcode' => 'MKT', 'namagroup' => 'Marketing & Sales', 'namadivisi' => 'Pemasaran & Penjualan', 'st' => '1'],
            ['idgroup' => 6, 'groupcode' => 'OPS', 'namagroup' => 'Operations & Logistics', 'namadivisi' => 'Operasional & Logistik', 'st' => '1'],
            ['idgroup' => 7, 'groupcode' => 'AP', 'namagroup' => 'AP', 'namadivisi' => 'Angkasa Pura Division', 'st' => '1'],
        ];
        foreach ($groups as $g) {
            DB::table('tbm_group_department')->updateOrInsert(['idgroup' => $g['idgroup']], $g);
        }

        // 6. Department / Extension
        $depts = [
            ['id' => 1,  'idgroup' => 1, 'extcode' => '100', 'extname' => 'DIREKTUR UTAMA',        'phonepassword' => '1000', 'divisi' => 'Direksi',    'jabatan' => 'Direktur Utama',    'nik' => 'DIR-100', 'budget' => 10000000, 'st' => '1'],
            ['id' => 2,  'idgroup' => 1, 'extcode' => '101', 'extname' => 'DIREKTUR KEUANGAN',     'phonepassword' => '1011', 'divisi' => 'Direksi',    'jabatan' => 'Direktur Keuangan', 'nik' => 'DIR-101', 'budget' => 8000000,  'st' => '1'],
            ['id' => 3,  'idgroup' => 2, 'extcode' => '200', 'extname' => 'KEPALA KEUANGAN',       'phonepassword' => '2000', 'divisi' => 'Finance',    'jabatan' => 'Kepala Bagian',     'nik' => 'FIN-200', 'budget' => 5000000,  'st' => '1'],
            ['id' => 4,  'idgroup' => 2, 'extcode' => '201', 'extname' => 'STAFF AKUNTANSI',       'phonepassword' => '2011', 'divisi' => 'Finance',    'jabatan' => 'Staff',             'nik' => 'FIN-201', 'budget' => 3000000,  'st' => '1'],
            ['id' => 5,  'idgroup' => 3, 'extcode' => '300', 'extname' => 'KEPALA IT',             'phonepassword' => '3000', 'divisi' => 'IT',         'jabatan' => 'Kepala IT',         'nik' => 'IT-300',  'budget' => 5000000,  'st' => '1'],
            ['id' => 6,  'idgroup' => 3, 'extcode' => '301', 'extname' => 'NETWORK ENGINEER',      'phonepassword' => '3011', 'divisi' => 'IT',         'jabatan' => 'Engineer',          'nik' => 'IT-301',  'budget' => 3000000,  'st' => '1'],
            ['id' => 7,  'idgroup' => 4, 'extcode' => '400', 'extname' => 'KEPALA OPERASIONAL',    'phonepassword' => '4000', 'divisi' => 'Operasional','jabatan' => 'Kepala Ops',        'nik' => 'OPS-400', 'budget' => 5000000,  'st' => '1'],
            ['id' => 8,  'idgroup' => 4, 'extcode' => '401', 'extname' => 'STAFF OPERASIONAL',     'phonepassword' => '4011', 'divisi' => 'Operasional','jabatan' => 'Staff',             'nik' => 'OPS-401', 'budget' => 3000000,  'st' => '1'],
            ['id' => 9,  'idgroup' => 5, 'extcode' => '500', 'extname' => 'KEPALA HRD',            'phonepassword' => '5000', 'divisi' => 'HRD',        'jabatan' => 'Kepala HRD',        'nik' => 'HRD-500', 'budget' => 5000000,  'st' => '1'],
            ['id' => 10, 'idgroup' => 6, 'extcode' => '600', 'extname' => 'KEPALA MARKETING',      'phonepassword' => '6000', 'divisi' => 'Marketing',  'jabatan' => 'Kepala Marketing',  'nik' => 'MKT-600', 'budget' => 5000000,  'st' => '1'],
            ['id' => 11, 'idgroup' => 7, 'extcode' => '569', 'extname' => 'ACCOUNT RECEIVABLE',    'phonepassword' => '5699', 'divisi' => 'AP',         'jabatan' => 'Finance AR Specialist', 'nik' => 'AP-569', 'budget' => 5000000, 'st' => '1'],
        ];
        foreach ($depts as $d) {
            DB::table('tbm_department')->updateOrInsert(['id' => $d['id']], $d);
        }

        // 7. Zona
        $zonas = [
            ['idzone' => 1,  'namazone' => 'Lokal (Jabodetabek)',              'kelompok' => 'LOCAL', 'st' => '1'],
            ['idzone' => 2,  'namazone' => 'Interlokal (SLJJ Zona 1: Jawa)',      'kelompok' => 'NDD',   'st' => '1'],
            ['idzone' => 3,  'namazone' => 'Interlokal (SLJJ Zona 2: Luar Jawa)', 'kelompok' => 'NDD',   'st' => '1'],
            ['idzone' => 4,  'namazone' => 'Seluler GSM Nasional',              'kelompok' => 'CELL',  'st' => '1'],
            ['idzone' => 5,  'namazone' => 'Internasional (SLI Zone Asia)',       'kelompok' => 'IDD',   'st' => '1'],
            ['idzone' => 6,  'namazone' => 'Internasional (SLI Rest of World)',    'kelompok' => 'IDD',   'st' => '1'],
        ];
        foreach ($zonas as $z) {
            DB::table('tbm_zona')->updateOrInsert(['idzone' => $z['idzone']], $z);
        }

        // 8. Prefixes
        $prefixes = [
            ['idprefix' => 1,  'idzone' => 1, 'kodeprefix' => '021',  'distrik' => '021',  'keterangan' => 'Jakarta, Bogor, Depok, Tangerang, Bekasi', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 2,  'idzone' => 2, 'kodeprefix' => '022',  'distrik' => '022',  'keterangan' => 'Bandung dan Cimahi', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 3,  'idzone' => 2, 'kodeprefix' => '024',  'distrik' => '024',  'keterangan' => 'Semarang', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 4,  'idzone' => 2, 'kodeprefix' => '031',  'distrik' => '031',  'keterangan' => 'Surabaya', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 5,  'idzone' => 3, 'kodeprefix' => '061',  'distrik' => '061',  'keterangan' => 'Medan, Sumatera Utara', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 6,  'idzone' => 3, 'kodeprefix' => '0411', 'distrik' => '0411', 'keterangan' => 'Makassar, Sulawesi Selatan', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 7,  'idzone' => 4, 'kodeprefix' => '0811', 'distrik' => 'CELL', 'keterangan' => 'Telkomsel Halo', 'kelompok' => 'CELL', 'st' => '1'],
            ['idprefix' => 8,  'idzone' => 4, 'kodeprefix' => '0812', 'distrik' => 'CELL', 'keterangan' => 'Telkomsel Simpati', 'kelompok' => 'CELL', 'st' => '1'],
            ['idprefix' => 9,  'idzone' => 4, 'kodeprefix' => '0813', 'distrik' => 'CELL', 'keterangan' => 'Telkomsel Simpati', 'kelompok' => 'CELL', 'st' => '1'],
            ['idprefix' => 10, 'idzone' => 4, 'kodeprefix' => '0815', 'distrik' => 'CELL', 'keterangan' => 'Indosat IM3 / Matrix', 'kelompok' => 'CELL', 'st' => '1'],
            ['idprefix' => 11, 'idzone' => 4, 'kodeprefix' => '0817', 'distrik' => 'CELL', 'keterangan' => 'XL Axiata', 'kelompok' => 'CELL', 'st' => '1'],
            ['idprefix' => 12, 'idzone' => 4, 'kodeprefix' => '0896', 'distrik' => 'CELL', 'keterangan' => 'Tri (3)', 'kelompok' => 'CELL', 'st' => '1'],
            ['idprefix' => 13, 'idzone' => 5, 'kodeprefix' => '001',  'distrik' => 'INTL', 'keterangan' => 'Indosat IDD Singapore / Malaysia', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 14, 'idzone' => 5, 'kodeprefix' => '007',  'distrik' => 'INTL', 'keterangan' => 'Telkom IDD Asia Pacific', 'kelompok' => 'PHONE', 'st' => '1'],
            ['idprefix' => 15, 'idzone' => 6, 'kodeprefix' => '008',  'distrik' => 'INTL', 'keterangan' => 'IDD USA / Europe', 'kelompok' => 'PHONE', 'st' => '1'],
        ];
        foreach ($prefixes as $p) {
            DB::table('tbm_prefix')->updateOrInsert(['idprefix' => $p['idprefix']], $p);
        }

        // 9. Hari
        $haris = [
            ['id' => 1, 'namahari' => 'Senin', 'kode' => 1],
            ['id' => 2, 'namahari' => 'Selasa', 'kode' => 2],
            ['id' => 3, 'namahari' => 'Rabu', 'kode' => 3],
            ['id' => 4, 'namahari' => 'Kamis', 'kode' => 4],
            ['id' => 5, 'namahari' => 'Jumat', 'kode' => 5],
            ['id' => 6, 'namahari' => 'Sabtu', 'kode' => 6],
            ['id' => 7, 'namahari' => 'Minggu', 'kode' => 7],
        ];
        foreach ($haris as $h) {
            DB::table('tbm_hari')->updateOrInsert(['id' => $h['id']], $h);
        }

        // 10. Master Tarif
        $tarifs = [
            ['idtarif' => 1, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'TARIF-LOKAL', 'catatan' => 'Tarif Panggilan Telepon Lokal per Menit', 'harikhusus' => 'Tidak', 'namahari' => 'Semua Hari', 'mindurasi' => 60, 'st' => '1'],
            ['idtarif' => 2, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'TARIF-SLJJ-JAWA', 'catatan' => 'Tarif Panggilan SLJJ Antar Kota Pulau Jawa', 'harikhusus' => 'Tidak', 'namahari' => 'Semua Hari', 'mindurasi' => 60, 'st' => '1'],
            ['idtarif' => 3, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'TARIF-SLJJ-LUAR', 'catatan' => 'Tarif Panggilan SLJJ Luar Pulau Jawa', 'harikhusus' => 'Tidak', 'namahari' => 'Semua Hari', 'mindurasi' => 60, 'st' => '1'],
            ['idtarif' => 4, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'TARIF-SELULER', 'catatan' => 'Tarif Panggilan ke Semua Operator Seluler HP', 'harikhusus' => 'Tidak', 'namahari' => 'Semua Hari', 'mindurasi' => 30, 'st' => '1'],
            ['idtarif' => 5, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'TARIF-INTERNASIONAL', 'catatan' => 'Tarif Panggilan Luar Negeri IDD', 'harikhusus' => 'Tidak', 'namahari' => 'Semua Hari', 'mindurasi' => 60, 'st' => '1'],
        ];
        foreach ($tarifs as $t) {
            DB::table('tbm_tarif')->updateOrInsert(['idtarif' => $t['idtarif']], $t);
        }

        // 11. Tariff Rates (tbd_rate)
        $rates = [
            ['id' => 1, 'idrate' => 1, 'mulai' => '08:00:00', 'selesai' => '17:00:00', 'tarifnormal' => '250', 'tarifkhusus' => '200'],
            ['id' => 2, 'idrate' => 1, 'mulai' => '17:00:01', 'selesai' => '07:59:59', 'tarifnormal' => '180', 'tarifkhusus' => '150'],
            ['id' => 3, 'idrate' => 2, 'mulai' => '08:00:00', 'selesai' => '17:00:00', 'tarifnormal' => '750', 'tarifkhusus' => '600'],
            ['id' => 4, 'idrate' => 2, 'mulai' => '17:00:01', 'selesai' => '07:59:59', 'tarifnormal' => '550', 'tarifkhusus' => '450'],
            ['id' => 5, 'idrate' => 3, 'mulai' => '08:00:00', 'selesai' => '17:00:00', 'tarifnormal' => '1250', 'tarifkhusus' => '1000'],
            ['id' => 6, 'idrate' => 3, 'mulai' => '17:00:01', 'selesai' => '07:59:59', 'tarifnormal' => '950', 'tarifkhusus' => '800'],
            ['id' => 7, 'idrate' => 4, 'mulai' => '00:00:00', 'selesai' => '23:59:59', 'tarifnormal' => '900', 'tarifkhusus' => '800'],
            ['id' => 8, 'idrate' => 5, 'mulai' => '00:00:00', 'selesai' => '23:59:59', 'tarifnormal' => '4500', 'tarifkhusus' => '4000'],
        ];
        foreach ($rates as $rt) {
            DB::table('tbd_rate')->updateOrInsert(['id' => $rt['id']], $rt);
        }

        // 12. Master Area (tbm_area)
        $areas = [
            ['id' => 1, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idprefix' => 1, 'namaprefix' => '021', 'idzone' => 1, 'idrate' => 1, 'kodearea' => '021', 'zona' => 'Lokal (Jabodetabek)', 'keterangan' => 'Area Ibukota dan sekitarnya', 'koderate' => 'TARIF-LOKAL', 'namarate' => 'Tarif Panggilan Telepon Lokal', 'tipe' => 'Lokal', 'st' => '1'],
            ['id' => 2, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idprefix' => 2, 'namaprefix' => '022', 'idzone' => 2, 'idrate' => 2, 'kodearea' => '022', 'zona' => 'Interlokal (SLJJ Zona 1: Jawa)', 'keterangan' => 'Bandung Kota & Kab', 'koderate' => 'TARIF-SLJJ-JAWA', 'namarate' => 'Tarif SLJJ Jawa Barat', 'tipe' => 'SLJJ', 'st' => '1'],
            ['id' => 3, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idprefix' => 4, 'namaprefix' => '031', 'idzone' => 2, 'idrate' => 2, 'kodearea' => '031', 'zona' => 'Interlokal (SLJJ Zona 1: Jawa)', 'keterangan' => 'Surabaya Raya', 'koderate' => 'TARIF-SLJJ-JAWA', 'namarate' => 'Tarif SLJJ Jawa Timur', 'tipe' => 'SLJJ', 'st' => '1'],
            ['id' => 4, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idprefix' => 5, 'namaprefix' => '061', 'idzone' => 3, 'idrate' => 3, 'kodearea' => '061', 'zona' => 'Interlokal (SLJJ Zona 2: Luar Jawa)', 'keterangan' => 'Medan & Deli', 'koderate' => 'TARIF-SLJJ-LUAR', 'namarate' => 'Tarif SLJJ Sumatera', 'tipe' => 'SLJJ', 'st' => '1'],
            ['id' => 5, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idprefix' => 8, 'namaprefix' => '0812', 'idzone' => 4, 'idrate' => 4, 'kodearea' => '0812', 'zona' => 'Seluler GSM Nasional', 'keterangan' => 'Telkomsel Nasional', 'koderate' => 'TARIF-SELULER', 'namarate' => 'Tarif Seluler Operator GSM', 'tipe' => 'Seluler', 'st' => '1'],
            ['id' => 6, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idprefix' => 10, 'namaprefix' => '0815', 'idzone' => 4, 'idrate' => 4, 'kodearea' => '0815', 'zona' => 'Seluler GSM Nasional', 'keterangan' => 'Indosat Ooredoo', 'koderate' => 'TARIF-SELULER', 'namarate' => 'Tarif Seluler Operator GSM', 'tipe' => 'Seluler', 'st' => '1'],
        ];
        foreach ($areas as $a) {
            DB::table('tbm_area')->updateOrInsert(['id' => $a['id']], $a);
        }

        // 13. Master Bisnis
        DB::table('tbm_bisnis')->updateOrInsert(['id' => 1], [
            'nama' => 'Head Office Jakarta Pusat',
            'notelp' => '021-52901234',
            'alamat' => 'Sudirman Business District',
            'st' => '1',
        ]);
        DB::table('tbm_bisnis')->updateOrInsert(['id' => 2], [
            'nama' => 'Branch Office Surabaya',
            'notelp' => '031-7788990',
            'alamat' => 'Jl. Darmo No. 45 Surabaya',
            'st' => '1',
        ]);

        // 14. CDR Data Masuk (14 records lengkap)
        $records = [
            [
                'id' => 1, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0001',
                'ext_pemanggil' => '100', 'iddepartment' => 1, 'namadepartment' => 'DIREKTUR UTAMA',
                'noline' => 'CO-01', 'tglmasuk' => '2026-10-05', 'jammasuk' => '08:15:22',
                'durasi' => '00:03:30', 'durasi_detik' => 210, 'kodeakses' => '9',
                'no_tujuan' => '081288991122', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'idprefix' => 8, 'kodearea' => '0812', 'ketarea' => 'Telkomsel Nasional',
                'idrate' => 4, 'namarate' => 'Tarif Seluler Operator GSM', 'formularate' => '4 x 900',
                'biaya' => 3600, 'tambahan' => 396, 'subtotal' => 3996, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '4',
            ],
            [
                'id' => 2, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0002',
                'ext_pemanggil' => '200', 'iddepartment' => 3, 'namadepartment' => 'KEPALA KEUANGAN',
                'noline' => 'CO-02', 'tglmasuk' => '2026-10-05', 'jammasuk' => '08:42:10',
                'durasi' => '00:06:15', 'durasi_detik' => 375, 'kodeakses' => '9',
                'no_tujuan' => '02157901234', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'Lokal (Jabodetabek)',
                'idprefix' => 1, 'kodearea' => '021', 'ketarea' => 'Area Ibukota dan sekitarnya',
                'idrate' => 1, 'namarate' => 'Tarif Panggilan Telepon Lokal', 'formularate' => '7 x 250',
                'biaya' => 1750, 'tambahan' => 192.5, 'subtotal' => 1942.5, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '7',
            ],
            [
                'id' => 3, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0003',
                'ext_pemanggil' => '300', 'iddepartment' => 5, 'namadepartment' => 'KEPALA IT',
                'noline' => 'CO-01', 'tglmasuk' => '2026-10-05', 'jammasuk' => '09:05:44',
                'durasi' => '00:08:50', 'durasi_detik' => 530, 'kodeakses' => '9',
                'no_tujuan' => '081512345678', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'idprefix' => 10, 'kodearea' => '0815', 'ketarea' => 'Indosat Ooredoo',
                'idrate' => 4, 'namarate' => 'Tarif Seluler Operator GSM', 'formularate' => '9 x 900',
                'biaya' => 8100, 'tambahan' => 891, 'subtotal' => 8991, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '9',
            ],
            [
                'id' => 4, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0004',
                'ext_pemanggil' => '301', 'iddepartment' => 6, 'namadepartment' => 'NETWORK ENGINEER',
                'noline' => 'CO-03', 'tglmasuk' => '2026-10-05', 'jammasuk' => '09:18:02',
                'durasi' => '00:02:10', 'durasi_detik' => 130, 'kodeakses' => '9',
                'no_tujuan' => '03188997766', 'no_trunk' => 'TRK-03', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 2, 'zona' => 'Interlokal (SLJJ Zona 1: Jawa)',
                'idprefix' => 4, 'kodearea' => '031', 'ketarea' => 'Surabaya Raya',
                'idrate' => 2, 'namarate' => 'Tarif SLJJ Jawa Timur', 'formularate' => '3 x 750',
                'biaya' => 2250, 'tambahan' => 247.5, 'subtotal' => 2497.5, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '3',
            ],
            [
                'id' => 5, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0005',
                'ext_pemanggil' => '201', 'iddepartment' => 4, 'namadepartment' => 'STAFF AKUNTANSI',
                'noline' => 'CO-02', 'tglmasuk' => '2026-10-05', 'jammasuk' => '09:30:15',
                'durasi' => '00:04:45', 'durasi_detik' => 285, 'kodeakses' => '9',
                'no_tujuan' => '0224201122', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 2, 'zona' => 'Interlokal (SLJJ Zona 1: Jawa)',
                'idprefix' => 2, 'kodearea' => '022', 'ketarea' => 'Bandung Kota & Kab',
                'idrate' => 2, 'namarate' => 'Tarif SLJJ Jawa Barat', 'formularate' => '5 x 750',
                'biaya' => 3750, 'tambahan' => 412.5, 'subtotal' => 4162.5, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '5',
            ],
            [
                'id' => 6, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0006',
                'ext_pemanggil' => '101', 'iddepartment' => 2, 'namadepartment' => 'DIREKTUR KEUANGAN',
                'noline' => 'CO-01', 'tglmasuk' => '2026-10-05', 'jammasuk' => '09:45:00',
                'durasi' => '00:01:20', 'durasi_detik' => 80, 'kodeakses' => '9',
                'no_tujuan' => '081399887766', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'idprefix' => 9, 'kodearea' => '0813', 'ketarea' => 'Telkomsel Simpati',
                'idrate' => 4, 'namarate' => 'Tarif Seluler Operator GSM', 'formularate' => '2 x 900',
                'biaya' => 1800, 'tambahan' => 198, 'subtotal' => 1998, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '2',
            ],
            [
                'id' => 7, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0007',
                'ext_pemanggil' => '400', 'iddepartment' => 7, 'namadepartment' => 'KEPALA OPERASIONAL',
                'noline' => 'CO-04', 'tglmasuk' => '2026-10-05', 'jammasuk' => '10:02:18',
                'durasi' => '00:05:40', 'durasi_detik' => 340, 'kodeakses' => '9',
                'no_tujuan' => '08176543210', 'no_trunk' => 'TRK-04', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'idprefix' => 11, 'kodearea' => '0817', 'ketarea' => 'XL Axiata',
                'idrate' => 4, 'namarate' => 'Tarif Seluler Operator GSM', 'formularate' => '6 x 900',
                'biaya' => 5400, 'tambahan' => 594, 'subtotal' => 5994, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '6',
            ],
            [
                'id' => 8, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0008',
                'ext_pemanggil' => '500', 'iddepartment' => 9, 'namadepartment' => 'KEPALA HRD',
                'noline' => 'CO-02', 'tglmasuk' => '2026-10-05', 'jammasuk' => '10:15:33',
                'durasi' => '00:12:00', 'durasi_detik' => 720, 'kodeakses' => '9',
                'no_tujuan' => '0614567890', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 3, 'zona' => 'Interlokal (SLJJ Zona 2: Luar Jawa)',
                'idprefix' => 5, 'kodearea' => '061', 'ketarea' => 'Medan & Deli',
                'idrate' => 3, 'namarate' => 'Tarif SLJJ Sumatera', 'formularate' => '12 x 1250',
                'biaya' => 15000, 'tambahan' => 1650, 'subtotal' => 16650, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '12',
            ],
            [
                'id' => 9, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0009',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-01', 'tglmasuk' => '2026-10-05', 'jammasuk' => '08:14:00',
                'durasi' => '00:03:00', 'durasi_detik' => 180, 'kodeakses' => '9',
                'no_tujuan' => '1500805', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'Lokal (Jabodetabek)',
                'idprefix' => 1, 'kodearea' => '021', 'ketarea' => 'Customer Service Line',
                'idrate' => 1, 'namarate' => 'Tarif Panggilan Telepon Lokal', 'formularate' => '3 x 500',
                'biaya' => 1500, 'tambahan' => 165, 'subtotal' => 1665, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '3',
            ],
            [
                'id' => 10, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0010',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-02', 'tglmasuk' => '2026-10-05', 'jammasuk' => '09:22:15',
                'durasi' => '00:04:00', 'durasi_detik' => 240, 'kodeakses' => '9',
                'no_tujuan' => '08113866479', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'idprefix' => 7, 'kodearea' => '0811', 'ketarea' => 'Denpasar, (TEL)',
                'idrate' => 4, 'namarate' => 'Tarif Seluler Operator GSM', 'formularate' => '8 x 900',
                'biaya' => 7200, 'tambahan' => 792, 'subtotal' => 7992, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '8',
            ],
            [
                'id' => 11, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0011',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-01', 'tglmasuk' => '2026-10-05', 'jammasuk' => '10:05:00',
                'durasi' => '00:01:00', 'durasi_detik' => 60, 'kodeakses' => '0',
                'no_tujuan' => '7722', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'Internal Extension',
                'idprefix' => 1, 'kodearea' => 'INT', 'ketarea' => 'Internal Call',
                'idrate' => 1, 'namarate' => 'TARIF-INTERNAL', 'formularate' => '1 x 0',
                'biaya' => 0, 'tambahan' => 0, 'subtotal' => 0, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '1',
            ],
            [
                'id' => 12, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0012',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-03', 'tglmasuk' => '2026-10-05', 'jammasuk' => '11:30:10',
                'durasi' => '00:05:20', 'durasi_detik' => 320, 'kodeakses' => '9',
                'no_tujuan' => '8050895', 'no_trunk' => 'TRK-03', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'Lokal (Jabodetabek)',
                'idprefix' => 1, 'kodearea' => '021', 'ketarea' => 'Jakarta, (TEL)',
                'idrate' => 1, 'namarate' => 'Tarif Panggilan Telepon Lokal', 'formularate' => '6 x 250',
                'biaya' => 1500, 'tambahan' => 165, 'subtotal' => 1665, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '6',
            ],
            [
                'id' => 13, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0013',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-02', 'tglmasuk' => '2026-10-05', 'jammasuk' => '14:15:45',
                'durasi' => '00:07:00', 'durasi_detik' => 420, 'kodeakses' => '9',
                'no_tujuan' => '087762957298', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'idprefix' => 11, 'kodearea' => '0817', 'ketarea' => 'Jakarta, (XL)',
                'idrate' => 4, 'namarate' => 'Tarif Seluler Operator GSM', 'formularate' => '14 x 900',
                'biaya' => 12600, 'tambahan' => 1386, 'subtotal' => 13986, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '14',
            ],
            [
                'id' => 14, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0014',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-04', 'tglmasuk' => '2026-10-05', 'jammasuk' => '15:40:20',
                'durasi' => '00:03:10', 'durasi_detik' => 190, 'kodeakses' => '9',
                'no_tujuan' => '081330559406', 'no_trunk' => 'TRK-04', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'idprefix' => 9, 'kodearea' => '0813', 'ketarea' => 'Surabaya, (SAT)',
                'idrate' => 4, 'namarate' => 'Tarif Seluler Operator GSM', 'formularate' => '7 x 900',
                'biaya' => 6300, 'tambahan' => 693, 'subtotal' => 6993, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '7',
            ],
        ];

        foreach ($records as $r) {
            DB::table('tbm_data_masuk')->updateOrInsert(['id' => $r['id']], $r);
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }
}
