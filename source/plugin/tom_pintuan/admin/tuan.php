<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=tuan'; 
$modListUrl = $adminListUrl.'&tmod=tuan';
$modFromUrl = $adminFromUrl.'&tmod=tuan';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';

$get_list_url_value = get_list_url("tom_pintuan_admin_tuan_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($formhash == FORMHASH && $act == 'info'){
    
}else if($formhash == FORMHASH && $act == 'editstatus'){
    $fenghao = $Lang['fenghao'];
    $info = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $tuan_status = intval($_GET['tuan_status']);
        $prolong_hours = intval($_GET['prolong_hours']);
        $updateData = array();
        $updateData['prolong_hours'] = $prolong_hours;
        $updateData['tuan_status'] = $tuan_status;
        if(C::t('#tom_pintuan#tom_pintuan_tuan')->update($_GET['id'],$updateData)){
            C::t('#tom_pintuan#tom_pintuan_order')->update_tuan_status_by_tuan_id($_GET['id'],$tuan_status);
        }
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        showformheader($modFromUrl.'&act=editstatus&id='.$_GET['id'].'&formhash='.FORMHASH);
        showtableheader();
        echo '<tr><th colspan="15" class="partition">' . $Lang['tuan_editstatus_title'] . '</th></tr>';
        showtablefooter();
        showtableheader();
        echo '<tr><td width="100" align="right"><b>' . $Lang['tuan_prolong_hours'].$fenghao. '</b></td><td><input name="prolong_hours" type="text" value="'.$info['prolong_hours'].'" size="40" /></td></tr>';
        echo '<tr><td align="right"><b>'.$Lang['tuan_tuan_status'].$fenghao.'</b></td><td>';
        foreach ($tuanStatusArray as $key => $value){
            if($key == $info['tuan_status']){
                echo '<input type="radio" name="tuan_status" value="'.$key.'" checked><b><font color="'.$tuanStatusColorArray[$key].'">'.$value.'</font></b>&nbsp;';
            }else{
                echo '<input type="radio" name="tuan_status" value="'.$key.'" ><b><font color="'.$tuanStatusColorArray[$key].'">'.$value.'</font></b>&nbsp;';
            }
        }
        echo '</td></tr>';
        tomshowsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($formhash == FORMHASH && $act == 'dostatus'){
    
    $do_status = intval($_GET['do_status']);
    $updateData = array();
    $updateData['do_status'] = $do_status;
    C::t('#tom_pintuan#tom_pintuan_tuan')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($formhash == FORMHASH && $act == 'refund'){
    
    $tuan_id = intval($_GET['id']);
   
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
    
    $tuanTeamList = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_list(" AND tuan_id={$tuan_id} ","ORDER BY add_time DESC",0,50);
    
    $flag = false;
    if(is_array($tuanTeamList) && !empty($tuanTeamList)){
        foreach ($tuanTeamList as $key => $value){
            $id = intval($value['order_id']);
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
                    $flag = true;
                    $updateData = array();
                    $updateData['order_status'] = 7;
                    C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
                    DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num+{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
                    DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num-{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
                }else{
                    file_put_contents("./data/log/refundTuan.txt", print_r($return,true),FILE_APPEND);
                }
                sleep(1);
            }
        }
    }
    
    if($flag){
        $updateData = array();
        $updateData['tuan_status'] = 4;
        C::t('#tom_pintuan#tom_pintuan_tuan')->update($_GET['id'],$updateData);
        
        C::t('#tom_pintuan#tom_pintuan_order')->update_tuan_status_by_tuan_id($_GET['id'],4);
        
    }
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'updatetuanstatus'){
    
    $page           = isset($_GET['page'])? intval($_GET['page']):1;
    $allPageNum       = isset($_GET['allPageNum'])? intval($_GET['allPageNum']):1;
    $nextpage = $page + 1;
    
    $pagesize = 20;
    $start = ($page-1)*$pagesize;	
    $where= " AND tuan_status=2 ";
    if($page == 1){
        $count = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_all_count($where);
        $allPageNum = ceil($count/$pagesize);
    }
    if($page <= $allPageNum){
        $tuanList = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_all_list($where," ORDER BY tuan_time DESC,id DESC ",0,$pagesize);
        
        if(is_array($tuanList) && !empty($tuanList)){
            foreach ($tuanList as $key => $value){
                $tuanTeamListTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_list(" AND tuan_id={$value['id']} AND type_id=2 ","ORDER BY add_time ASC",0,500);
                $tuanTeamList = array();
                $tuanTeamListCount = 1;
                if(is_array($tuanTeamListTmp) && !empty($tuanTeamListTmp)){
                    foreach ($tuanTeamListTmp as $k => $v){
                        $ordersinfoTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($v['order_id']);
                        if($ordersinfoTmp['order_status'] == 2 || $ordersinfoTmp['order_status'] == 3 || $ordersinfoTmp['order_status'] == 4 || $ordersinfoTmp['order_status'] == 5){
                            $tuanTeamListCount++;
                        }
                    }
                }
                
                $goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($value['goods_id']);
                if($value['tlevel'] == 2){
                    $goodsInfo['tuan_num'] = $goodsInfo['tuan_num_2'];
                }else if($value['tlevel'] == 3){
                    $goodsInfo['tuan_num'] = $goodsInfo['tuan_num_3'];
                }
                
                $shengyuTuanTeamNum = 0;
                if($goodsInfo['tuan_num'] > $tuanTeamListCount){
                    $shengyuTuanTeamNum = $goodsInfo['tuan_num'] - $tuanTeamListCount;
                }
                
                if($shengyuTuanTeamNum > $pintuanConfig['admin_shengyuTuanTeamNum']){
                    $tuanHours = $goodsInfo['tuan_hours'];
                    if(!empty($value['prolong_hours'])){
                        $tuanHours = $goodsInfo['tuan_hours']+$value['prolong_hours'];
                    }
                    $tuanHours = intval($tuanHours);

                    $daojishiTimes = $value['tuan_time']+$tuanHours*3600 - TIMESTAMP;
                    if($daojishiTimes <= $pintuanConfig['admin_daojishiTimes']){
                        $updateData = array();
                        $updateData['tuan_status'] = $pintuanConfig['admin_tuan_tuan_status4'];
                        C::t('#tom_pintuan#tom_pintuan_tuan')->update($value['id'],$updateData);
                        C::t('#tom_pintuan#tom_pintuan_order')->update_tuan_status_by_tuan_id($value['id'],4);
                    }
                }
                
            }
        }
        
        $do_msg = str_replace("{PAGES}", $page, $Lang['tuan_update_status']);
        $do_msg = str_replace("{COUNT}", $allPageNum, $do_msg);
        $modupdatelistUrl = $adminListUrl.$pintuanConfig['admin_tuan_updatetuanstatus_action'].$nextpage."&allPageNum={$allPageNum}".'&formhash='.FORMHASH;
        tom_doing($do_msg, $modupdatelistUrl);
        
    }else{
        cpmsg($Lang['tuan_update_success'], $modListUrl, 'succeed');
    }
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_pintuan#tom_pintuan_tuan')->delete_by_id($_GET['id']);
    C::t('#tom_pintuan#tom_pintuan_tuan_team')->delete_by_tuan_id($_GET['id']);
    
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    set_list_url("tom_pintuan_admin_tuan_list");
    
    $page       = intval($_GET['page'])>0? intval($_GET['page']):1;
    $goods_id   = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;
    $user_id   = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $tuan_id   = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
    $tuan_status   = isset($_GET['tuan_status'])? intval($_GET['tuan_status']):0;
    $tuan_order   = isset($_GET['tuan_order'])? intval($_GET['tuan_order']):0;
    $do_status   = isset($_GET['do_status'])? intval($_GET['do_status']):0;
    
    $where = "";
    $order = " ORDER BY tuan_time DESC,id DESC ";
    if($goods_id){
        $where.= " AND goods_id={$goods_id} ";
    }
    if($user_id){
        $where.= " AND user_id={$user_id} ";
    }
    if($tuan_id){
        $where.= " AND id={$tuan_id} ";
    }
    if($tuan_status){
        $where.= " AND tuan_status={$tuan_status} ";
    }
    if($do_status == 1){
        $where.= " AND do_status=0 ";
    }
    if($do_status == 2){
        $where.= " AND do_status=1 ";
    }
    if($tuan_order == 2){
        $order = "ORDER BY success_time DESC,id DESC";
    }
    
    $modBasePageUrl = $modBaseUrl."&goods_id={$goods_id}&user_id={$user_id}&tuan_status={$tuan_status}&tuan_order={$tuan_order}&do_status={$do_status}";
    
    $pagesize = 10;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_all_count($where);
    $tuanList = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_all_list($where,$order,$start,$pagesize);
    
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['tuan_search_title'] . '</th></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['tuan_id'] . '</b></td><td><input name="tuan_id" type="text" value="'.$tuan_id.'" size="40" /></td></tr>';
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
    
    echo '<tr><td width="100" align="right"><b>' . $Lang['tuan_sort_title'] . '</b></td><td><select name="tuan_order" >';
    echo '<option value="0">'.$Lang['tuan_sort_title'].'</option>';
    $tuan_order1_selected = "";
    $tuan_order2_selected = "";
    if(1 == $tuan_order){
        $tuan_order1_selected = "selected";
    }
    if(2 == $tuan_order){
        $tuan_order2_selected = "selected";
    }
    echo '<option value="1" '.$tuan_order1_selected.'>'.$Lang['tuan_tuan_time'].'</option>';
    echo '<option value="2" '.$tuan_order2_selected.'>'.$Lang['tuan_success_time'].'</option>';
    echo '</select></td></tr>';
    
    echo '<tr><td width="100" align="right"><b>' . $Lang['tuan_do_status'] . '</b></td><td><select name="do_status" >';
    echo '<option value="0">'.$Lang['tuan_do_status'].'</option>';
    $do_status1_selected = "";
    $do_status2_selected = "";
    if(1 == $do_status){
        $do_status1_selected = "selected";
    }
    if(2 == $do_status){
        $do_status2_selected = "selected";
    }
    echo '<option value="1" '.$do_status1_selected.'>'.$Lang['tuan_do_status0'].'</option>';
    echo '<option value="2" '.$do_status2_selected.'>'.$Lang['tuan_do_status1'].'</option>';
    echo '</select></td></tr>';
    
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    tomshownavheader();
    tomshownavli($Lang['tuan_update_status_btn'],$modBaseUrl.'&act=updatetuanstatus&formhash='.FORMHASH,false);
    tomshownavfooter();
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['tuan_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['tuan_id'] . '</th>';
    echo '<th>' . $Lang['tuan_user_nickname'] . '</th>';
    echo '<th width="200">' . $Lang['tuan_goods_name'] . '</th>';
    echo '<th>' . $Lang['goods_tuan_price'] . '</th>';
    echo '<th>' . $Lang['goods_tuan_num'] . '</th>';
    echo '<th>' . $Lang['tuan_tuan_status'] . '</th>';
    echo '<th width="120">' . $Lang['order_order_status'] . '</th>';
    echo '<th>' . $Lang['tuan_do_status'] . '</th>';
    echo '<th>' . $Lang['tuan_tuan_time'] . '</th>';
    echo '<th>' . $Lang['tuan_success_time'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    foreach ($tuanList as $key => $value){
        
        $goodsInfo  = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($value['goods_id']);
        $userInfo   = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_id($value['user_id']);
        
        $orderListTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_list(" AND tuan_id={$value['id']} ","ORDER BY order_time DESC",0,500,"");
        $order_status_tmp = array();
        for($i=1;$i<=$pintuanConfig['admin_tuan_for_num'];$i++){
            $order_status_tmp[$i] = 0;
        }
        if(is_array($orderListTmp) && !empty($orderListTmp)){
            foreach ($orderListTmp as $k1 => $v1){
                $order_status_tmp[$v1['order_status']]++;
            }
        }
        
        echo '<tr>';
        echo '<td>'.$value['id'].'</td>';
        echo '<td>'.$userInfo['nickname'].'</td>';
        echo '<td>'.$goodsInfo['name'].'</td>';
        
        if($value['tlevel'] == 1){
            echo '<td>' . $goodsInfo['tuan_price'] . '</td>';
            echo '<td>' . $goodsInfo['tuan_num'] . '</td>';
        }else if($value['tlevel'] == 2){
            echo '<td>' . $goodsInfo['tuan_price_2'] . '</td>';
            echo '<td>' . $goodsInfo['tuan_num_2'] . '</td>';
        }else if($value['tlevel'] == 3){
            echo '<td>' . $goodsInfo['tuan_price_3'] . '</td>';
            echo '<td>' . $goodsInfo['tuan_num_3'] . '</td>';
        }else{
            echo '<td>' . $goodsInfo['tuan_price'] . '</td>';
            echo '<td>' . $goodsInfo['tuan_num'] . '</td>';
        }
        
        echo '<td><font color="'.$tuanStatusColorArray[$value['tuan_status']].'">'.$tuanStatusArray[$value['tuan_status']].'</font></td>';
        echo '<td>';
        foreach ($orderStatusArray as $k2 => $v2){
            if($order_status_tmp[$k2] > 0){
               echo $v2.'<font color="'.$orderStatusColorArray[$k2].'">('.$order_status_tmp[$k2].')</font>&nbsp;&nbsp;<br/>'; 
            }
        }
        echo '</td>';
        if($value['do_status'] == 1){
            echo '<td><font color="#1e9203">' . $Lang['tuan_do_status1'] . '</font>(<a href="'.$modBaseUrl.'&act=dostatus&id='.$value['id'].'&do_status=0&formhash='.FORMHASH.'">' . $Lang['tuan_do_status_btn'] . '</a>)</td>';
        }else{
            echo '<td><font color="#fc2009">' . $Lang['tuan_do_status0'] . '</font>(<a href="'.$modBaseUrl.'&act=dostatus&id='.$value['id'].'&do_status=1&formhash='.FORMHASH.'">' . $Lang['tuan_do_status_btn'] . '</a>)</td>';
        }
        
        echo '<td>' . dgmdate($value['tuan_time'], 'Y-m-d H:i:s',$tomSysOffset) . '</td>';
        if($value['success_time'] == 0){
            echo '<td>-</td>';
        }else{
            echo '<td>' . dgmdate($value['success_time'], 'Y-m-d H:i:s',$tomSysOffset) . '</td>';
        }
        
        $qrcodeImg = $_G['siteurl']."plugin.php?id=tom_qrcode&data=".urlencode($_G['siteurl']."plugin.php?id=tom_pintuan&mod=tuan&tlevel={$value['tlevel']}&tuan_id={$value['id']}");
        
        echo '<td>';
        echo '<a href="'.$adminBaseUrl.'&tmod=order&tuan_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tuan_order_title'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=editstatus&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tuan_editstatus_title'] . '</a><br/>';
        //echo '<a href="'.$modBaseUrl.'&act=refund&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['tuan_refund_title'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="refund_confirm(\''.$modBaseUrl.'&act=refund&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['tuan_refund_title'] . '</a>&nbsp;|&nbsp;';
        echo '<a target="_blank" href="'.$_G['siteurl'].'plugin.php?id=tom_pintuan:ordersexport&tuan_id='.$value['id'].'">' . $Lang['tuan_dodao_title'] . '</a><br/>';
        echo '<a target="_blank" href="'.$qrcodeImg.'">' . $Lang['tuan_qrcode_title'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
    
    $jsstr = <<<EOF
<script type="text/javascript">
            
function refund_confirm(url){
  var r = confirm("{$Lang['tuan_refund_sure']}")
  if (r == true){
    window.location = url;
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
    echo $jsstr;
}
?>
