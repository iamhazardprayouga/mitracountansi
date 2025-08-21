-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 07, 2016 at 07:29 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sia1`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_akun`
--

CREATE TABLE IF NOT EXISTS `tb_akun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_akun` varchar(50) NOT NULL,
  `kategori` varchar(2) NOT NULL,
  `kode` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `tb_akun`
--

INSERT INTO `tb_akun` (`id`, `nama_akun`, `kategori`, `kode`) VALUES
(18, 'KAS', 'HL', '111'),
(19, 'Modal', 'HT', '112'),
(20, 'Hutang', 'HT', '134'),
(21, 'Pendapatan', 'HT', '115'),
(22, 'Perlengkapan', 'HL', '117'),
(23, 'Gedung', 'HT', '116');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jurnal`
--

CREATE TABLE IF NOT EXISTS `tb_jurnal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` varchar(30) NOT NULL,
  `id_akun` varchar(10) NOT NULL,
  `ket` varchar(50) NOT NULL,
  `ref` varchar(50) NOT NULL,
  `nominal` int(10) NOT NULL,
  `tipe` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `tb_jurnal`
--

INSERT INTO `tb_jurnal` (`id`, `tgl`, `id_akun`, `ket`, `ref`, `nominal`, `tipe`) VALUES
(34, '11/30/2015', '18', 'Investasi', '', 10000000, 'D'),
(35, '11/30/2015', '19', 'Investasi', '', 10000000, 'K'),
(36, '12/01/2015', '18', 'Pinjaman Bank', '', 5000000, 'D'),
(38, '12/01/2015', '20', 'Hutang Bank', '', 5000000, 'K'),
(39, '12/02/2015', '18', 'Bantuan Jasa', '', 500000, 'D'),
(40, '12/02/2015', '21', 'Bantuan Jasa', '', 500000, 'K'),
(41, '12/04/2015', '22', 'Beli ATK', '', 200000, 'D'),
(42, '12/04/2015', '18', 'Beli Perlengkapan', '', 200000, 'K'),
(43, '12/11/2015', '18', 'Pinjaman Bank', '', 10000000, 'D'),
(44, '12/11/2015', '20', 'Pinjaman Bank', '', 10000000, 'K');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
