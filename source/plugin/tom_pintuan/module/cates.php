<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

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

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:cates");  

?>
