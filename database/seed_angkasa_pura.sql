UPDATE pabx.tbm_profil SET namaperusahaan = 'ANGKASA PURA', pemilik = 'PT Angkasa Pura Indonesia' WHERE id = 1;

INSERT INTO pabx.tbm_group_department (idgroup, groupcode, namagroup, namadivisi, st) 
VALUES (7, 'AP', 'AP', 'Angkasa Pura Division', '1')
ON DUPLICATE KEY UPDATE namagroup='AP';

INSERT INTO pabx.tbm_department (id, idgroup, extcode, extname, phonepassword, divisi, jabatan, nik, additional, budget, st)
VALUES (11, 7, 569, 'ACCOUNT RECEIVABLE', '5699', 'AP', 'Finance AR Specialist', 'AP-569', 0, 5000000, '1')
ON DUPLICATE KEY UPDATE extname='ACCOUNT RECEIVABLE';

INSERT INTO pabx.tbm_data_masuk (
  kodeclient, kodetelp, nourut, ext_pemanggil, iddepartment, namadepartment,
  noline, tglmasuk, jammasuk, durasi, durasi_detik, kodeakses, no_tujuan,
  no_trunk, idmesin, namamesin, idzone, zona, idprefix, kodearea, ketarea,
  idrate, namarate, formularate, biaya, tambahan, subtotal, hitung, st,
  tglinsert, idperusahaan, namaperusahaan, pulsa
) VALUES 
('CLI-001', '01', 'CDR-0009', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-01', '2026-10-05', '08:14:00', '00:03:00', 180, '9', '1500805', 'TRK-01', 1, 'Panasonic KX-TDE600 Server Utama', 1, 'Lokal (Jabodetabek)', 1, '021', 'Customer Service Line', 1, 'TARIF-LOKAL', '3 x 500', 1500, 165, 1665, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '3'),
('CLI-001', '01', 'CDR-0010', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-02', '2026-10-05', '09:22:15', '00:04:00', 240, '9', '08113866479', 'TRK-02', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 7, '0811', 'Denpasar, (TEL)', 4, 'TARIF-SELULER', '8 x 900', 7200, 792, 7992, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '8'),
('CLI-001', '01', 'CDR-0011', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-01', '2026-10-05', '10:05:00', '00:01:00', 60, '0', '7722', 'TRK-01', 1, 'Panasonic KX-TDE600 Server Utama', 1, 'Internal Extension', 1, 'INT', 'Internal Call', 1, 'TARIF-INTERNAL', '1 x 0', 0, 0, 0, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '1'),
('CLI-001', '01', 'CDR-0012', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-03', '2026-10-05', '11:30:10', '00:05:20', 320, '9', '8050895', 'TRK-03', 1, 'Panasonic KX-TDE600 Server Utama', 1, 'Lokal (Jabodetabek)', 1, '021', 'Jakarta, (TEL)', 1, 'TARIF-LOKAL', '6 x 250', 1500, 165, 1665, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '6'),
('CLI-001', '01', 'CDR-0013', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-02', '2026-10-05', '14:15:45', '00:07:00', 420, '9', '087762957298', 'TRK-02', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 11, '0817', 'Jakarta, (XL)', 4, 'TARIF-SELULER', '14 x 900', 12600, 1386, 13986, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '14'),
('CLI-001', '01', 'CDR-0014', '569', 11, 'ACCOUNT RECEIVABLE', 'CO-04', '2026-10-05', '15:40:20', '00:03:10', 190, '9', '081330559406', 'TRK-04', 1, 'Panasonic KX-TDE600 Server Utama', 4, 'Seluler GSM Nasional', 9, '0813', 'Surabaya, (SAT)', 4, 'TARIF-SELULER', '7 x 900', 6300, 693, 6993, '1', '1', '2026-10-05', 1, 'ANGKASA PURA', '7');
