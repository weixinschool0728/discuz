<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$allHookData = array(
    'receiveAllStart' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'all'
	),
    'receiveMsg::text' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveMsg::location' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveMsg::image' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveMsg::video' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveMsg::link' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveMsg::voice' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveEvent::unsubscribe' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveEvent::location' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
    'receiveEvent::click' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
);
$tomSubscribeHookData = array(
    'receiveEvent::subscribe' => array(
		'plugin' => 'tom_weixin',
		'include' => 'wechat.class.php',
		'class' => 'tom_wechat',
		'method' => 'run'
	),
);
$wechatSubscribeHookData = array(
    'receiveEvent::subscribe' => array(
		'plugin' => 'wechat',
		'include' => 'response.class.php',
		'class' => 'WSQResponse',
		'method' => 'subscribe'
	),
);

$tomScriptLang = $scriptlang['tom_weixin'];
if(submitcheck('submit')){
    require_once DISCUZ_ROOT . './source/plugin/wechat/wechat.lib.class.php';
    WeChatHook::updateResponse($allHookData);
    $subscribeType = isset($_GET['subscribe'])? intval($_GET['subscribe']):1;
    if($subscribeType == 1){
        WeChatHook::updateResponse($wechatSubscribeHookData);
    }else{
        WeChatHook::updateResponse($tomSubscribeHookData);
    }
    cpmsg($tomScriptLang['act_success'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=wechat', 'succeed');
}
$wechatLibName = DISCUZ_ROOT . './source/plugin/wechat/wechat.lib.class.php';
$isWechat = false;
if(file_exists($wechatLibName)){
    $isWechat = true;
    require_once $wechatLibName;
}
$isHookError = false;

$tomSubscribeStatus = "";
$wechatSubscribeStatus = "";
if($isWechat){
    $doHookData = WeChatHook::getResponse();
    foreach ($allHookData as $key => $value){
        if($doHookData[$key]['plugin'] != $value['plugin'] || $doHookData[$key]['include'] != $value['include'] || $doHookData[$key]['class'] != $value['class'] || $doHookData[$key]['method'] != $value['method']){
            $isHookError = true;
        }
    }
    if($doHookData['receiveEvent::subscribe']['plugin'] == 'tom_weixin'){
        $tomSubscribeStatus = "checked";
    }
    if($doHookData['receiveEvent::subscribe']['plugin'] == 'wechat'){
        $wechatSubscribeStatus = "checked";
    }
}
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=wechat');
showtableheader();
echo '<tr><th colspan="15" class="partition">' . $tomScriptLang['wechat_title'] . '</th></tr>';
echo '<tr><td class="tipsblock" s="1"><ul id="tipslis">';
echo '<li>' . $tomScriptLang['wechat_help_1'] . '</li>';
echo '<li>' . $tomScriptLang['wechat_help_2'] . '</li>';
echo '<li>' . $tomScriptLang['wechat_help_3'] . '</li>';
echo '</ul></td></tr>';

echo '<tr><th colspan="15" class="partition">'.$tomScriptLang['wechat_setting'].'</th><th></th></tr>';
echo '<tr><td>'.$tomScriptLang['wechat_gz_title'].'<input type="radio" name="subscribe" value="1" '.$wechatSubscribeStatus.'>'.$tomScriptLang['wechat_gz_wechat'].'&nbsp;<input type="radio" name="subscribe" value="2" '.$tomSubscribeStatus.'>'.$tomScriptLang['wechat_gz_tom'].'</td><td></td></tr>';

if($isWechat && $isHookError){
    echo '<tr><th colspan="15"><font color="#FF0000"><b><img class="vmiddle" style="width: 20px;height: 20px;margin-right: 5px;" src="source/plugin/tom_weixin/images/no.png">' . $tomScriptLang['wechat_hook_error'] . '</b></font></th></tr>';
}
if($isWechat && !$isHookError){
    echo '<tr><th colspan="15"><font color="#05a705"><b><img class="vmiddle" style="width: 20px;height: 20px;margin-right: 5px;" src="source/plugin/tom_weixin/images/ok.png">' . $tomScriptLang['wechat_hook_ok'] . '</b></font></th></tr>';
}
if(!$isWechat){
    echo '<tr><th colspan="15"><font color="#FF0000"><b><img class="vmiddle" style="width: 20px;height: 20px;margin-right: 5px;" src="source/plugin/tom_weixin/images/no.png">' . $tomScriptLang['wechat_no_install'] . '</b></font></th></tr>';
}else{
    showsubmit('submit', $tomScriptLang['wechat_btn']);
}
showtablefooter();
showformfooter();



?>
