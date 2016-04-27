<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$openid = '';
$nickname = '';
$headimgurl = '';

$url = $weixinClass->get_url();

$redirect_uri = urlencode($url);

$subscribeFlag = false; 

$oauth2_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";

if(isset($_SESSION['tom_pintuan_openid']) && !empty($_SESSION['tom_pintuan_openid'])){
    $openid = $_SESSION['tom_pintuan_openid'];
    $nickname = $_SESSION['tom_pintuan_nickname'];
    $headimgurl = $_SESSION['tom_pintuan_headimgurl'];
}else{
    if(isset($_GET['code']) && !empty($_GET['code'])){
        $code = $_GET['code'];
        $openid_access_token = get_oauth2_openid_access_token($code,$appid,$appsecret);
        $openid = $openid_access_token['openid'];
        $access_token = $openid_access_token['access_token'];
        if(!empty($openid) && !empty($access_token)){
            $oauth2_snsapi_userinfo = get_oauth2_snsapi_userinfo($access_token,$openid);
            if($oauth2_snsapi_userinfo && isset($oauth2_snsapi_userinfo['nickname'])){
                $nickname = $oauth2_snsapi_userinfo['nickname'];
                $headimgurl = $oauth2_snsapi_userinfo['headimgurl'];
                $_SESSION['tom_pintuan_openid'] = $openid;
                $_SESSION['tom_pintuan_nickname'] = $nickname;
                $_SESSION['tom_pintuan_headimgurl'] = $headimgurl;
            }else{
				dheader('location:'.$oauth2_url);
				exit;
			}
        }else{
            dheader('location:'.$oauth2_url);
            exit;
        }

    }else{
        dheader('location:'.$oauth2_url);
        exit;
    }
}

function get_oauth2_openid($code,$appid,$appsecret){
    $openid = '';
    $get_openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
    $return = get_html($get_openid_url);
    if(!empty($return)){
        $content = json_decode($return,true);
        if(is_array($content) && !empty($content) && isset($content['openid']) && !empty($content['openid'])){
            $openid = $content['openid'];
        }
    }
    return $openid;
}

function get_oauth2_openid_access_token($code,$appid,$appsecret){
    $outArr = array(
        'access_token' => '',
        'openid' => '',
    );
    $get_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
    $return = get_html($get_url);
    if(!empty($return)){
        $content = json_decode($return,true);
        if(is_array($content) && !empty($content) && isset($content['access_token']) && !empty($content['access_token'])){
            $outArr = array(
                'access_token' => $content['access_token'],
                'openid' => $content['openid'],
            );
        }
    }
    return $outArr;
}

function get_oauth2_snsapi_userinfo($access_token,$openid){
    $outArr = array();
    
    $get_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
    $return = get_html($get_url);
    if(!empty($return)){
        $content = json_decode($return,true);
        if(is_array($content) && !empty($content) && isset($content['nickname']) && !empty($content['nickname'])){
            $outArr = $content;
        }
    }
    return $outArr;
}



?>
