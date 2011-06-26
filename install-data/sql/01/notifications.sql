-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 17, 2011 at 09:07 PM
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
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  `type` varchar(25) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `created`, `modified`, `status`) VALUES
(2, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'undefined', 'Sáº£n pháº©m', '2011-01-14 21:25:47', '2011-01-18 02:36:48', 1),
(3, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'undefined', 'Sáº£n pháº©m', '2011-01-15 21:28:59', '2011-01-18 02:25:24', 1),
(4, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng á»Ÿ phiÃªn Ä‘áº¥u giÃ¡ sáº£n pháº©m <a href=''http://beta.1bid.vn/auctions/view/193''>Apple iPhone 3GS 32GB (BLACK)</a>, hÃ£y xyz ... .', 'Sáº£n pháº©m', '2011-01-17 21:37:08', '2011-01-18 02:22:39', 1),
(5, NULL, NULL, NULL, NULL, '2011-01-17 23:05:01', '2011-01-17 23:05:01', 1),
(15, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng á»Ÿ phiÃªn Ä‘áº¥u giÃ¡ sáº£n pháº©m <a href=''http://beta.1bid.vn/auctions/view/194''>Apple Macbook Air 1.6GHz Core 2 Duo Notebook</a>, hÃ£y xyz ... .', 'Sáº£n pháº©m', '2011-01-17 23:31:45', '2011-01-17 23:33:48', 1),
(16, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng á»Ÿ phiÃªn Ä‘áº¥u giÃ¡ sáº£n pháº©m <a href=''http://beta.1bid.vn/auctions/view/195''>Apple MB950B/A 21.5" iMac</a>, hÃ£y xyz ... .', 'Sáº£n pháº©m', '2011-01-17 23:33:29', '2011-01-18 02:26:12', 1),
(17, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng á»Ÿ phiÃªn Ä‘áº¥u giÃ¡ sáº£n pháº©m <a href=''http://beta.1bid.vn/auctions/view/196''>Apple MB950B/A 21.5" iMac</a>, hÃ£y xyz ... .', 'Sáº£n pháº©m', '2011-01-17 23:35:07', '2011-01-17 23:35:19', 1),
(18, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng á»Ÿ phiÃªn Ä‘áº¥u giÃ¡ sáº£n pháº©m <a href=''http://beta.1bid.vn/auctions/view/197''>Apple iPad 64GB Wifi</a>, hÃ£y xyz ... .', 'Sáº£n pháº©m', '2011-01-17 23:36:36', '2011-01-17 23:38:23', 0),
(19, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng á»Ÿ phiÃªn Ä‘áº¥u giÃ¡ sáº£n pháº©m <a href=''http://beta.1bid.vn/auctions/view/198''>&#7844;m &#273;un n&#432;&#7899;c Inox Sunhouse</a>, hÃ£y xyz ... .', 'Sáº£n pháº©m', '2011-01-17 23:41:35', '2011-01-18 02:47:55', 1),
(20, 29, 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng 1 sáº£n pháº©m', 'Báº¡n Ä‘Ã£ chiáº¿n tháº¯ng á»Ÿ phiÃªn Ä‘áº¥u giÃ¡ sáº£n pháº©m <a href=''http://beta.1bid.vn/auctions/view/199''>Apple iPad 64GB Wifi</a>, hÃ£y xyz ... .', 'Sáº£n pháº©m', '2011-01-17 23:43:09', '2011-01-18 02:51:19', 1);
