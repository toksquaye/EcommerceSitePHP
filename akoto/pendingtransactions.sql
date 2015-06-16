-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 20, 2014 at 11:58 PM
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
-- Table structure for table `pendingtransactions`
--

CREATE TABLE IF NOT EXISTS `pendingtransactions` (
  `id` char(40) NOT NULL,
  `number` int(11) NOT NULL,
  `item_names` text NOT NULL,
  `item_prices` text NOT NULL,
  `item_codes` text NOT NULL,
  `item_qtys` text NOT NULL,
  `tax` float NOT NULL,
  `shipping` float NOT NULL,
  `shipdiscount` float NOT NULL,
  `insurance` float NOT NULL,
  `handling` float NOT NULL,
  `grandtotal` float NOT NULL,
  `status` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pendingtransactions`
--

INSERT INTO `pendingtransactions` (`id`, `number`, `item_names`, `item_prices`, `item_codes`, `item_qtys`, `tax`, `shipping`, `shipdiscount`, `insurance`, `handling`, `grandtotal`, `status`, `timestamp`) VALUES
('oluseun1050', 1, 'bagname bag4*', '0.20*', '1*', '1*', 0.02, 5, 0, 0, 0, 5.22, 'pending', '2014-11-21 04:51:17'),
('toksquaye2401', 3, 'bagname bag4*pillow2*pillow6*', '0.20*23.90*39.00*', '1*111002*111006*', '1*1*1*', 5.34, 5, 0, 0, 0, 73.44, 'pending', '2014-11-21 04:57:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pendingtransactions`
--
ALTER TABLE `pendingtransactions`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
