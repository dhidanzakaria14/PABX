<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class pabx extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Matikan sementara foreign key checks untuk SQLite / MySQL saat seeding
        if (DB::getDriverName() === 'sqlite') {
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

        // 3. User Roles / Level
        DB::table('tbm_level')->updateOrInsert(['id' => 1], ['idcabang' => 1, 'level' => 'Administrator', 'valid' => 'Ya']);
        DB::table('tbm_level')->updateOrInsert(['id' => 2], ['idcabang' => 1, 'level' => 'Operator', 'valid' => 'Ya']);
        DB::table('tbm_level')->updateOrInsert(['id' => 3], ['idcabang' => 1, 'level' => 'User', 'valid' => 'Ya']);

        // 4. Group Department / Divisi
        $groups = [
            ['idgroup' => 1, 'groupcode' => 'DIR', 'namagroup' => 'Direksi', 'namadivisi' => 'Direksi', 'st' => '1'],
            ['idgroup' => 2, 'groupcode' => 'FIN', 'namagroup' => 'Finance', 'namadivisi' => 'Keuangan & Akuntansi', 'st' => '1'],
            ['idgroup' => 3, 'groupcode' => 'IT', 'namagroup' => 'IT', 'namadivisi' => 'Teknologi Informasi & Jaringan', 'st' => '1'],
            ['idgroup' => 4, 'groupcode' => 'OPS', 'namagroup' => 'Operasional', 'namadivisi' => 'Operasional Bandara', 'st' => '1'],
            ['idgroup' => 5, 'groupcode' => 'HRD', 'namagroup' => 'HRD', 'namadivisi' => 'Human Resources & General Affairs', 'st' => '1'],
            ['idgroup' => 6, 'groupcode' => 'MKT', 'namagroup' => 'Marketing', 'namadivisi' => 'Komersial & Pemasaran', 'st' => '1'],
            ['idgroup' => 7, 'groupcode' => 'AP', 'namagroup' => 'AP', 'namadivisi' => 'Angkasa Pura Division', 'st' => '1'],
        ];
        foreach ($groups as $g) {
            DB::table('tbm_group_department')->updateOrInsert(['idgroup' => $g['idgroup']], $g);
        }

        // 5. Department / Extension
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

        // 6. Zona
        $zonas = [
            ['idzone' => 1,  'namazone' => 'Lokal 1 (L1)',              'kelompok' => 'LOCAL', 'st' => '1'],
            ['idzone' => 2,  'namazone' => 'Domestic Zone 1 (N1)',      'kelompok' => 'NDD',   'st' => '1'],
            ['idzone' => 3,  'namazone' => 'Domestic Zone 2 (N2)',      'kelompok' => 'NDD',   'st' => '1'],
            ['idzone' => 4,  'namazone' => 'Domestic Zone 3 (N3)',      'kelompok' => 'NDD',   'st' => '1'],
            ['idzone' => 5,  'namazone' => 'Cellular Local (C0)',       'kelompok' => 'CELL',  'st' => '1'],
            ['idzone' => 6,  'namazone' => 'Cellular Zone 1 (C1)',      'kelompok' => 'CELL',  'st' => '1'],
            ['idzone' => 7,  'namazone' => 'Cellular Zone 2 (C2)',      'kelompok' => 'CELL',  'st' => '1'],
            ['idzone' => 8,  'namazone' => 'Cellular Zone 3 (C3)',      'kelompok' => 'CELL',  'st' => '1'],
            ['idzone' => 9,  'namazone' => 'International Zone 2 (I2)', 'kelompok' => 'IDD',   'st' => '1'],
            ['idzone' => 10, 'namazone' => 'Free of Charge / Internal (X)', 'kelompok' => 'LOCAL', 'st' => '1'],
        ];
        foreach ($zonas as $z) {
            DB::table('tbm_zona')->updateOrInsert(['idzone' => $z['idzone']], $z);
        }

        // 7. Master Tarif
        $tarifs = [
            ['idtarif' => 1, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'L1', 'namahari' => 'Senin-Jumat', 'mindurasi' => 1, 'st' => '1'],
            ['idtarif' => 2, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'N1', 'namahari' => 'Senin-Jumat', 'mindurasi' => 1, 'st' => '1'],
            ['idtarif' => 3, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'N2', 'namahari' => 'Senin-Jumat', 'mindurasi' => 1, 'st' => '1'],
            ['idtarif' => 4, 'idmesin' => 1, 'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'ratecode' => 'C2', 'namahari' => 'Senin-Jumat', 'mindurasi' => 1, 'st' => '1'],
        ];
        foreach ($tarifs as $t) {
            DB::table('tbm_tarif')->updateOrInsert(['idtarif' => $t['idtarif']], $t);
        }

        // 8. CDR Data Masuk (tbm_data_masuk)
        $records = [
            [
                'id' => 1, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0001',
                'ext_pemanggil' => '100', 'iddepartment' => 1, 'namadepartment' => 'DIREKTUR UTAMA',
                'noline' => 'CO-01', 'tglmasuk' => '2026-09-01', 'jammasuk' => '08:00:00',
                'durasi' => '00:05:00', 'durasi_detik' => 300, 'kodeakses' => '9',
                'no_tujuan' => '02150505050', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'L1',
                'kodearea' => '021', 'ketarea' => 'Jakarta Lokal',
                'idrate' => 1, 'namarate' => 'TARIF-LOKAL', 'formularate' => '5 x 500',
                'biaya' => 2500, 'tambahan' => 275, 'subtotal' => 2775, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-01 08:05:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '5',
            ],
            [
                'id' => 2, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0002',
                'ext_pemanggil' => '200', 'iddepartment' => 3, 'namadepartment' => 'KEPALA KEUANGAN',
                'noline' => 'CO-02', 'tglmasuk' => '2026-09-01', 'jammasuk' => '09:15:00',
                'durasi' => '00:03:30', 'durasi_detik' => 210, 'kodeakses' => '9',
                'no_tujuan' => '08123456789', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 7, 'zona' => 'C2',
                'kodearea' => '0812', 'ketarea' => 'Telkomsel',
                'idrate' => 4, 'namarate' => 'TARIF-SELULER', 'formularate' => '7 x 900',
                'biaya' => 6300, 'tambahan' => 693, 'subtotal' => 6993, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-01 09:18:30', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '7',
            ],
            [
                'id' => 3, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0003',
                'ext_pemanggil' => '300', 'iddepartment' => 5, 'namadepartment' => 'KEPALA IT',
                'noline' => 'CO-03', 'tglmasuk' => '2026-09-02', 'jammasuk' => '10:00:00',
                'durasi' => '00:10:00', 'durasi_detik' => 600, 'kodeakses' => '9',
                'no_tujuan' => '02290001111', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 2, 'zona' => 'N1',
                'kodearea' => '022', 'ketarea' => 'Bandung',
                'idrate' => 2, 'namarate' => 'TARIF-NDD', 'formularate' => '10 x 600',
                'biaya' => 6000, 'tambahan' => 660, 'subtotal' => 6660, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-02 10:10:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '10',
            ],
            [
                'id' => 4, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0004',
                'ext_pemanggil' => '400', 'iddepartment' => 7, 'namadepartment' => 'KEPALA OPERASIONAL',
                'noline' => 'CO-04', 'tglmasuk' => '2026-09-02', 'jammasuk' => '13:30:00',
                'durasi' => '00:07:00', 'durasi_detik' => 420, 'kodeakses' => '9',
                'no_tujuan' => '08567890123', 'no_trunk' => 'TRK-03', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 7, 'zona' => 'C2',
                'kodearea' => '0856', 'ketarea' => 'Indosat Ooredoo',
                'idrate' => 4, 'namarate' => 'TARIF-SELULER', 'formularate' => '14 x 900',
                'biaya' => 12600, 'tambahan' => 1386, 'subtotal' => 13986, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-02 13:37:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '14',
            ],
            [
                'id' => 5, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0005',
                'ext_pemanggil' => '500', 'iddepartment' => 9, 'namadepartment' => 'KEPALA HRD',
                'noline' => 'CO-05', 'tglmasuk' => '2026-09-03', 'jammasuk' => '08:45:00',
                'durasi' => '00:02:00', 'durasi_detik' => 120, 'kodeakses' => '9',
                'no_tujuan' => '02140002222', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'L1',
                'kodearea' => '021', 'ketarea' => 'Jakarta Lokal',
                'idrate' => 1, 'namarate' => 'TARIF-LOKAL', 'formularate' => '2 x 500',
                'biaya' => 1000, 'tambahan' => 110, 'subtotal' => 1110, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-03 08:47:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '2',
            ],
            [
                'id' => 6, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0006',
                'ext_pemanggil' => '101', 'iddepartment' => 2, 'namadepartment' => 'DIREKTUR KEUANGAN',
                'noline' => 'CO-01', 'tglmasuk' => '2026-09-03', 'jammasuk' => '11:20:00',
                'durasi' => '00:15:00', 'durasi_detik' => 900, 'kodeakses' => '9',
                'no_tujuan' => '02430003333', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 3, 'zona' => 'N2',
                'kodearea' => '024', 'ketarea' => 'Semarang',
                'idrate' => 3, 'namarate' => 'TARIF-NDD-N2', 'formularate' => '15 x 700',
                'biaya' => 10500, 'tambahan' => 1155, 'subtotal' => 11655, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-03 11:35:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '15',
            ],
            [
                'id' => 7, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0007',
                'ext_pemanggil' => '600', 'iddepartment' => 10, 'namadepartment' => 'KEPALA MARKETING',
                'noline' => 'CO-06', 'tglmasuk' => '2026-09-04', 'jammasuk' => '14:00:00',
                'durasi' => '00:06:00', 'durasi_detik' => 360, 'kodeakses' => '9',
                'no_tujuan' => '08990004444', 'no_trunk' => 'TRK-04', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 7, 'zona' => 'C2',
                'kodearea' => '0899', 'ketarea' => 'Three/Tri',
                'idrate' => 4, 'namarate' => 'TARIF-SELULER', 'formularate' => '12 x 900',
                'biaya' => 10800, 'tambahan' => 1188, 'subtotal' => 11988, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-04 14:06:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '12',
            ],
            [
                'id' => 8, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0008',
                'ext_pemanggil' => '301', 'iddepartment' => 6, 'namadepartment' => 'NETWORK ENGINEER',
                'noline' => 'CO-03', 'tglmasuk' => '2026-09-04', 'jammasuk' => '16:30:00',
                'durasi' => '00:04:00', 'durasi_detik' => 240, 'kodeakses' => '9',
                'no_tujuan' => '02770005555', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 2, 'zona' => 'N1',
                'kodearea' => '027', 'ketarea' => 'Yogyakarta',
                'idrate' => 2, 'namarate' => 'TARIF-NDD', 'formularate' => '4 x 600',
                'biaya' => 2400, 'tambahan' => 264, 'subtotal' => 2664, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-09-04 16:34:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '4',
            ],
            // Data Angkasa Pura AR
            [
                'id' => 9, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0009',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-01', 'tglmasuk' => '2026-10-05', 'jammasuk' => '08:14:00',
                'durasi' => '00:03:00', 'durasi_detik' => 180, 'kodeakses' => '9',
                'no_tujuan' => '1500805', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'Lokal (Jabodetabek)',
                'kodearea' => '021', 'ketarea' => 'Customer Service Line',
                'idrate' => 1, 'namarate' => 'TARIF-LOKAL', 'formularate' => '3 x 500',
                'biaya' => 1500, 'tambahan' => 165, 'subtotal' => 1665, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05 08:17:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '3',
            ],
            [
                'id' => 10, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0010',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-02', 'tglmasuk' => '2026-10-05', 'jammasuk' => '09:22:15',
                'durasi' => '00:04:00', 'durasi_detik' => 240, 'kodeakses' => '9',
                'no_tujuan' => '08113866479', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'kodearea' => '0811', 'ketarea' => 'Denpasar, (TEL)',
                'idrate' => 4, 'namarate' => 'TARIF-SELULER', 'formularate' => '8 x 900',
                'biaya' => 7200, 'tambahan' => 792, 'subtotal' => 7992, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05 09:26:15', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '8',
            ],
            [
                'id' => 11, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0011',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-01', 'tglmasuk' => '2026-10-05', 'jammasuk' => '10:05:00',
                'durasi' => '00:01:00', 'durasi_detik' => 60, 'kodeakses' => '0',
                'no_tujuan' => '7722', 'no_trunk' => 'TRK-01', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'Internal Extension',
                'kodearea' => 'INT', 'ketarea' => 'Internal Call',
                'idrate' => 1, 'namarate' => 'TARIF-INTERNAL', 'formularate' => '1 x 0',
                'biaya' => 0, 'tambahan' => 0, 'subtotal' => 0, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05 10:06:00', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '1',
            ],
            [
                'id' => 12, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0012',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-03', 'tglmasuk' => '2026-10-05', 'jammasuk' => '11:30:10',
                'durasi' => '00:05:20', 'durasi_detik' => 320, 'kodeakses' => '9',
                'no_tujuan' => '8050895', 'no_trunk' => 'TRK-03', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 1, 'zona' => 'Lokal (Jabodetabek)',
                'kodearea' => '021', 'ketarea' => 'Jakarta, (TEL)',
                'idrate' => 1, 'namarate' => 'TARIF-LOKAL', 'formularate' => '6 x 250',
                'biaya' => 1500, 'tambahan' => 165, 'subtotal' => 1665, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05 11:35:30', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '6',
            ],
            [
                'id' => 13, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0013',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-02', 'tglmasuk' => '2026-10-05', 'jammasuk' => '14:15:45',
                'durasi' => '00:07:00', 'durasi_detik' => 420, 'kodeakses' => '9',
                'no_tujuan' => '087762957298', 'no_trunk' => 'TRK-02', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'kodearea' => '0817', 'ketarea' => 'Jakarta, (XL)',
                'idrate' => 4, 'namarate' => 'TARIF-SELULER', 'formularate' => '14 x 900',
                'biaya' => 12600, 'tambahan' => 1386, 'subtotal' => 13986, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05 14:22:45', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '14',
            ],
            [
                'id' => 14, 'kodeclient' => 'CLI-001', 'kodetelp' => '01', 'nourut' => 'CDR-0014',
                'ext_pemanggil' => '569', 'iddepartment' => 11, 'namadepartment' => 'ACCOUNT RECEIVABLE',
                'noline' => 'CO-04', 'tglmasuk' => '2026-10-05', 'jammasuk' => '15:40:20',
                'durasi' => '00:03:10', 'durasi_detik' => 190, 'kodeakses' => '9',
                'no_tujuan' => '081330559406', 'no_trunk' => 'TRK-04', 'idmesin' => 1,
                'namamesin' => 'Panasonic KX-TDE600 Server Utama', 'idzone' => 4, 'zona' => 'Seluler GSM Nasional',
                'kodearea' => '0813', 'ketarea' => 'Surabaya, (SAT)',
                'idrate' => 4, 'namarate' => 'TARIF-SELULER', 'formularate' => '7 x 900',
                'biaya' => 6300, 'tambahan' => 693, 'subtotal' => 6993, 'hitung' => '1',
                'st' => '1', 'tglinsert' => '2026-10-05 15:43:30', 'idperusahaan' => 1,
                'namaperusahaan' => 'ANGKASA PURA', 'pulsa' => '7',
            ],
        ];

        foreach ($records as $r) {
            DB::table('tbm_data_masuk')->updateOrInsert(['id' => $r['id']], $r);
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }
}
