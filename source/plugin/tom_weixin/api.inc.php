<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$pluginVarList = C::t('common_pluginvar')->fetch_all_by_pluginid($pluginid);
$tomConfig = array();
foreach ($pluginVarList as $vark => $varv){
    $tomConfig[$varv['variable']] = $varv['value'];
}

$tomScriptLang = $scriptlang['tom_weixin'];

$wxurl = $_G['siteurl']."source/plugin/tom_weixin/wx.php";

showtableheader();
echo '<tr><th colspan="15" class="partition">' . $tomScriptLang['api_help_title'] . '</th></tr>';
echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
echo '<li>'.$tomScriptLang['api_help_1'].'</li>';
echo '</ul></td></tr>';
echo '<tr><th colspan="15" class="partition">' . $tomScriptLang['api_title'] . '</th></tr>';
echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
echo '<li>' . $tomScriptLang['api_url'] . '<input name="" readonly="readonly" type="text" value="'.$wxurl.'" size="100" /></li>';
echo '<li>' . $tomScriptLang['api_token'] . '<input name="" readonly="readonly" type="text" value="'.$tomConfig['wx_token'].'" size="30" /></li>';
echo '</ul></td></tr>';
showtablefooter();

?>
