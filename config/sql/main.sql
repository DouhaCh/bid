-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 19, 2010 at 07:44 PM
-- Server version: 5.1.36
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bids` decimal(30,2) NOT NULL,
  `price` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `accounts`
--


-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_address_type_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `suburb` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL,
  `phone` varchar(80) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address_type_id` (`user_address_type_id`),
  KEY `user_id` (`user_id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `addresses`
--


-- --------------------------------------------------------

--
-- Table structure for table `affiliates`
--

DROP TABLE IF EXISTS `affiliates`;
CREATE TABLE IF NOT EXISTS `affiliates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `credit` int(11) NOT NULL,
  `debit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `affiliates`
--


-- --------------------------------------------------------

--
-- Table structure for table `affiliate_codes`
--

DROP TABLE IF EXISTS `affiliate_codes`;
CREATE TABLE IF NOT EXISTS `affiliate_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `credit` decimal(30,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `affiliate_codes`
--


-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

DROP TABLE IF EXISTS `auctions`;
CREATE TABLE IF NOT EXISTS `auctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `max_end` tinyint(1) NOT NULL,
  `max_end_time` datetime NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `autolist` tinyint(1) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `peak_only` tinyint(1) NOT NULL,
  `nail_bitter` tinyint(1) NOT NULL,
  `penny` tinyint(1) NOT NULL,
  `hidden_reserve` decimal(30,2) NOT NULL,
  `autobids` int(11) NOT NULL,
  `random` decimal(30,2) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `winner_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `closed` tinyint(1) NOT NULL,
  `bid_debit` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `auctions`
--


-- --------------------------------------------------------

--
-- Table structure for table `auction_emails`
--

DROP TABLE IF EXISTS `auction_emails`;
CREATE TABLE IF NOT EXISTS `auction_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `auction_emails`
--


-- --------------------------------------------------------

--
-- Table structure for table `autobids`
--

DROP TABLE IF EXISTS `autobids`;
CREATE TABLE IF NOT EXISTS `autobids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `deploy` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `autobids`
--


-- --------------------------------------------------------

--
-- Table structure for table `bidbutlers`
--

DROP TABLE IF EXISTS `bidbutlers`;
CREATE TABLE IF NOT EXISTS `bidbutlers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `maximum_price` decimal(30,2) NOT NULL,
  `bids` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `bidbutlers`
--

INSERT INTO `bidbutlers` (`id`, `user_id`, `auction_id`, `minimum_price`, `maximum_price`, `bids`, `created`, `modified`) VALUES
(1, 1, 7, '0.50', '0.88', 2, '2008-10-24 13:24:06', '2008-10-24 13:24:06'),
(2, 1, 1, '0.00', '0.00', 0, '2008-10-28 09:01:49', '2008-10-28 09:01:49'),
(3, 3, 10, '1.00', '1000.00', 0, '2008-10-28 20:38:17', '2008-10-28 20:38:17'),
(4, 4, 10, '1.00', '1000.00', 0, '2008-10-28 20:41:20', '2008-10-28 20:41:20');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

DROP TABLE IF EXISTS `bids`;
CREATE TABLE IF NOT EXISTS `bids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `credit` int(11) NOT NULL,
  `debit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `user_id`, `auction_id`, `description`, `credit`, `debit`, `created`, `modified`) VALUES
(95, 3, 9, 'Single Bid', 0, 2, '2008-11-03 15:42:13', '2008-11-03 15:42:13');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `lft`, `rght`, `name`, `meta_description`, `meta_keywords`, `image`, `created`, `modified`) VALUES
(2, 0, 3, 4, 'Coffee & Espresso', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(3, 0, 5, 6, 'Television', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(4, 0, 7, 8, 'Cash and Coupons', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(5, 0, 9, 10, 'iPods, MP3 & Audio', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(6, 0, 11, 12, 'Consoles & Games', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(7, 0, 13, 14, 'House & Garden', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(8, 0, 15, 16, 'Laptops & Notebooks', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(9, 0, 17, 18, 'Cellphones & Telephones', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(10, 0, 19, 20, 'PCs & Accessories', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(11, 0, 21, 22, 'Photography', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(12, 0, 23, 24, 'GPS', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(13, 0, 25, 26, 'Watches & Sunglasses', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(14, 0, 27, 28, 'Health & Fitness', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(15, 0, 29, 30, 'Fragrances', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00'),
(16, 0, 31, 32, 'Kids Toys', '', '', '', '2008-09-29 12:02:00', '2008-09-29 12:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `created`, `modified`) VALUES
(1, 'US', 'United States', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `saving` decimal(30,2) NOT NULL,
  `coupon_type_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `coupons`
--


-- --------------------------------------------------------

--
-- Table structure for table `coupon_types`
--

DROP TABLE IF EXISTS `coupon_types`;
CREATE TABLE IF NOT EXISTS `coupon_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `coupon_types`
--

INSERT INTO `coupon_types` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Percentage', '2008-12-10 15:18:06', '2008-12-10 15:18:06'),
(2, 'Total Off', '2008-12-10 15:18:06', '2008-12-10 15:18:06'),
(3, 'Free Bids', '2008-12-10 15:18:06', '2008-12-10 15:18:06'),
(4, 'Percentage Free Bids', '2009-03-01 20:53:03', '0000-00-00 00:00:00'),
(5, 'Free Rewards', '2009-03-06 18:24:03', '2009-03-06 18:24:07'),
(6, 'Free Registration Bids', '2009-05-04 23:58:49', '2009-05-04 23:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `credits`
--

DROP TABLE IF EXISTS `credits`;
CREATE TABLE IF NOT EXISTS `credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `credit` decimal(30,2) NOT NULL,
  `debit` decimal(30,2) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `credits`
--


-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) NOT NULL,
  `rate` decimal(30,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency`, `rate`) VALUES
(1, 'USD', '1.0000');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `departments`
--


-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

DROP TABLE IF EXISTS `genders`;
CREATE TABLE IF NOT EXISTS `genders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `name`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_default_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `product_id`, `image`, `image_default_id`, `order`, `created`, `modified`) VALUES
(1, 1, '0b1c505f688d457796c522972f7541ed8a7bbd51.jpg', 0, 0, '2008-10-21 10:31:20', '2008-10-21 10:31:20'),
(2, 1, '84063b2d90511e94785d5a99bde2074a96841612.jpg', 0, 1, '2008-10-21 10:31:29', '2008-10-21 10:31:29'),
(3, 1, '99b3ccaf0e3efc5ac52d0cdb116206216219539f.jpg', 0, 2, '2008-10-21 10:31:37', '2008-10-21 10:31:37'),
(5, 2, 'fde59360d7804d2bde72b2119583e9c1a7a5d359.jpg', 0, 0, '2008-10-28 09:30:29', '2008-10-28 09:30:29'),
(6, 2, 'b076521359dc53f6e5c1e2db0838d2de1c4b2ba3.jpg', 0, 1, '2008-10-28 09:30:52', '2008-10-28 09:30:52');

-- --------------------------------------------------------

--
-- Table structure for table `image_defaults`
--

DROP TABLE IF EXISTS `image_defaults`;
CREATE TABLE IF NOT EXISTS `image_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `image_defaults`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `server_name` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `languages`
--


-- --------------------------------------------------------

--
-- Table structure for table `limits`
--

DROP TABLE IF EXISTS `limits`;
CREATE TABLE IF NOT EXISTS `limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `limits`
--


-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `auction_id`, `message`, `created`, `modified`) VALUES
(12, 5, '1 Single bid + 5p + 30 seconds', '2008-10-23 07:55:36', '2008-10-23 07:55:36'),
(13, 5, '', '2008-10-23 08:01:01', '2008-10-23 08:01:01'),
(14, 5, '', '2008-10-23 08:43:09', '2008-10-23 08:43:09'),
(17, 6, '1 Single bid + 5p + 30 seconds', '2008-10-27 09:46:44', '2008-10-27 09:46:44'),
(16, 2, '', '2008-10-23 09:56:52', '2008-10-23 09:56:52'),
(18, 7, '', '2008-10-28 09:07:35', '2008-10-28 09:07:35'),
(19, 9, '1 Single bid + 5p + 30 seconds', '2008-10-28 09:37:31', '2008-10-28 09:37:31'),
(20, 10, '2 Bidbutler + 5p + 30 seconds', '2008-10-28 20:37:39', '2008-10-28 20:37:39'),
(21, 3, '1 Single bid + 5p + 30 seconds', '2008-10-31 11:15:11', '2008-10-31 11:15:11');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `brief` text NOT NULL,
  `content` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `meta_description`, `meta_keywords`, `brief`, `content`, `created`, `modified`) VALUES
