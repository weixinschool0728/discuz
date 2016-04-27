<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/plugin');

if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
    
    $sql = '';

    $tom_pintuan_goods_field = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_field();

    if (!isset($tom_pintuan_goods_field['tuan_num_2'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `tuan_num_2` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['tuan_price_2'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `tuan_price_2` decimal(10,2) DEFAULT '0.00';\n";
    }
    if (!isset($tom_pintuan_goods_field['tuan_num_3'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `tuan_num_3` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['tuan_price_3'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `tuan_price_3` decimal(10,2) DEFAULT '0.00';\n";
    }
    if (!isset($tom_pintuan_goods_field['take_type'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `take_type` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_pintuan_goods_field['take_pwd'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `take_pwd` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_pintuan_goods_field['cate_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `cate_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['shop_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `shop_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['xiangou_num'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `xiangou_num` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['tuanz_price'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `tuanz_price` decimal(10,2) DEFAULT '0.00';\n";
    }
    if (!isset($tom_pintuan_goods_field['open_3_tuan'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `open_3_tuan` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['express_price'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `express_price` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['paixu'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `paixu` int(11) DEFAULT '10000';\n";
    }
    if (!isset($tom_pintuan_goods_field['tuanz_price_num'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `tuanz_price_num` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['only_one_buy'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `only_one_buy` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['clicks'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `clicks` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_goods_field['express_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_goods')." ADD `express_id` int(11) DEFAULT '0';\n";
    }

    $tom_pintuan_order_field = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_field();

    if (!isset($tom_pintuan_order_field['take_type'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_order')." ADD `take_type` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_pintuan_order_field['tuan_status'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_order')." ADD `tuan_status` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_order_field['order_beizu'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_order')." ADD `order_beizu` varchar(255) DEFAULT NULL;\n";
    }
    if (!isset($tom_pintuan_order_field['express_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_order')." ADD `express_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_order_field['qianshou_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_order')." ADD `qianshou_time` int(11) DEFAULT '0';\n";
    }

    $tom_pintuan_tuan_field = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_all_field();

    if (!isset($tom_pintuan_tuan_field['tlevel'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_tuan')." ADD `tlevel` int(11) DEFAULT '1';\n";
    }
    if (!isset($tom_pintuan_tuan_field['success_time'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_tuan')." ADD `success_time` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_tuan_field['do_status'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_tuan')." ADD `do_status` int(11) DEFAULT '0';\n";
    }

    if (!empty($sql)) {
        runquery($sql);
    }

    $sql = <<<EOF

    CREATE TABLE IF NOT EXISTS `pre_tom_pintuan_cate` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `picurl` varchar(255) DEFAULT NULL,
      `csort` int(11) DEFAULT '10',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

    CREATE TABLE IF NOT EXISTS `pre_tom_pintuan_shop` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `bbs_uid` int(11) DEFAULT '0',
      `name` varchar(255) DEFAULT NULL,
      `tel` varchar(255) DEFAULT NULL,
      `logo` varchar(255) DEFAULT NULL,
      `address` varchar(255) DEFAULT NULL,
      `order_pwd` varchar(255) DEFAULT NULL,
      `manage_openid` varchar(255) DEFAULT NULL,
      `province_id` int(11) DEFAULT '0',
      `city_id` int(11) DEFAULT '0',
      `area_id` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

    CREATE TABLE IF NOT EXISTS `pre_tom_pintuan_express` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) DEFAULT NULL,
      `default_price` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

    CREATE TABLE IF NOT EXISTS `pre_tom_pintuan_express_item` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `express_id` int(11) DEFAULT '0',
      `province_id` int(11) DEFAULT '0',
      `province_name` varchar(255) DEFAULT NULL,
      `city_id` int(11) DEFAULT '0',
      `city_name` varchar(255) DEFAULT NULL,
      `area_id` int(11) DEFAULT '0',
      `area_name` varchar(255) DEFAULT NULL,
      `express_price` int(11) DEFAULT '0',
      `part1` varchar(255) DEFAULT NULL,
      `part2` varchar(255) DEFAULT NULL,
      `part3` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM;

EOF;

    runquery($sql);


    $sql = '';
    $tom_pintuan_shop_field = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_field();

    if (!isset($tom_pintuan_shop_field['bbs_uid'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_shop')." ADD `bbs_uid` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_shop_field['province_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_shop')." ADD `province_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_shop_field['city_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_shop')." ADD `city_id` int(11) DEFAULT '0';\n";
    }
    if (!isset($tom_pintuan_shop_field['area_id'])) {
        $sql .= "ALTER TABLE ".DB::table('tom_pintuan_shop')." ADD `area_id` int(11) DEFAULT '0';\n";
    }

    if (!empty($sql)) {
        runquery($sql);
    }

    echo 'OK';exit;
    
}else{
    exit('Access Denied');
}



?>