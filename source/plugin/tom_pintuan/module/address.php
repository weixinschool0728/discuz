<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act        = isset($_GET['act'])? addslashes($_GET['act']):"";

$addressUrl = "plugin.php?id=tom_pintuan&mod=address";

if($act == 'add'){
    
    $bstatus  = isset($_GET['bstatus'])? intval($_GET['bstatus']):0;
    $goods_id   = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;
    $tstatus = isset($_GET['tstatus'])? intval($_GET['tstatus']):0;
    $tlevel    = isset($_GET['tlevel'])? intval($_GET['tlevel']):1;
    $tuan_id   = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
    
    $provinceList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_level(1);
    
    $ajaxSaveUrl = "plugin.php?id=tom_pintuan&mod=address&act=addsave";
    $buyUrl = "plugin.php?id=tom_pintuan&mod=buy&showwxpaytitle=1&tstatus={$tstatus}&tlevel={$tlevel}&tuan_id={$tuan_id}&goods_id=".$goods_id;
    
    $goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($goods_id);
    if(!$goodsInfo){
        $bstatus = 0;
    }
    //获取共享收货地址js函数参数
//    $tools = new WxPayJsApiPay();
//    $NonceStr= WxPayApi::getNonceStr();//随机字符串
//    $appid = $weixinClass->get_appid();
//    $access_token = $weixinClass->get_access_token();
//    $editAddress = $tools->GetEditAddressParameters($appid,$access_token,$NonceStr);    
//    print_r($editAddress);
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_pintuan:addressadd"); 
    
}else if($act == 'addsave' && $_GET['formhash'] == FORMHASH){
    
    $default    = isset($_GET['adddefault'])? intval($_GET['adddefault']):0;
    $xm         = isset($_GET['addxm'])? daddslashes(diconv(urldecode($_GET['addxm']),'utf-8')):'';
    $tel        = isset($_GET['addtel'])? daddslashes(diconv(urldecode($_GET['addtel']),'utf-8')):'';
    $province   = isset($_GET['province'])? intval($_GET['province']):0;
    $city       = isset($_GET['city'])? intval($_GET['city']):0;
    $area       = isset($_GET['area'])? intval($_GET['area']):0;
    $type       = isset($_GET['addtype'])? intval($_GET['addtype']):0;
    $info       = isset($_GET['addinfo'])? daddslashes(diconv(urldecode($_GET['addinfo']),'utf-8')):'';
    
    if($default == 1){
        $addressListTmp = C::t('#tom_pintuan#tom_pintuan_address')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND default_id=1 ","ORDER BY id DESC",0,100);
        if(is_array($addressListTmp) && !empty($addressListTmp)){
            foreach ($addressListTmp as $key => $value){
                $updateData = array();
                $updateData['default_id']      = 0;
                C::t('#tom_pintuan#tom_pintuan_address')->update($value['id'],$updateData);
            }
        }
    }
    
    $addressListCount = C::t('#tom_pintuan#tom_pintuan_address')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");
    if($addressListCount == 0){
        $default = 1;
    }
    
    $provinceStr = "";
    $cityStr = "";
    $areaStr = "";
    $provinceInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($province);
    $cityInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($city);
    $areaInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($area);
    if($provinceInfo){
        $provinceStr = $provinceInfo['name'];
    }
    if($cityInfo){
        $cityStr = $cityInfo['name'];
    }
    if($areaInfo){
        $areaStr = $areaInfo['name'];
    }
    
    $insertData = array();
    $insertData['user_id']      = $__UserInfo['id'];
    $insertData['default_id']      = $default;
    $insertData['xm']           = $xm;
    $insertData['tel']          = $tel;
    $insertData['type_id']      = $type;
    $insertData['province_id']  = $province;
    $insertData['city_id']      = $city;
    $insertData['area_id']      = $area;
    $insertData['area_str']     = $provinceStr." ".$cityStr." ".$areaStr;
    $insertData['info']         = $info;
    if(C::t('#tom_pintuan#tom_pintuan_address')->insert($insertData)){
        echo 200;exit;
    }
    echo 400;exit;
}else if($act == 'edit'){
    
    $address_id = isset($_GET['address_id'])? intval($_GET['address_id']):0;
    
    $addressInfo = C::t('#tom_pintuan#tom_pintuan_address')->fetch_by_id($address_id);
    
    $provinceList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_level(1);
    $cityList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($addressInfo['province_id']);
    $areaList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($addressInfo['city_id']);
    
    $ajaxSaveUrl = "plugin.php?id=tom_pintuan&mod=address&act=editsave";
    $delUrl = "plugin.php?id=tom_pintuan&mod=address&act=del&formhash=".FORMHASH."&address_id=";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_pintuan:addressedit");  
    
}else if($act == 'editsave' && $_GET['formhash'] == FORMHASH){
    
    $address_id    = isset($_GET['address_id'])? intval($_GET['address_id']):0;
    $default    = isset($_GET['adddefault'])? intval($_GET['adddefault']):0;
    $xm         = isset($_GET['addxm'])? daddslashes(diconv(urldecode($_GET['addxm']),'utf-8')):'';
    $tel        = isset($_GET['addtel'])? daddslashes(diconv(urldecode($_GET['addtel']),'utf-8')):'';
    $province   = isset($_GET['province'])? intval($_GET['province']):0;
    $city       = isset($_GET['city'])? intval($_GET['city']):0;
    $area       = isset($_GET['area'])? intval($_GET['area']):0;
    $type       = isset($_GET['addtype'])? intval($_GET['addtype']):0;
    $info       = isset($_GET['addinfo'])? daddslashes(diconv(urldecode($_GET['addinfo']),'utf-8')):'';
    
    if($default == 1){
        $addressListTmp = C::t('#tom_pintuan#tom_pintuan_address')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND default_id=1 ","ORDER BY id DESC",0,100);
        if(is_array($addressListTmp) && !empty($addressListTmp)){
            foreach ($addressListTmp as $key => $value){
                $updateData = array();
                $updateData['default_id']      = 0;
                C::t('#tom_pintuan#tom_pintuan_address')->update($value['id'],$updateData);
            }
        }
    }
    
    $provinceStr = "";
    $cityStr = "";
    $areaStr = "";
    $provinceInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($province);
    $cityInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($city);
    $areaInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($area);
    if($provinceInfo){
        $provinceStr = $provinceInfo['name'];
    }
    if($cityInfo){
        $cityStr = $cityInfo['name'];
    }
    if($areaInfo){
        $areaStr = $areaInfo['name'];
    }
    
    $updateData = array();
    $updateData['default_id']      = $default;
    $updateData['xm']           = $xm;
    $updateData['tel']          = $tel;
    $updateData['type_id']      = $type;
    $updateData['province_id']  = $province;
    $updateData['city_id']      = $city;
    $updateData['area_id']      = $area;
    $updateData['area_str']     = $provinceStr." ".$cityStr." ".$areaStr;
    $updateData['info']         = $info;
    if(C::t('#tom_pintuan#tom_pintuan_address')->update($address_id,$updateData)){
        echo 200;exit;
    }
    echo 400;exit;
}else if($act == 'del' && $_GET['formhash'] == FORMHASH){
    
   $address_id    = isset($_GET['address_id'])? intval($_GET['address_id']):0;
   C::t('#tom_pintuan#tom_pintuan_address')->delete_by_id($address_id);
   dheader('location:'.$_G['siteurl'].$addressUrl);
   exit;
   
}else{
    
    $bstatus  = isset($_GET['bstatus'])? intval($_GET['bstatus']):0;
    $goods_id   = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;
    $tstatus = isset($_GET['tstatus'])? intval($_GET['tstatus']):0;
    $tlevel    = isset($_GET['tlevel'])? intval($_GET['tlevel']):1;
    $tuan_id   = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
    $address_id   = isset($_GET['address_id'])? intval($_GET['address_id']):0;
    
    $addressList = C::t('#tom_pintuan#tom_pintuan_address')->fetch_all_list(" AND user_id={$__UserInfo['id']} ","ORDER BY id DESC",0,100);
    
    $editUrl = "plugin.php?id=tom_pintuan&mod=address&act=edit&address_id=";
    $buyUrl = "plugin.php?id=tom_pintuan&mod=buy&showwxpaytitle=1&tstatus={$tstatus}&tlevel={$tlevel}&tuan_id={$tuan_id}&goods_id={$goods_id}&address_id=";
    
    $isGbk = false;
    if (CHARSET == 'gbk') $isGbk = true;
    include template("tom_pintuan:address");  
}

?>