(1, 'first news', '', '', 'first news brief', 'first news content', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'second news', '', '', 'second news brief', 'second news content', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `sent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `newsletters`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) NOT NULL,
  `method` varchar(25) NOT NULL,
  `model` varchar(25) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fulfulled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bids` int(11) NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `gateway_url` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `bids`, `price`, `gateway_url`, `created`, `modified`) VALUES
(1, 'Basic Package', 100, '10.00', '', '2008-09-25 17:28:34', '2008-09-25 17:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `package_points`
--

DROP TABLE IF EXISTS `package_points`;
CREATE TABLE IF NOT EXISTS `package_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `package_points`
--


-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `top_show` tinyint(1) NOT NULL,
  `top_order` int(11) NOT NULL,
  `bottom_show` tinyint(1) NOT NULL,
  `bottom_order` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `title`, `meta_description`, `meta_keywords`, `content`, `slug`, `top_show`, `top_order`, `bottom_show`, `bottom_order`, `created`, `modified`) VALUES
(3, 'Privacy', 'Privacy', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '', '<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'privacy', 0, 0, 0, 0, '2010-08-12 11:32:16', '2010-08-12 11:32:16'),
(4, 'About', 'About', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '<p>\r\n	Sample About Us Page here. Can be edited from your Admin Panel under Manage Content -&gt; Pages. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'about', 0, 0, 0, 0, '2010-08-12 11:33:11', '2010-08-12 11:33:11'),
(5, 'Terms', 'Terms', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia volupta', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '<p>\r\n	Your Terms of Use go here. We do NOT provide a default template for this. You can edit your Terms of Use page (this page!) in your Admin Panel under Manage Content -&gt; Pages. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?v</p>', 'terms', 0, 0, 0, 0, '2010-08-12 11:34:13', '2010-08-12 11:34:13'),
(6, 'Guide', 'Guide', '', '', '<p>\r\n	Your Sample Guide/Intro page here.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>', 'guide', 0, 0, 0, 0, '2010-08-12 11:35:12', '2010-08-12 11:35:12'),
(7, 'Sitemap', 'Sitemap', '', '', '<p>\r\n	Sitemap generator coming soon! Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'sitemap', 0, 0, 0, 0, '2010-08-12 11:35:39', '2010-08-12 11:35:39'),
(8, 'Start', 'Start', '', '', '<p>\r\n	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', 'start', 0, 0, 0, 0, '2010-08-12 11:37:47', '2010-08-12 11:37:47');

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

DROP TABLE IF EXISTS `points`;
CREATE TABLE IF NOT EXISTS `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `points`
--


-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `brief` text NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `rrp` decimal(30,2) NOT NULL,
  `start_price` decimal(30,2) NOT NULL,
  `delivery_cost` decimal(30,2) NOT NULL,
  `delivery_information` text NOT NULL,
  `fixed` tinyint(1) NOT NULL,
  `fixed_price` decimal(30,2) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `autobid` tinyint(1) NOT NULL,
  `autobid_limit` int(11) NOT NULL,
  `limit_id` int(11) NOT NULL,
  `free` tinyint(1) NOT NULL,
  `stock` tinyint(1) NOT NULL,
  `stock_number` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `buy_now` decimal(30,2) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `meta_description`, `meta_keywords`, `brief`, `description`, `category_id`, `rrp`, `start_price`, `delivery_cost`, `delivery_information`, `fixed`, `fixed_price`, `minimum_price`, `autobid`, `autobid_limit`, `limit_id`, `free`, `stock`, `stock_number`, `code`, `buy_now`, `created`, `modified`, `deleted`) VALUES
