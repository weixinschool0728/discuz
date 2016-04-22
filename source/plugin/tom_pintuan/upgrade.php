<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

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

$tom_pintuan_order_field = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_field();

if (!isset($tom_pintuan_order_field['take_type'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_pintuan_order')." ADD `take_type` int(11) DEFAULT '1';\n";
}
if (!isset($tom_pintuan_order_field['tuan_status'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_pintuan_order')." ADD `tuan_status` int(11) DEFAULT '0';\n";
}

$tom_pintuan_tuan_field = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_all_field();

if (!isset($tom_pintuan_tuan_field['tlevel'])) {
    $sql .= "ALTER TABLE ".DB::table('tom_pintuan_tuan')." ADD `tlevel` int(11) DEFAULT '1';\n";
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

EOF;

runquery($sql);

$finish = TRUE;

?>