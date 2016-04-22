<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=order'; 
$modListUrl = $adminListUrl.'&tmod=order';
$modFromUrl = $adminFromUrl.'&tmod=order';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';

$get_list_url_value = get_list_url("tom_pintuan_admin_order_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($formhash == FORMHASH && $act == 'info'){
    
    $info = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $order_status = intval($_GET['order_status']);
        $updateData = array();
        $updateData['order_status'] = $order_status;
        C::t('#tom_pintuan#tom_pintuan_order')->update($_GET['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        $fenghao = $Lang['fenghao'];
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_info_goods_title'] . '</th></tr>';
        echo '<tr><td align="right"><b>'.$Lang['goods_name'].$fenghao.'</b></td><td>'.$info['goods_name'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_goods_num'].$fenghao.'</b></td><td>'.$info['goods_num'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_goods_price'].$fenghao.'</b></td><td>'.$info['goods_price'].'</td></tr>';
        
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_info_order_title'] . '</th></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_order_no'].$fenghao.'</b></td><td>'.$info['order_no'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_pay_price'].$fenghao.'</b></td><td>'.$info['pay_price'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_order_status'].$fenghao.'</b></td><td><b><font color="'.$orderStatusColorArray[$info['order_status']].'">' . $orderStatusArray[$info['order_status']] . '</font></b></td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_order_time'].$fenghao.'</b></td><td>'.dgmdate($info['order_time'], 'Y-m-d H:i:s',$tomSysOffset).'</td></tr>';
        if($info['pay_time'] > 0){
            echo '<tr><td align="right"><b>'.$Lang['order_pay_time'].$fenghao.'</b></td><td>'.dgmdate($info['pay_time'], 'Y-m-d H:i:s',$tomSysOffset).'</td></tr>';
        }else{
            echo '<tr><td align="right"><b>'.$Lang['order_pay_time'].$fenghao.'</b></td><td>-</td></tr>';
        }
        
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_info_user_title'] . '</th></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_xm'].$fenghao.'</b></td><td>'.$info['xm'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_tel'].$fenghao.'</b></td><td>'.$info['tel'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_address'].$fenghao.'</b></td><td>'.$info['address'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_order_beizu'].$fenghao.'</b></td><td>'.$info['order_beizu'].'</td></tr>';
        
        showtablefooter();
        showformheader($modFromUrl.'&act=info&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_edit'] . '</th></tr>';
        showtablefooter();
        showtableheader();
        echo '<tr><td align="right"><b>'.$Lang['order_order_status'].$fenghao.'</b></td><td>';
        foreach ($orderStatusArray as $key => $value){
            if($key == $info['order_status']){
                echo '<input type="radio" name="order_status" value="'.$key.'" checked><b><font color="'.$orderStatusColorArray[$key].'">'.$value.'</font></b>&nbsp;';
            }else{
                echo '<input type="radio" name="order_status" value="'.$key.'" ><b><font color="'.$orderStatusColorArray[$key].'">'.$value.'</font></b>&nbsp;';
            }
        }
        echo '</td></tr>';
        echo '<tr><td>&nbsp;</td><td colspan="14" ><b><font color="#fd0303">(' . $Lang['order_order_status_msg'] . ')</font></b></td></tr>';
        tomshowsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'express'){
    $info = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        
        $express_name = !empty($_GET['express_name'])? addslashes($_GET['express_name']):'';
        $express_no = !empty($_GET['express_no'])? addslashes($_GET['express_no']):'';
        
        $updateData = array();
        $updateData['express_name'] = $express_name;
        $updateData['express_no']   = $express_no;
        $updateData['express_time'] = TIMESTAMP;
        $updateData['order_status'] = $pintuanConfig['admin_order_order_status4'];
        C::t('#tom_pintuan#tom_pintuan_order')->update($_GET['id'],$updateData);
        
        if($pintuanConfig['open_template_sms'] == 1){
            include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/weixin.class.php';
            include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/templatesms.class.php';
            $weixinClass = new weixinClass($pintuanConfig['wxpay_appid'],$pintuanConfig['wxpay_appsecret']);
            $access_token = $weixinClass->get_access_token();
            if($access_token){
                $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_pintuan&mod=orders");
                $Lang['template_sms_fahuo'] = str_replace("{NAME}", $info['goods_name'], $Lang['template_sms_fahuo']);
                $smsData = array(
                    'first'         => $Lang['template_sms_fahuo'],
                    'OrderSn'       => $info['order_no'],
                    'OrderStatus'   => $orderStatusArray['4'],
                    'remark'        => $Lang['template_sms_express_name'].$kuaidi100Array[$express_name].'\n'.$Lang['template_sms_express_no'].$express_no
                );
                $r = $templateSmsClass->sendSmsTm00017($info['user_openid'],$pintuanConfig['template_tm00017'],$smsData);
            }
        }
        
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        showformheader($modFromUrl.'&act=express&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_express_title'] . '</th></tr>';
        showtablefooter();
        showtableheader();
        
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_info_order_title'] . '</th></tr>';
        echo '<tr><td align="right"><b>'.$Lang['goods_name'].$fenghao.'</b></td><td>'.$info['goods_name'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_order_no'].$fenghao.'</b></td><td>'.$info['order_no'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_pay_price'].$fenghao.'</b></td><td>'.$info['pay_price'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_order_status'].$fenghao.'</b></td><td><b><font color="'.$orderStatusColorArray[$info['order_status']].'">' . $orderStatusArray[$info['order_status']] . '</font></b></td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_xm'].$fenghao.'</b></td><td>'.$info['xm'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_tel'].$fenghao.'</b></td><td>'.$info['tel'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_address'].$fenghao.'</b></td><td>'.$info['address'].'</td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['order_order_beizu'].$fenghao.'</b></td><td>'.$info['order_beizu'].'</td></tr>';
        
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_info_user_title'] . '</th></tr>';
        if($info['express_time'] > 0){
            echo '<tr><td align="right"><b>'.$Lang['order_express_time'].'</b></td><td>'.dgmdate($info['express_time'], 'Y-m-d H:i:s',$tomSysOffset).'</td></tr>';
        }else{
            echo '<tr><td width="100" align="right"><b>' . $Lang['order_express_time'] . '</b></td><td>-</td></tr>';
        }
        echo '<tr><td width="100" align="right"><b>' . $Lang['order_express_name'] . '</b></td><td><select name="express_name" >';
        echo '<option value="0">'.$Lang['order_express_name'].'</option>';
        foreach ($kuaidi100Array as $key => $value){
            if($key == $info['express_name']){
                echo '<option value="'.$key.'" selected>'.$value.'</option>';
            }else{
                echo '<option value="'.$key.'">'.$value.'</option>';
            }
        }
        echo '</select></td></tr>';
        echo '<tr><td width="100" align="right"><b>' . $Lang['order_express_no'] . '</b></td><td><input name="express_no" type="text" value="'.$info['express_no'].'" size="40" /></td></tr>';
        
        tomshowsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'batchfahuo'){
    
    if(submitcheck('submit')){
        
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/weixin.class.php';
        include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/templatesms.class.php';
        $weixinClass = new weixinClass($pintuanConfig['wxpay_appid'],$pintuanConfig['wxpay_appsecret']);
        $access_token = $weixinClass->get_access_token();
        if($access_token){
            $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_pintuan&mod=orders");
            if(is_array($_GET['ids']) && !empty($_GET['ids'])){
                foreach ($_GET['ids'] as $key => $value){
                    $id = intval($value);
                    $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($id);
                    if($orderInfo && $orderInfo['take_type']==1 && ($orderInfo['order_status']==2 || $orderInfo['order_status']==3) && !empty($_GET['express_name'][$key]) && !empty($_GET['express_no'][$key])){

                        $updateData = array();
                        $updateData['express_name'] = $_GET['express_name'][$key];
                        $updateData['express_no']   = $_GET['express_no'][$key];
                        $updateData['express_time'] = TIMESTAMP;
                        $updateData['order_status'] = $pintuanConfig['admin_order_order_status4'];
                        C::t('#tom_pintuan#tom_pintuan_order')->update($id,$updateData);

                        if($pintuanConfig['open_template_sms'] == 1 && $pintuanConfig['admin_template_sms'] == 1){
                            $Lang['template_sms_fahuo'] = str_replace("{NAME}", $orderInfo['goods_name'], $Lang['template_sms_fahuo']);
                            $smsData = array(
                                'first'         => $Lang['template_sms_fahuo'],
                                'OrderSn'       => $orderInfo['order_no'],
                                'OrderStatus'   => $orderStatusArray['4'],
                                'remark'        => $Lang['template_sms_express_name'].$kuaidi100Array[$_GET['express_name'][$key]].'\n'.$Lang['template_sms_express_no'].$_GET['express_no'][$key]
                            );
                            $r = $templateSmsClass->sendSmsTm00017($orderInfo['user_openid'],$pintuanConfig['template_tm00017'],$smsData);
                        }

                    }
                }
            }
        }
        
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        $idArr = array();
        if(is_array($_GET['ids']) && !empty($_GET['ids'])){
            foreach ($_GET['ids'] as $key => $value){
                $value = intval($value);
                if(!empty($value)){
                    $idArr[] = $value;
                }
            }
        }
        
        $where = " AND id IN(".  implode(",", $idArr).") ";
        $orderList = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_list($where,"ORDER BY order_time DESC",0,20,"");
        
        showformheader($modFromUrl.$pintuanConfig['admin_batchfahuo_action'].FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['batch_fahuo'] . '</th></tr>';
        echo '<tr class="header">';
        echo '<th>' . $Lang['order_order_no'] . '</th>';
        echo '<th>' . $Lang['order_goods_name'] . '</th>';
        echo '<th>' . $Lang['order_pay_price'] . '</th>';
        echo '<th>' . $Lang['order_order_status'] . '</th>';
        echo '<th>' . $Lang['order_xm'] . '</th>';
        echo '<th>' . $Lang['order_tel'] . '</th>';
        echo '<th>' . $Lang['order_address'] . '</th>';
        echo '<th>' . $Lang['order_order_beizu'] . '</th>';
        echo '<th>' . $Lang['order_express_name'] . '</th>';
        echo '<th>' . $Lang['order_express_no'] . '</th>';
        echo '</tr>';
        foreach ($orderList as $key => $value){

            echo '<tr style="height: 60px;">';
            echo '<td>' . $value['order_no'] . '</td>';
            echo '<td>' . $value['goods_name'] . '</td>';
            echo '<td><font color="#009900">' . $value['pay_price'] . '</font></td>';
            echo '<td><b><font color="'.$orderStatusColorArray[$value['order_status']].'">' . $orderStatusArray[$value['order_status']] . '</font></b></td>';
            echo '<td align="center">' . $value['xm'] . '</td>';
            echo '<td align="center">' . $value['tel'] . '</td>';
            echo '<td align="center">' . $value['address'] . '</td>';
            echo '<td align="center">' . $value['order_beizu'] . '</td>';
            echo '<td><select name="express_name[]" >';
            echo '<option value="0">'.$Lang['order_express_name'].'</option>';
            foreach ($kuaidi100Array as $k => $v){
                if($k == $value['express_name']){
                    echo '<option value="'.$k.'" selected>'.$v.'</option>';
                }else{
                    echo '<option value="'.$k.'">'.$v.'</option>';
                }
            }
            echo '</select></td>';
            echo '<td><input name="express_no[]" type="text" value="'.$value['express_no'].'" size="30" /><input type="hidden" name="ids[]" value="'.$value['id'].'" /></td>';
            echo '</tr>';
        }
        tomshowsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
    
}else if($formhash == FORMHASH && $act == 'refund'){
    
    $wxpay_appid        = trim($pintuanConfig['wxpay_appid']);
    $wxpay_mchid        = trim($pintuanConfig['wxpay_mchid']);
    $wxpay_key          = trim($pintuanConfig['wxpay_key']);
    $wxpay_appsecret    = trim($pintuanConfig['wxpay_appsecret']);

    define("TOM_WXPAY_APPID", $wxpay_appid);
    define("TOM_WXPAY_MCHID", $wxpay_mchid);
    define("TOM_WXPAY_KEY", $wxpay_key);
    define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);
    define("TOM_WXPAY_SSLCERT_PATH", DISCUZ_ROOT.'source/plugin/tom_pintuan/class/wxpay/cert/apiclient_cert.pem');
    define("TOM_WXPAY_SSLKEY_PATH", DISCUZ_ROOT.'source/plugin/tom_pintuan/class/wxpay/cert/apiclient_key.pem');
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/wxpay/lib/WxPay.Api.php';
    
    if(is_array($_GET['ids']) && !empty($_GET['ids'])){
        foreach ($_GET['ids'] as $key => $value){
            $id = intval($value);
            $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($id);
            
            if($orderInfo && !empty($orderInfo['order_no']) && !empty($orderInfo['pay_price']) && $orderInfo['order_status']==2){
                $pay_price = $orderInfo['pay_price']*100;
                $input = new WxPayRefund();
                $input->SetOut_trade_no($orderInfo['order_no']);
                $input->SetTotal_fee($pay_price);
                $input->SetRefund_fee($pay_price);
                $input->SetOut_refund_no(WxPayConfig::MCHID.date("YmdHis"));
                $input->SetOp_user_id(WxPayConfig::MCHID);
                $return = WxPayApi::refund($input);
                if(is_array($return) && $return['result_code'] == 'SUCCESS'){
                    $updateData = array();
                    $updateData['order_status'] = 7;
                    C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
                    DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num+{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
                    DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num-{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
                }
            }
        }
    }
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($formhash == FORMHASH && $act == 'refundquery'){
    
    $wxpay_appid        = trim($pintuanConfig['wxpay_appid']);
    $wxpay_mchid        = trim($pintuanConfig['wxpay_mchid']);
    $wxpay_key          = trim($pintuanConfig['wxpay_key']);
    $wxpay_appsecret    = trim($pintuanConfig['wxpay_appsecret']);

    define("TOM_WXPAY_APPID", $wxpay_appid);
    define("TOM_WXPAY_MCHID", $wxpay_mchid);
    define("TOM_WXPAY_KEY", $wxpay_key);
    define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);

    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/wxpay/lib/WxPay.Api.php';
    
    if(is_array($_GET['ids']) && !empty($_GET['ids'])){
        foreach ($_GET['ids'] as $key => $value){
            $id = intval($value);
            $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($id);
            
            if($orderInfo && !empty($orderInfo['order_no']) && !empty($orderInfo['pay_price'])  && $orderInfo['order_status']==7){
                $input = new WxPayRefundQuery();
                $input->SetOut_trade_no($orderInfo['order_no']);
                $return = WxPayApi::refundQuery($input);
                if(is_array($return) && $return['refund_status_0'] == 'SUCCESS'){
                    $updateData = array();
                    $updateData['order_status'] = 8;
                    C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
                }
            }
        }
    }
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'updateorderstatus'){
    
    $page           = isset($_GET['page'])? intval($_GET['page']):1;
    $allPageNum       = isset($_GET['allPageNum'])? intval($_GET['allPageNum']):1;
    $nextpage = $page + 1;
    
    $pagesize = $pintuanConfig['admin_updateorderstatus_size'];
    $start = ($page-1)*$pagesize;	
    $where= " AND order_status=4 ";
    if($page == 1){
        $count = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count($where);
        $allPageNum = ceil($count/$pagesize);
    }
    if($page <= $allPageNum){
        $orderList = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_list($where," ORDER BY order_time DESC,id DESC ",0,$pagesize);
        
        if(is_array($orderList) && !empty($orderList)){
            foreach ($orderList as $key => $value){
                if($value['express_time'] > 0 ){
                    $do_express_time = $pintuanConfig['express_days']*$pintuanConfig['admin_express_time_onetimes'];
                    if((TIMESTAMP - $value['express_time']) > $do_express_time){
                        $updateData = array();
                        $updateData['order_status'] = 5;
                        C::t('#tom_pintuan#tom_pintuan_order')->update($value['id'],$updateData);
                    }
                }
            }
        }
        
        $do_msg = str_replace("{PAGES}", $page, $Lang['order_update_status']);
        $do_msg = str_replace("{COUNT}", $allPageNum, $do_msg);
        $modupdatelistUrl = $adminListUrl.$pintuanConfig['admin_order_updateorderstatus_action'].$nextpage."&allPageNum={$allPageNum}".'&formhash='.FORMHASH;
        tom_doing($do_msg, $modupdatelistUrl);
        
    }else{
        cpmsg($Lang['order_update_success'], $modListUrl, 'succeed');
    }
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_pintuan#tom_pintuan_order')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    set_list_url("tom_pintuan_admin_order_list");
    
    $file_apiclient_cert = DISCUZ_ROOT.'source/plugin/tom_pintuan/class/wxpay/cert/apiclient_cert.pem';
    $file_apiclient_key = DISCUZ_ROOT.'source/plugin/tom_pintuan/class/wxpay/cert/apiclient_key.pem';
    if(!file_exists($file_apiclient_cert) || !file_exists($file_apiclient_key)){
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['order_error_title'] . '</th></tr>';
        echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
        echo '<li><font color="#FF0000"><b>' . $Lang['order_error_1'] . '</b></font></a></li>';
        echo '</ul></td></tr>';
        showtablefooter();
    }
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $goods_name = !empty($_GET['goods_name'])? addslashes($_GET['goods_name']):'';
    $order_no = !empty($_GET['order_no'])? addslashes($_GET['order_no']):'';
    $start_time_tmp = !empty($_GET['start_time'])? addslashes($_GET['start_time']):'';
    $start_time = strtotime($start_time_tmp);
    $end_time_tmp = !empty($_GET['end_time'])? addslashes($_GET['end_time']):'';
    $end_time = strtotime($end_time_tmp);
    $order_status = isset($_GET['order_status'])? intval($_GET['order_status']):0;
    $user_id = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $tuan_id = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
    $tstatus = isset($_GET['tstatus'])? intval($_GET['tstatus']):0;
    $goods_id = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;
    $tuan_status   = isset($_GET['tuan_status'])? intval($_GET['tuan_status']):0;
    $shop_id = isset($_GET['shop_id'])? intval($_GET['shop_id']):0;
    
    $where = "";
    if(!empty($order_no)){
        $where.=" AND order_no='{$order_no}' ";
    }
    if(!empty($start_time_tmp) && $end_time_tmp){
        $where.=" AND order_time>$start_time AND order_time<$end_time ";
    }
    if(!empty($order_status)){
        $where.=" AND order_status={$order_status} ";
    }
    if(!empty($user_id)){
        $where.=" AND user_id={$user_id} ";
    }
    if(!empty($tuan_id)){
        $where.=" AND tuan_id={$tuan_id} ";
    }
    if(!empty($tstatus)){
        $where.=" AND tstatus={$tstatus} ";
    }
    if(!empty($goods_id)){
        $where.=" AND goods_id={$goods_id} ";
    }
    if($tuan_status){
        $where.= " AND tuan_status={$tuan_status} ";
    }
    
    if($shop_id){
        $goodsListTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_list_id(" AND shop_id={$shop_id} "," ORDER BY add_time DESC ",0,10000);
        if(is_array($goodsListTmp) && !empty($goodsListTmp)){
            $goodsIdArr = array();
            foreach ($goodsListTmp as $key => $value){
                $goodsIdArr[] = $value['id'];
            }
            if(!empty($goodsIdArr)){
                $where.= " AND goods_id IN(".  implode(",", $goodsIdArr).") ";
            }
        }
    }
    
    $modBasePageUrl = $modBaseUrl."&goods_name={$goods_name}&order_no={$order_no}&start_time={$start_time_tmp}&end_time={$end_time_tmp}&order_status={$order_status}&tuan_id={$tuan_id}&user_id={$user_id}&tstatus={$tstatus}&goods_id={$goods_id}&tuan_status={$tuan_status}&shop_id={$shop_id}";
    
    $pagesize = $pintuanConfig['admin_order_pagesize'];
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_count($where,$goods_name);
    $orderList = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_list($where,"ORDER BY order_time DESC",$start,$pagesize,$goods_name);
    $fenghao = $Lang['fenghao'];
    echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['order_search_list'] . '</th></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['goods_id'] . '</b></td><td><input name="goods_id" type="text" value="'.$goods_id.'" size="40" /></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['order_order_no'] . '</b></td><td><input name="order_no" type="text" value="'.$order_no.'" size="40" /></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['order_order_time'] . '</b></td><td><input name="start_time" type="text" value="'.$start_time_tmp.'" onclick="showcalendar(event, this, 1)" size="40" />--<input name="end_time" type="text" value="'.$end_time_tmp.'" onclick="showcalendar(event, this, 1)" size="40" /></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['order_order_status'] . '</b></td><td><select name="order_status" >';
    echo '<option value="0">'.$Lang['order_order_status'].'</option>';
    foreach ($orderStatusArray as $key => $value){
        if($key == $order_status){
            echo '<option value="'.$key.'" selected>'.$value.'</option>';
        }else{
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo '</select></td></tr>';
    $shopList = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_list(""," ORDER BY id DESC ",0,500);
    $shop_list_item = array();
    if(is_array($shopList) && !empty($shopList)){
        foreach ($shopList as $key => $value){
            $shop_list_item[$value['id']] = $value['name'];
        }
    }
    echo '<tr><td width="100" align="right"><b>' . $Lang['goods_shop_id'] . '</b></td><td><select name="shop_id" >';
    echo '<option value="0">'.$Lang['goods_shop_id'].'</option>';
    foreach ($shop_list_item as $key => $value){
        if($key == $shop_id){
            echo '<option value="'.$key.'" selected>'.$value.'</option>';
        }else{
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo '</select></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['order_tstatus'] . '</b></td><td><select name="tstatus" >';
    echo '<option value="0">'.$Lang['order_tstatus'].'</option>';
    foreach ($tstatusArray as $key => $value){
        if($key == $tstatus){
            echo '<option value="'.$key.'" selected>'.$value.'</option>';
        }else{
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo '</select></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['tuan_tuan_status'] . '</b></td><td><select name="tuan_status" >';
    echo '<option value="0">'.$Lang['tuan_tuan_status'].'</option>';
    foreach ($tuanStatusArray as $key => $value){
        if($key == $tuan_status){
            echo '<option value="'.$key.'" selected>'.$value.'</option>';
        }else{
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo '</select></td></tr>';
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    tomshownavheader();
    tomshownavli($Lang['order_export'],$_G['siteurl']."plugin.php?id=tom_pintuan:ordersexport&goods_name={$goods_name}&order_no={$order_no}&start_time={$start_time_tmp}&end_time={$end_time_tmp}&order_status={$order_status}&tuan_id={$tuan_id}&user_id={$user_id}&tstatus={$tstatus}&goods_id={$goods_id}&tuan_status={$tuan_status}&shop_id={$shop_id}",false);
    tomshownavli($Lang['order_update_status_btn'],$modBaseUrl.'&act=updateorderstatus&formhash='.FORMHASH,false);
    tomshownavfooter();
    
    $anchor = isset($_GET['anchor']) ? dhtmlspecialchars($_GET['anchor']) : '';
    echo '<form name="cpform2" id="cpform2" method="post" autocomplete="off" action="'.ADMINSCRIPT.'?action='.$modFromUrl.'&formhash='.FORMHASH.'" onsubmit="return order_form();">'.
		'<input type="hidden" name="formhash" value="'.FORMHASH.'" />'.
		'<input type="hidden" id="formscrolltop" name="scrolltop" value="" />'.
		'<input type="hidden" name="anchor" value="'.$anchor.'" />';
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['order_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th width="10">&nbsp;</th>';
    echo '<th width="100">' . $Lang['order_order_no'] . '</th>';
    echo '<th width="200">' . $Lang['order_goods_name'] . '</th>';
    echo '<th>' . $Lang['order_pay_price'] . '</th>';
    echo '<th>' . $Lang['order_goods_num'] . '</th>';
    echo '<th>' . $Lang['user_id'] . '</th>';
    echo '<th>' . $Lang['order_user_nickname'] . '</th>';
    echo '<th>' . $Lang['order_tstatus'] . '</th>';
    echo '<th>' . $Lang['tuan_id'] . '</th>';
    echo '<th>' . $Lang['order_order_status'] . '</th>';
    echo '<th>' . $Lang['goods_take_type'] . '</th>';
    echo '<th width="90">' . $Lang['order_order_time'] . '</th>';
    echo '<th width="120">' . $Lang['handle'] . '</th>';
    echo '</tr>';
    foreach ($orderList as $key => $value){
        
        echo '<tr>';
        echo '<td><input class="checkbox" type="checkbox" name="ids[]" value="' . $value['id'] . '" ></td>';
        echo '<td>' . $value['order_no'] . '</td>';
        echo '<td>' . $value['goods_name'] . '</td>';
        echo '<td><font color="#009900">' . $value['pay_price'] . '</font></td>';
        echo '<td align="center">' . $value['goods_num'] . '</td>';
        echo '<td><font color="#0585d6"><b>' . $value['user_id'] . '</b></font></td>';
        echo '<td>' . $value['user_nickname'] . '</td>';
        if($value['tstatus'] == 1 || $value['tstatus'] == 2){
            echo '<td><b><font color="'.$tstatusColorArray[$value['tstatus']].'">' . $tstatusArray[$value['tstatus']] . '</font></b>(<font color="'.$tuanStatusColorArray[$value['tuan_status']].'">'.$tuanStatusArray[$value['tuan_status']].'</font>)</td>';
        }else{
            echo '<td><b><font color="'.$tstatusColorArray[$value['tstatus']].'">' . $tstatusArray[$value['tstatus']] . '</font></b></td>';
        }
        
        echo '<td><font color="#0585d6">' . $value['tuan_id'] . '</font></td>';
        echo '<td><b><font color="'.$orderStatusColorArray[$value['order_status']].'">' . $orderStatusArray[$value['order_status']] . '</font></b></td>';
        if($value['take_type'] == 1){
            echo '<td>' . $Lang['goods_take_type_1'] . '</td>'; 
        }else{
            echo '<td><b>' . $Lang['goods_take_type_2'] . '</b></td>'; 
        }
        echo '<td>' . dgmdate($value['order_time'], 'Y-m-d H:i:s',$tomSysOffset) . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=info&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['order_info'] . '</a>&nbsp;|&nbsp;';
        if($value['take_type'] == 1){
            echo '<a href="'.$modBaseUrl.'&act=express&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['order_express_title'] . '</a>';
        }
        echo '<br/>';
        echo '<a target="_blank" href="'.$_G['siteurl'].'plugin.php?id=tom_pintuan:print&order_no='.$value['order_no'].'">' . $Lang['order_print'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    
    $formstr = <<<EOF
        <tr>
            <td class="td25">
                <input type="checkbox" name="chkall" id="chkallFh9R" class="checkbox" onclick="checkAll('prefix', this.form, 'ids')" />
                <label for="chkallFh9R">{$Lang['checkall']}</label>
            </td>
            <td class="td25">
                <select name="act" >
                    <option value="batchfahuo">{$Lang['batch_fahuo']}</option>
                    <option value="refund">{$Lang['batch_refund']}</option>
                    <option value="refundquery">{$Lang['batch_refundquery']}</option>
                </select>
            </td>
            <td colspan="15">
                <div class="fixsel"><input type="submit" class="btn" id="submit_announcesubmit" name="announcesubmit" value="{$Lang['batch_btn']}" /></div>
            </td>
        </tr>
        <script type="text/javascript">
        function order_form(){
          var r = confirm("{$Lang['batch_make_sure']}")
          if (r == true){
            return true;
          }else{
            return false;
          }
        }
        function del_confirm(url){
  var r = confirm("{$Lang['makesure_del_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
        </script>
EOF;
    
    echo $formstr;
    showtablefooter();
    showformfooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
}
?>
