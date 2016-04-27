<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;
$shop_id = intval($_GET['shop_id'])>0? intval($_GET['shop_id']):0;

$shopInfo = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_by_id($shop_id);

if(!preg_match('/^http/', $shopInfo['logo']) ){
    $shop_logo = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$shopInfo['logo'];
}else{
    $shop_logo = $shopInfo['logo'];
}
$sun_sales_num = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_sun_sales_num(" AND shop_id=$shop_id ");


$pagesize = 20;
$start = ($page-1)*$pagesize;	

$whereStr = " AND is_show=1 ";
if(!empty($shop_id)){
    $whereStr.= " AND shop_id=$shop_id ";
}

$count = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_count(" $whereStr ","");
$goodsListTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_list(" $whereStr ","ORDER BY paixu ASC, add_time DESC",$start,$pagesize,"");
$goodsList = array();
foreach ($goodsListTmp as $key => $value) {
    $goodsList[$key] = $value;
    if(!preg_match('/^http/', $value['list_pic']) ){
        $list_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['list_pic'];
    }else{
        $list_pic = $value['list_pic'];
    }
    $goodsList[$key]['list_pic'] = $list_pic;
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_pintuan&mod=shop&page={$prePage}&shop_id={$_GET['shop_id']}";
$nextPageUrl = "plugin.php?id=tom_pintuan&mod=shop&page={$nextPage}&shop_id={$_GET['shop_id']}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:shop");  

?>
