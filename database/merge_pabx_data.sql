-- =====================================================================
-- PABX DATABASE MERGE SCRIPT
-- Merging raw data from pabx_source into the relational pabx schema
-- =====================================================================

USE pabx;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Company Profile
TRUNCATE TABLE tbm_profil;
INSERT INTO tbm_profil (id, pemilik, namaperusahaan, alamat, telp, hp, fax, email, url, logo, tgldaftar, totalhari, expire, ppn, service, tinggi, lebar)
SELECT id, pemilik, namaperusahaan, alamat, telp, hp, fax, email, url, logo, tgldaftar, totalhari, expire, ppn, service, tinggi, lebar
FROM pabx_source.tbm_profil;

-- 2. Setting PABX
TRUNCATE TABLE tbm_setting;
INSERT INTO tbm_setting (id, ip, port, keterangan, st, tgltambah, optambah)
SELECT id, ip, port, keterangan, st, tgltambah, optambah
FROM pabx_source.tbm_setting;

-- 3. User Roles / Level
TRUNCATE TABLE tbm_level;
INSERT INTO tbm_level (id, idcabang, level, valid)
SELECT id, idcabang, level, valid
FROM pabx_source.tbm_level;

-- 4. Users
TRUNCATE TABLE tbl_user;
INSERT INTO tbl_user (iduser, idcabang, idlevel, username, password, nama, telp, photo, leveluser, blokir, id_session, login, aktif, hit, st_del, idmanajemen)
SELECT 
    u.iduser, 
    u.idcabang, 
    COALESCE(l.id, 1) AS idlevel, 
    u.username, 
    u.password, 
    u.nama, 
    u.telp, 
    u.photo, 
    u.leveluser, 
    u.blokir, 
    u.id_session, 
    u.login, 
    u.aktif, 
    u.hit, 
    u.st_del, 
    NULL AS idmanajemen
FROM pabx_source.tbl_user u
LEFT JOIN pabx.tbm_level l ON l.level = u.leveluser;

-- 5. User Modules & Permissions
TRUNCATE TABLE tbd_modul_user;
INSERT INTO tbd_modul_user (iddetail, idmodul, kelompok, kategorimenu, namadetail, namadetailin, link, namafile, aktif, parent, department)
SELECT iddetail, idmodul, kelompok, kategorimenu, namadetail, namadetailin, link, namafile, aktif, parent, department
FROM pabx_source.tbd_modul_user;

TRUNCATE TABLE tbd_akses_user;
INSERT INTO tbd_akses_user (id, idlevel, iddetailmodul, tampil, ubah, hapus, tambah, aktif)
SELECT a.id, a.idlevel, a.iddetailmodul, a.tampil, a.ubah, a.hapus, a.tambah, a.aktif
FROM pabx_source.tbd_akses_user a
INNER JOIN pabx.tbd_modul_user m ON m.iddetail = a.iddetailmodul;

-- 6. Group Department (de-duplicate by groupcode, keep first per groupcode)
TRUNCATE TABLE tbm_group_department;
INSERT INTO tbm_group_department (idgroup, groupcode, namagroup, namadivisi, tgltambah, optambah, st, tgldelete, opdelete)
SELECT g.idgroup, g.groupcode, g.namagroup, g.namadivisi, g.tgltambah, g.optambah, g.st, g.tgldelete, g.opdelete
FROM pabx_source.tbm_group_department g
INNER JOIN (
    SELECT MIN(idgroup) AS min_id FROM pabx_source.tbm_group_department GROUP BY groupcode
) dedup ON g.idgroup = dedup.min_id;

-- Make sure group AP (id 16) exists and is active
INSERT INTO tbm_group_department (idgroup, groupcode, namagroup, namadivisi, st)
VALUES (16, 'AP_SURABAYA', 'AP', 'AP', '1')
ON DUPLICATE KEY UPDATE namagroup = 'AP', st = '1';

-- 7. Department / Extensions (de-duplicate extcode, keep row with smallest id)
TRUNCATE TABLE tbm_department;
INSERT INTO tbm_department (id, idgroup, extcode, extname, phonepassword, divisi, jabatan, nik, additional, budget, tgltambah, optambah, tgledit, opedit, st, tgldelete, opdelete)
SELECT 
    d.id, 
    COALESCE(g.idgroup, 16) AS idgroup,
    d.extcode, 
    d.extname, 
    d.phonepassword, 
    d.divisi, 
    d.jabatan, 
    d.nik, 
    d.additional, 
    d.budget, 
    d.tgltambah, 
    d.optambah, 
    d.tgledit, 
    d.opedit, 
    d.st, 
    d.tgldelete, 
    d.opdelete
