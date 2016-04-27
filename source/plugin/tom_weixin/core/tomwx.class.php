<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class tom_weixin{
    
    var $openid = '';
    var $msgtype = '';
    
    public function __construct(){}
    
    public static function getInstance(){
        return new self();
    }
    
    public function set_openid($openid = ''){
        $openid = addslashes(dhtmlspecialchars($openid));
        $this->openid = $openid;
        return;
    }
    
    public function set_msgtype($msgtype = ''){
        $this->msgtype = addslashes(dhtmlspecialchars($msgtype));
        return;
    }
    
    public function get_openid(){
        return $this->openid;
    }

    public function subscribe(){
        global $_G,$tomConfig,$moduleClass;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array(
            'type'      => 'text',
            'content'   => '',
        );
        $outArr['content'] = str_replace("{n}", "\n", $tomConfig['wx_subscribe']);
        
        $subData = array();
        $subData['open_id'] = $openid;
        $subData['sub_time'] = TIMESTAMP;
        C::t('#tom_weixin#tom_weixin_subuser')->insert($subData);
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/subscribe.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
        return $outArr;
    }
    public function unsubscribe(){
        global $_G,$tomConfig,$moduleClass;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $this->delActivity();
        
        C::t('#tom_weixin#tom_weixin_log')->delete_by_openid($openid);
        C::t('#tom_weixin#tom_weixin_subuser')->delete_by_openid($openid);
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/unsubscribe.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
    }
    
    public function click($keyword){
        global $_G,$tomConfig;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array();
        
        $this->delActivity();
        $outArr = $this->doCmd($keyword);
        
        return $outArr;
    }
    
    public function msg($keyword){
        global $_G,$tomConfig,$moduleClass;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $keyword = addslashes(dhtmlspecialchars($keyword));
        
//        $logData = array();
//        $logData['open_id'] = $openid;
//        $logData['keywords'] = $keyword;
//        $logData['msg_type'] = $msgtype;
//        $logData['log_time'] = TIMESTAMP;
//        C::t('#tom_weixin#tom_weixin_log')->insert($logData);
//        
//        $logCount = C::t('#tom_weixin#tom_weixin_log')->fetch_all_count("");
//        if($logCount > 100000){
//            C::t('#tom_weixin#tom_weixin_log')->delete_by_logtime();
//        }
        
        $subuser = C::t('#tom_weixin#tom_weixin_subuser')->fetch_by_openid($openid);
        if(!$subuser){
            $subData = array();
            $subData['open_id'] = $openid;
            $subData['sub_time'] = TIMESTAMP;
            C::t('#tom_weixin#tom_weixin_subuser')->insert($subData);
        }
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/msg.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
        
        return true;
    }
    
    public function view($keyword){
        global $_G,$tomConfig,$moduleClass;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array(
            'type'      => 'text',
            'content'   => '',
        );
        $keyword = addslashes(dhtmlspecialchars($keyword));
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/view.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
        return $outArr;
    }
    
    public function location($keyword){
        global $_G,$tomConfig,$moduleClass;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array(
            'type'      => 'text',
            'content'   => '',
        );
        $keyword = addslashes(dhtmlspecialchars($keyword));
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/location.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
        
        return $outArr;
    }
    
    public function scan($keyword){
        global $_G,$tomConfig,$moduleClass;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array(
            'type'      => 'text',
            'content'   => '',
        );
        $keyword = addslashes(dhtmlspecialchars($keyword));
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/scan.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
        
        return $outArr;
    }

    public function text($keyword = ''){
        global $_G,$tomConfig;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array();
        $outArr = $this->doCmd($keyword);
        return $outArr;
    }
    
    public function main(){
        global $_G,$tomConfig;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array(
            'type'      => 'text',
            'content'   => '',
        );
        $pluginList = C::t('#tom_weixin#tom_weixin_plugin')->fetch_all_list('*','status = 1',"sort ASC");
        $moduleList = C::t('#tom_weixin#tom_weixin_module')->fetch_all_list('*','status = 1 AND is_menu = 1 ',"sort ASC");
        
        $menuStr = lang('plugin/tom_weixin','tom_menu');
        foreach ($moduleList as $key => $value){
            $menuStr.= "\n".$value['module_cmd'].lang('plugin/tom_weixin','tom_menu_tag').$value['module_desc'];
        }
        foreach ($pluginList as $key => $value){
            $menuStr.= "\n".$value['plugin_cmd'].lang('plugin/tom_weixin','tom_menu_tag').$value['plugin_desc'];
        }
        
        $outArr['content'] = $menuStr;
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/main.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
        return $outArr;
    }

    public function doCmd($keyword){
        global $_G,$tomConfig,$moduleClass,$userClass;
        $openid = $this->openid;
        $msgtype = $this->msgtype;
        $outArr = array(
            'type'      => '',
            'content'   => '',
        );
        
        $keyword = addslashes(dhtmlspecialchars($keyword));
        
        if($keyword == $tomConfig['main_cmd'] || $keyword == "help"){
            $outArr = $this->main();
            $this->delActivity();
            return $outArr;
        }
        
        if($keyword == $tomConfig['exit_cmd']){
             $outArr = $this->exitActivity();
             return $outArr;
        }
        
        $isDoHookContent = false;
        $hookFilename = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/docmd.hook.php';
        if(file_exists($hookFilename)){
            include $hookFilename;
        }
        if($isDoHookContent){
            return $outArr;
        }
        
        $manageUser = unserialize($tomConfig['manage_user']);
        
        $activityClass = new tom_activity($openid,$keyword);
        
        $userInfo = $userClass->getOneByOpenid($openid);
        $exitMsg = "\n".str_replace('{exit}',$tomConfig['exit_cmd'],lang('plugin/tom_weixin','exit_msg'));
        $activityClass->delete_by_acttime();
        $tomActivity = $activityClass->getActivity();
        $tomActivityStatus = false;
        if($tomActivity){
            $tomActivityStatus = true;
            if($tomActivity['act_type'] == 1){
                $moduleInfo = $moduleClass->getOneByModuleCmd($tomActivity['act_cmd']);
                if($moduleInfo){
                    $moduleSetting = array();
                    if(!empty($moduleInfo['module_setting'])){
                        $moduleSetting = $moduleClass->decodeSetting($moduleInfo['module_setting']);
                    }
                    $moduleConfigName = $moduleClass->getConfigName($moduleInfo['module_id']);
                    if($moduleConfigName){
                        include $moduleConfigName;
                    }
                    $moduleFilename = $moduleClass->getWeixinName($moduleInfo['module_id']);
                    if($moduleFilename){
                        include $moduleFilename;
                    }
                }
            }else if($tomActivity['act_type'] == 2){
                $pluginInfo= C::t('#tom_weixin#tom_weixin_plugin')->fetch_one_by_plugincmd($tomActivity['act_cmd']);
                if($pluginInfo){
                    $pluginFilename = DISCUZ_ROOT.'./source/plugin/'.$pluginInfo['plugin_id'].'/tom.plugin.php';
                    if(file_exists($pluginFilename)){
                        include $pluginFilename;
                    }
                }
            }
        }else{
            $pluginInfo = C::t('#tom_weixin#tom_weixin_plugin')->fetch_one_by_plugincmd2($keyword);
            $moduleInfo = $moduleClass->getOneByModuleCmd($keyword);
            if($pluginInfo){
                $activityClass->setActtype(2);
                $pluginFilename = DISCUZ_ROOT.'./source/plugin/'.$pluginInfo['plugin_id'].'/tom.plugin.php';
                if(file_exists($pluginFilename)){
                    include $pluginFilename;
                }
            }else if($moduleInfo){
                $activityClass->setActtype(1);
                $moduleSetting = array();
                if(!empty($moduleInfo['module_setting'])){
                    $moduleSetting = $moduleClass->decodeSetting($moduleInfo['module_setting']);
                }
                $moduleConfigName = $moduleClass->getConfigName($moduleInfo['module_id']);
                if($moduleConfigName){
                    include $moduleConfigName;
                }
                $moduleFilename = $moduleClass->getWeixinName($moduleInfo['module_id']);
                if($moduleInfo['power_id'] == 1 || $moduleInfo['power_id'] == 2){
                    if($userInfo){
                        if($moduleInfo['power_id'] == 2){ 
                            $groupInfo = C::t('common_member')->fetch_by_username($userInfo['username']);
                            if(in_array($groupInfo['groupid'],$manageUser)){
                                if($moduleFilename){
                                    include $moduleFilename;
                                }
                            }else{
                                $outArr = array(
                                    'type'      => 'text',
                                    'content'   => lang('plugin/tom_weixin','manage_cmd'),
                                );
                            }
                        }else{
                            if($moduleFilename){
                                include $moduleFilename;
                            }
                        }
                    }else{
                        $outArr = array(
                            'type'      => 'text',
                            'content'   => lang('plugin/tom_weixin','must_bind'),
                        );
                        if(isset($tomConfig['must_bind']) && !empty($tomConfig['must_bind'])){
                            $mustBindStr = str_replace("SITEURL", TOM_SITEURL, $tomConfig['must_bind']);
                            $mustBindStr = str_replace("{openid}", $openid, $mustBindStr);
                            $outArr['content'] = $mustBindStr;
                        }
                    }
                }else{
                    if(file_exists($moduleFilename)){
                        include $moduleFilename;
                    }
                }
                
            }
        }
        $isDoHookContent = false;
        $hookFilenameEnd = DISCUZ_ROOT.'./source/plugin/tom_weixin/hook/doend.hook.php';
        if(file_exists($hookFilenameEnd)){
            include $hookFilenameEnd;
        }
        if(empty($outArr['type']) || empty($outArr['content'])){
            if(!empty($tomConfig['cmd_err_msg'])){
                $errorMsg = lang('plugin/tom_weixin','errorcmd');
                $outArr = array(
                    'type'      => 'text',
                    'content'   => $errorMsg.$tomConfig['cmd_err_msg'],
                );
            }else{
                echo 'success';exit;
            }
        }
        return $outArr;
    }
    public function exitActivity(){
        global $_G,$tomConfig;
        $outArr = array(
            'type'      => 'text',
            'content'   => lang('plugin/tom_weixin','exit_ok_msg'),
        );
        C::t('#tom_weixin#tom_weixin_activity')->delete($this->openid);
        return $outArr;
    }
    public function delActivity(){
        C::t('#tom_weixin#tom_weixin_activity')->delete($this->openid);
    }
    
}
?>
