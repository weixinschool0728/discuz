<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$outArr = array(
            'type'      => 'text',
            'content'   => '',
        );
$moduleActivity = $activityClass->getActivityData($tomActivity);
loaducenter();
if(!$tomActivityStatus || empty($moduleActivity)){
    $isBind = $userClass->getOneByOpenid($openid);
    if($isBind){
        $outArr['content'] = $moduleLang['isbind_error'];
    }else{
        $moduleActivity['step'] = 'inusername';
        $activityClass->add($moduleActivity);
        $outArr['content'] = $moduleLang['uidusername'].$exitMsg;
    }
}else{
    if($moduleActivity['step'] == 'inusername'){
        $checkUser = wx_check_user($keyword);
        if($checkUser){
            $moduleActivity['step'] = 'inpassword';
            $moduleActivity['uid'] = $checkUser['0'];
            $moduleActivity['username'] = $checkUser['1'];
            $activityClass->update($moduleActivity);
            $outArr['content'] = $moduleLang['password'].$exitMsg;
        }else{
            $outArr['content'] = $moduleLang['nouidusername'].$exitMsg;
        }
        $moduleActivity = array();
    }
    
    if($moduleActivity['step'] == 'inpassword'){
        $user = uc_user_login($moduleActivity['uid'],$keyword,1);
        if($user['0'] < 0){
            $outArr['content'] = $moduleLang['againpassword'].$exitMsg;
        }else{
            $insertData = array();
            $insertData['openid'] = $openid;
            $insertData['uid'] = $moduleActivity['uid'];
            $insertData['username'] = $moduleActivity['username'];
            $insertData['bind_time'] = TIMESTAMP;
            $userClass->insert($insertData);
            $activityClass->delete();
            $outArr['content'] = $moduleLang['bind_succeed'];		
        }
        $moduleActivity = array();
    }
    
}

?>