FROM pabx_source.tbm_department d
INNER JOIN (
    -- Keep only the row with smallest id per extcode to avoid UNIQUE constraint violation
    SELECT MIN(id) AS min_id FROM pabx_source.tbm_department GROUP BY extcode
) dedup ON d.id = dedup.min_id
LEFT JOIN pabx.tbm_group_department g ON g.namagroup = d.divisi;

-- 8. Master Tariffs & Rates
TRUNCATE TABLE tbm_tarif;
INSERT INTO tbm_tarif (idtarif, idmesin, namamesin, ratecode, catatan, harikhusus, namahari, mindurasi, tgltambah, optambah, tgledit, opedit, st)
SELECT idtarif, 1, namamesin, ratecode, catatan, harikhusus, namahari, mindurasi, tgltambah, optambah, tgledit, opedit, st
FROM pabx_source.tbm_tarif;

TRUNCATE TABLE tbd_rate;
INSERT INTO tbd_rate (id, idrate, mulai, selesai, tarifnormal, tarifkhusus)
SELECT r.id, r.idrate, r.mulai, r.selesai, r.tarifnormal, r.tarifkhusus
FROM pabx_source.tbd_rate r
INNER JOIN pabx.tbm_tarif t ON t.idtarif = r.idrate;

-- 9. Zones
TRUNCATE TABLE tbm_zona;
INSERT INTO tbm_zona (idzone, namazone, kelompok, st) VALUES
(1, 'Lokal 1 (L1)', 'LOCAL', '1'),
(2, 'Domestic Zone 1 (N1)', 'NDD', '1'),
(3, 'Domestic Zone 2 (N2)', 'NDD', '1'),
(4, 'Domestic Zone 3 (N3)', 'NDD', '1'),
(5, 'Cellular Local (C0)', 'CELL', '1'),
(6, 'Cellular Zone 1 (C1)', 'CELL', '1'),
(7, 'Cellular Zone 2 (C2)', 'CELL', '1'),
(8, 'Cellular Zone 3 (C3)', 'CELL', '1'),
(9, 'International Zone 2 (I2)', 'IDD', '1'),
(10, 'Free of Charge / Internal (X)', 'LOCAL', '1');

-- 10. Dial Prefixes
TRUNCATE TABLE tbm_prefix;
INSERT INTO tbm_prefix (idprefix, idzone, kodeprefix, distrik, keterangan, kelompok, st, tgltambah, optambah, tglubah, opubah, tgldelete, opdelete)
SELECT 
    p.idprefix,
    CASE 
        WHEN p.distrik = 'IDD' THEN 9
        WHEN p.distrik = 'NDD' THEN 2
        WHEN p.distrik = 'CELL' THEN 7
        WHEN p.distrik = 'LDD' THEN 1
        ELSE 1
    END AS idzone,
    p.kodeprefix,
    p.distrik,
    p.keterangan,
    p.kelompok,
    p.st,
    p.tgltambah,
    p.optambah,
    p.tglubah,
    p.opubah,
    p.tgldelete,
    p.opdelete
FROM pabx_source.tbm_prefix p;

-- 11. Area Mapping
TRUNCATE TABLE tbm_area;
INSERT INTO tbm_area (id, idmesin, namamesin, idprefix, namaprefix, idzone, idrate, kodearea, zona, keterangan, koderate, namarate, tipe, tgltambah, optambah, tglubah, opubah, tgldelete, opdelete, st)
SELECT 
    a.id,
    1 AS idmesin,
    a.namamesin,
    COALESCE(p.idprefix, 10) AS idprefix,
    a.namaprefix,
    CASE 
        WHEN a.zona = 'L1' THEN 1
        WHEN a.zona = 'N1' THEN 2
        WHEN a.zona = 'N2' THEN 3
        WHEN a.zona = 'N3' THEN 4
        WHEN a.zona = 'C0' THEN 5
        WHEN a.zona = 'C1' THEN 6
        WHEN a.zona = 'C2' THEN 7
        WHEN a.zona = 'C3' THEN 8
        WHEN a.zona = 'I2' THEN 9
        WHEN a.zona = 'X'  THEN 10
        ELSE 1
    END AS idzone,
    COALESCE(t.idtarif, 10) AS idrate,
    a.kodearea,
    a.zona,
    a.keterangan,
    a.koderate,
    a.namarate,
    a.tipe,
    a.tgltambah,
    a.optambah,
    a.tglubah,
    a.opubah,
    a.tgldelete,
    a.opdelete,
    a.st
