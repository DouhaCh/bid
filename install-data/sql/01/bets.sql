-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 11, 2011 at 01:02 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `newpenny`
--

-- --------------------------------------------------------

--
-- Table structure for table `bets`
--

CREATE TABLE IF NOT EXISTS `bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `bids` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `bets`
--

INSERT INTO `bets` (`id`, `user_id`, `auction_id`, `bids`, `value`, `active`) VALUES
(1, 29, 4, 0, 0, 0),
(2, 29, 0, 0, 0, 0),
(3, 29, 0, 0, 0, 0),
(4, 262, 262, 0, 0, 0),
(5, 262, 262, 0, 0, 0),
(6, 29, 262, 0, 0, 0),
(7, 29, 262, 0, 0, 0),
(8, 29, 262, 400, 0, 0),
(9, 34, 262, 500, 1, 0);
