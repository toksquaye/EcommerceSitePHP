-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 10, 2014 at 04:22 PM
-- Server version: 5.6.20
-- PHP Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `akotos_boutique`
--

-- --------------------------------------------------------

--
-- Table structure for table `sales_tax`
--

CREATE TABLE IF NOT EXISTS `sales_tax` (
  `State` varchar(2) NOT NULL,
  `Tax` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales_tax`
--

INSERT INTO `sales_tax` (`State`, `Tax`) VALUES
('AK', 1.69),
('AL', 8.51),
('AR', 9.19),
('AZ', 8.17),
('CA', 8.41),
('CO', 7.39),
('CT', 6.35),
('DC', 5.75),
('DE', 0),
('FL', 6.62),
('GA', 6.97),
('HI', 4.35),
('IA', 6.78),
('ID', 6.03),
('IL', 8.16),
('IN', 7),
('KS', 8.15),
('KY', 6),
('LA', 8.89),
('MA', 6.25),
('MD', 6),
('ME', 5.5),
('MI', 6),
('MN', 7.19),
('MO', 7.58),
('MS', 7),
('MT', 0),
('NC', 6.9),
('ND', 6.55),
('NE', 6.79),
('NH', 0),
('NJ', 6.97),
('NM', 7.26),
('NV', 7.93),
('NY', 8.47),
('OH', 7.11),
('OK', 8.72),
('OR', 0),
('PA', 6.34),
('RI', 7),
('SC', 7.19),
('SD', 5.83),
('TN', 9.45),
('TX', 8.15),
('UT', 6.68),
('VA', 5.63),
('VT', 6.14),
('WA', 8.88),
('WI', 5.43),
('WV', 6.07),
('WY', 5.49);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sales_tax`
--
ALTER TABLE `sales_tax`
 ADD PRIMARY KEY (`State`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
