

# phpMyAdmin SQL Dump
# version 2.5.6-rc2
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Dec 11, 2010 at 02:35 PM
# Server version: 5.1.51
# PHP Version: 5.3.3
# 
# Database : `newpenny`
# 

#
# Dumping data for table `settings`
#

INSERT INTO `settings` VALUES (1, 'auction_peak_start', '9:00', 'The time (in 24 hour time) that the peak time should begin.');
INSERT INTO `settings` VALUES (2, 'auction_peak_end', '22:30', 'The time (in 24 hour time) that the peak time should end.');
INSERT INTO `settings` VALUES (3, 'bid_butler_time', '864000', 'The number of seconds from the auction closing that the bid butler bids should be placed.  We recommend setting this to at least 30 seconds.');
INSERT INTO `settings` VALUES (4, 'free_referral_bids', '20', 'The number of free bids a user receives for referring another user.  This only gets given when the new user purchase bids.');
INSERT INTO `settings` VALUES (5, 'site_live', 'yes', 'Use this setting to turn off the website for any reason.  Change the value to \'no\' to turn off the website, and \'yes\' to turn the website on.');
INSERT INTO `settings` VALUES (6, 'free_registeration_bids', '20', 'The number of free bids a user gets for registering on the website (given once their account is activated.)');
INSERT INTO `settings` VALUES (7, 'free_bid_packages_bids', '30%', 'The number of free bids a user gets the first time they purchase a bid package.  Alternatively make this a % for the user to receive x% more bids instead.');
INSERT INTO `settings` VALUES (8, 'free_won_auction_bids', '5', 'The number of free bids a user gets for paying for an auction. Alternatively make this a % for the user to receive a % of the bids back that they bid on the auction.');
INSERT INTO `settings` VALUES (9, 'offline_message', 'We are currently experiencing a higher number of visitors than usual. The website is currently down, please try again later.', 'The message that should be displayed when the website is offline.');
INSERT INTO `settings` VALUES (10, 'default_meta_title', 'Reverse Auctions', 'Used as part of Search Engine Optimisation, this is the default meta title.');
INSERT INTO `settings` VALUES (11, 'default_meta_description', '', 'Used as part of Search Engine Optimisation, this is the default meta description.');
INSERT INTO `settings` VALUES (12, 'default_meta_keywords', '', 'Used as part of Search Engine Optimisation, this is the default meta keywords.');
INSERT INTO `settings` VALUES (13, 'user_invite_message', 'Hi There2\\n\\nSign up at SITENAME to receive great deals on products.\\n\\nURL\\n\\nCheck it out if you can!\\nSENDER', 'This is the default message that the user will send when inviting friends to the website.');
INSERT INTO `settings` VALUES (14, 'autolist_expire_time', '1440', 'This is the number of minutes after an auction has closed that an autolist will set the expire time.  This time will be the current time (unless an autolist delay is used), plus the number of minutes set here.');
INSERT INTO `settings` VALUES (15, 'autobid_time', '60', 'The number of seconds from the auction closing that the autobidders should start bidding.  Set this to 0 to make them always bid.');
INSERT INTO `settings` VALUES (16, 'mark_up', '30', 'This is the mark up that you aim to make on each product.  The number should be a percentage, e.g. \'30\' for 30%.  This is used to automatically calculate the minimum price. ');
INSERT INTO `settings` VALUES (17, 'autolist_delay_time', '0', 'Use the autolist delay time to delay the start time of auto relisting auctions.  This feature will delay the start time of the new auction by the number of minutes set here.');
INSERT INTO `settings` VALUES (18, 'local_license_key', '<?PHP\n/*--\nMTEyMThlOGNmZDMwOGU1MDhlNDMyMmZm\nNTJjYTNlN2V8MTI4NzAzNjAwMHw2ZWQ0\nYjE4ZDdhYjE5Yzc3MmM3OTc5NDFlODc5\nOGYzMHw4MWYyNTk3Zjc3OTc3NmQxOTNj\nZmFmNjMxYTRkNzc3OHwwNDRhZTdkODk0\nNTI4OTgyODlmMzNkMTFhOWZhNGQyOSxk\nOTE1OWU4OWVlZTVmZTAwYTA5ZTA0YzIz\nMzRiMzc0MnxmNjk0YmRkZDBmOGFkOWY1\nNGVlN2NhY2E0NjM4ZGNjZXxlbXB0eXxu\nZXZlcnxhOWIyMTRjNTU3ZDBhZjQ3MGUx\nZjdhMDY0MTJmMTVkM3x8ZWJiNTE1ZDk3\nMGIyMGE3MmY0OGUyZWI5NmJjMDExOTV8\nMC40MDAwNzAwMCAxMjg2MjA4MzAzfGYz\nZDhjZjEwZGUzYzg2MzM4NjNmZDNmYTk4\nMjU5NGZifDB8MjBlMmIzYzUzNTE4MDdj\nMzllODEzMWNmNDQwZWFlZjl8djEuNi4w\nLTEyNzM1OTM1OTkrdjEuNS4wLTEyNzA1\nNjk1OTkrdjEuNC4wLTEyNjUxMjk5OTkr\ndjEuMy4wLTEyNTk1MTM5OTkrdjEuMi4w\nLTEyNTczNTM5OTkrdjEuMS4wLTEyNTEz\nODg3OTkrdjEuMC4wLTEyNTAwOTI3OTl8\nNWRjOGNiOWRmNmEyZTY2MmZjZGE2NjYz\nNzM4NmU1YTE=\n--*/\n?>', 'Private, do not edit.');
INSERT INTO `settings` VALUES (19, 'phppa_version', '2.4.1', 'Internal use only, modifying this value can cause software instability!');
    

#
# Dumping data for table `currencies`
#

INSERT INTO `currencies` VALUES (1, 'vnd', '1.0000');
    


#
# Dumping data for table `genders`
#

INSERT INTO `genders` VALUES (1, 'Nam');
INSERT INTO `genders` VALUES (2, 'N&#7919;');
    