FROM pabx_source.tbm_area a
LEFT JOIN pabx.tbm_prefix p ON p.kodeprefix = a.kodearea
LEFT JOIN pabx.tbm_tarif t ON t.ratecode = a.zona;

-- 12. Business Units
TRUNCATE TABLE tbm_bisnis;
INSERT INTO tbm_bisnis (id, nama, notelp, alamat, st, tgltambah, optambah, tglubah, opubah, tgldelete, opdelete)
SELECT id, nama, notelp, alamat, st, tgltambah, optambah, tglubah, opubah, tgldelete, opdelete
FROM pabx_source.tbm_bisnis;

-- 13. Login Logs
TRUNCATE TABLE log_login;
INSERT INTO log_login (idlog, iduser, ip, tgl)
SELECT l.idlog, COALESCE(u.iduser, 1), l.ip, l.tgl
FROM pabx_source.log_login l
LEFT JOIN pabx.tbl_user u ON u.iduser = l.iduser;

-- 14. Central CDR: tbm_data_masuk (ALL ~50,000 Call Records)
TRUNCATE TABLE tbm_data_masuk;

INSERT IGNORE INTO tbm_data_masuk (
    id,
    kodeclient,
    kodetelp,
    nourut,
    ext_pemanggil,
    iddepartment,
    namadepartment,
    noline,
    tglmasuk,
    jammasuk,
    durasi,
    durasi_detik,
    kodeakses,
    no_tujuan,
    gabung,
    no_trunk,
    idmesin,
    namamesin,
    idzone,
    zona,
    idprefix,
    kodearea,
    ketarea,
    idrate,
    namarate,
    formularate,
    biaya,
    tambahan,
    subtotal,
    hitung,
    st,
    tglinsert,
    idd,
    iddname,
    cell,
    idperusahaan,
    namaperusahaan,
    pulsa
)
SELECT 
    dm.id,
    dm.kodeclient,
    dm.kodetelp,
    dm.nourut,
    dm.ext_pemanggil,
    -- Prioritise matching by iddepartment (PK), then fall back to extcode
    COALESCE(
        (SELECT d1.id FROM pabx.tbm_department d1 WHERE d1.id = dm.iddepartment LIMIT 1),
        (SELECT d2.id FROM pabx.tbm_department d2 WHERE d2.extcode = dm.ext_pemanggil LIMIT 1)
    ) AS iddepartment,
    dm.namadepartment,
    dm.noline,
    DATE_FORMAT(STR_TO_DATE(dm.tglmasuk, '%d.%m.%y'), '%Y-%m-%d') AS tglmasuk,
    dm.jammasuk,
    dm.durasi,
    COALESCE(TIME_TO_SEC(dm.durasi), 0) AS durasi_detik,
    dm.kodeakses,
    dm.no_tujuan,
    dm.gabung,
    dm.no_trunk,
    1 AS idmesin,
    dm.namamesin,
    CASE 
        WHEN dm.zona = 'L1' THEN 1
        WHEN dm.zona = 'N1' THEN 2
        WHEN dm.zona = 'N2' THEN 3
        WHEN dm.zona = 'N3' THEN 4
        WHEN dm.zona = 'C0' THEN 5
        WHEN dm.zona = 'C1' THEN 6
        WHEN dm.zona = 'C2' THEN 7
        WHEN dm.zona = 'C3' THEN 8
        WHEN dm.zona = 'I2' THEN 9
        WHEN dm.zona = 'X'  THEN 10
        ELSE NULL
    END AS idzone,
    dm.zona,
    NULL AS idprefix,
    dm.kodearea,
    dm.ketarea,
    -- Match tariff by ratecode = zona code (the original system's zone=ratecode pattern)
    COALESCE(
        (SELECT t1.idtarif FROM pabx.tbm_tarif t1 WHERE t1.idtarif = dm.idrate LIMIT 1),
        (SELECT t2.idtarif FROM pabx.tbm_tarif t2 WHERE t2.ratecode = dm.zona LIMIT 1)
    ) AS idrate,
    dm.namarate,
    dm.formularate,
    COALESCE(dm.biaya, 0)    AS biaya,
    COALESCE(dm.tambahan, 0) AS tambahan,
    COALESCE(dm.subtotal, 0) AS subtotal,
    dm.hitung,
    dm.st,
    dm.tglinsert,
    dm.idd,
    dm.iddname,
    dm.cell,
    1 AS idperusahaan,
    dm.namaperusahaan,
    dm.pulsa
FROM pabx_source.tbm_data_masuk dm;

SET FOREIGN_KEY_CHECKS = 1;

