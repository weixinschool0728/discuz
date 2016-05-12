<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('TPL_DEFAULT', true);
$formhash = FORMHASH;
$pintuanConfig = $_G['cache']['plugin']['tom_pintuan'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;

if (CHARSET == 'gbk') {
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.gbk.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.utf8.php';
}

if(empty($_G['uid'])){
    showmessage('to_login', 'member.php?mod=logging&action=login', array(), array('showmsg' => true, 'login' => 1));
}

$shopInfo = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_by_bbs_uid($_G['uid']);

if(!$shopInfo){
    $pc_shop_no_power = lang('plugin/tom_pintuan','pc_shop_no_power');
    showmessage($pc_shop_no_power,'index.php',array(),array('refreshtime'=>5));exit;
}

if($_GET['mod'] == 'orders'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/shop/orders.php';
}else if($_GET['mod'] == 'goods'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/shop/goods.php';
}else if($_GET['mod'] == 'print'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/shop/print.php';
}
else{
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/shop/goods.php';
}

?>
