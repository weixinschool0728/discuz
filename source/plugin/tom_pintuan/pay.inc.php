<?php



/**
   1 待支付 2 已支付，未确认 3 已确认，待发货  4 配送中 5 已签收 6 交易已取消 7 退款处理中  8 退款成功
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$pintuanConfig = $_G['cache']['plugin']['tom_pintuan'];

$wxpay_appid        = trim($pintuanConfig['wxpay_appid']);
$wxpay_mchid        = trim($pintuanConfig['wxpay_mchid']);
$wxpay_key          = trim($pintuanConfig['wxpay_key']);
$wxpay_appsecret    = trim($pintuanConfig['wxpay_appsecret']);

define("TOM_WXPAY_APPID", $wxpay_appid);
define("TOM_WXPAY_MCHID", $wxpay_mchid);
define("TOM_WXPAY_KEY", $wxpay_key);
define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);

include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/wxpay/lib/WxPay.Api.php';

$act = isset($_GET['act'])? addslashes($_GET['act']):"order";

if($act == "order" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );

    $goods_id   = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;
    $address_id = isset($_GET['address_id'])? intval($_GET['address_id']):0;
    $tstatus    = isset($_GET['tstatus'])? intval($_GET['tstatus']):0;
    $tlevel     = isset($_GET['tlevel'])? intval($_GET['tlevel']):1;
    $tuan_id    = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
    $user_id    = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $goods_num  = intval($_GET['goods_num'])>0? intval($_GET['goods_num']):1;
    $take_type    = isset($_GET['take_type'])? intval($_GET['take_type']):1;
	$fieldbb    = isset($_GET['fieldbb'])? addslashes($_GET['fieldbb']):1;
	$fieldbd    = isset($_GET['fieldbd'])? addslashes($_GET['fieldbd']):1;
	$fieldbf    = isset($_GET['fieldbf'])? addslashes($_GET['fieldbf']):1;
    $order_beizu  = isset($_GET['order_beizu'])? daddslashes(diconv(urldecode($_GET['order_beizu']),'utf-8')):'';

    $addressInfo = array('xm'=>'','tel'=>'','area_str'=>'','info'=>'');
    
    $goodsInfo  = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($goods_id);
    $userInfo   = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_id($user_id);
    if($address_id){
        $addressInfo = C::t('#tom_pintuan#tom_pintuan_address')->fetch_by_id($address_id);
    }
    
    if(!$goodsInfo || !$userInfo){
        $outArr = array(
            'status'=> 404,
        );
        echo json_encode($outArr); exit;
    }
    
    if($goods_num > $goodsInfo['goods_num']){
        $outArr = array(
            'status'=> 304,
        );
        echo json_encode($outArr); exit;
    }
    
    if($goodsInfo['express_price'] > 0){
        $pintuanConfig['express_price'] = $goodsInfo['express_price'];
    }
    
    if($take_type == 2 || $take_type == 4){
        $pintuanConfig['express_price'] = 0;
    }
	//限制下单次数
	$uidNumCount = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count(" AND user_id=$user_id AND goods_id=$goods_id");
	if($uidNumCount > $goodsInfo['fieldb4'] && $goodsInfo['fieldb4'] != 0){
    	$outArr = array(
            'status'=> 309,
        );
       echo json_encode($outArr); exit;
	}
    
    $openid = $userInfo['openid'];
    $order_no = "PT".date("YmdHis")."-".mt_rand(111111, 666666);
    $goods_name = diconv($goodsInfo['name'],CHARSET,'utf-8');
    if($tstatus == 1 || $tstatus == 2){
        if($tlevel == 1){
            $base_price = $goodsInfo['tuan_price']*100;
            $goods_price = $goodsInfo['tuan_price'];
            $tuan_num = $goodsInfo['tuan_num'];
        }else if($tlevel == 2){
            $base_price = $goodsInfo['tuan_price_2']*100;
            $goods_price = $goodsInfo['tuan_price_2'];
            $tuan_num = $goodsInfo['tuan_num_2'];
        }else if($tlevel == 3){
            $base_price = $goodsInfo['tuan_price_3']*100;
            $goods_price = $goodsInfo['tuan_price_3'];
            $tuan_num = $goodsInfo['tuan_num_3'];
        }else{
            $base_price = $goodsInfo['tuan_price']*100;
            $goods_price = $goodsInfo['tuan_price'];
            $tuan_num = $goodsInfo['tuan_num'];
        }
    }else{
        $base_price = $goodsInfo['one_price']*100;
        $goods_price = $goodsInfo['one_price'];
    }
    
    if($tstatus == 1){
        if($goodsInfo['tuanz_price'] > 0){
            //$base_price = $goodsInfo['tuanz_price']*100;
            $goods_price = $goodsInfo['tuanz_price'];
        }
    }
    
    $pay_price = $pintuanConfig['express_price']+$base_price*$goods_num;

	if(empty($tuan_id)){
		//团长价格
		if($goodsInfo['tuanz_price'] > 0 && $tstatus == 1){
			$pay_price = $pintuanConfig['express_price']+$base_price*($goods_num-1)+$goodsInfo['tuanz_price']*100;
		}
	}
	
    $notifyUrl = $_G['siteurl']."source/plugin/tom_pintuan/notify.php";
    
    if($tstatus == 2 && $tuan_id){
        $tuanTeamListTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_list(" AND tuan_id={$tuan_id} AND type_id=2 ","ORDER BY add_time ASC",0,500);
        $tuanTeamListCount = 1;
        if(is_array($tuanTeamListTmp) && !empty($tuanTeamListTmp)){
            foreach ($tuanTeamListTmp as $key => $value){
                $ordersinfoTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($value['order_id']);
                if($ordersinfoTmp['order_status'] == 2 || $ordersinfoTmp['order_status'] == 3 || $ordersinfoTmp['order_status'] == 4 || $ordersinfoTmp['order_status'] == 5){
                    $tuanTeamListCount++;
                }
            }
        }
        
        if($tuan_num <= $tuanTeamListCount){
            $outArr = array(
                'status'=> 305,
            );
            echo json_encode($outArr); exit;
        }
    }
    
    if($goodsInfo['xiangou_num'] > 0){
        $sun_goods_num = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_sun_goods_num(" AND user_id={$user_id} AND goods_id={$goods_id} AND order_status IN(2,3,4,5) ");
        $new_sun_goods_num = $sun_goods_num + $goods_num;
        if( $new_sun_goods_num > $goodsInfo['xiangou_num']){
            $outArr = array(
                'status'=> 306,
            );
            echo json_encode($outArr); exit;
        }
    }

    $orderInput = new WxPayUnifiedOrder();
    $orderInput->SetBody($goods_name);		
    $orderInput->SetAttach("tom_pintuan");		
    $orderInput->SetOut_trade_no($order_no);	
    $orderInput->SetTotal_fee($pay_price);	
    //$orderInput->SetTime_start(date("YmdHis")); 
    //$orderInput->SetTime_expire(date("YmdHis", time() + 7200)); 
    $orderInput->SetGoods_tag("null");	
    $orderInput->SetNotify_url($notifyUrl);	
    $orderInput->SetTrade_type("JSAPI");
    $orderInput->SetOpenid($openid);
    $orderInfo = WxPayApi::unifiedOrder($orderInput);

    if(is_array($orderInfo) && $orderInfo['result_code']=='SUCCESS' && $orderInfo['return_code']=='SUCCESS'){
        
        $tuanInfo = array();
        if($tstatus == 1){
            $insertData = array();
            $insertData['goods_id']          = $goods_id;
            $insertData['user_id']           = $user_id;
            $insertData['tlevel']            = $tlevel;
            $insertData['tuan_time']         = TIMESTAMP;
            $insertData['tuan_status']       = 1;
            if(C::t('#tom_pintuan#tom_pintuan_tuan')->insert($insertData)){
                $isTuanTop = true;
                $tuan_id = C::t('#tom_pintuan#tom_pintuan_tuan')->insert_id();
                $tuanInfo = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_by_id($tuan_id);
            }
        }else if($tstatus == 2){
            $tuanInfo = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_by_id($tuan_id);
        }
        
        if($tstatus == 1 || $tstatus == 2){
            if(!$tuanInfo){
                $outArr = array(
                    'status'=> 301,
                );
                echo json_encode($outArr); exit;
            }
        }

        $insertData = array();
        $insertData['tstatus']          = $tstatus;
        $insertData['order_no']         = $order_no;
        $insertData['goods_id']         = $goods_id;
        $insertData['take_type']        = $take_type;
        $insertData['goods_name']       = $goodsInfo['name'];
        $insertData['goods_num']        = $goods_num;
        $insertData['goods_price']      = $goods_price;
        $insertData['pay_price']        = $pay_price/100;
        $insertData['user_id']          = $user_id;
        $insertData['user_nickname']    = $userInfo['nickname'];
        $insertData['user_openid']      = $openid;
		$insertData['fieldbb']      = $fieldbb;
		$insertData['fieldbd']      = $fieldbd;
		$insertData['fieldbf']      = $fieldbf;
        $insertData['xm']               = $addressInfo['xm'];
        $insertData['tel']              = $addressInfo['tel'];
		$insertData['fielda']              = $addressInfo['fielda'];
        if($take_type == 1){
            $insertData['address']        = $addressInfo['area_str']." ".$addressInfo['info'];  
        }else{
            $insertData['address']      = lang('plugin/tom_pintuan','take_type_2_msg');
        }
        $insertData['tuan_id']          = $tuan_id;
        $insertData['prepay_id']        = $orderInfo['prepay_id'];
        $insertData['order_status']     = 1;
        $insertData['order_time']       = TIMESTAMP;
        $insertData['order_beizu']      = $order_beizu;
        if(C::t('#tom_pintuan#tom_pintuan_order')->insert($insertData)){
            $order_id = C::t('#tom_pintuan#tom_pintuan_order')->insert_id();
            
            DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num-{$goods_num} WHERE id='$goods_id'", 'UNBUFFERED');
            DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num+{$goods_num} WHERE id='$goods_id'", 'UNBUFFERED');

            if($tstatus == 1 || $tstatus == 2){
                
                $tuanUserTeamTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_by_tuan_user_id($tuan_id,$__UserInfo['id']);
                if($tuanUserTeamTmp){
                    $updateData['order_id'] = $order_id;
                    $updateData['add_time'] = TIMESTAMP;
                    C::t('#tom_pintuan#tom_pintuan_tuan_team')->update($tuanUserTeamTmp['id'],$updateData);
                }else{
                    $insertData = array();
                    $insertData['tuan_id']          = $tuan_id;
                    $insertData['goods_id']         = $goods_id;
                    $insertData['order_id']         = $order_id;
                    $insertData['user_id']          = $user_id;
                    if($tstatus == 1){
                        $insertData['type_id']          = 1;
                    }else{
                        $insertData['type_id']          = 2;
                    }
                    $insertData['add_time']         = TIMESTAMP;
                    C::t('#tom_pintuan#tom_pintuan_tuan_team')->insert($insertData);
                }
                
            }

            $jsapi = new WxPayJsApiPay();
            $jsapi->SetAppid($orderInfo["appid"]);
            $timeStamp = time();
            $timeStamp = "$timeStamp";
            $jsapi->SetTimeStamp($timeStamp);
            $jsapi->SetNonceStr(WxPayApi::getNonceStr());
            $jsapi->SetPackage("prepay_id=" . $orderInfo['prepay_id']);
            $jsapi->SetSignType("MD5");
            $jsapi->SetPaySign($jsapi->MakeSign());
            $parameters = $jsapi->GetValues();

            $outArr = array(
                'status'=> 200,
                'tstatus'=> $tstatus,
                'tuan_id'=> $tuan_id,
                'parameters' => $parameters,
            );
            echo json_encode($outArr); exit;
        }else{
            $outArr = array(
                'status'=> 302,
            );
            echo json_encode($outArr); exit;
        }

    }else{
        $outArr = array(
            'status'=> 303,
        );
        echo json_encode($outArr); exit;
    }
}else if($act == "pay" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    $order_id   = isset($_GET['order_id'])? intval($_GET['order_id']):0;
    
    $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($order_id);
    
    $tuanUrl = '';
    if($orderInfo && $orderInfo['order_status'] == 1){
        
        $goodsInfo  = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($orderInfo['goods_id']);
        
        $tuanInfo = array();
        if($orderInfo['tuan_id']){
            $tuanInfo = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_by_id($orderInfo['tuan_id']);
            $tuanUrl = "plugin.php?id=tom_pintuan&mod=tuan&tlevel={$tuanInfo['tlevel']}&tuan_id={$orderInfo['tuan_id']}";
        }
        
        if($orderInfo['tstatus'] == 2 && $tuanInfo){
            $tuanTeamListTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_list(" AND tuan_id={$orderInfo['tuan_id']} AND type_id=2 ","ORDER BY add_time ASC",0,500);
            $tuanTeamListCount = 1;
            if(is_array($tuanTeamListTmp) && !empty($tuanTeamListTmp)){
                foreach ($tuanTeamListTmp as $key => $value){
                    $ordersinfoTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($value['order_id']);
                    if($ordersinfoTmp['order_status'] == 2 || $ordersinfoTmp['order_status'] == 3 || $ordersinfoTmp['order_status'] == 4 || $ordersinfoTmp['order_status'] == 5){
                        $tuanTeamListCount++;
                    }
                }
            }
            
            if($tuanInfo['tlevel'] == 1){
                $tuan_num = $goodsInfo['tuan_num'];
            }else if($tuanInfo['tlevel'] == 2){
                $tuan_num = $goodsInfo['tuan_num_2'];
            }else if($tuanInfo['tlevel'] == 3){
                $tuan_num = $goodsInfo['tuan_num_3'];
            }else{
                $tuan_num = $goodsInfo['tuan_num'];
            }
            
            if($tuan_num <= $tuanTeamListCount){
                
                $updateData = array();
                $updateData['order_status'] = 6;
                C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
                DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num+{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
                DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num-{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
                
                $outArr = array(
                    'status'=> 305,
                );
                echo json_encode($outArr); exit;
            }
        }
        
        if($goodsInfo['xiangou_num'] > 0){
            $sun_goods_num = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_sun_goods_num(" AND user_id={$orderInfo['user_id']} AND goods_id={$orderInfo['goods_id']} AND order_status IN(2,3,4,5) ");
            $new_sun_goods_num = $sun_goods_num + $orderInfo['goods_num'];
            if( $new_sun_goods_num > $goodsInfo['xiangou_num']){
                $outArr = array(
                    'status'=> 306,
                );
                echo json_encode($outArr); exit;
            }
        }
        
        if((TIMESTAMP - $orderInfo['order_time']) > 6900){
            $updateData = array();
            $updateData['order_status'] = 6;
            C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
            DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num+{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
            DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num-{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
            
            $outArr = array(
                'status'=> 301,
            );
            echo json_encode($outArr); exit;
        }
        
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($wxpay_appid);
        $timeStamp = time();
        $timeStamp = "$timeStamp";
        $jsapi->SetTimeStamp($timeStamp);
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $orderInfo['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        $parameters = $jsapi->GetValues();

        $outArr = array(
            'status'=> 200,
            'tstatus'=> $orderInfo['tstatus'],
            'tuan_url'=> $tuanUrl,
            'parameters' => $parameters,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 302,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "cancelpay" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    $order_id   = isset($_GET['order_id'])? intval($_GET['order_id']):0;
    
    $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($order_id);
    
    if($orderInfo && $orderInfo['order_status'] == 1){
        
        $updateData = array();
        $updateData['order_status'] = 6;
        C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
        
        DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num+{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
        DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num-{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');

        $outArr = array(
            'status'=> 200,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "qrsh" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    $order_id   = isset($_GET['order_id'])? intval($_GET['order_id']):0;
    
    $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($order_id);
    
    if($orderInfo && $orderInfo['order_status'] == 4){
        
        $updateData = array();
        $updateData['order_status'] = 5;
        C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);

        $outArr = array(
            'status'=> 200,
        );
        echo json_encode($outArr); exit;
        
    }else{
        $outArr = array(
            'status'=> 301,
        );
        echo json_encode($outArr); exit;
    }
    
}else if($act == "sure" && $_GET['formhash'] == FORMHASH){
    
    $outArr = array(
        'status'=> 1,
    );
    
    $order_id   = isset($_GET['order_id'])? intval($_GET['order_id']):0;
    $take_pwd   = isset($_GET['take_pwd'])? addslashes($_GET['take_pwd']):"";
    
    $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($order_id);
    $goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($orderInfo['goods_id']);
    
    if($orderInfo && $goodsInfo && ($orderInfo['order_status'] == 2 || $orderInfo['order_status'] == 3 || $orderInfo['order_status'] == 4) && $goodsInfo['take_pwd'] == $take_pwd && $orderInfo['take_type'] == 2 ){
        
        $updateData = array();
        $updateData['order_status'] = 5;
        C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);

        echo 200;exit;
        
    }else{
        echo 301;exit;
    }
    
}else{
    $outArr = array(
        'status'=> 111111,
    );
    echo json_encode($outArr); exit;
}


    
?>
