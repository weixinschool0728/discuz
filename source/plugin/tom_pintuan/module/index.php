<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;
$cate_id = intval($_GET['cate_id'])>0? intval($_GET['cate_id']):0;
$goods_name = !empty($_GET['goods_name'])? addslashes(urldecode($_GET['goods_name'])):'';

$pagesize = $pintuanConfig['index_num'];
$start = ($page-1)*$pagesize;	

$now=date('Y-m-d H:i:s');
$whereStr = " AND is_show=1 AND (shangjia_time<'{$now}' AND xiajia_time>'{$now}')";
if(!empty($cate_id)){
    $whereStr.= " AND cate_id=$cate_id ";
}

$count = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_count(" $whereStr ",$goods_name);
$goodsListTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_list(" $whereStr ","ORDER BY paixu ASC, add_time DESC",$start,$pagesize,$goods_name);
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
$prePageUrl = "plugin.php?id=tom_pintuan&mod=index&page={$prePage}&cate_id={$_GET['cate_id']}&goods_name={$_GET['goods_name']}";
$nextPageUrl = "plugin.php?id=tom_pintuan&mod=index&page={$nextPage}&cate_id={$_GET['cate_id']}&goods_name={$_GET['goods_name']}";

$focuspicListTmp = C::t('#tom_pintuan#tom_pintuan_focuspic')->fetch_all_list(""," ORDER BY fsort ASC,id DESC ",0,3);
$focuspicList = array();
foreach ($focuspicListTmp as $key => $value) {
    $focuspicList[$key] = $value;    
    if(!preg_match('/^http/', $value['picurl']) ){
        $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
    }else{
        $picurl = $value['picurl'];
    }
    $focuspicList[$key]['picurl'] = $picurl;
}

$cateListCount = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_all_count("");
$cateListLength = 8;
$showMoreCateBtn = 0;
if($cateListCount > 8){
    $cateListLength = 7;
    $showMoreCateBtn = 1;
}
$cateListTmp = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_all_list(""," ORDER BY csort ASC,id DESC ",0,$cateListLength);
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

$searchUrl = "plugin.php?id=tom_pintuan:api&act=get_search_url";

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:index");  

?>
