<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$tomScriptLang = $scriptlang['tom_weixin'];
$moduleBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=module'; 
$moduleListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=module';
include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/module.class.php';
$moduleClass = new tom_module();
$sysCmdStringArray = array('@','#','+');
$sysCmdString = implode(',', $sysCmdStringArray);

$act =  $_GET['act']? $_GET['act']:'';
$formhash =  $_GET['formhash']? $_GET['formhash']:'';

$moduleConfig = array();
$moduleSettingExt = array();
$moduleLang = array();
if(isset($_GET['moduleid'])){
    $configName = $moduleClass->getConfigName($_GET['moduleid']);
    if($configName){
        include $configName;
    }
}
if($act == 'config'){
    $moduleInfo = $moduleClass->getOneByModuleId($_GET['moduleid']);
    $moduleSetting = array();
    if ($moduleInfo && !empty($moduleInfo['module_setting'])){
        $moduleSetting = $moduleClass->decodeSetting($moduleInfo['module_setting']);
    }
    showformheader('plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=module&act=save&moduleid='.$moduleInfo['module_id']);
    showtableheader();
    echo '<tr class="header"><th>'.$tomScriptLang['module_cmd'].'</th><th></th></tr>';
    echo '<tr><td width="300"><input name="modulecmd" type="text" value="'.$moduleInfo['module_cmd'].'" size="40" /></td><td>'.$sysCmdString.$tomScriptLang['not_cmd_msg'].'</td></tr>';
    echo '<tr class="header"><th>'.$tomScriptLang['sort'].'</th><th></th></tr>';
    echo '<tr><td><input name="sort" type="text" value="'.$moduleInfo['sort'].'" size="40" /></td><td></td></tr>';
    if(is_array($moduleSettingExt) && !empty($moduleSettingExt)){
        foreach ($moduleSettingExt as $key => $value){
            if(isset($moduleSetting[$value['name']])){
                $moduleSettingExt[$key]['value'] = $moduleSetting[$value['name']];
            }
        }
        echo $moduleClass->createExtendHtml($moduleSettingExt);
    }
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
}else if($act == 'save' && submitcheck('submit')){
    $moduleInfo = $moduleClass->getOneByModuleId($_GET['moduleid']);
    $checkCmd = true;
    foreach ($sysCmdStringArray as $key => $value){
        if(strpos($_GET['modulecmd'], $value) !==false ){
            $checkCmd = false;
        }
    }
    if(!$checkCmd){
        cpmsg($sysCmdString.$tomScriptLang['not_cmd_set'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=module&act=config&moduleid='.$moduleInfo['module_id'], 'succeed');
    }else{
        $moduleSettingStr = $moduleClass->encodeSetting($moduleSettingExt, $_GET);
        $updateData = array();
        $updateData['module_cmd']       = $_GET['modulecmd'];
        $updateData['sort']             = $_GET['sort'];
        $updateData['module_setting']   = $moduleSettingStr;
        $moduleClass->update($moduleInfo['id'],$updateData);
        cpmsg($tomScriptLang['act_success'], $moduleListUrl, 'succeed');
    }
}else if ($formhash == FORMHASH && $act == 'install') {
    $isInstallModule = $moduleClass->getOneByModuleId($_GET['moduleid']);
    if (!$isInstallModule) {
        $moduleSettingStr = $moduleClass->encodeSetting($moduleSettingExt);
        $insertData = array();
        $insertData['module_id']        = $_GET['moduleid'];
        $insertData['module_cmd']       = $moduleConfig['module_cmd'];
        $insertData['module_desc']      = $moduleConfig['module_desc'];
        $insertData['module_setting']   = $moduleSettingStr;
        $insertData['power_id']         = $moduleConfig['power_id'];
        $insertData['part1']            = $moduleConfig['module_ver'];
        if(isset($moduleConfig['is_menu']) && $moduleConfig['is_menu'] == 2){
            $insertData['is_menu']      = 2;
        }
        $moduleClass->insert($insertData);
        
        $extendName = $moduleClass->getExtendName($_GET['moduleid']);
        if($extendName){
            include $extendName;
            moduleInstall();
        }
    }
    cpmsg($tomScriptLang['act_success'], $moduleListUrl, 'succeed');
}elseif($formhash == FORMHASH && $act == 'upgrade'){
    C::t('#tom_weixin#tom_weixin_module')->update_module_ver($_GET['moduleid'],$moduleConfig['module_ver']);
    $extendName = $moduleClass->getExtendName($_GET['moduleid']);
    if($extendName){
        include $extendName;
        moduleUpgrade();
    }
    cpmsg($tomScriptLang['act_success'], $moduleListUrl, 'succeed');
}elseif($formhash == FORMHASH && $act == 'uninstall'){
    $moduleClass->deleteBymoduleId($_GET['moduleid']);
    $extendName = $moduleClass->getExtendName($_GET['moduleid']);
    if($extendName){
        include $extendName;
        moduleUninstall();
    }
    cpmsg($tomScriptLang['act_success'], $moduleListUrl, 'succeed');
}else if($formhash == FORMHASH && ($act == 'enable' || $act == 'disable')){
    if($act == 'enable'){
        $moduleClass->enableByModuleId($_GET['moduleid']);
    }else{
        $moduleClass->disableByModuleId($_GET['moduleid']);
    }
    cpmsg($tomScriptLang['act_success'], $moduleListUrl, 'succeed');
}elseif($act == 'admin'){
    $adminName = $moduleClass->getAdminName($_GET['moduleid']);
    if ($adminName) {
        include $adminName;
    }
}else{
    $allModuleList = $moduleClass->getAllList();
    $installModuleList = $moduleClass->getInstallList();
    $tomScriptLang['module_help_1'] = str_replace("{more}",$_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=moremodule',$tomScriptLang['module_help_1']);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $tomScriptLang['module_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $tomScriptLang['module_help_1'] . '</li>';
    echo '<li>' . $tomScriptLang['module_help_2'] . '</li>';
    echo '<li>' . $tomScriptLang['module_help_3'] . '</li>';
    echo '</ul></td></tr>';
    showtablefooter();
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $tomScriptLang['module_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $tomScriptLang['module_cmd'] . '</th>';
    echo '<th>' . $tomScriptLang['module_desc'] . '</th>';
    echo '<th>' . $tomScriptLang['module_ver'] . '</th>';
    echo '<th>' . $tomScriptLang['power'] . '</th>';
    echo '<th>' . $tomScriptLang['sort'] . '</th>';
    echo '<th>' . $tomScriptLang['handle'] . '</th>';
    echo '</tr>';
    $installModuleArr = array();
    foreach ($installModuleList as $key => $value) {
        $installModuleArr[$value['module_id']] = $value;
    }
    foreach ($allModuleList as $key => $moduleid) {
        $moduleConfig = array();
        $configName = $moduleClass->getConfigName($moduleid);
        if($configName){
            include $configName;
        }
        $isInstall = false;
        $moduleConfig['sort'] = 100;
        $moduleConfig['old_ver'] = 0;
        if(isset($installModuleArr[$moduleid])){
            $isInstall = true;
            $moduleConfig['module_cmd'] = $installModuleArr[$moduleid]['module_cmd'];
            $moduleConfig['module_desc'] = $installModuleArr[$moduleid]['module_desc'];
            $moduleConfig['sort'] = $installModuleArr[$moduleid]['sort'];
            $moduleConfig['status'] = $installModuleArr[$moduleid]['status'];
            $moduleConfig['old_ver'] = empty($installModuleArr[$moduleid]['part1'])? 0:$installModuleArr[$moduleid]['part1'];
        }
        $powerStr = '';
        if($moduleConfig['power_id'] == 1){
            $powerStr = $tomScriptLang['member'];
        }else if($moduleConfig['power_id'] == 2){
            $powerStr = $tomScriptLang['manage'];
        }else{
            $powerStr = $tomScriptLang['all'];
        }
        
        echo '<tr>';
        echo '<td>' . $moduleConfig['module_cmd'] . '</td>';
        echo '<td>' . $moduleConfig['module_desc'] . '</td>';
        if($moduleConfig['module_ver'] > $moduleConfig['old_ver']){
            echo '<td>' . $moduleConfig['old_ver'].'<font color="#F60">('.$moduleConfig['module_ver'].')</font></td>';
        }else{
            echo '<td>' . $moduleConfig['module_ver'].'</td>';
        }
        echo '<td>' . $powerStr . '</td>';
        echo '<td>' . $moduleConfig['sort'] . '</td>';
        echo '<td>';
        if($isInstall){
            echo '<a href="'.$moduleBaseUrl.'&act=config&moduleid='.$moduleid.'">' . $tomScriptLang['setup'] . '</a>&nbsp;|&nbsp;';
            echo '<a href="'.$moduleBaseUrl.'&act=uninstall&moduleid='.$moduleid.'&formhash='.FORMHASH.'">' . $tomScriptLang['delete'] . '</a>&nbsp;|&nbsp;';
            if($moduleConfig['status'] == 1){
                echo '<a href="'.$moduleBaseUrl.'&act=disable&moduleid='.$moduleid.'&formhash='.FORMHASH.'">' . $tomScriptLang['disable'] . '</a>&nbsp;|&nbsp;';
            }else{
                echo '<a href="'.$moduleBaseUrl.'&act=enable&moduleid='.$moduleid.'&formhash='.FORMHASH.'">' . $tomScriptLang['enable'] . '</a>&nbsp;|&nbsp;';
            }
            if($moduleConfig['module_ver'] > $moduleConfig['old_ver']){
                echo '<a href="'.$moduleBaseUrl.'&act=upgrade&moduleid='.$moduleid.'&formhash='.FORMHASH.'"><font color="#F60">' . $tomScriptLang['update'] . '</font></a>';
            }else{
                echo '<a href="'.$moduleBaseUrl.'&act=upgrade&moduleid='.$moduleid.'&formhash='.FORMHASH.'">' . $tomScriptLang['update'] . '</a>';
            }

            if(isset($moduleConfig['admin']) && $moduleConfig['admin'] == 1){
                echo '&nbsp;|&nbsp;<a href="'.$moduleBaseUrl.'&act=admin&moduleid='.$moduleid.'">' . $moduleConfig['admin_name'] . '</a>';
            }
        }else{
            echo '<a href="'.$moduleBaseUrl.'&act=install&moduleid='.$moduleid.'&formhash='.FORMHASH.'">' . $tomScriptLang['module_install'] . '</a>';
        }
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
}
?>
