-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2018 at 02:37 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u673279960_rm`
--
CREATE DATABASE IF NOT EXISTS `u673279960_rm` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `u673279960_rm`;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_mobil`
--

CREATE TABLE `jenis_mobil` (
  `id` int(10) UNSIGNED NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `tipe` mediumint(10) UNSIGNED NOT NULL,
  `merek` mediumint(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leasing`
--

CREATE TABLE `leasing` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `merek_mobil`
--

CREATE TABLE `merek_mobil` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `merek` varchar(25) NOT NULL,
  `picture` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis` int(10) UNSIGNED NOT NULL,
  `transmission` smallint(5) UNSIGNED NOT NULL,
  `tahun` mediumint(8) UNSIGNED NOT NULL,
  `harga` int(10) UNSIGNED NOT NULL,
  `warna` varchar(25) NOT NULL,
  `odometer` int(10) UNSIGNED NOT NULL,
  `no_mesin` varchar(25) NOT NULL,
  `no_rangka` varchar(25) NOT NULL,
  `pic` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_mobil`
--

CREATE TABLE `nilai_mobil` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nilai_dokumen` double NOT NULL,
  `nilai_mesin` double NOT NULL,
  `nilai_odometer` double NOT NULL,
  `nilai_interior` double NOT NULL,
  `nilai_exterior` double NOT NULL,
  `nilai_tahun` double NOT NULL,
  `nilai_harga` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `mobil` bigint(20) UNSIGNED NOT NULL,
  `customer` bigint(20) UNSIGNED NOT NULL,
  `harga` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` bigint(20) UNSIGNED ZEROFILL NOT NULL,
  `customer` bigint(20) UNSIGNED NOT NULL,
  `mobil` bigint(20) UNSIGNED NOT NULL,
  `metode_pay_NULL_CA_EIN_CR` tinyint(1) NOT NULL,
  `tanggal` datetime NOT NULL,
  `komentar` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` bigint(20) UNSIGNED ZEROFILL NOT NULL,
  `id_pemesanan` bigint(20) UNSIGNED NOT NULL,
  `tanggal` datetime NOT NULL,
  `total_harga` bigint(20) UNSIGNED NOT NULL,
  `leasing` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `red_motor_user`
--

CREATE TABLE `red_motor_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` tinyint(1) UNSIGNED NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `level_user` tinyint(3) UNSIGNED NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `simulasi_kredit`
--

CREATE TABLE `simulasi_kredit` (
  `tanggal` date NOT NULL,
  `bunga_per_tahun` float NOT NULL,
  `biaya_administrasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tipe_mobil`
--

CREATE TABLE `tipe_mobil` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `tipe` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_transmission`
--

CREATE TABLE `t_transmission` (
  `id` smallint(5) UNSIGNED ZEROFILL NOT NULL,
  `transmission` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_level`
--

CREATE TABLE `user_level` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `level_user` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_mobil`
--
ALTER TABLE `jenis_mobil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipe` (`tipe`),
  ADD KEY `merek` (`merek`);

--
-- Indexes for table `leasing`
--
ALTER TABLE `leasing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merek_mobil`
--
ALTER TABLE `merek_mobil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis` (`jenis`),
  ADD KEY `transmission` (`transmission`);

--
-- Indexes for table `nilai_mobil`
--
ALTER TABLE `nilai_mobil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobil` (`mobil`),
  ADD KEY `customer` (`customer`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer` (`customer`),
  ADD KEY `mobil` (`mobil`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leasing` (`leasing`),
  ADD KEY `pemesanan` (`id_pemesanan`);

--
-- Indexes for table `red_motor_user`
--
ALTER TABLE `red_motor_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `level_user` (`level_user`);

--
-- Indexes for table `simulasi_kredit`
--
ALTER TABLE `simulasi_kredit`
  ADD PRIMARY KEY (`tanggal`);

--
-- Indexes for table `tipe_mobil`
--
ALTER TABLE `tipe_mobil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_transmission`
--
ALTER TABLE `t_transmission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_level`
--
ALTER TABLE `user_level`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jenis_mobil`
--
ALTER TABLE `jenis_mobil`
  ADD CONSTRAINT `jenis_mobil_ibfk_1` FOREIGN KEY (`tipe`) REFERENCES `tipe_mobil` (`id`),
  ADD CONSTRAINT `jenis_mobil_ibfk_2` FOREIGN KEY (`merek`) REFERENCES `merek_mobil` (`id`);

--
-- Constraints for table `mobil`
--
ALTER TABLE `mobil`
  ADD CONSTRAINT `mobil_ibfk_1` FOREIGN KEY (`jenis`) REFERENCES `jenis_mobil` (`id`),
  ADD CONSTRAINT `mobil_ibfk_2` FOREIGN KEY (`transmission`) REFERENCES `t_transmission` (`id`);

--
-- Constraints for table `nilai_mobil`
--
ALTER TABLE `nilai_mobil`
  ADD CONSTRAINT `nilai_mobil_ibfk_1` FOREIGN KEY (`id`) REFERENCES `mobil` (`id`);

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `red_motor_user` (`id`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`mobil`) REFERENCES `mobil` (`id`),
  ADD CONSTRAINT `pembelian_ibfk_3` FOREIGN KEY (`customer`) REFERENCES `red_motor_user` (`id`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `red_motor_user` (`id`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`mobil`) REFERENCES `mobil` (`id`);

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_3` FOREIGN KEY (`leasing`) REFERENCES `leasing` (`id`),
  ADD CONSTRAINT `penjualan_ibfk_4` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id`);

--
-- Constraints for table `red_motor_user`
--
ALTER TABLE `red_motor_user`
  ADD CONSTRAINT `red_motor_user_ibfk_1` FOREIGN KEY (`level_user`) REFERENCES `user_level` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
