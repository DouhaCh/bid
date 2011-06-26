

# phpMyAdmin SQL Dump
# version 2.5.6-rc2
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Dec 11, 2010 at 02:34 PM
# Server version: 5.1.51
# PHP Version: 5.3.3
# 
# Database : `newpenny`
# 

# --------------------------------------------------------

#
# Table structure for table `accounts`
#

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `bids` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `addresses`
#

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
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

# --------------------------------------------------------

#
# Table structure for table `affiliate_codes`
#

DROP TABLE IF EXISTS `affiliate_codes`;
CREATE TABLE `affiliate_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `credit` decimal(30,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `affiliates`
#

DROP TABLE IF EXISTS `affiliates`;
CREATE TABLE `affiliates` (
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

# --------------------------------------------------------

#
# Table structure for table `auction_emails`
#

DROP TABLE IF EXISTS `auction_emails`;
CREATE TABLE `auction_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

# --------------------------------------------------------

#
# Table structure for table `auctions`
#

DROP TABLE IF EXISTS `auctions`;
CREATE TABLE `auctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `max_end` tinyint(1) NOT NULL,
  `max_end_time` datetime NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `price_step` int(11) NOT NULL,
  `bp_cost` int(11) NOT NULL,
  `time_increment` tinyint(4) NOT NULL,
  `max_bid` int(11) NOT NULL,
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
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `deleted` (`deleted`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;

# --------------------------------------------------------

#
# Table structure for table `autobids`
#

DROP TABLE IF EXISTS `autobids`;
CREATE TABLE `autobids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `deploy` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=656330 DEFAULT CHARSET=utf8 AUTO_INCREMENT=656330 ;

# --------------------------------------------------------

#
# Table structure for table `bidbutlers`
#

