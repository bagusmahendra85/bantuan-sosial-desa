-- CREATE DATABASES
CREATE DATABASE mengwi;

-- CREATING REF TABLE
CREATE TABLE wp_mgw_ref_banjar (
  banjar_id INT PRIMARY KEY AUTO_INCREMENT,
  banjar VARCHAR(20) NOT NULL
);

-- INSERTING REF VALUE
INSERT INTO wp_mgw_ref_banjar VALUES
(NULL, 'Batu'),
(NULL, 'Gambang'),
(NULL, 'Pande'),
(NULL, 'Munggu'),
(NULL, 'Pandean'),
(NULL, 'Serangan'),
(NULL, 'Peregae'),
(NULL, 'Lebah Pangkung'),
(NULL, 'Pengiasan'),
(NULL, 'Alangkajeng'),
(NULL, 'Delod Bale Agung');

-- CREATING REF TABLE
CREATE TABLE wp_mgw_ref_bansos (
  bansos_id INT PRIMARY KEY AUTO_INCREMENT,
  bansos VARCHAR(30)
);

-- INSERTING REF VALUE
INSERT INTO wp_mgw_ref_bansos VALUES
(NULL, 'BLT-DD'),
(NULL, 'BPNT'),
(NULL, 'PKH'),
(NULL, 'BPNT dan PKH'),
(NULL, 'Ketahanan Pangan');

-- CREATE REF TABLE
CREATE TABLE wp_mgw_keluarga (
  kk_id INT PRIMARY KEY AUTO_INCREMENT,
  nomor_kk CHAR(16) NOT NULL,
  nama_kk VARCHAR(100) NOT NULL
);

-- INSERTING DUMMY VALUES
INSERT INTO wp_mgw_keluarga VALUES
(NULL, '5103021211140005', 'I RAI ASTIKA'),
(NULL, '5103021403170015', 'I KETUT ALIT SOMA PRAPTA'),
(NULL, '5103022812060215', 'I MADE KERTEYASA'),
(NULL, '5103021901070011', 'I NYOMAN GEDE YASA');

-- CREATE MAIN TABLE
CREATE TABLE wp_mgw_kpm_bansos (
  kpm_id INT PRIMARY KEY AUTO_INCREMENT,
  kk_id INT NOT NULL,
  nik CHAR(16) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  banjar_id INT NOT NULL,
  bansos_id INT NOT NULL,
  FOREIGN KEY (kk_id) REFERENCES wp_mgw_keluarga(kk_id),
  FOREIGN KEY (banjar_id) REFERENCES wp_mgw_ref_banjar(banjar_id),
  FOREIGN KEY (bansos_id) REFERENCES wp_mgw_ref_bansos(bansos_id)
);

-- INSERTING DUMMY VALUES
INSERT INTO wp_mgw_kpm_bansos VALUES
(NULL, 1, '3573014402720005', 'GST AYU NGR SANTA HANDAJANI', 9, 2),
(NULL, 2, '3315133007620001', 'I KETUT ALIT SOMA PRAPTA', 11, 1),
(NULL, 3, '5103020106670007', 'I MADE KERTEYASA', 8, 3),
(NULL, 4, '5103021405770006', 'I NYOMAN GEDE YASA', 3, 4);

-- MAIN QUERY [JOINED]
SELECT
wp_mgw_keluarga.nomor_kk AS Nomor_KK,
wp_mgw_kpm_bansos.nik AS Nomor_Induk_Kependudukan,
wp_mgw_kpm_bansos.nama AS Nama_Nasabah,
CONCAT( 'Br. ', wp_mgw_ref_banjar.banjar, ', Mengwi' ) AS Alamat,
wp_mgw_ref_bansos.bansos AS bansos
FROM wp_mgw_kpm_bansos
JOIN wp_mgw_keluarga USING (kk_id)
JOIN wp_mgw_ref_banjar USING (banjar_id)
JOIN wp_mgw_ref_bansos USING (bansos_id)
ORDER BY bansos_id;