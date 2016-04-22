<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;
$type = intval($_GET['type'])>0? intval($_GET['type']):0;

$pagesize = 10;
$start = ($page-1)*$pagesize;	

$whereStr = " AND user_id={$__UserInfo['id']} ";
if($type == 1){
    $whereStr = " AND user_id={$__UserInfo['id']} AND order_status=1 ";
}else if($type == 2){
    $whereStr = " AND user_id={$__UserInfo['id']} AND order_status=4 ";
}

$count = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count($whereStr);
$orderListTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_list($whereStr,"ORDER BY order_time DESC",$start,$pagesize);

$orderList = array();
if(is_array($orderListTmp) && !empty($orderListTmp)){
    foreach ($orderListTmp as $key => $value){
        $orderList[$key] = $value;
        $goodsInfoTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($value['goods_id']);
        if(!preg_match('/^http/', $goodsInfoTmp['goods_pic']) ){
            $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfoTmp['goods_pic'];
        }else{
            $goods_pic = $goodsInfoTmp['goods_pic'];
        }
        $orderList[$key]['goods_pic'] = $goods_pic;
        $orderList[$key]['goods_unit'] = $goodsInfoTmp['goods_unit'];
        $orderList[$key]['order_time'] = dgmdate($value['order_time'], 'Y-m-d H:i:s',$tomSysOffset);
        $orderList[$key]['orderUrl'] = "plugin.php?id=tom_pintuan&mod=ordersinfo&order_id=".$value['id'];
        
        if($value['order_status'] == 1){
            if((TIMESTAMP - $value['order_time']) > 6900 ){
                $updateData = array();
                $updateData['order_status'] = 6;
                C::t('#tom_pintuan#tom_pintuan_order')->update($value['id'],$updateData);
                $orderList[$key]['order_status'] = 6;
                
                DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num+{$value['goods_num']} WHERE id='{$value['goods_id']}'", 'UNBUFFERED');
                DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num-{$value['goods_num']} WHERE id='{$value['goods_id']}'", 'UNBUFFERED');
                
            }
        }
    }
}

$ajaxPayUrl = "plugin.php?id=tom_pintuan:pay&act=pay&formhash=".FORMHASH;
$ajaxCancelPayUrl = "plugin.php?id=tom_pintuan:pay&act=cancelpay&formhash=".FORMHASH;
$ajaxQrshUrl = "plugin.php?id=tom_pintuan:pay&act=qrsh&formhash=".FORMHASH;
$backUrl = $_G['siteurl']."plugin.php?id=tom_pintuan&mod=orders";
$backUrl = urlencode($backUrl);

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_pintuan&mod=orders&page={$prePage}";
$nextPageUrl = "plugin.php?id=tom_pintuan&mod=orders&page={$nextPage}";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:orders");  

?>
