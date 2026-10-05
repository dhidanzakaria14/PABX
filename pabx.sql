-- =====================================================================
-- DATABASE: pabx
-- PABX Telephone Billing System Schema with Full Relational Constraints
-- Engine: InnoDB with Foreign Keys, Indexes, and Master Sample Data
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `pabx` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pabx`;

SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if exists in reverse dependency order
DROP TABLE IF EXISTS `tbm_data_masuk`;
DROP TABLE IF EXISTS `tbd_tarif_khusus`;
DROP TABLE IF EXISTS `tbm_tarif_khusus`;
DROP TABLE IF EXISTS `tbd_rate`;
DROP TABLE IF EXISTS `tbm_area`;
DROP TABLE IF EXISTS `tbm_tarif`;
DROP TABLE IF EXISTS `tbm_prefix`;
DROP TABLE IF EXISTS `tbm_zona`;
DROP TABLE IF EXISTS `tbm_department`;
DROP TABLE IF EXISTS `tbm_group_department`;
DROP TABLE IF EXISTS `tbd_akses_user`;
DROP TABLE IF EXISTS `tbd_modul_user`;
DROP TABLE IF EXISTS `user_log`;
DROP TABLE IF EXISTS `log_login`;
DROP TABLE IF EXISTS `tbl_user`;
DROP TABLE IF EXISTS `tbm_level`;
DROP TABLE IF EXISTS `tbm_setting`;
DROP TABLE IF EXISTS `tbm_profil`;
DROP TABLE IF EXISTS `tbm_bisnis`;
DROP TABLE IF EXISTS `tbm_hari`;
DROP TABLE IF EXISTS `tbm_gabung`;
DROP TABLE IF EXISTS `pjs_table`;
DROP TABLE IF EXISTS `pjs_logs_table`;
DROP TABLE IF EXISTS `phpjobscheduler`;
DROP TABLE IF EXISTS `phpjobscheduler_logs`;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- 1. Table: tbm_profil (Company / System Profile)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_profil` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pemilik` VARCHAR(200) DEFAULT NULL,
  `namaperusahaan` VARCHAR(200) NOT NULL,
  `alamat` TEXT DEFAULT NULL,
  `telp` VARCHAR(25) DEFAULT NULL,
  `hp` VARCHAR(30) DEFAULT NULL,
  `fax` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(200) DEFAULT NULL,
  `url` VARCHAR(200) DEFAULT NULL,
  `logo` VARCHAR(200) DEFAULT NULL,
  `tgldaftar` DATE DEFAULT NULL,
  `totalhari` SMALLINT(6) DEFAULT 365,
  `expire` DATE DEFAULT NULL,
  `ppn` TINYINT(3) DEFAULT 11,
  `service` TINYINT(3) DEFAULT 0,
  `tinggi` TINYINT(3) DEFAULT 50,
  `lebar` TINYINT(3) DEFAULT 100,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. Table: tbm_setting (PABX PBX Hardware / Connection Setting)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_setting` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(35) NOT NULL,
  `port` MEDIUMINT(8) NOT NULL,
  `keterangan` VARCHAR(200) DEFAULT NULL,
  `st` CHAR(1) DEFAULT '1',
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Table: tbm_level (User Roles / Levels)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_level` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idcabang` INT(11) DEFAULT 1,
  `level` VARCHAR(300) NOT NULL,
  `valid` ENUM('Ya','Tidak') DEFAULT 'Ya',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. Table: tbl_user (Application Users)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_user` (
  `iduser` INT(11) NOT NULL AUTO_INCREMENT,
  `idcabang` VARCHAR(80) DEFAULT '1',
  `idlevel` INT(11) DEFAULT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `telp` VARCHAR(20) DEFAULT NULL,
  `photo` VARCHAR(200) DEFAULT NULL,
  `leveluser` VARCHAR(200) DEFAULT NULL,
  `blokir` ENUM('Y','N') DEFAULT 'N',
  `id_session` VARCHAR(100) DEFAULT NULL,
  `login` DATETIME DEFAULT NULL,
  `aktif` INT(2) DEFAULT 1,
  `hit` INT(11) DEFAULT 0,
  `st_del` INT(2) DEFAULT 0,
  `idmanajemen` INT(11) DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  KEY `fk_user_level` (`idlevel`),
  KEY `fk_user_manajemen` (`idmanajemen`),
  CONSTRAINT `fk_user_level` FOREIGN KEY (`idlevel`) REFERENCES `tbm_level` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_user_manajemen` FOREIGN KEY (`idmanajemen`) REFERENCES `tbl_user` (`iduser`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. Table: user_log (User Activity Audit Log)
-- ---------------------------------------------------------------------
CREATE TABLE `user_log` (
  `idlog` INT(11) NOT NULL AUTO_INCREMENT,
  `idcabang` INT(11) DEFAULT 1,
  `iduser` INT(11) NOT NULL,
  `aksi` ENUM('tambah','edit','delete') NOT NULL,
  `tgl` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idlog`),
  KEY `fk_userlog_user` (`iduser`),
  CONSTRAINT `fk_userlog_user` FOREIGN KEY (`iduser`) REFERENCES `tbl_user` (`iduser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. Table: log_login (User Login History)
-- ---------------------------------------------------------------------
CREATE TABLE `log_login` (
  `idlog` INT(11) NOT NULL AUTO_INCREMENT,
  `iduser` INT(11) NOT NULL,
  `ip` VARCHAR(50) DEFAULT NULL,
  `tgl` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idlog`),
  KEY `fk_loglogin_user` (`iduser`),
  CONSTRAINT `fk_loglogin_user` FOREIGN KEY (`iduser`) REFERENCES `tbl_user` (`iduser`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. Table: tbd_modul_user (Navigation Modules)
-- ---------------------------------------------------------------------
CREATE TABLE `tbd_modul_user` (
  `iddetail` INT(11) NOT NULL AUTO_INCREMENT,
  `idmodul` INT(11) DEFAULT 0,
  `kelompok` VARCHAR(50) DEFAULT NULL,
  `kategorimenu` VARCHAR(50) DEFAULT NULL,
  `namadetail` VARCHAR(100) NOT NULL,
  `namadetailin` VARCHAR(200) DEFAULT NULL,
  `link` VARCHAR(100) DEFAULT NULL,
  `namafile` VARCHAR(200) DEFAULT NULL,
  `aktif` INT(2) DEFAULT 1,
  `parent` INT(11) DEFAULT 0,
  `department` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `idx_modul_parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 8. Table: tbd_akses_user (Module Permissions per Level)
-- ---------------------------------------------------------------------
CREATE TABLE `tbd_akses_user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idlevel` INT(11) NOT NULL,
  `iddetailmodul` INT(11) NOT NULL,
  `tampil` INT(2) DEFAULT 1,
  `ubah` INT(2) DEFAULT 0,
  `hapus` INT(2) DEFAULT 0,
  `tambah` INT(2) DEFAULT 0,
  `aktif` INT(2) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_akses_level` (`idlevel`),
  KEY `fk_akses_modul` (`iddetailmodul`),
  CONSTRAINT `fk_akses_level` FOREIGN KEY (`idlevel`) REFERENCES `tbm_level` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_akses_modul` FOREIGN KEY (`iddetailmodul`) REFERENCES `tbd_modul_user` (`iddetail`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 9. Table: tbm_group_department (Divisions / Groups)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_group_department` (
  `idgroup` INT(11) NOT NULL AUTO_INCREMENT,
  `groupcode` VARCHAR(20) NOT NULL UNIQUE,
  `namagroup` VARCHAR(200) NOT NULL,
  `namadivisi` VARCHAR(200) DEFAULT NULL,
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(50) DEFAULT NULL,
  `st` CHAR(1) DEFAULT '1',
  `tgldelete` DATETIME DEFAULT NULL,
  `opdelete` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 10. Table: tbm_department (Departments / Extensions)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_department` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idgroup` INT(11) DEFAULT NULL,
  `extcode` INT(11) NOT NULL UNIQUE,
  `extname` VARCHAR(100) NOT NULL,
  `phonepassword` VARCHAR(50) DEFAULT NULL,
  `divisi` VARCHAR(100) DEFAULT NULL,
  `jabatan` VARCHAR(100) DEFAULT NULL,
  `nik` VARCHAR(50) DEFAULT NULL,
  `additional` DOUBLE DEFAULT 0,
  `budget` DOUBLE DEFAULT 0,
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(50) DEFAULT NULL,
  `tgledit` DATETIME DEFAULT NULL,
  `opedit` VARCHAR(50) DEFAULT NULL,
  `st` CHAR(1) DEFAULT '1',
  `tgldelete` DATETIME DEFAULT NULL,
  `opdelete` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dept_group` (`idgroup`),
  KEY `idx_dept_extcode` (`extcode`),
  CONSTRAINT `fk_dept_group` FOREIGN KEY (`idgroup`) REFERENCES `tbm_group_department` (`idgroup`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 11. Table: tbm_zona (Destination Zones: IDD, NDD, LOCAL, CELL)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_zona` (
  `idzone` INT(11) NOT NULL AUTO_INCREMENT,
  `namazone` VARCHAR(200) NOT NULL,
  `kelompok` ENUM('IDD','NDD','LOCAL','CELL') DEFAULT 'LOCAL',
  `st` CHAR(1) DEFAULT '1',
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` DATE DEFAULT NULL,
  `tglubah` DATE DEFAULT NULL,
  `opubah` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (`idzone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 12. Table: tbm_prefix (Dial Prefixes)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_prefix` (
  `idprefix` INT(11) NOT NULL AUTO_INCREMENT,
  `idzone` INT(11) DEFAULT NULL,
  `kodeprefix` VARCHAR(20) NOT NULL,
  `distrik` VARCHAR(8) DEFAULT NULL,
  `keterangan` VARCHAR(200) DEFAULT NULL,
  `kelompok` ENUM('CELL','PHONE') DEFAULT 'PHONE',
  `st` CHAR(1) DEFAULT '1',
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(100) DEFAULT NULL,
  `tglubah` DATETIME DEFAULT NULL,
  `opubah` VARCHAR(100) DEFAULT NULL,
  `tgldelete` DATETIME DEFAULT NULL,
  `opdelete` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`idprefix`),
  KEY `fk_prefix_zone` (`idzone`),
  KEY `idx_prefix_code` (`kodeprefix`),
  CONSTRAINT `fk_prefix_zone` FOREIGN KEY (`idzone`) REFERENCES `tbm_zona` (`idzone`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 13. Table: tbm_hari (Days Master)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_hari` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `namahari` VARCHAR(30) NOT NULL,
  `kode` TINYINT(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 14. Table: tbm_tarif (Tariff Schemes / Master Rates)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_tarif` (
  `idtarif` INT(11) NOT NULL AUTO_INCREMENT,
  `idmesin` INT(11) DEFAULT NULL,
  `namamesin` VARCHAR(100) DEFAULT NULL,
  `ratecode` VARCHAR(50) NOT NULL,
  `catatan` TEXT DEFAULT NULL,
  `harikhusus` VARCHAR(80) DEFAULT NULL,
  `namahari` VARCHAR(100) DEFAULT NULL,
  `mindurasi` SMALLINT(6) DEFAULT 0,
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(100) DEFAULT NULL,
  `tgledit` DATETIME DEFAULT NULL,
  `opedit` VARCHAR(50) DEFAULT NULL,
  `st` CHAR(1) DEFAULT '1',
  PRIMARY KEY (`idtarif`),
  KEY `fk_tarif_setting` (`idmesin`),
  CONSTRAINT `fk_tarif_setting` FOREIGN KEY (`idmesin`) REFERENCES `tbm_setting` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 15. Table: tbd_rate (Time Breakdown Rates for Tariffs)
-- ---------------------------------------------------------------------
CREATE TABLE `tbd_rate` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idrate` INT(11) NOT NULL,
  `mulai` TIME NOT NULL,
  `selesai` TIME NOT NULL,
  `tarifnormal` VARCHAR(40) NOT NULL,
  `tarifkhusus` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rate_tarif` (`idrate`),
  CONSTRAINT `fk_rate_tarif` FOREIGN KEY (`idrate`) REFERENCES `tbm_tarif` (`idtarif`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 16. Table: tbm_tarif_khusus (Special Event/Date Tariffs)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_tarif_khusus` (
  `idharga` INT(11) NOT NULL AUTO_INCREMENT,
  `idtarif` INT(11) NOT NULL,
  `namatarif` VARCHAR(150) NOT NULL,
  `keterangan` VARCHAR(200) DEFAULT NULL,
  `tglmulai` DATE NOT NULL,
  `tglselesai` DATE NOT NULL,
  `mindurasi` SMALLINT(6) DEFAULT 0,
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(100) DEFAULT NULL,
  `tglubah` DATETIME DEFAULT NULL,
  `opubah` VARCHAR(100) DEFAULT NULL,
  `tgldelete` DATETIME DEFAULT NULL,
  `opdelete` DATETIME DEFAULT NULL,
  `st` CHAR(1) DEFAULT '1',
  PRIMARY KEY (`idharga`),
  KEY `fk_tarifkhusus_tarif` (`idtarif`),
  CONSTRAINT `fk_tarifkhusus_tarif` FOREIGN KEY (`idtarif`) REFERENCES `tbm_tarif` (`idtarif`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 17. Table: tbd_tarif_khusus (Special Rate Time Breakdown)
-- ---------------------------------------------------------------------
CREATE TABLE `tbd_tarif_khusus` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idtarifkhusus` INT(11) NOT NULL,
  `mulai` TIME NOT NULL,
  `selesai` TIME NOT NULL,
  `tarif` VARCHAR(50) NOT NULL,
  `tgltarif` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detail_tarifkhusus` (`idtarifkhusus`),
  CONSTRAINT `fk_detail_tarifkhusus` FOREIGN KEY (`idtarifkhusus`) REFERENCES `tbm_tarif_khusus` (`idharga`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 18. Table: tbm_area (Area & Destination Dialing Rule Mapping)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_area` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `idmesin` INT(11) DEFAULT NULL,
  `namamesin` VARCHAR(200) DEFAULT NULL,
  `idprefix` INT(11) DEFAULT NULL,
  `namaprefix` VARCHAR(100) DEFAULT NULL,
  `idzone` INT(11) DEFAULT NULL,
  `idrate` INT(11) DEFAULT NULL,
  `kodearea` VARCHAR(12) NOT NULL,
  `zona` VARCHAR(200) DEFAULT NULL,
  `keterangan` VARCHAR(200) DEFAULT NULL,
  `koderate` VARCHAR(65) DEFAULT NULL,
  `namarate` VARCHAR(100) DEFAULT NULL,
  `tipe` VARCHAR(50) DEFAULT NULL,
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(100) DEFAULT NULL,
  `tglubah` DATETIME DEFAULT NULL,
  `opubah` VARCHAR(100) DEFAULT NULL,
  `tgldelete` DATETIME DEFAULT NULL,
  `opdelete` DATETIME DEFAULT NULL,
  `st` CHAR(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_area_mesin` (`idmesin`),
  KEY `fk_area_prefix` (`idprefix`),
  KEY `fk_area_zone` (`idzone`),
  KEY `fk_area_rate` (`idrate`),
  CONSTRAINT `fk_area_mesin` FOREIGN KEY (`idmesin`) REFERENCES `tbm_setting` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_area_prefix` FOREIGN KEY (`idprefix`) REFERENCES `tbm_prefix` (`idprefix`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_area_zone` FOREIGN KEY (`idzone`) REFERENCES `tbm_zona` (`idzone`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_area_rate` FOREIGN KEY (`idrate`) REFERENCES `tbm_tarif` (`idtarif`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 19. Table: tbm_bisnis (Business Entities / Clients)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_bisnis` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(40) NOT NULL,
  `notelp` VARCHAR(40) DEFAULT NULL,
  `alamat` TEXT DEFAULT NULL,
  `st` CHAR(1) DEFAULT '1',
  `tgltambah` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `optambah` VARCHAR(50) DEFAULT NULL,
  `tglubah` DATETIME DEFAULT NULL,
  `opubah` VARCHAR(50) DEFAULT NULL,
  `tgldelete` DATETIME DEFAULT NULL,
  `opdelete` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 20. Table: tbm_gabung (Raw Buffer SMDR / CDR)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_gabung` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `gabung` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 21. Table: pjs_table (PHPJobScheduler Master)
-- ---------------------------------------------------------------------
CREATE TABLE `pjs_table` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `scriptpath` VARCHAR(255) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `time_interval` INT(11) NOT NULL,
  `fire_time` INT(11) NOT NULL,
  `time_last_fired` INT(11) DEFAULT 0,
  `run_only_once` TINYINT(1) DEFAULT 0,
  `currently_running` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 22. Table: pjs_logs_table (PHPJobScheduler Logs)
-- ---------------------------------------------------------------------
CREATE TABLE `pjs_logs_table` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date_added` INT(11) NOT NULL,
  `script` VARCHAR(128) NOT NULL,
  `output` TEXT DEFAULT NULL,
  `execution_time` VARCHAR(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 23. Table: phpjobscheduler (Alternative Job Scheduler Master)
-- ---------------------------------------------------------------------
CREATE TABLE `phpjobscheduler` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `scriptpath` VARCHAR(255) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `time_interval` INT(11) NOT NULL,
  `fire_time` INT(11) NOT NULL,
  `time_last_fired` INT(11) DEFAULT 0,
  `run_only_once` TINYINT(1) DEFAULT 0,
  `currently_running` TINYINT(1) DEFAULT 0,
  `paused` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 24. Table: phpjobscheduler_logs (Alternative Job Scheduler Logs)
-- ---------------------------------------------------------------------
CREATE TABLE `phpjobscheduler_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date_added` INT(11) NOT NULL,
  `script` VARCHAR(128) NOT NULL,
  `output` TEXT DEFAULT NULL,
  `execution_time` VARCHAR(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 25. Table: tbm_data_masuk (Central CDR Call Records & Billing Calculation)
-- ---------------------------------------------------------------------
CREATE TABLE `tbm_data_masuk` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kodeclient` VARCHAR(25) DEFAULT NULL,
  `kodetelp` VARCHAR(5) DEFAULT NULL,
  `nourut` VARCHAR(25) DEFAULT NULL,
  `ext_pemanggil` VARCHAR(30) DEFAULT NULL,
  `iddepartment` INT(11) DEFAULT NULL,
  `namadepartment` VARCHAR(200) DEFAULT NULL,
  `noline` VARCHAR(30) DEFAULT NULL,
  `tglmasuk` VARCHAR(25) DEFAULT NULL,
  `jammasuk` TIME DEFAULT NULL,
  `durasi` TIME DEFAULT NULL,
  `durasi_detik` INT(11) DEFAULT 0,
  `kodeakses` VARCHAR(25) DEFAULT NULL,
  `no_tujuan` VARCHAR(30) DEFAULT NULL,
  `gabung` TEXT DEFAULT NULL,
  `no_trunk` VARCHAR(30) DEFAULT NULL,
  `idmesin` INT(11) DEFAULT NULL,
  `namamesin` VARCHAR(150) DEFAULT NULL,
  `idzone` INT(11) DEFAULT NULL,
  `zona` VARCHAR(100) DEFAULT NULL,
  `idprefix` INT(11) DEFAULT NULL,
  `kodearea` VARCHAR(100) DEFAULT NULL,
  `ketarea` VARCHAR(200) DEFAULT NULL,
  `idrate` INT(11) DEFAULT NULL,
  `namarate` VARCHAR(100) DEFAULT NULL,
  `formularate` VARCHAR(40) DEFAULT NULL,
  `biaya` DOUBLE DEFAULT 0,
  `tambahan` DOUBLE DEFAULT 0,
  `subtotal` DOUBLE DEFAULT 0,
  `hitung` CHAR(1) DEFAULT '1',
  `st` CHAR(1) DEFAULT '1',
  `tglinsert` DATE DEFAULT NULL,
  `idd` VARCHAR(25) DEFAULT NULL,
  `iddname` VARCHAR(100) DEFAULT NULL,
  `cell` VARCHAR(30) DEFAULT NULL,
  `idperusahaan` INT(11) DEFAULT NULL,
  `namaperusahaan` VARCHAR(50) DEFAULT NULL,
  `pulsa` VARCHAR(35) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_data_department` (`iddepartment`),
  KEY `fk_data_mesin` (`idmesin`),
  KEY `fk_data_zone` (`idzone`),
  KEY `fk_data_prefix` (`idprefix`),
  KEY `fk_data_rate` (`idrate`),
  KEY `fk_data_perusahaan` (`idperusahaan`),
  KEY `idx_data_ext` (`ext_pemanggil`),
  KEY `idx_data_tgl` (`tglmasuk`),
  CONSTRAINT `fk_data_department` FOREIGN KEY (`iddepartment`) REFERENCES `tbm_department` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_data_mesin` FOREIGN KEY (`idmesin`) REFERENCES `tbm_setting` (`id`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_data_zone` FOREIGN KEY (`idzone`) REFERENCES `tbm_zona` (`idzone`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_data_prefix` FOREIGN KEY (`idprefix`) REFERENCES `tbm_prefix` (`idprefix`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_data_rate` FOREIGN KEY (`idrate`) REFERENCES `tbm_tarif` (`idtarif`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_data_perusahaan` FOREIGN KEY (`idperusahaan`) REFERENCES `tbm_profil` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- INITIAL MASTER & SEED DATA
-- =====================================================================

-- 1. Company Profile
INSERT INTO `tbm_profil` (`id`, `pemilik`, `namaperusahaan`, `alamat`, `telp`, `hp`, `fax`, `email`, `url`, `logo`, `tgldaftar`, `totalhari`, `expire`, `ppn`, `service`) VALUES
(1, 'PT Telekomunikasi Solusindo', 'PT Telekomunikasi Solusindo Corp', 'Jl. Jenderal Sudirman Kav. 52-53, Jakarta Selatan', '021-52901234', '081234567890', '021-52901235', 'admin@solusindo-telecom.com', 'https://solusindo-telecom.com', 'logo.png', CURDATE(), 365, DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 11, 0);

-- 2. PBX Settings
INSERT INTO `tbm_setting` (`id`, `ip`, `port`, `keterangan`, `st`, `optambah`) VALUES
(1, '192.168.1.200', 5038, 'Panasonic KX-TDE600 Server Utama', '1', 'admin'),
(2, '192.168.1.201', 5060, 'Asterisk IP-PBX Gateway Cabang', '1', 'admin');

-- 3. User Roles / Level
INSERT INTO `tbm_level` (`id`, `idcabang`, `level`, `valid`) VALUES
(1, 1, 'Administrator System', 'Ya'),
(2, 1, 'Supervisor Billing', 'Ya'),
(3, 1, 'Operator PABX', 'Ya'),
(4, 1, 'Finance & Accounting', 'Ya');

-- 4. Users (Password: admin123 -> $2y$12$KkQ1...)
INSERT INTO `tbl_user` (`iduser`, `idcabang`, `idlevel`, `username`, `password`, `nama`, `telp`, `leveluser`, `blokir`, `aktif`) VALUES
(1, '1', 1, 'admin', '$2y$12$RjVomK92eS65Z2U/n0uCxeuL0xK69J25W50fV77cO77gR0gZ57Z1K', 'Super Administrator', '08123456789', 'Administrator System', 'N', 1),
(2, '1', 2, 'supervisor', '$2y$12$RjVomK92eS65Z2U/n0uCxeuL0xK69J25W50fV77cO77gR0gZ57Z1K', 'Supervisor Billing', '08129876543', 'Supervisor Billing', 'N', 1),
(3, '1', 4, 'finance', '$2y$12$RjVomK92eS65Z2U/n0uCxeuL0xK69J25W50fV77cO77gR0gZ57Z1K', 'Finance Dept', '08131122334', 'Finance & Accounting', 'N', 1);

-- 5. Navigation Modules
INSERT INTO `tbd_modul_user` (`iddetail`, `idmodul`, `kelompok`, `kategorimenu`, `namadetail`, `namadetailin`, `link`, `namafile`, `aktif`, `parent`) VALUES
(1, 1, 'Dashboard', 'Dashboard', 'Dashboard Billing', 'Ringkasan Tagihan', 'dashboard', 'DashboardController', 1, 0),
(2, 2, 'Transaksi', 'Billing', 'Data Billing Masuk', 'Log Panggilan & Tagihan', 'billing', 'BillingController', 1, 0),
(3, 3, 'Master', 'Department', 'Master Ekstensi', 'Kelola Extension Dept', 'extensions', 'ExtensionController', 1, 0),
(4, 4, 'Master', 'Tarif', 'Master Tarif & Rate', 'Pengaturan Tarif PABX', 'tarifs', 'TarifController', 1, 0),
(5, 5, 'Laporan', 'Laporan', 'Laporan Pemakaian', 'Rekapitulasi Tagihan', 'reports', 'ReportController', 1, 0);

-- 6. Access Permissions
INSERT INTO `tbd_akses_user` (`id`, `idlevel`, `iddetailmodul`, `tampil`, `ubah`, `hapus`, `tambah`, `aktif`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 1, 1, 1),
(3, 1, 3, 1, 1, 1, 1, 1),
(4, 1, 4, 1, 1, 1, 1, 1),
(5, 1, 5, 1, 1, 1, 1, 1),
(6, 2, 1, 1, 0, 0, 0, 1),
(7, 2, 2, 1, 1, 0, 1, 1),
(8, 2, 5, 1, 0, 0, 0, 1);

-- 7. Group Department / Divisi
INSERT INTO `tbm_group_department` (`idgroup`, `groupcode`, `namagroup`, `namadivisi`, `st`) VALUES
(1, 'DIR', 'Direksi', 'Executive Management', '1'),
(2, 'FIN', 'Finance & Accounting', 'Keuangan & Akuntansi', '1'),
(3, 'IT', 'Information Technology', 'Teknologi Informasi & Jaringan', '1'),
(4, 'HRD', 'Human Resources & General Affairs', 'SDM & Umum', '1'),
(5, 'MKT', 'Marketing & Sales', 'Pemasaran & Penjualan', '1'),
(6, 'OPS', 'Operations & Logistics', 'Operasional & Logistik', '1'),
(7, 'AP', 'AP', 'Angkasa Pura Division', '1');

-- 8. Department / Extensions
INSERT INTO `tbm_department` (`id`, `idgroup`, `extcode`, `extname`, `phonepassword`, `divisi`, `jabatan`, `nik`, `additional`, `budget`, `st`) VALUES
(1, 1, 101, 'Ruang Direktur Utama', '1234', 'Direksi', 'Direktur Utama', 'DIR-001', 0, 5000000, '1'),
(2, 1, 102, 'Sekretaris Direksi', '1234', 'Direksi', 'Sekretaris', 'DIR-002', 0, 1500000, '1'),
(3, 2, 201, 'Finance Manager', '2011', 'Finance & Accounting', 'Manajer Keuangan', 'FIN-001', 0, 2000000, '1'),
(4, 2, 202, 'Accounting Officer', '2022', 'Finance & Accounting', 'Staf Akuntansi', 'FIN-002', 0, 800000, '1'),
(5, 3, 301, 'IT Support & Network', '3011', 'Information Technology', 'IT Supervisor', 'IT-001', 0, 1500000, '1'),
(6, 3, 302, 'Helpdesk / NOC', '3022', 'Information Technology', 'Helpdesk Operator', 'IT-002', 0, 1000000, '1'),
(7, 4, 401, 'HRD Recruitment', '4011', 'Human Resources', 'HR Specialist', 'HR-001', 0, 1200000, '1'),
(8, 5, 501, 'Sales Executive 1', '5011', 'Marketing & Sales', 'Account Executive', 'MKT-001', 0, 3500000, '1'),
(9, 5, 502, 'Sales Executive 2', '5022', 'Marketing & Sales', 'Account Executive', 'MKT-002', 0, 3500000, '1'),
(10, 6, 601, 'Warehouse / Logistik', '6011', 'Operations', 'Staf Gudang', 'OPS-001', 0, 750000, '1'),
(11, 7, 569, 'ACCOUNT RECEIVABLE', '5699', 'AP', 'Finance AR Specialist', 'AP-569', 0, 5000000, '1');

-- 9. Zones
INSERT INTO `tbm_zona` (`idzone`, `namazone`, `kelompok`, `st`) VALUES
(1, 'Lokal (Jabodetabek)', 'LOCAL', '1'),
(2, 'Interlokal (SLJJ Zona 1: Jawa)', 'NDD', '1'),
(3, 'Interlokal (SLJJ Zona 2: Luar Jawa)', 'NDD', '1'),
(4, 'Seluler GSM Nasional', 'CELL', '1'),
(5, 'Internasional (SLI Zone Asia)', 'IDD', '1'),
(6, 'Internasional (SLI Zone Rest of World)', 'IDD', '1');

-- 10. Prefixes
INSERT INTO `tbm_prefix` (`idprefix`, `idzone`, `kodeprefix`, `distrik`, `keterangan`, `kelompok`, `st`) VALUES
(1, 1, '021', '021', 'Jakarta, Bogor, Depok, Tangerang, Bekasi', 'PHONE', '1'),
(2, 2, '022', '022', 'Bandung dan Cimahi', 'PHONE', '1'),
(3, 2, '024', '024', 'Semarang', 'PHONE', '1'),
(4, 2, '031', '031', 'Surabaya', 'PHONE', '1'),
(5, 3, '061', '061', 'Medan, Sumatera Utara', 'PHONE', '1'),
(6, 3, '0411', '0411', 'Makassar, Sulawesi Selatan', 'PHONE', '1'),
(7, 4, '0811', 'CELL', 'Telkomsel Halo', 'CELL', '1'),
(8, 4, '0812', 'CELL', 'Telkomsel Simpati', 'CELL', '1'),
(9, 4, '0813', 'CELL', 'Telkomsel Simpati', 'CELL', '1'),
(10, 4, '0815', 'CELL', 'Indosat IM3 / Matrix', 'CELL', '1'),
(11, 4, '0817', 'CELL', 'XL Axiata', 'CELL', '1'),
(12, 4, '0896', 'CELL', 'Tri (3)', 'CELL', '1'),
(13, 5, '001', 'INTL', 'Indosat IDD Singapore / Malaysia', 'PHONE', '1'),
(14, 5, '007', 'INTL', 'Telkom IDD Asia Pacific', 'PHONE', '1'),
(15, 6, '008', 'INTL', 'IDD USA / Europe', 'PHONE', '1');

-- 11. Days Master
INSERT INTO `tbm_hari` (`id`, `namahari`, `kode`) VALUES
(1, 'Senin', 1),
(2, 'Selasa', 2),
(3, 'Rabu', 3),
(4, 'Kamis', 4),
(5, 'Jumat', 5),
(6, 'Sabtu', 6),
(7, 'Minggu', 7);

-- 12. Master Tariff
INSERT INTO `tbm_tarif` (`idtarif`, `idmesin`, `namamesin`, `ratecode`, `catatan`, `harikhusus`, `namahari`, `mindurasi`, `st`) VALUES
(1, 1, 'Panasonic KX-TDE600 Server Utama', 'TARIF-LOKAL', 'Tarif Panggilan Telepon Lokal per Menit', 'Tidak', 'Semua Hari', 60, '1'),
(2, 1, 'Panasonic KX-TDE600 Server Utama', 'TARIF-SLJJ-JAWA', 'Tarif Panggilan SLJJ Antar Kota Pulau Jawa', 'Tidak', 'Semua Hari', 60, '1'),
(3, 1, 'Panasonic KX-TDE600 Server Utama', 'TARIF-SLJJ-LUAR', 'Tarif Panggilan SLJJ Luar Pulau Jawa', 'Tidak', 'Semua Hari', 60, '1'),
(4, 1, 'Panasonic KX-TDE600 Server Utama', 'TARIF-SELULER', 'Tarif Panggilan ke Semua Operator Seluler HP', 'Tidak', 'Semua Hari', 30, '1'),
(5, 1, 'Panasonic KX-TDE600 Server Utama', 'TARIF-INTERNASIONAL', 'Tarif Panggilan Luar Negeri IDD', 'Tidak', 'Semua Hari', 60, '1');

-- 13. Tariff Rates Breakdown (Peak & Off-Peak)
INSERT INTO `tbd_rate` (`id`, `idrate`, `mulai`, `selesai`, `tarifnormal`, `tarifkhusus`) VALUES
(1, 1, '08:00:00', '17:00:00', '250', '200'),
(2, 1, '17:00:01', '07:59:59', '180', '150'),
(3, 2, '08:00:00', '17:00:00', '750', '600'),
(4, 2, '17:00:01', '07:59:59', '550', '450'),
(5, 3, '08:00:00', '17:00:00', '1250', '1000'),
(6, 3, '17:00:01', '07:59:59', '950', '800'),
(7, 4, '00:00:00', '23:59:59', '900', '800'),
(8, 5, '00:00:00', '23:59:59', '4500', '4000');

-- 14. Special Tariffs
INSERT INTO `tbm_tarif_khusus` (`idharga`, `idtarif`, `namatarif`, `keterangan`, `tglmulai`, `tglselesai`, `mindurasi`, `st`) VALUES
(1, 1, 'Promo Tahun Baru & Libur Nasional', 'Diskon 50% panggilan lokal pada hari libur', '2026-01-01', '2026-01-02', 60, '1'),
(2, 4, 'Promo Lebaran Seluler Diskon', 'Diskon tarif panggilan HP selama cuti bersama', '2026-03-20', '2026-03-27', 30, '1');

-- 15. Detail Special Tariffs
INSERT INTO `tbd_tarif_khusus` (`id`, `idtarifkhusus`, `mulai`, `selesai`, `tarif`, `tgltarif`) VALUES
(1, 1, '00:00:00', '23:59:59', '125', '2026-01-01'),
(2, 2, '00:00:00', '23:59:59', '450', '2026-03-20');

-- 16. Master Area
INSERT INTO `tbm_area` (`id`, `idmesin`, `namamesin`, `idprefix`, `namaprefix`, `idzone`, `idrate`, `kodearea`, `zona`, `keterangan`, `koderate`, `namarate`, `tipe`, `st`) VALUES
(1, 1, 'Panasonic KX-TDE600 Server Utama', 1, '021', 1, 1, '021', 'Lokal (Jabodetabek)', 'Area Ibukota dan sekitarnya', 'TARIF-LOKAL', 'Tarif Panggilan Telepon Lokal', 'Lokal', '1'),
(2, 1, 'Panasonic KX-TDE600 Server Utama', 2, '022', 2, 2, '022', 'Interlokal (SLJJ Zona 1: Jawa)', 'Bandung Kota & Kab', 'TARIF-SLJJ-JAWA', 'Tarif SLJJ Jawa Barat', 'SLJJ', '1'),
(3, 1, 'Panasonic KX-TDE600 Server Utama', 4, '031', 2, 2, '031', 'Interlokal (SLJJ Zona 1: Jawa)', 'Surabaya Raya', 'TARIF-SLJJ-JAWA', 'Tarif SLJJ Jawa Timur', 'SLJJ', '1'),
(4, 1, 'Panasonic KX-TDE600 Server Utama', 5, '061', 3, 3, '061', 'Interlokal (SLJJ Zona 2: Luar Jawa)', 'Medan & Deli', 'TARIF-SLJJ-LUAR', 'Tarif SLJJ Sumatera', 'SLJJ', '1'),
(5, 1, 'Panasonic KX-TDE600 Server Utama', 8, '0812', 4, 4, '0812', 'Seluler GSM Nasional', 'Telkomsel Nasional', 'TARIF-SELULER', 'Tarif Seluler Operator GSM', 'Seluler', '1'),
(6, 1, 'Panasonic KX-TDE600 Server Utama', 10, '0815', 4, 4, '0815', 'Seluler GSM Nasional', 'Indosat Ooredoo', 'TARIF-SELULER', 'Tarif Seluler Operator GSM', 'Seluler', '1');

-- 17. Master Bisnis
INSERT INTO `tbm_bisnis` (`id`, `nama`, `notelp`, `alamat`, `st`) VALUES
(1, 'Head Office Jakarta Pusat', '021-52901234', 'Sudirman Business District', '1'),
(2, 'Branch Office Surabaya', '031-7788990', 'Jl. Darmo No. 45 Surabaya', '1');

-- 18. Buffer SMDR / CDR
INSERT INTO `tbm_gabung` (`id`, `gabung`) VALUES
(1, '2026/10/05 08:30:15 101 01 081234567890 00:04:12 0001 ...');

-- 19. Scheduler Master
INSERT INTO `pjs_table` (`id`, `scriptpath`, `name`, `time_interval`, `fire_time`, `run_only_once`, `currently_running`) VALUES
(1, 'jobs/calculate_billing.php', 'Hitung Otomatis Tagihan CDR PABX', 300, UNIX_TIMESTAMP(), 0, 0);

INSERT INTO `phpjobscheduler` (`id`, `scriptpath`, `name`, `time_interval`, `fire_time`, `run_only_once`, `currently_running`, `paused`) VALUES
(1, 'jobs/calculate_billing.php', 'Hitung Otomatis Tagihan CDR PABX', 300, UNIX_TIMESTAMP(), 0, 0, 0);

-- 20. Central Call Detail Records / Billing Data Masuk
INSERT INTO `tbm_data_masuk` (
  `id`, `kodeclient`, `kodetelp`, `nourut`, `ext_pemanggil`, `iddepartment`, `namadepartment`,
  `noline`, `tglmasuk`, `jammasuk`, `durasi`, `durasi_detik`, `kodeakses`, `no_tujuan`,
  `no_trunk`, `idmesin`, `namamesin`, `idzone`, `zona`, `idprefix`, `kodearea`, `ketarea`,
  `idrate`, `namarate`, `formularate`, `biaya`, `tambahan`, `subtotal`, `hitung`, `st`,
  `tglinsert`, `idperusahaan`, `namaperusahaan`, `pulsa`
) VALUES
(1, 'CLI-001', '01', 'CDR-0001', '101', 1, 'Ruang Direktur Utama', 'CO-01', '2026-10-05', '08:15:22', '00:03:30', 210, '9', '081288991122', 'TRK-01', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 8, '0812', 'Telkomsel Nasional', 4, 'Tarif Seluler Operator GSM', '4 x 900', 3600, 396, 3996, '1', '1', '2026-10-05', 1, 'PT Telekomunikasi Solusindo Corp', '4'),
(2, 'CLI-001', '01', 'CDR-0002', '501', 8, 'Sales Executive 1', 'CO-02', '2026-10-05', '08:42:10', '00:06:15', 375, '9', '02157901234', 'TRK-02', 1, 'Panasonic KX-TDE600 Server Utama', 1, 'Lokal (Jabodetabek)', 1, '021', 'Area Ibukota dan sekitarnya', 1, 'Tarif Panggilan Telepon Lokal', '7 x 250', 1750, 192.5, 1942.5, '1', '1', '2026-10-05', 1, 'PT Telekomunikasi Solusindo Corp', '7'),
(3, 'CLI-001', '01', 'CDR-0003', '502', 9, 'Sales Executive 2', 'CO-01', '2026-10-05', '09:05:44', '00:08:50', 530, '9', '081512345678', 'TRK-01', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 10, '0815', 'Indosat Ooredoo', 4, 'Tarif Seluler Operator GSM', '9 x 900', 8100, 891, 8991, '1', '1', '2026-10-05', 1, 'PT Telekomunikasi Solusindo Corp', '9'),
(4, 'CLI-001', '01', 'CDR-0004', '301', 5, 'IT Support & Network', 'CO-03', '2026-10-05', '09:18:02', '00:02:10', 130, '9', '03188997766', 'TRK-03', 1, 'Panasonic KX-TDE600 Server Utama', 2, 'Interlokal (SLJJ Zona 1: Jawa)', 4, '031', 'Surabaya Raya', 2, 'Tarif SLJJ Jawa Timur', '3 x 750', 2250, 247.5, 2497.5, '1', '1', '2026-10-05', 1, 'PT Telekomunikasi Solusindo Corp', '3'),
(5, 'CLI-001', '01', 'CDR-0005', '201', 3, 'Finance Manager', 'CO-02', '2026-10-05', '09:30:15', '00:04:45', 285, '9', '0224201122', 'TRK-02', 1, 'Panasonic KX-TDE600 Server Utama', 2, 'Interlokal (SLJJ Zona 1: Jawa)', 2, '022', 'Bandung Kota & Kab', 2, 'Tarif SLJJ Jawa Barat', '5 x 750', 3750, 412.5, 4162.5, '1', '1', '2026-10-05', 1, 'PT Telekomunikasi Solusindo Corp', '5'),
(6, 'CLI-001', '01', 'CDR-0006', '102', 2, 'Sekretaris Direksi', 'CO-01', '2026-10-05', '09:45:00', '00:01:20', 80, '9', '081399887766', 'TRK-01', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 9, '0813', 'Telkomsel Simpati', 4, 'Tarif Seluler Operator GSM', '2 x 900', 1800, 198, 1998, '1', '1', '2026-10-05', 1, 'PT Telekomunikasi Solusindo Corp', '2'),
(7, 'CLI-001', '01', 'CDR-0007', '401', 7, 'HRD Recruitment', 'CO-04', '2026-10-05', '10:02:18', '00:05:40', 340, '9', '08176543210', 'TRK-04', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 11, '0817', 'XL Axiata', 4, 'Tarif Seluler Operator GSM', '6 x 900', 5400, 594, 5994, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '6'),
(8, 'CLI-001', '01', 'CDR-0008', '501', 8, 'Sales Executive 1', 'CO-02', '2026-10-05', '10:15:33', '00:12:00', 720, '9', '0614567890', 'TRK-02', 1, 'Panasonic KX-TDE600 Server Utama', 3, 'Interlokal (SLJJ Zona 2: Luar Jawa)', 5, '061', 'Medan & Deli', 3, 'Tarif SLJJ Sumatera', '12 x 1250', 15000, 1650, 16650, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '12'),
(9, 'CLI-001', '01', 'CDR-0009', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-01', '2026-10-05', '08:14:00', '00:03:00', 180, '9', '1500805', 'TRK-01', 1, 'Panasonic KX-TDE600 Server Utama', 1, 'Lokal (Jabodetabek)', 1, '021', 'Customer Service Line', 1, 'Tarif Panggilan Telepon Lokal', '3 x 500', 1500, 165, 1665, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '3'),
(10, 'CLI-001', '01', 'CDR-0010', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-02', '2026-10-05', '09:22:15', '00:04:00', 240, '9', '08113866479', 'TRK-02', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 7, '0811', 'Denpasar, (TEL)', 4, 'Tarif Seluler Operator GSM', '8 x 900', 7200, 792, 7992, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '8'),
(11, 'CLI-001', '01', 'CDR-0011', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-01', '2026-10-05', '10:05:00', '00:01:00', 60, '0', '7722', 'TRK-01', 1, 'Panasonic KX-TDE600 Server Utama', 1, 'Internal Extension', 1, 'INT', 'Internal Call', 1, 'TARIF-INTERNAL', '1 x 0', 0, 0, 0, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '1'),
(12, 'CLI-001', '01', 'CDR-0012', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-03', '2026-10-05', '11:30:10', '00:05:20', 320, '9', '8050895', 'TRK-03', 1, 'Panasonic KX-TDE600 Server Utama', 1, 'Lokal (Jabodetabek)', 1, '021', 'Jakarta, (TEL)', 1, 'Tarif Panggilan Telepon Lokal', '6 x 250', 1500, 165, 1665, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '6'),
(13, 'CLI-001', '01', 'CDR-0013', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-02', '2026-10-05', '14:15:45', '00:07:00', 420, '9', '087762957298', 'TRK-02', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 11, '0817', 'Jakarta, (XL)', 4, 'Tarif Seluler Operator GSM', '14 x 900', 12600, 1386, 13986, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '14'),
(14, 'CLI-001', '01', 'CDR-0014', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-04', '2026-10-05', '15:40:20', '00:03:10', 190, '9', '081330559406', 'TRK-04', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 9, '0813', 'Surabaya, (SAT)', 4, 'Tarif Seluler Operator GSM', '7 x 900', 6300, 693, 6993, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '7');

