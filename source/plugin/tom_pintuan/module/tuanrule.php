<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tuanrule = discuzcode($pintuanConfig['tuanrule'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:tuanrule");  

?>
