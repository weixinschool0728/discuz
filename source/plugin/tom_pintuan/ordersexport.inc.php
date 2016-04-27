<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if (CHARSET == 'gbk') {
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.gbk.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.utf8.php';
}

$pintuanConfig = $_G['cache']['plugin']['tom_pintuan'];

$page           = isset($_GET['page'])? intval($_GET['page']):1;
$goods_name     = !empty($_GET['goods_name'])? addslashes($_GET['goods_name']):'';
$order_no       = !empty($_GET['order_no'])? addslashes($_GET['order_no']):'';
$order_tel = !empty($_GET['order_tel'])? trim(addslashes($_GET['order_tel'])):'';
$start_time_tmp = !empty($_GET['start_time'])? addslashes($_GET['start_time']):'';
$start_time     = strtotime($start_time_tmp);
$end_time_tmp   = !empty($_GET['end_time'])? addslashes($_GET['end_time']):'';
$end_time       = strtotime($end_time_tmp);

$qs_start_time_tmp = !empty($_GET['qs_start_time'])? addslashes($_GET['qs_start_time']):'';
$qs_start_time = strtotime($qs_start_time_tmp);
$qs_end_time_tmp = !empty($_GET['qs_end_time'])? addslashes($_GET['qs_end_time']):'';
$qs_end_time = strtotime($qs_end_time_tmp);

$order_status   = isset($_GET['order_status'])? intval($_GET['order_status']):0;
$user_id        = isset($_GET['user_id'])? intval($_GET['user_id']):0;
$tuan_id        = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
$tstatus        = isset($_GET['tstatus'])? intval($_GET['tstatus']):0;
$goods_id = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;
$tuan_status   = isset($_GET['tuan_status'])? intval($_GET['tuan_status']):0;
$shop_id = isset($_GET['shop_id'])? intval($_GET['shop_id']):0;

$pagesize = 10000;
$start = ($page-1)*$pagesize;

$tomSysOffset = getglobal('setting/timeoffset');


$manageFlag = 0;
if(isset($_G['uid']) && $_G['uid'] > 0 && $_G['groupid'] == 1){
    $manageFlag = 1;
}

if(isset($_G['uid']) && $_G['uid'] > 0){
    $shopInfo = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_by_bbs_uid($_G['uid']);
    if($shopInfo['id'] == $shop_id){
        $manageFlag = 1;
    }
}

if(isset($_G['uid']) && $_G['uid'] > 0 && $manageFlag == 1){
    
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
            }else{
                $where.= " AND goods_id IN(9999999999) ";
            }
        }else{
            $where.= " AND goods_id IN(9999999999) ";
        }
    }
    
    $ordersListTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_like_list($where,"ORDER BY order_time DESC",$start,$pagesize,$goods_name);
    $orderList = array();
    foreach ($ordersListTmp as $key => $value) {
        $orderList[$key] = $value;
        if($value['tstatus'] == 1 || $value['tstatus'] == 2){
            $orderList[$key]['tstatus'] = $tstatusArray[$value['tstatus']]."(".$tuanStatusArray[$value['tuan_status']].")";
        }else{
            $orderList[$key]['tstatus'] = $tstatusArray[$value['tstatus']];
        }
        $orderList[$key]['express_name'] = $kuaidi100Array[$value['express_name']];
        $orderList[$key]['order_status'] = $orderStatusArray[$value['order_status']];
        
        if($value['take_type'] == 1){
            $orderList[$key]['take_type'] = lang('plugin/tom_pintuan','goods_take_type_1');
        }else{
            $orderList[$key]['take_type'] = lang('plugin/tom_pintuan','goods_take_type_2');
        }
        
        $orderList[$key]['order_time'] = dgmdate($value['order_time'],"Y-m-d H:i:s",$tomSysOffset);
        if($value['qianshou_time'] > 0){
            $orderList[$key]['qianshou_time'] = dgmdate($value['qianshou_time'],"Y-m-d H:i:s",$tomSysOffset);
        }else{
            $orderList[$key]['qianshou_time'] = '-';
        }
        
    }

    $order_tstatus = lang('plugin/tom_pintuan','order_tstatus');
    $order_tuan_id = lang('plugin/tom_pintuan','tuan_id');
    $order_order_no = lang('plugin/tom_pintuan','order_order_no');
    $goods_id = lang('plugin/tom_pintuan','goods_id');
    $order_goods_name = lang('plugin/tom_pintuan','order_goods_name');
    $order_goods_num = lang('plugin/tom_pintuan','order_goods_num');
    $order_goods_price = lang('plugin/tom_pintuan','order_goods_price');
    $order_pay_price = lang('plugin/tom_pintuan','order_pay_price');
    $user_id = lang('plugin/tom_pintuan','user_id');
    $order_user_openid = lang('plugin/tom_pintuan','order_user_openid');
    $order_xm = lang('plugin/tom_pintuan','order_xm');
    $order_tel = lang('plugin/tom_pintuan','order_tel');
    $order_address = lang('plugin/tom_pintuan','order_address');
    $order_order_beizu = lang('plugin/tom_pintuan','order_order_beizu');
    $order_express_name = lang('plugin/tom_pintuan','order_express_name');
    $order_express_no = lang('plugin/tom_pintuan','order_express_no');
    $order_order_status = lang('plugin/tom_pintuan','order_order_status');
    $goods_take_type = lang('plugin/tom_pintuan','goods_take_type');
    $order_order_time = lang('plugin/tom_pintuan','order_order_time');
    $order_qianshou_time = lang('plugin/tom_pintuan','order_qianshou_time');

    $listData[] = array(
        $order_tstatus,
        $order_tuan_id,
        $order_order_no,
        $goods_id,
        $order_goods_name,
        $order_goods_num,
        $order_goods_price,
        $order_pay_price,
        $user_id,
        $order_user_openid,
        $order_xm,
        $order_tel,
        $order_address,
        $order_order_beizu,
        $order_express_name,
        $order_express_no,
        $order_order_status,
        $goods_take_type,
        $order_order_time,
        $order_qianshou_time,
    ); 
    foreach ($orderList as $v){
        $lineData = array();
        $lineData[] = $v['tstatus'];
        $lineData[] = $v['tuan_id'];
        $lineData[] = $v['order_no'];
        $lineData[] = $v['goods_id'];
        $lineData[] = $v['goods_name'];
        $lineData[] = $v['goods_num'];
        $lineData[] = $v['goods_price'];
        $lineData[] = $v['pay_price'];
        $lineData[] = $v['user_id'];
        $lineData[] = $v['user_openid'];
        $lineData[] = $v['xm'];
        $lineData[] = $v['tel'];
        $v['address'] = str_replace("\r\n", "", $v['address']);
        $v['address'] = str_replace("\n", "", $v['address']);
        $lineData[] = $v['address'];
        $lineData[] = $v['order_beizu'];
        $v['order_beizu'] = str_replace("\r\n", "", $v['order_beizu']);
        $v['order_beizu'] = str_replace("\n", "", $v['order_beizu']);
        $lineData[] = $v['express_name'];
        $lineData[] = $v['express_no'];
        $lineData[] = $v['order_status'];
        $lineData[] = $v['take_type'];
        $lineData[] = $v['order_time'];
        $lineData[] = $v['qianshou_time'];
        
        $listData[] = $lineData;
    }
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition:filename=exportOrders.xls");

    foreach ($listData as $fields){
        foreach ($fields as $k=> $v){
            $str = @diconv("$v",CHARSET,"GB2312");
            echo $str ."\t";
        }
        echo "\n";
    }
    exit;
}else{
    exit('Access Denied');
}

?>
