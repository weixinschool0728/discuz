<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($_GET['act'] == 'express'){
    
    $info = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($_GET['order_id']);
    
    $info['express_time'] = dgmdate($info['express_time'], 'Y-m-d H:i:s',$tomSysOffset);
    
    include template("tom_pintuan:shop/ordersexpress");
    
}else if($_GET['act'] == 'expresssave' && $_GET['formhash'] == FORMHASH){
    
    $info = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($_GET['order_id']);
    
    $express_name = !empty($_GET['express_name'])? addslashes($_GET['express_name']):'';
    $express_no = !empty($_GET['express_no'])? addslashes($_GET['express_no']):'';

    $updateData = array();
    $updateData['express_name'] = $express_name;
    $updateData['express_no']   = $express_no;
    $updateData['express_time'] = TIMESTAMP;
    $updateData['order_status'] = 4;
    C::t('#tom_pintuan#tom_pintuan_order')->update($_GET['order_id'],$updateData);

    if($pintuanConfig['open_template_sms'] == 1){
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/weixin.class.php';
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/templatesms.class.php';
        $weixinClass = new weixinClass($pintuanConfig['wxpay_appid'],$pintuanConfig['wxpay_appsecret']);
        $access_token = $weixinClass->get_access_token();
        if($access_token){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_pintuan&mod=orders");
            $template_sms_fahuo = lang('plugin/tom_pintuan','template_sms_fahuo');
            $template_sms_fahuo = str_replace("{NAME}", $info['goods_name'], $template_sms_fahuo);
            $smsData = array(
                'first'         => $template_sms_fahuo,
                'OrderSn'       => $info['order_no'],
                'OrderStatus'   => $orderStatusArray['4'],
                'remark'        => lang('plugin/tom_pintuan','template_sms_express_name').$kuaidi100Array[$express_name].'\n'.lang('plugin/tom_pintuan','template_sms_express_no').$express_no
            );
            $r = $templateSmsClass->sendSmsTm00017($info['user_openid'],$pintuanConfig['template_tm00017'],$smsData);
        }
    }
    $express_success_msg = lang('plugin/tom_pintuan','express_success_msg');
    showmessage($express_success_msg,'plugin.php?id=tom_pintuan:shop&mod=order&act=express&order_id='.$_GET['order_id']);
    exit;
        
}else{
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    
    $order_no = !empty($_GET['order_no'])? addslashes($_GET['order_no']):'';
    $order_tel = !empty($_GET['order_tel'])? trim(addslashes($_GET['order_tel'])):'';
    $start_time_tmp = !empty($_GET['start_time'])? addslashes($_GET['start_time']):'';
    $start_time = strtotime($start_time_tmp);
    $end_time_tmp = !empty($_GET['end_time'])? addslashes($_GET['end_time']):'';
    $end_time = strtotime($end_time_tmp);
    $qs_start_time_tmp = !empty($_GET['qs_start_time'])? addslashes($_GET['qs_start_time']):'';
    $qs_start_time = strtotime($qs_start_time_tmp);
    $qs_end_time_tmp = !empty($_GET['qs_end_time'])? addslashes($_GET['qs_end_time']):'';
    $qs_end_time = strtotime($qs_end_time_tmp);
    $order_status = isset($_GET['order_status'])? intval($_GET['order_status']):0;
    
    $goods_name = '';
    $where = "";
    if(!empty($order_no)){
        $where.=" AND order_no='{$order_no}' ";
    }
    if(!empty($order_tel)){
        $where.=" AND tel='{$order_tel}' ";
    }
    if(!empty($start_time_tmp) && $end_time_tmp){
        $where.=" AND order_time>$start_time AND order_time<$end_time ";
    }
    if(!empty($qs_start_time_tmp) && $qs_end_time_tmp){
        $where.=" AND qianshou_time>$qs_start_time AND qianshou_time<$qs_end_time ";
    }
    if(!empty($order_status)){
        $where.=" AND order_status={$order_status} ";
    }
    
    $goodsListTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_list_id(" AND shop_id={$shopInfo['id']} "," ORDER BY add_time DESC ",0,10000);
    if(is_array($goodsListTmp) && !empty($goodsListTmp)){
        $goodsIdArr = array();
        foreach ($goodsListTmp as $key => $value){
            $goodsIdArr[] = $value['id'];
        }
        if(!empty($goodsIdArr)){
            $where.= " AND goods_id IN(".  implode(",", $goodsIdArr).") ";
        }else{
            $where.= " AND goods_id IN(9999999999) ";
        }
    }else{
        $where.= " AND goods_id IN(9999999999) ";
    }
    
    
    $searchUrl = "&order_no={$order_no}&order_tel={$order_tel}&start_time={$start_time_tmp}&end_time={$end_time_tmp}&qs_start_time={$qs_start_time_tmp}&qs_end_time={$qs_end_time_tmp}&order_status={$order_status}";
    
    $ordersexportUrl = $_G['siteurl']."plugin.php?id=tom_pintuan:ordersexport&shop_id={$shopInfo['id']}&order_no={$order_no}&order_tel={$order_tel}&start_time={$start_time_tmp}&end_time={$end_time_tmp}&qs_start_time={$qs_start_time_tmp}&qs_end_time={$qs_end_time_tmp}&order_status={$order_status}";
    
    $pagesize = 10;
    $start = ($page-1)*$pagesize;
    $count = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_count($where,$goods_name);
    $orderListTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_list($where,"ORDER BY order_time DESC",$start,$pagesize,$goods_name);
    $orderList = array();
    if(is_array($orderListTmp) && !empty($orderListTmp)){
        foreach ($orderListTmp as $key => $value){
            $orderList[$key] = $value;
            $orderList[$key]['order_time'] = dgmdate($value['order_time'], 'Y-m-d H:i:s',$tomSysOffset);
            $orderList[$key]['qianshou_time'] = dgmdate($value['qianshou_time'], 'Y-m-d H:i:s',$tomSysOffset);
        }
    }
    
    $pages = helper_page::multi($count, $pagesize, $page, "plugin.php?id=tom_pintuan:shop&mod=orders".$searchUrl, 0, 11, false, false);
    
    include template("tom_pintuan:shop/orders");
    
}



?>