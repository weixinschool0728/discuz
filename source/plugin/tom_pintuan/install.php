<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_tom_pintuan_address`;
CREATE TABLE `pre_tom_pintuan_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `default_id` int(11) DEFAULT '0',
  `xm` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `fielda` varchar(255) DEFAULT NULL,
  `type_id` int(11) DEFAULT '1',
  `province_id` int(11) DEFAULT '0',
  `city_id` int(11) DEFAULT '0',
  `area_id` int(11) DEFAULT '0',
  `area_str` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `pre_tom_pintuan_district`;
CREATE TABLE `pre_tom_pintuan_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `level` tinyint(3) unsigned DEFAULT '0',
  `upid` mediumint(8) unsigned DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_upid` (`upid`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_tom_pintuan_focuspic`;
CREATE TABLE `pre_tom_pintuan_focuspic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `picurl` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `fsort` int(11) DEFAULT '10',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_tom_pintuan_goods`;
CREATE TABLE `pre_tom_pintuan_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `cate_id` int(11) DEFAULT '0',
  `shop_id` int(11) DEFAULT '0',
  `goods_pic` varchar(255) DEFAULT NULL,
  `list_pic` varchar(255) DEFAULT NULL,
  `tuan_num` int(11) DEFAULT '0',
  `sales_num` int(11) DEFAULT '0',
  `virtual_sales_num` int(11) DEFAULT '0',
  `goods_num` int(11) DEFAULT '0',
  `xiangou_num` int(11) DEFAULT '0',
  `goods_discount` varchar(255) DEFAULT NULL,
  `market_price` decimal(10,2) DEFAULT '0.00',
  `tuanz_price` decimal(10,2) DEFAULT '0.00',
  `tuan_price` decimal(10,2) DEFAULT '0.00',
  `one_price` decimal(10,2) DEFAULT '0.00',
  `tuan_num_2` int(11) DEFAULT '0',
  `tuan_price_2` decimal(10,2) DEFAULT '0.00',
  `tuan_num_3` int(11) DEFAULT '0',
  `tuan_price_3` decimal(10,2) DEFAULT '0.00',
  `open_3_tuan` int(11) DEFAULT '0',
  `express_price` int(11) DEFAULT '0',
  `pics1` varchar(255) DEFAULT NULL,
  `pics2` varchar(255) DEFAULT NULL,
  `pics3` varchar(255) DEFAULT NULL,
  `tuan_hours` int(11) DEFAULT '24',
  `allow_num` int(11) DEFAULT '1',
  `goods_unit` varchar(255) DEFAULT NULL,
  `take_type` int(11) DEFAULT '1',
  `take_pwd` varchar(255) DEFAULT NULL,
  `describe` varchar(255) DEFAULT NULL,
  `share_title` varchar(255) DEFAULT NULL,
  `share_desc` varchar(255) DEFAULT NULL,
  `content` text,
  `is_show` int(11) DEFAULT '1',
  `paixu` int(11) DEFAULT '10000',
  `add_time` int(11) DEFAULT '0',
  `edit_time` int(11) DEFAULT '0',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT '0',
  `end_time` int(11) DEFAULT '0',
  `fieldb1` varchar(155) NOT NULL,
  `fieldb2` varchar(155) NOT NULL,
  `fieldb3` varchar(155) NOT NULL,
  `fieldb4` varchar(155) NOT NULL,
  `fieldb5` varchar(155) NOT NULL,
  `fieldb6` varchar(155) NOT NULL,
  `fieldb7` varchar(155) NOT NULL,
  `fieldb8` varchar(155) NOT NULL,
  `fieldb9` varchar(155) NOT NULL,
  `fieldb10` varchar(155) NOT NULL,
  `fieldb11` varchar(155) NOT NULL,
  `fieldb12` varchar(155) NOT NULL,
  `fieldb13` varchar(155) NOT NULL,
  `fieldb14` varchar(155) NOT NULL,
  `fieldb15` varchar(155) NOT NULL,
  `fieldb16` varchar(155) NOT NULL,
  `fieldb17` varchar(155) NOT NULL,
  `fieldb18` varchar(155) NOT NULL,
  `fieldb19` varchar(155) NOT NULL,
  `fieldb20` varchar(155) NOT NULL,
  `fieldb21` varchar(155) NOT NULL,
  `fieldb22` varchar(155) NOT NULL,
  `fieldb23` varchar(155) NOT NULL,
  `fieldb24` varchar(155) NOT NULL,
  `fieldb25` varchar(155) NOT NULL,
  `fieldb26` varchar(155) NOT NULL,
  `fieldb27` varchar(155) NOT NULL,
  `fieldb28` varchar(155) NOT NULL,
  `fieldb29` varchar(155) NOT NULL,
  `fieldb30` varchar(155) NOT NULL,
  `fieldba` varchar(155) NOT NULL,
  `fieldbb` varchar(155) NOT NULL,
  `fieldbc` varchar(155) NOT NULL,
  `fieldbd` varchar(155) NOT NULL,
  `fieldbe` varchar(155) NOT NULL,
  `fieldbf` varchar(155) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_is_show` (`is_show`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_tom_pintuan_order`;
CREATE TABLE `pre_tom_pintuan_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tstatus` int(11) DEFAULT '0',
  `order_no` varchar(255) DEFAULT NULL,
  `goods_id` int(11) DEFAULT '0',
  `take_type` int(11) DEFAULT '1',
  `goods_name` varchar(255) DEFAULT NULL,
  `goods_num` int(11) DEFAULT '0',
  `goods_price` decimal(10,2) DEFAULT '0.00',
  `pay_price` decimal(10,2) DEFAULT '0.00',
  `user_id` int(11) DEFAULT '0',
  `user_nickname` varchar(255) DEFAULT NULL,
  `user_openid` varchar(255) DEFAULT NULL,
  `xm` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `fielda` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `express_name` varchar(255) DEFAULT NULL,
  `express_no` varchar(255) DEFAULT NULL,
  `express_time` int(11) DEFAULT '0',
  `tuan_id` int(11) DEFAULT '0',
  `tuan_status` int(11) DEFAULT '0',
  `prepay_id` varchar(255) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `pay_time` int(11) DEFAULT '0',
  `order_time` int(11) DEFAULT '0',
  `order_beizu` varchar(255) DEFAULT NULL,
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  `fieldba` varchar(155) NOT NULL,
  `fieldbb` varchar(155) NOT NULL,
  `fieldbc` varchar(155) NOT NULL,
  `fieldbd` varchar(155) NOT NULL,
  `fieldbe` varchar(155) NOT NULL,
  `fieldbf` varchar(155) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_no` (`order_no`),
  KEY `idx_uid_status` (`user_id`,`order_status`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_tom_pintuan_shop`;
CREATE TABLE `pre_tom_pintuan_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `order_pwd` varchar(255) DEFAULT NULL,
  `manage_openid` varchar(255) DEFAULT NULL,
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_tom_pintuan_tuan`;
CREATE TABLE `pre_tom_pintuan_tuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `prolong_hours` int(11) DEFAULT '0',
  `tlevel` int(11) DEFAULT '1',
  `tuan_time` int(11) DEFAULT '0',
  `success_time` int(11) DEFAULT '0',
  `tuan_status` int(11) DEFAULT '1',
  `do_status` int(11) DEFAULT '0',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

        
DROP TABLE IF EXISTS `pre_tom_pintuan_tuan_team`;
CREATE TABLE `pre_tom_pintuan_tuan_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tuan_id` int(11) DEFAULT '0',
  `goods_id` int(11) DEFAULT '0',
  `order_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `type_id` int(11) DEFAULT '0',
  `add_time` int(11) DEFAULT '0',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_tuid_tyid` (`tuan_id`,`type_id`)
) ENGINE=MyISAM;
        
DROP TABLE IF EXISTS `pre_tom_pintuan_user`;
CREATE TABLE `pre_tom_pintuan_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `picurl` varchar(255) DEFAULT NULL,
  `add_time` int(11) DEFAULT '0',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_openid` (`openid`)
) ENGINE=MyISAM;
        
DROP TABLE IF EXISTS `pre_tom_pintuan_cate`;
CREATE TABLE `pre_tom_pintuan_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `picurl` varchar(255) DEFAULT NULL,
  `csort` int(11) DEFAULT '10',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_tom_pintuan_jgg`;
CREATE TABLE `pre_tom_pintuan_jgg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `picurl` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `tuannum` int(11) DEFAULT '10',
  `csort` int(11) DEFAULT '10',
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
        
EOF;

runquery($sql);

$finish = TRUE;
?>