DROP TABLE IF EXISTS `bidbutlers`;
CREATE TABLE `bidbutlers` (
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

# --------------------------------------------------------

#
# Table structure for table `bids`
#

DROP TABLE IF EXISTS `bids`;
CREATE TABLE `bids` (
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
) ENGINE=MyISAM AUTO_INCREMENT=26586 DEFAULT CHARSET=utf8 AUTO_INCREMENT=26586 ;

# --------------------------------------------------------

#
# Table structure for table `categories`
#

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `featured` (`featured`),
  KEY `featured_2` (`featured`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

# --------------------------------------------------------

#
# Table structure for table `comments`
#
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `time` date DEFAULT NULL,
  `emo` varchar(24) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;
    

# --------------------------------------------------------
#
# Table structure for table `countries`
#

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `coupon_types`
#

DROP TABLE IF EXISTS `coupon_types`;
CREATE TABLE `coupon_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

# --------------------------------------------------------

#
# Table structure for table `coupons`
#

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `saving` decimal(30,2) NOT NULL,
  `coupon_type_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Table structure for table `credits`
#

DROP TABLE IF EXISTS `credits`;
CREATE TABLE `credits` (
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

# --------------------------------------------------------

#
# Table structure for table `currencies`
#

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) NOT NULL,
  `rate` decimal(30,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `departments`
#

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

# --------------------------------------------------------

#
# Table structure for table `genders`
#

DROP TABLE IF EXISTS `genders`;
CREATE TABLE `genders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `image_defaults`
#

DROP TABLE IF EXISTS `image_defaults`;
CREATE TABLE `image_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

# --------------------------------------------------------

#
# Table structure for table `images`
#

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_default_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

# --------------------------------------------------------

#
# Table structure for table `languages`
#

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `server_name` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `limits`
#

DROP TABLE IF EXISTS `limits`;
CREATE TABLE `limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `messages`
#

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

# --------------------------------------------------------

#
# Table structure for table `news`
#

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `brief` text NOT NULL,
  `content` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

# --------------------------------------------------------

#
# Table structure for table `newsletters`
#

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE `newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `sent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Table structure for table `orders`
#

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) NOT NULL,
  `method` varchar(25) NOT NULL,
  `model` varchar(25) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fulfilled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `package_points`
#

DROP TABLE IF EXISTS `package_points`;
CREATE TABLE `package_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Table structure for table `packages`
#

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bids` int(11) NOT NULL,
  `price` decimal(30,2) NOT NULL,
  `gateway_url` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `pages`
#

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

# --------------------------------------------------------

#
# Table structure for table `phptraffica_conf`
#

DROP TABLE IF EXISTS `phptraffica_conf`;
CREATE TABLE `phptraffica_conf` (
  `variable` varchar(32) NOT NULL DEFAULT '',
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `phptraffica_conf_ipban`
#

DROP TABLE IF EXISTS `phptraffica_conf_ipban`;
CREATE TABLE `phptraffica_conf_ipban` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ip` bigint(11) NOT NULL DEFAULT '0',
  `range` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `last` date NOT NULL DEFAULT '0000-00-00',
  `count` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

# --------------------------------------------------------

#
# Table structure for table `phptraffica_conf_sites`
#

DROP TABLE IF EXISTS `phptraffica_conf_sites`;
CREATE TABLE `phptraffica_conf_sites` (
  `id` mediumint(9) NOT NULL,
  `table` varchar(100) NOT NULL,
  `site` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `trim` tinyint(1) NOT NULL,
  `crawler` tinyint(1) NOT NULL,
  `counter` tinyint(1) NOT NULL,
  `timediff` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `points`
#

DROP TABLE IF EXISTS `points`;
CREATE TABLE `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `product_tags`
#

DROP TABLE IF EXISTS `product_tags`;
CREATE TABLE `product_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_id` (`tag_id`,`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=126 DEFAULT CHARSET=utf8 AUTO_INCREMENT=126 ;

# --------------------------------------------------------

#
# Table structure for table `products`
#

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `brief` text NOT NULL,
  `description` text NOT NULL,
  `specification` text NOT NULL,
  `warranty` text NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

# --------------------------------------------------------

#
# Table structure for table `referrals`
#

DROP TABLE IF EXISTS `referrals`;
CREATE TABLE `referrals` (
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

# --------------------------------------------------------

#
# Table structure for table `reminders`
#

DROP TABLE IF EXISTS `reminders`;
CREATE TABLE `reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `rewards`
#

DROP TABLE IF EXISTS `rewards`;
CREATE TABLE `rewards` (
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

# --------------------------------------------------------

#
# Table structure for table `setting_increments`
#

DROP TABLE IF EXISTS `setting_increments`;
CREATE TABLE `setting_increments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `lower_price` decimal(30,2) NOT NULL,
  `upper_price` decimal(30,2) NOT NULL,
  `bid_debit` int(11) NOT NULL,
  `price_increment` decimal(30,2) NOT NULL,
  `time_increment` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

# --------------------------------------------------------

#
# Table structure for table `settings`
#

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

# --------------------------------------------------------

#
# Table structure for table `smartbids`
#

DROP TABLE IF EXISTS `smartbids`;
CREATE TABLE `smartbids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_id` (`auction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2448 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2448 ;

# --------------------------------------------------------

#
# Table structure for table `sources`
#

DROP TABLE IF EXISTS `sources`;
CREATE TABLE `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `extra` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

# --------------------------------------------------------

#
# Table structure for table `statuses`
#

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

# --------------------------------------------------------

#
# Table structure for table `tags`
#

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=156 DEFAULT CHARSET=utf8 AUTO_INCREMENT=156 ;

# --------------------------------------------------------

#
# Table structure for table `translations`
#

DROP TABLE IF EXISTS `translations`;
CREATE TABLE `translations` (
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

# --------------------------------------------------------

#
# Table structure for table `user_address_types`
#

DROP TABLE IF EXISTS `user_address_types`;
CREATE TABLE `user_address_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `users`
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
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
  `avatar` tinytext,
  `sid` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gender_id` (`gender_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

# --------------------------------------------------------

#
# Table structure for table `watchlists`
#

DROP TABLE IF EXISTS `watchlists`;
CREATE TABLE `watchlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
    