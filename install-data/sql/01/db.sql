/*
SQLyog Ultimate v8.61 
MySQL - 5.0.51b-community-nt-log : Database - newpenny
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `accounts` */

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `bids` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `addresses` */

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL auto_increment,
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
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `address_type_id` (`user_address_type_id`),
  KEY `user_id` (`user_id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `affiliate_codes` */

CREATE TABLE `affiliate_codes` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `credit` decimal(30,2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `affiliates` */

CREATE TABLE `affiliates` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `credit` int(11) NOT NULL,
  `debit` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `auction_emails` */

CREATE TABLE `auction_emails` (
  `id` int(11) NOT NULL auto_increment,
  `auction_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `auctions` */

CREATE TABLE `auctions` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `parent_id` int(11) default NULL,
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
  `beginner` tinyint(1) NOT NULL,
  `reverse` tinyint(1) NOT NULL,
  `autobids` int(11) NOT NULL,
  `random` decimal(30,2) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `winner_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `closed` tinyint(1) NOT NULL,
  `closed_status` tinyint(4) NOT NULL,
  `bid_debit` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `product_id` (`product_id`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

/*Table structure for table `autobids` */

CREATE TABLE `autobids` (
  `id` int(11) NOT NULL auto_increment,
  `auction_id` int(11) NOT NULL,
  `deploy` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=656330 DEFAULT CHARSET=utf8;

/*Table structure for table `bidbutlers` */

CREATE TABLE `bidbutlers` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `minimum_price` decimal(30,2) NOT NULL,
  `maximum_price` decimal(30,2) NOT NULL,
  `bids` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `bids` */

CREATE TABLE `bids` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `credit` int(11) NOT NULL,
  `debit` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26518 DEFAULT CHARSET=utf8;

/*Table structure for table `categories` */

CREATE TABLE `categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `featured` (`featured`),
  KEY `featured_2` (`featured`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Table structure for table `countries` */

CREATE TABLE `countries` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `coupon_types` */

CREATE TABLE `coupon_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `coupons` */

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(255) NOT NULL,
  `saving` decimal(30,2) NOT NULL,
  `coupon_type_id` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `credits` */

CREATE TABLE `credits` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `credit` decimal(30,2) NOT NULL,
  `debit` decimal(30,2) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `currencies` */

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL auto_increment,
  `currency` varchar(255) NOT NULL,
  `rate` decimal(30,4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `departments` */

CREATE TABLE `departments` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `genders` */

CREATE TABLE `genders` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `image_defaults` */

CREATE TABLE `image_defaults` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `images` */

CREATE TABLE `images` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_default_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

/*Table structure for table `languages` */

CREATE TABLE `languages` (
  `id` int(11) NOT NULL auto_increment,
  `language` varchar(255) NOT NULL,
  `server_name` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `limits` */

CREATE TABLE `limits` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `messages` */

CREATE TABLE `messages` (
  `id` int(11) NOT NULL auto_increment,
  `auction_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Table structure for table `news` */

CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `brief` text NOT NULL,
  `content` text NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `newsletters` */

CREATE TABLE `newsletters` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `sent` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `orders` */

CREATE TABLE `orders` (
  `id` int(11) NOT NULL auto_increment,
  `transaction_id` varchar(50) NOT NULL,
  `method` varchar(25) NOT NULL,
  `model` varchar(25) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fulfilled` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `package_points` */

CREATE TABLE `package_points` (
  `id` int(11) NOT NULL auto_increment,
  `package_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `packages` */

CREATE TABLE `packages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `bids` int(11) NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `gateway_url` varchar(255) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `pages` */

CREATE TABLE `pages` (
  `id` int(11) NOT NULL auto_increment,
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
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `phptraffica_conf` */

CREATE TABLE `phptraffica_conf` (
  `variable` varchar(32) NOT NULL default '',
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `phptraffica_conf_ipban` */

CREATE TABLE `phptraffica_conf_ipban` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `ip` bigint(11) NOT NULL default '0',
  `range` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `last` date NOT NULL default '0000-00-00',
  `count` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Table structure for table `phptraffica_conf_sites` */

CREATE TABLE `phptraffica_conf_sites` (
  `id` mediumint(9) NOT NULL,
  `table` varchar(100) NOT NULL,
  `site` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `trim` tinyint(1) NOT NULL,
  `crawler` tinyint(1) NOT NULL,
  `counter` tinyint(1) NOT NULL,
  `timediff` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `points` */

CREATE TABLE `points` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `product_tags` */

CREATE TABLE `product_tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag_id` (`tag_id`,`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;

/*Table structure for table `products` */

CREATE TABLE `products` (
  `id` int(11) NOT NULL auto_increment,
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
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `category_id` (`category_id`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

/*Table structure for table `referrals` */

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `referrer_id` int(11) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `referrer_id` (`referrer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `reminders` */

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL auto_increment,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `rewards` */

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rrp` decimal(30,2) NOT NULL,
  `points` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `setting_increments` */

CREATE TABLE `setting_increments` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `lower_price` decimal(30,2) NOT NULL,
  `upper_price` decimal(30,2) NOT NULL,
  `bid_debit` int(11) NOT NULL,
  `price_increment` decimal(30,2) NOT NULL,
  `time_increment` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `settings` */

CREATE TABLE `settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Table structure for table `smartbids` */

CREATE TABLE `smartbids` (
  `id` int(11) NOT NULL auto_increment,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2448 DEFAULT CHARSET=utf8;

/*Table structure for table `sources` */

CREATE TABLE `sources` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `extra` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `statuses` */

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `tags` */

CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=utf8;

/*Table structure for table `translations` */

CREATE TABLE `translations` (
  `id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `brief` text NOT NULL,
  `description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `delivery_information` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `language_id` (`language_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `user_address_types` */

CREATE TABLE `user_address_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
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
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `avatar` tinytext,
  `sid` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gender_id` (`gender_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Table structure for table `watchlists` */

CREATE TABLE `watchlists` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
