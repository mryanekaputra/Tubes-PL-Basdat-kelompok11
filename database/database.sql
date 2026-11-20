CREATE DATABASE IF NOT EXISTS db_dagri_hmsi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_dagri_hmsi;

CREATE TABLE divisi (
  id_divisi INT AUTO_INCREMENT PRIMARY KEY,
  nama_divisi VARCHAR(100) NOT NULL UNIQUE,
  fokus_kegiatan TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE pengurus (
  id_pengurus INT AUTO_INCREMENT PRIMARY KEY,
  id_divisi INT NULL,
  nama_pengurus VARCHAR(120) NOT NULL,
  nim VARCHAR(30) NOT NULL UNIQUE,
  jabatan VARCHAR(100) NOT NULL,
  status_aktif ENUM('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pengurus_divisi FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  id_pengurus INT NOT NULL UNIQUE,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_pengurus FOREIGN KEY (id_pengurus) REFERENCES pengurus(id_pengurus)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE program_kerja (
  id_proker INT AUTO_INCREMENT PRIMARY KEY,
  id_divisi INT NOT NULL,
  nama_proker VARCHAR(150) NOT NULL,
  periode VARCHAR(20) NOT NULL,
  tanggal_pelaksanaan DATE NULL,
  status_persiapan ENUM('Perencanaan','Persiapan','Pelaksanaan','Selesai') NOT NULL DEFAULT 'Perencanaan',
  total_rab DECIMAL(15,2) NOT NULL DEFAULT 0,
  konsep_kegiatan TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_proker_divisi FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE detail_pengurus_proker (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_pengurus INT NOT NULL,
  id_proker INT NOT NULL,
  jobdesk VARCHAR(150) NOT NULL,
  UNIQUE KEY uq_pengurus_proker (id_pengurus, id_proker),
  CONSTRAINT fk_detail_pengurus FOREIGN KEY (id_pengurus) REFERENCES pengurus(id_pengurus)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detail_proker FOREIGN KEY (id_proker) REFERENCES program_kerja(id_proker)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE dokumen_kegiatan (
  id_dokumen INT AUTO_INCREMENT PRIMARY KEY,
  id_proker INT NOT NULL,
  jenis_dokumen VARCHAR(100) NOT NULL,
  nama_dokumen VARCHAR(150) NOT NULL,
  tanggal_dokumen DATE NULL,
  status_dokumen ENUM('Draft','Final') NOT NULL DEFAULT 'Draft',
  tautan_drive VARCHAR(500) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_dokumen_proker FOREIGN KEY (id_proker) REFERENCES program_kerja(id_proker)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE rapat_koordinasi (
  id_rapat INT AUTO_INCREMENT PRIMARY KEY,
  id_proker INT NULL,
  tanggal_rapat DATETIME NOT NULL,
  agenda TEXT NOT NULL,
  media_rapat VARCHAR(100) NULL,
  hasil_keputusan TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_rapat_proker FOREIGN KEY (id_proker) REFERENCES program_kerja(id_proker)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- Data awal divisi
INSERT IGNORE INTO divisi (nama_divisi, fokus_kegiatan) VALUES
('Keterampilan Internal','Pengembangan keterampilan internal'),
('Harmonisasi Internal','Membangun keharmonisan anggota'),
('Hubungan Internal','Menjaga hubungan internal'),
('Kajian Internal','Kajian dan pengembangan internal');

-- Akun awal dibuat melalui halaman setup_admin.php (hapus file tersebut setelah akun dibuat).
