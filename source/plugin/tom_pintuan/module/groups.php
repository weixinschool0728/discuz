<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;

$pagesize = 8;
$start = ($page-1)*$pagesize;	

$count = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");
$tuanListTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_list(" AND user_id={$__UserInfo['id']}  ","ORDER BY add_time DESC",$start,$pagesize);

$tuanList = array();
if(is_array($tuanListTmp) && !empty($tuanListTmp)){
    foreach ($tuanListTmp as $key => $value){
        $tuanList[$key] = $value;
        
        $tuanInfo = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_by_id($value['tuan_id']);
        $tuanList[$key]['tuanInfo'] = $tuanInfo;
        
        $goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($value['goods_id']);
        if(!preg_match('/^http/', $goodsInfo['goods_pic']) ){
            $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['goods_pic'];
        }else{
            $goods_pic = $goodsInfo['goods_pic'];
        }
        if($tuanInfo['tlevel'] == 2){
            $goodsInfo['tuan_price'] = $goodsInfo['tuan_price_2'];
        }else if($tuanInfo['tlevel'] == 3){
            $goodsInfo['tuan_price'] = $goodsInfo['tuan_price_3'];
        }
        $goodsInfo['goods_pic'] = $goods_pic;
        $tuanList[$key]['goodsInfo'] = $goodsInfo;
        
        $ordersinfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($value['order_id']);
        $tuanList[$key]['ordersinfo'] = $ordersinfo;
        
        $tuanList[$key]['tuanUrl'] = "plugin.php?id=tom_pintuan&mod=tuan&tlevel={$tuanInfo['tlevel']}&tuan_id=".$value['tuan_id'];
        $tuanList[$key]['orderUrl'] = "plugin.php?id=tom_pintuan&mod=ordersinfo&order_id=".$value['order_id'];
    }
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_pintuan&mod=groups&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_pintuan&mod=groups&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:groups");  

?>
