-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 10, 2024 at 05:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rumahsakit`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `password` char(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'ae48a2f3c808496a66e061139acdcd7c0c7c5d86');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id_obat` char(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `nama_obat` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `bentuk_obat` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tanggal_kadaluarsa` date DEFAULT NULL,
  `stok` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `bentuk_obat`, `tanggal_kadaluarsa`, `stok`) VALUES
('45678', 'Bodrex', 'Kapsul', '2024-07-26', 60),
('45678', 'Mixagrip', 'Tablet', '2024-07-26', 60),
('68907', 'Paracetamol', 'Syrup', '2027-07-28', 70),
('45679', 'Fg TRosen', 'Tablet', '2024-07-31', 60),
('89799', 'Ibu profen', 'Kapsul', '0000-00-00', 70);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id_pasien` char(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `nama_pasien` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `diagnosa` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `alamat` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ruang_inap` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nomor_kamar` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id_pasien`, `nama_pasien`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `diagnosa`, `alamat`, `ruang_inap`, `nomor_kamar`) VALUES
('0567', 'Sandy kelvin', 'Semarang', '2003-07-02', 'Laki-Laki', 'Gagal ginjal', 'jln. pahlawan no 10 semarang', 'Arjuna', 23),
('0786', 'Kaila Faisha', 'Grobogan', '2005-10-28', 'perempuan', 'Asam Lambung', 'jln. Surojenggolo no 234. Grobogan', 'Yudistira', 67),
('0987', 'Maxwell Sunjoyo', 'Surabaya', '2003-09-14', 'lakilaki', 'Jantung', 'jln.Soekarno 5 Surabaya', 'Nakula', 5),
('0980', 'Maxwell Sunjoyo', 'semarang', '1996-01-12', 'lakilaki', 'Maag', 'bohh', 'Arjuna', 57),
('0989', 'kaila zahra', 'Grobogan', '2000-01-01', 'lakilaki', 'Jantung', 'jln.Soekarno 80 Surabaya', 'Yudistira', 30);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
