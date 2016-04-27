<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function wx_array_sort($array,$keys,$type='desc'){ 
	$keyValue = $new_array = array();
	foreach ($array as $k => $v){
		$keyValue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keyValue);
	}else{
		arsort($keyValue);
	}
	reset($keyValue);
	foreach ($keyValue as $k => $v){
		$new_array[$k] = $array[$k];
	}
	return $new_array; 
} 

function wx_forum_threadimage($tid = 0,$type=""){
    global $_G;
    $threadimage = DB::fetch_first("SELECT * FROM %t WHERE tid=%d", array('forum_threadimage', $tid));
    $picUrl = '';
    if($threadimage){
        //$picUrl = $_G['siteurl'].$_G['setting']['attachurl'].'forum/'.$threadimage['attachment'];
        $picUrl = $threadimage['remote'] ? $_G['setting']['ftp']['attachurl'].'forum/'.$threadimage['attachment'] : $_G['siteurl'].$_G['setting']['attachurl'].'forum/'.$threadimage['attachment'];
    }else{
        if($type == "big"){
            $picUrl = $_G['siteurl']."source/plugin/tom_weixin/images/news_no_big.jpg";
        } else {
            $picUrl = $_G['siteurl']."source/plugin/tom_weixin/images/news_no_small.jpg";
        }
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/noimage.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
    }
    return $picUrl;
}

function wx_check_user($uidUsername = ""){
    loaducenter();
    $checkUsername = uc_get_user($uidUsername);
    if(is_array($checkUsername)){
        return $checkUsername;
    }
    $checkUid = uc_get_user(intval($uidUsername),1);
    if(is_array($checkUid)){
        return $checkUid;
    }
    return false;
}

function wx_forum_login($openid = "",$dreferer = ""){
    $userInfo = C::t('#tom_weixin#tom_weixin_user')->fetch_one_by_openid($openid);
    if($userInfo && TOM_AUTO_LOGIN == 1){
        $outUrl = TOM_SITEURL."plugin.php?id=tom_weixin:login&openid=".$openid."&dreferer=".base64_encode($dreferer);
    }else{
        $outUrl = $dreferer;
    }
    return $outUrl;
}

function get_access_token($appid = "",$appsecret = ""){
    $access_token = '';
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
    $returnData = get_html($url);
    $returnArr = array();
    if($returnData){
        $returnArr = json_decode($returnData, true);
        if(isset($returnArr['access_token'])){
            $access_token = $returnArr['access_token'];
        }
    }
    return $access_token;
}

function get_html($url){
    if(function_exists('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $return = curl_exec($ch);
        curl_close($ch); 
        return $return;
    }
    return false;
    
}

function check_server_status($url){
    if(function_exists('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $return = curl_exec($ch);
        
        $http_code = "";
        if(!curl_errno($ch)){
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        curl_close($ch); 
        
        if($http_code === 200 || $http_code === "200"){
            return true;
        }else{
            return false;
        }
    }
    return false;
}

?>