(1, 'Example Auction (Don''t Bid)', 'META description here', 'META Keywords here, comma-separated', 'Lorem ipsum nostro vocent posidonium ut eum, ei quo error salutandi. Mea debitis detraxit rationibus ut, eros simul atomorum ea eam. Eu sit aliquam atomorum facilisis, est cu mutat oblique tractatos. Vel et tota vitae deseruisse, vis et accusata eleifend sapientem. An suscipit lobortis intellegebat his, affert volumus appellantur has id.', '<p>\r\n	You can use the WYSIWYG Editor to adjust the font <span style="font-size: 16px;">size</span>, <span style="color: rgb(0, 100, 0);">font</span> <span style="background-color: rgb(255, 0, 0);">color</span>, <em>format</em>, and much more - just like Microsoft Word but all from a regular browser interface.</p>\r\n<p>\r\n	Lorem ipsum nostro vocent posidonium ut eum, ei quo error salutandi. Mea debitis detraxit rationibus ut, eros simul atomorum ea eam. Eu sit aliquam atomorum facilisis, est cu mutat oblique tractatos. Vel et tota vitae deseruisse, vis et accusata eleifend sapientem. An suscipit lobortis intellegebat his, affert volumus appellantur has id.</p>\r\n<p>\r\n	<strong>His ea quidam voluptatibus, dolore verterem accommodare vim ex, mei te stet iisque interpretaris. Usu no nostrum argumentum, omnis reprimique consequuntur ex pri, illum clita melius id pro. Velit everti in vel, an eum magna adipisci comprehensam. Vix mutat dicunt splendide at, est ad dico graeci regione, per timeam urbanitas et. Ei impedit appetere est, pro zzril affert corpora in.</strong></p>\r\n', 2, '2000.00', '10.00', '16.00', 'Delivery information here', 0, '0.00', '100.00', 1, 9999, 0, 0, 0, 0, '', '1000.00', '2008-10-21 10:29:32', '2010-09-19 19:35:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

DROP TABLE IF EXISTS `referrals`;
CREATE TABLE IF NOT EXISTS `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `referrer_id` int(11) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `referrer_id` (`referrer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `referrals`
--


-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
CREATE TABLE IF NOT EXISTS `reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `reminders`
--


-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

DROP TABLE IF EXISTS `rewards`;
CREATE TABLE IF NOT EXISTS `rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rrp` decimal(30,2) NOT NULL,
  `points` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rewards`
--


-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `description`) VALUES
(1, 'auction_peak_start', '9:00', 'The time (in 24 hour time) that the peak time should begin.'),
(2, 'auction_peak_end', '22:30', 'The time (in 24 hour time) that the peak time should end.'),
(3, 'bid_butler_time', '864000', 'The number of seconds from the auction closing that the bid butler bids should be placed.  We recommend setting this to at least 30 seconds.'),
(4, 'free_referral_bids', '10', 'The number of free bids a user receives for referring another user.  This only gets given when the new user purchase bids.'),
(5, 'site_live', 'yes', 'Use this setting to turn off the website for any reason.  Change the value to ''no'' to turn off the website, and ''yes'' to turn the website on.'),
(6, 'free_registeration_bids', '5', 'The number of free bids a user gets for registering on the website (given once their account is activated.)'),
(7, 'free_bid_packages_bids', '30%', 'The number of free bids a user gets the first time they purchase a bid package.  Alternatively make this a % for the user to receive x% more bids instead.'),
(8, 'free_won_auction_bids', '5', 'The number of free bids a user gets for paying for an auction. Alternatively make this a % for the user to receive a % of the bids back that they bid on the auction.'),
(9, 'offline_message', 'We are currently experiencing a higher number of visitors than usual. The website is currently down, please try again later.', 'The message that should be displayed when the website is offline.'),
(10, 'default_meta_title', 'Reverse Auctions', 'Used as part of Search Engine Optimisation, this is the default meta title.'),
(11, 'default_meta_description', '', 'Used as part of Search Engine Optimisation, this is the default meta description.'),
(12, 'default_meta_keywords', '', 'Used as part of Search Engine Optimisation, this is the default meta keywords.'),
(13, 'user_invite_message', 'Hi There2\\n\\nSign up at SITENAME to receive great deals on products.\\n\\nURL\\n\\nCheck it out if you can!\\nSENDER', 'This is the default message that the user will send when inviting friends to the website.'),
(14, 'autolist_expire_time', '1440', 'This is the number of minutes after an auction has closed that an autolist will set the expire time.  This time will be the current time (unless an autolist delay is used), plus the number of minutes set here.'),
(15, 'autobid_time', '60', 'The number of seconds from the auction closing that the autobidders should start bidding.  Set this to 0 to disable.'),
(16, 'mark_up', '30', 'This is the mark up that you aim to make on each product.  The number should be a percentage, e.g. ''30'' for 30%.  This is used to automatically calculate the minimum price. '),
(17, 'autolist_delay_time', '0', 'Use the autolist delay time to delay the start time of auto relisting auctions.  This feature will delay the start time of the new auction by the number of minutes set here.');

