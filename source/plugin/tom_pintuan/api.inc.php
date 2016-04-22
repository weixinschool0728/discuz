<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act = isset($_GET['act'])? addslashes($_GET['act']):"";

if($act == 'city'){
    $callback = $_GET['callback'];
    $pid = isset($_GET['pid'])? intval($_GET['pid']):0;
    $outArr = array();
    $cityList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($pid);
    if(is_array($cityList) && !empty($cityList)){
        foreach ($cityList as $key => $value) {
            $outArr[$key]['id'] = $value['id'];
            $outArr[$key]['name'] = diconv($value['name'],CHARSET,'utf-8');
        }
    }
    $outStr = '';
    $outStr = json_encode($outArr);
    if($callback){
        $outStr = $callback . "(" . $outStr. ")";
    }
    echo $outStr;
    die();
}else if($act == 'area'){
    $callback = $_GET['callback'];
    $pid = isset($_GET['pid'])? intval($_GET['pid']):0;
    $outArr = array();
    $areaList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($pid);
    if(is_array($areaList) && !empty($areaList)){
        foreach ($areaList as $key => $value) {
            $outArr[$key]['id'] = $value['id'];
            $outArr[$key]['name'] = diconv($value['name'],CHARSET,'utf-8');
        }
    }
    $outStr = '';
    $outStr = json_encode($outArr);
    if($callback){
        $outStr = $callback . "(" . $outStr. ")";
    }
    echo $outStr;
    die();
    
}else if($act == 'get_search_url' && $_GET['formhash'] == FORMHASH){
    
    $goods_name = isset($_GET['goods_name'])? daddslashes(diconv(urldecode($_GET['goods_name']),'utf-8')):'';
    $url = $_G['siteurl']."plugin.php?id=tom_pintuan&mod=index&goods_name=".urlencode($goods_name);
    echo $url;exit;
}else if($act == 'tuan_ok_sms'  && $_GET['formhash'] == FORMHASH){
    
    $tuan_id = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
    
    $pintuanConfig = $_G['cache']['plugin']['tom_pintuan'];
    if (CHARSET == 'gbk') {
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.gbk.php';
    }else{
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.utf8.php';
    }
    
    if($pintuanConfig['open_template_sms'] == 1){
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/weixin.class.php';
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/templatesms.class.php';
        $weixinClass = new weixinClass($pintuanConfig['wxpay_appid'],$pintuanConfig['wxpay_appsecret']);
        $access_token = $weixinClass->get_access_token();
        if($access_token){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_pintuan&mod=groups");
            
            $orderList = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_list(" AND tuan_id={$tuan_id} ","ORDER BY order_time DESC",0,500,"");
            
            if(is_array($orderList) && !empty($orderList)){
                foreach ($orderList as $key => $value){
                    if($value['order_status'] == 2 || $value['order_status'] == 3 || $value['order_status'] == 4 || $value['order_status'] == 5){
                        $smsData = array(
                            'first'         => lang('plugin/tom_pintuan','template_sms_tuan_ok'),
                            'OrderSn'       => $value['order_no'],
                            'OrderStatus'   => $orderStatusArray[$value['order_status']],
                            'remark'        => lang('plugin/tom_pintuan','template_sms_tuan_goodsname').$value['goods_name']
                        );
                        $r = $templateSmsClass->sendSmsTm00017($value['user_openid'],$pintuanConfig['template_tm00017'],$smsData);
                    }
                    
                }
            }
        }
    }
    
}

?>
