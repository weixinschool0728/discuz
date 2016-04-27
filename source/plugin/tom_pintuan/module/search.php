<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;

$goods_name = !empty($_GET['goods_name'])? addslashes(urldecode($_GET['goods_name'])):'';
$cate_id = intval($_GET['cate_id'])>0? intval($_GET['cate_id']):0;
$province_id = intval($_GET['province_id'])>0? intval($_GET['province_id']):0;
$city_id = intval($_GET['city_id'])>0? intval($_GET['city_id']):0;
$area_id = intval($_GET['area_id'])>0? intval($_GET['area_id']):0;

$show_list_box = 1;

$shopWhereStr = "";
$shopIdsArr = array();
if(!empty($province_id)){
    $shopWhereStr.= " AND province_id=$province_id ";
}
if(!empty($city_id)){
    $shopWhereStr.= " AND city_id=$city_id ";
}
if(!empty($area_id)){
    $shopWhereStr.= " AND area_id=$area_id ";
}
if(!empty($shopWhereStr)){
    $shopListTmp = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_list($shopWhereStr," ORDER BY id DESC ",0,10000);
    if(is_array($shopListTmp) && !empty($shopListTmp)){
        foreach ($shopListTmp as $key => $value){
            $shopIdsArr[] = $value['id'];
        }
    }else{
        $show_list_box = 2;
    }
}

$pagesize = 8;
$start = ($page-1)*$pagesize;	

$whereStr = " AND is_show=1 ";
if(!empty($cate_id)){
    $whereStr.= " AND cate_id=$cate_id ";
}
if(!empty($shopIdsArr)){
    $whereStr.= " AND shop_id IN(".  implode(",", $shopIdsArr).") ";
}

$count = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_count(" $whereStr ",$goods_name);
$goodsListTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_list(" $whereStr ","ORDER BY paixu ASC, add_time DESC",$start,$pagesize,$goods_name);
$goodsList = array();
if(is_array($goodsListTmp) && !empty($goodsListTmp)){
    foreach ($goodsListTmp as $key => $value) {
        $goodsList[$key] = $value;
        if(!preg_match('/^http/', $value['list_pic']) ){
            $list_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['list_pic'];
        }else{
            $list_pic = $value['list_pic'];
        }
        $goodsList[$key]['list_pic'] = $list_pic;
    }
}else{
    $show_list_box = 2;
}

$showNextPage = 1;
if(($start + $pagesize) >= $count){
    $showNextPage = 0;
}
$allPageNum = ceil($count/$pagesize);
$prePage = $page - 1;
$nextPage = $page + 1;
$prePageUrl = "plugin.php?id=tom_pintuan&mod=search&page={$prePage}&cate_id={$_GET['cate_id']}&goods_name={$_GET['goods_name']}&province_id={$province_id}&city_id={$city_id}&area_id={$area_id}";
$nextPageUrl = "plugin.php?id=tom_pintuan&mod=search&page={$nextPage}&cate_id={$_GET['cate_id']}&goods_name={$_GET['goods_name']}&province_id={$province_id}&city_id={$city_id}&area_id={$area_id}";

$searchUrl = "plugin.php?id=tom_pintuan:api&act=get_search_url";

$cateListTmp = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_all_list(""," ORDER BY csort ASC,id DESC ",0,100);
$cateList = array();
foreach ($cateListTmp as $key => $value) {
    $cateList[$key] = $value;    
    if(!preg_match('/^http/', $value['picurl']) ){
        $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
    }else{
        $picurl = $value['picurl'];
    }
    $cateList[$key]['picurl'] = $picurl;  
}
$cate_name = '';
$cateInfo = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_by_id($cate_id);
if($cateInfo){
    $cate_name = $cateInfo['name'];
}


$provinceList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_level(1);
$search_province_id = $province_id;
if($province_id == 0){
    foreach ($provinceList as $key => $value){
        $search_province_id = $value['id'];
        break;
    }
}
$cityList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($search_province_id);
$search_city_id = $city_id;
if($city_id == 0){
    foreach ($cityList as $key => $value){
        $search_city_id = $value['id'];
        break;
    }
}
$areaList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($search_city_id);

$provinceStr = "";
$cityStr = "";
$areaStr = "";
$provinceInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($province_id);
$cityInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($city_id);
$areaInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($area_id);
if($provinceInfo){
    $provinceStr = $provinceInfo['name'];
}
if($cityInfo){
    $cityStr = $cityInfo['name'];
}
if($areaInfo){
    $areaStr = $areaInfo['name'];
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:search");  

?>
