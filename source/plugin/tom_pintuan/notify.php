<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
   微信支付回调接口文件
*/

define('APPTYPEID', 127);
define('CURSCRIPT', 'plugin');
define('DISABLEXSSCHECK', true); 

$_GET['id'] = 'tom_pintuan';

require substr(dirname(__FILE__), 0, -26).'/source/class/class_core.php';

$discuz = C::app();
$cachelist = array('plugin', 'diytemplatename');

$discuz->cachelist = $cachelist;
$discuz->init();

define('CURMODULE', 'tom_pintuan');

$_G['siteurl'] = substr($_G['siteurl'], 0, -26);
$_G['siteroot'] = substr( $_G ['siteroot'], 0, - 26);

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
include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/wxpay/lib/WxPay.Notify.php';
include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/wxpay/log.php';

$logDir = DISCUZ_ROOT."./source/plugin/tom_pintuan/logs/";
if(!is_dir($logDir)){
    mkdir($logDir, 0777,true);
}else{
    chmod($logDir, 0777); 
}
$logHandler= new CLogFileHandler(DISCUZ_ROOT."./source/plugin/tom_pintuan/logs/".date("Y-m-d").".log");
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify{
    
	public function Queryorder($transaction_id){
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
			return true;
		}
		return false;
	}
	
	public function NotifyProcess($data, &$msg){
        global $pintuanConfig,$_G;
        
        Log::DEBUG("call back:" . json_encode($data));
        
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
            Log::DEBUG("error:can shu cuo wu");
            $msg = "can shu cuo wu";
			return false;
		}
		if(!$this->Queryorder($data["transaction_id"])){
            Log::DEBUG("error:ding dan cha xu shi bai");
            $msg = "ding dan cha xu shi bai";
			return false;
		}
        
        if(isset($data['result_code']) && $data['result_code']=='SUCCESS'){
        }else{
            Log::DEBUG("error:result_code error");
            $msg = "result_code error";
            return false;
        }
        
        if(isset($data['out_trade_no']) && !empty($data['out_trade_no'])){
        }else{
            Log::DEBUG("error:out_trade_no error");
            $msg = "out_trade_no error";
            return false;
        }
        
        $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_order_no($data['out_trade_no']);
        if($orderInfo && $orderInfo['order_status'] == 1){
            $updateData = array();
            $updateData['order_status'] = 2;
            $updateData['pay_time'] = TIMESTAMP;
            C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
            
            Log::DEBUG("update order:" . json_encode($orderInfo));
            
            if($orderInfo['tstatus'] == 1){
                $tuanInfo = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_by_id($orderInfo['tuan_id']);
                if($tuanInfo && $tuanInfo['tuan_status'] == 1){
                    $updateData = array();
                    $updateData['tuan_status'] = 2;
                    $updateData['tuan_time'] = TIMESTAMP;
                    C::t('#tom_pintuan#tom_pintuan_tuan')->update($tuanInfo['id'],$updateData);
                }
                Log::DEBUG("update tuan:" . json_encode($tuanInfo));
            }  
            
            if($pintuanConfig['open_template_sms'] == 1){
                include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/weixin.class.php';
                include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/templatesms.class.php';
                if (CHARSET == 'gbk') {
                    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.gbk.php';
                }else{
                    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.utf8.php';
                }
                $weixinClass = new weixinClass($pintuanConfig['wxpay_appid'],$pintuanConfig['wxpay_appsecret']);
                $access_token = $weixinClass->get_access_token();
                if($access_token){
                    $tomSysOffset = getglobal('setting/timeoffset');
                    $templateSmsClass = new templateSms($access_token, $_G['siteurl']."plugin.php?id=tom_pintuan&mod=index");
                    $smsData = array(
                        'first'                 => lang('plugin/tom_pintuan','template_sms_neworder'),
                        'tradeDateTime'         => dgmdate(TIMESTAMP, 'Y-m-d H:i:s',$tomSysOffset),
                        'orderType'             => $tstatusArray[$orderInfo['tstatus']],
                        'customerInfo'          => $orderInfo['xm'].' '.$orderInfo['tel'],
                        'orderItemName'         => lang('plugin/tom_pintuan','template_sms_goodsname'),
                        'orderItemData'         => $orderInfo['goods_name'],
                        'remark'                => lang('plugin/tom_pintuan','template_sms_order_no').$orderInfo['order_no'],
                    );
                    
                    if(!empty($pintuanConfig['manage_1_openid'])){
                        $r = $templateSmsClass->sendSmsTm00351($pintuanConfig['manage_1_openid'],$pintuanConfig['template_tm00351'],$smsData);
                        if($r){
                            Log::DEBUG("template sms manage_1_openid:" . json_encode($smsData));
                        }
                    }
                    
                    $goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($orderInfo['goods_id']);
                    $shopInfo = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_by_id($goodsInfo['shop_id']);
                    if($shopInfo && !empty($shopInfo['manage_openid'])){
                        $r = $templateSmsClass->sendSmsTm00351($shopInfo['manage_openid'],$pintuanConfig['template_tm00351'],$smsData);
                        if($r){
                            Log::DEBUG("template sms manage_shop_openid:" . json_encode($smsData));
                        }
                    }
                    
                    if(!empty($pintuanConfig['manage_2_openid'])){
                        $r = $templateSmsClass->sendSmsTm00351($pintuanConfig['manage_2_openid'],$pintuanConfig['template_tm00351'],$smsData);
                        if($r){
                            Log::DEBUG("template sms manage_2_openid:" . json_encode($smsData));
                        }
                    }
                    
                }
            }
            
        }
        
        
        
		return true;
	}
    
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
?>