-- --------------------------------------------------------

--
-- Table structure for table `setting_increments`
--

DROP TABLE IF EXISTS `setting_increments`;
CREATE TABLE IF NOT EXISTS `setting_increments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `lower_price` decimal(30,2) NOT NULL,
  `upper_price` decimal(30,2) NOT NULL,
  `bid_debit` int(11) NOT NULL,
  `price_increment` decimal(30,2) NOT NULL,
  `time_increment` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `setting_increments`
--

INSERT INTO `setting_increments` (`id`, `product_id`, `lower_price`, `upper_price`, `bid_debit`, `price_increment`, `time_increment`) VALUES
(1, 130, '0.00', '0.00', 2, '0.10', 30);

-- --------------------------------------------------------

--
-- Table structure for table `smartbids`
--

DROP TABLE IF EXISTS `smartbids`;
CREATE TABLE IF NOT EXISTS `smartbids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `smartbids`
--


-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
CREATE TABLE IF NOT EXISTS `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `extra` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`id`, `name`, `order`, `extra`) VALUES
(1, 'Google', 1, 0),
(2, 'Yahoo', 2, 0),
(3, 'Other (please state)', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `message`) VALUES
(1, 'Unpaid', 'This auction has not been paid for.  Please pay for the auction to continue with the transaction.'),
(2, 'Paid, Awaiting Shipment', 'We have received your payment and will be shipping the item shortly.'),
(3, 'Shipped & Completed', 'Your auction has been shipped.'),
(4, 'Refunded', 'This auction has been refunded.'),
(5, 'Declined', 'This auction has been declined.'),
(6, 'Investigating Problem', 'We are currently investigating a problem in relation to this auction.');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `brief` text NOT NULL,
  `description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `delivery_information` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `translations`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender_id` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `key` varchar(40) NOT NULL,
  `newsletter` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `autobidder` tinyint(1) NOT NULL,
  `source_id` int(11) NOT NULL,
  `source_extra` varchar(255) NOT NULL,
  `tax_number` varchar(255) NOT NULL,
  `bid_balance` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gender_id` (`gender_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `mobile`, `date_of_birth`, `gender_id`, `email`, `active`, `key`, `newsletter`, `admin`, `autobidder`, `source_id`, `source_extra`, `tax_number`, `bid_balance`, `ip`, `created`, `modified`) VALUES
(6, 'autobidder1', '', '', '', '', '0000-00-00', 0, '', 1, '', 0, 0, 1, 0, '', '', 0, '', '2008-11-02 08:32:24', '2008-11-02 08:32:24'),
(7, 'autobidder2', '', '', '', '', '0000-00-00', 0, '', 1, '', 0, 0, 1, 0, '', '', 0, '', '2008-11-02 08:32:34', '2008-11-02 08:32:34'),
(10, 'admin', 'a193a95db94cd44089953d0c4535119fdf102f19', 'admin', 'account', '', '1993-09-19', 1, 'info@phppennyauction.com', 1, '8414bc7284b3c18c7020903ccfcce77def6ef28f', 1, 1, 0, 1, '', '', 0, '127.0.0.1', '2010-09-19 19:31:25', '2010-09-19 19:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_address_types`
--

DROP TABLE IF EXISTS `user_address_types`;
CREATE TABLE IF NOT EXISTS `user_address_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_address_types`
--

INSERT INTO `user_address_types` (`id`, `name`) VALUES
(1, 'Billing'),
(2, 'Shipping');

-- --------------------------------------------------------

--
-- Table structure for table `watchlists`
--

DROP TABLE IF EXISTS `watchlists`;
CREATE TABLE IF NOT EXISTS `watchlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `watchlists`
--

INSERT INTO `watchlists` (`id`, `user_id`, `auction_id`, `created`, `modified`) VALUES
(3, 1, 6, '2008-10-30 23:42:44', '2008-10-30 23:42:44');


INSERT INTO `settings` (`id` , `name` , `value` , `description`)
VALUES (NULL , 'phppa_version', '2.4.1', 'Internal use only, modifying this value can cause software instability!');
