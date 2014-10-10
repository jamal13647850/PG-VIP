-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 28, 2014 at 01:39 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `giti`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_av_payments`
--

CREATE TABLE IF NOT EXISTS `wp_av_payments` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `paymenter_ip` varchar(50) NOT NULL,
  `payment_date` int(11) NOT NULL,
  `payment_cost` int(15) NOT NULL,
  `refNumber` int(15) NOT NULL,
  `payment_agancy` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `wp_av_payments`
--

INSERT INTO `wp_av_payments` (`ID`, `paymenter_ip`, `payment_date`, `payment_cost`, `refNumber`, `payment_agancy`) VALUES
(1, '::1', 1411894781, 1000, 145710, 'درگاه آزمایشی'),
(2, '::1', 1411897029, 1000, 113850, 'درگاه آزمایشی');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
