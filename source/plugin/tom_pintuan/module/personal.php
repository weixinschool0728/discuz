<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//��Ϲ����Ҹ���20151023
if($subscribeFlag == 2 && $pintuanConfig['yihe_followtx'] == 1){
    $showGuanzuBox = 1;
}
//end��Ϲ����Ҹ���20151023
$allCount = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");
$daifukuanCount = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND order_status=1 ");
$daishouhuoCount = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND order_status=4 ");


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:personal");  

?>
