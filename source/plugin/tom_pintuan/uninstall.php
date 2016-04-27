<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_pintuan_address;
DROP TABLE IF EXISTS pre_tom_pintuan_district;
DROP TABLE IF EXISTS pre_tom_pintuan_focuspic;
DROP TABLE IF EXISTS pre_tom_pintuan_goods;
DROP TABLE IF EXISTS pre_tom_pintuan_order;
DROP TABLE IF EXISTS pre_tom_pintuan_tuan;
DROP TABLE IF EXISTS pre_tom_pintuan_tuan_team;
DROP TABLE IF EXISTS pre_tom_pintuan_user;
DROP TABLE IF EXISTS pre_tom_pintuan_cate;
DROP TABLE IF EXISTS pre_tom_pintuan_shop;

EOF;

runquery($sql);

$finish = TRUE;

?>