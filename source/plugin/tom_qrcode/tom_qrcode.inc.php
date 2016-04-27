<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$data = isset($_GET["data"])? addslashes(urldecode($_GET["data"])):"";
$size = isset($_GET["size"])? intval($_GET['size']):5;

include DISCUZ_ROOT.'./source/plugin/tom_qrcode/phpqrcode/phpqrcode.php';
$data = addslashes(urldecode($_GET["data"]));
if(!empty($data)){
    QRcode::png($data,false,'H',$size,2);
}else{
    echo 'NULL';exit;
}

