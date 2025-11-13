-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Agu 2025 pada 15.36
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_eskul`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `eskul`
--

CREATE TABLE `eskul` (
  `id_eskul` int(11) NOT NULL,
  `nama_eskul` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `pembina` varchar(100) NOT NULL,
  `hari_kegiatan` varchar(10) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `kuota` int(11) DEFAULT NULL,
  `foto_eskul` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `eskul`
--

INSERT INTO `eskul` (`id_eskul`, `nama_eskul`, `deskripsi`, `pembina`, `hari_kegiatan`, `jam_mulai`, `jam_selesai`, `lokasi`, `kuota`, `foto_eskul`) VALUES
(24, 'Pramuka', 'Semangat 13', 'bu linda', 'selasa', '10:07:00', '12:07:00', 'lapangan smkn 13 bdg', 20, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran_eskul`
--

CREATE TABLE `pendaftaran_eskul` (
  `id_pendaftaran` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_eskul` int(11) NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `status` enum('tunda','diterima','ditolak') NOT NULL DEFAULT 'tunda',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran_eskul`
--

INSERT INTO `pendaftaran_eskul` (`id_pendaftaran`, `id_siswa`, `id_eskul`, `tanggal_daftar`, `status`, `keterangan`) VALUES
(57, 49, 24, '2025-08-27', 'tunda', 'Semoga masuk');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','pembina','pelatih') NOT NULL,
  `terakhir_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `nama_lengkap`, `role`, `terakhir_login`) VALUES
(38, 'test1', '123', 'test', 'admin', NULL),
(39, 'test2', '123', 'test', 'pembina', NULL),
(40, 'test3', '123', 'test', 'pelatih', NULL),
(41, 'radit', '$2y$10$SUGok1EMLbuCa.zyiKSHseBENYHHaEYT8.4cy859QSyXzhL3WNozO', '', 'admin', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id_presensi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_eskul` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_hadir` enum('hadir','izin','sakit','alpa') NOT NULL,
  `status` enum('tunda','diterima','ditolak') NOT NULL DEFAULT 'tunda',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prestasi`
--

CREATE TABLE `prestasi` (
  `id_prestasi` int(11) NOT NULL,
  `id_pendaftaran` int(11) NOT NULL,
  `nama_prestasi` varchar(100) NOT NULL,
  `tingkat` enum('sekolah','kecamatan','kabupaten','provinsi','nasional','internasional') NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `sertifikat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prestasi`
--

INSERT INTO `prestasi` (`id_prestasi`, `id_pendaftaran`, `nama_prestasi`, `tingkat`, `tanggal`, `deskripsi`, `sertifikat`) VALUES
(7, 57, 'Olimpiade Matematika', 'kabupaten', '2025-08-26', 'Halo pak', '1756213780_Screenshot 2025-08-08 140457.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_siswa` varchar(200) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nama_siswa`, `kelas`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_telp`, `email`, `foto_profil`) VALUES
(49, '123', 'test', 'X RPL 1', 'L', '2019-02-27', 'KOMP PERMATA BIRU', '0892893899', 'test@gmail.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `eskul`
--
ALTER TABLE `eskul`
  ADD PRIMARY KEY (`id_eskul`);

--
-- Indeks untuk tabel `pendaftaran_eskul`
--
ALTER TABLE `pendaftaran_eskul`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD KEY `pendaftaran_eskul_ibfk_1` (`id_eskul`),
  ADD KEY `pendaftaran_eskul_ibfk_2` (`id_siswa`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `presensi_ibfk_1` (`id_eskul`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD PRIMARY KEY (`id_prestasi`),
  ADD KEY `id_pendaftaran` (`id_pendaftaran`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `eskul`
--
ALTER TABLE `eskul`
  MODIFY `id_eskul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran_eskul`
--
ALTER TABLE `pendaftaran_eskul`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id_presensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  MODIFY `id_prestasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pendaftaran_eskul`
--
ALTER TABLE `pendaftaran_eskul`
  ADD CONSTRAINT `pendaftaran_eskul_ibfk_1` FOREIGN KEY (`id_eskul`) REFERENCES `eskul` (`id_eskul`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_eskul_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_eskul`) REFERENCES `eskul` (`id_eskul`),
  ADD CONSTRAINT `presensi_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD CONSTRAINT `prestasi_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `pendaftaran_eskul` (`id_pendaftaran`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
