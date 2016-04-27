<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class tom_user {
    
    public function __construct() {}
    
    public function getOneByOpenid($openid){
        $bindUser = C::t('#tom_weixin#tom_weixin_user')->fetch_one_by_openid($openid);
        if($bindUser){
            return $bindUser;
        }else if(defined('TOM_IN_WECHAT')){
            $wechatLibName = DISCUZ_ROOT . './source/plugin/wechat/wechat.lib.class.php';
            if(file_exists($wechatLibName)){
                $wechatBindUser = C::t('#wechat#common_member_wechat')->fetch_by_openid($openid);
                if($wechatBindUser){
                    loaducenter();
                    $userInfo = uc_get_user($wechatBindUser['uid'],1);
                    $insertData = array();
                    $insertData['openid'] = $openid;
                    $insertData['uid'] = $userInfo['0'];
                    $insertData['username'] = $userInfo['1'];
                    $insertData['bind_time'] = TIMESTAMP;
                    C::t('#tom_weixin#tom_weixin_user')->insert($insertData);
                    return $insertData;
                }
                
            }
        }
        return false;
    }
    public function getOneByUid($uid){
        return C::t('#tom_weixin#tom_weixin_user')->fetch_one_by_uid($uid);
    }
    public function getList($condition,$orders = '',$start = 0,$limit = 10){
        return C::t('#tom_weixin#tom_weixin_user')->fetch_all_list($condition,$orders,$start,$limit);
    }
    public function getCount($condition){
        return C::t('#tom_weixin#tom_weixin_user')->fetch_all_count($condition);
    }
    public function deleteByOpenid($openid){
        C::t('#tom_weixin#tom_weixin_activity')->delete($openid);
        C::t('#tom_weixin#tom_weixin_user')->delete_by_openid($openid);
        if(defined('TOM_IN_WECHAT')){
            $wechatLibName = DISCUZ_ROOT . './source/plugin/wechat/wechat.lib.class.php';
            if(file_exists($wechatLibName)){
                C::t('#tom_weixin#common_member_wechat')->delete_by_openid($openid);
            }
        }
        return true;
    }
    public function deleteByUid($uid){
        return C::t('#tom_weixin#tom_weixin_user')->delete_by_uid($uid);
    }
    public function insert($data = array()){
        if(defined('TOM_IN_WECHAT')){
            $wechatLibName = DISCUZ_ROOT . './source/plugin/wechat/wechat.lib.class.php';
            if(file_exists($wechatLibName)){
                require_once $wechatLibName;
                $wechatBindUser = C::t('#wechat#common_member_wechat')->fetch_by_openid($data['openid']);
                if(!$wechatBindUser){
                    WeChatHook::bindOpenId($data['uid'], $data['openid'], 0);
                }
            }
        }
        return C::t('#tom_weixin#tom_weixin_user')->insert($data);
    }
    
}

?>
