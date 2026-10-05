INSERT INTO phpmyadmin.pma__pdf_pages (db_name, page_nr, page_descr) VALUES ('pabx', 1, 'PABX Relational Schema') ON DUPLICATE KEY UPDATE page_descr='PABX Relational Schema';

INSERT INTO phpmyadmin.pma__table_coords (db_name, table_name, pdf_page_number, x, y) VALUES
('pabx', 'tbm_profil', 1, 50, 30),
('pabx', 'tbm_setting', 1, 230, 30),
('pabx', 'user_log', 1, 410, 30),
('pabx', 'pjs_table', 1, 590, 30),
('pabx', 'tbm_area', 1, 770, 30),
('pabx', 'tbd_modul_user', 1, 950, 30),
('pabx', 'tbm_data_masuk', 1, 1150, 30),
('pabx', 'tbm_tarif', 1, 50, 380),
('pabx', 'tbm_tarif_khusus', 1, 230, 220),
('pabx', 'tbd_tarif_khusus', 1, 410, 190),
('pabx', 'tbl_user', 1, 410, 340),
('pabx', 'tbd_rate', 1, 590, 220),
('pabx', 'tbm_department', 1, 590, 380),
('pabx', 'phpjobscheduler_logs', 1, 950, 240),
('pabx', 'tbm_gabung', 1, 770, 380),
('pabx', 'tbm_zona', 1, 50, 640),
('pabx', 'tbm_prefix', 1, 230, 550),
('pabx', 'pjs_logs_table', 1, 230, 780),
('pabx', 'phpjobscheduler', 1, 410, 670),
('pabx', 'tbd_akses_user', 1, 590, 680),
('pabx', 'tbm_group_department', 1, 770, 520),
('pabx', 'tbm_hari', 1, 770, 700),
('pabx', 'log_login', 1, 770, 800),
('pabx', 'tbm_bisnis', 1, 950, 650),
('pabx', 'tbm_level', 1, 1150, 750)
ON DUPLICATE KEY UPDATE x=VALUES(x), y=VALUES(y);
