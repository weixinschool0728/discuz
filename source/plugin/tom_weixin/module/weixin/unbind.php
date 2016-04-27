<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$outArr = array(
            'type'      => 'text',
            'content'   => '',
        );

if($userInfo){
    $userClass->deleteByOpenid($openid);
}
$outArr['content'] = $moduleLang['unbind_success'];
?>