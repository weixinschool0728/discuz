<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$tomScriptLang = $scriptlang['tom_weixin'];

$act = isset($_GET['act'])? $_GET['act']:'';
$formhash =  $_GET['formhash']? $_GET['formhash']:'';
if ($formhash == FORMHASH && $act == 'del'){
    C::t('#tom_weixin#tom_weixin_plugin')->delete($_GET['id']);
    cpmsg($tomScriptLang['act_success'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=plugin', 'succeed');
}else{
    $pluginList = C::t('#tom_weixin#tom_weixin_plugin')->fetch_all_list('*','',"sort ASC");
	showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $tomScriptLang['plugin_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $tomScriptLang['plugin_cmd'] . '</th>';
    echo '<th>' . $tomScriptLang['plugin_desc'] . '</th>';
    echo '<th>' . $tomScriptLang['plugin_status'] . '</th>';
    echo '<th>' . $tomScriptLang['handle'] . '</th>';
    echo '</tr>';
	foreach ($pluginList as $key => $value){
        $statusMsg = '';
        if($value['status'] == 1){
            $statusMsg = $tomScriptLang['enable'];
        }else if($value['status'] == 2){
            $statusMsg = $tomScriptLang['disable'];
        }
        echo '<tr>';
        echo '<td>' . $value['plugin_cmd'] . '</td>';
        echo '<td>' . $value['plugin_desc'] . '</td>';
        echo '<td>' . $statusMsg . '</td>';
        echo '<td>';
        echo '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=plugin&act=del&id='.$value['id'].'&formhash='.FORMHASH.'">' . $tomScriptLang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
	}
	showtablefooter();
}

?>