<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class tom_activity {
    
    var $openid = '';
    var $keyword = '';
    var $act_type = 0;
    
    public function __construct($openid,$keyword) {
        $this->openid = $openid;
        $this->keyword = $keyword;
    }
    
    public function setActtype($act_type){
        $this->act_type = $act_type;
        return true;
    }
    
    public function setKeyword($keyword){
        $this->keyword = $keyword;
        return true;
    }
    
    public function getActivity(){
        $return = C::t('#tom_weixin#tom_weixin_activity')->fetch_one_by_openid($this->openid);
        return $return;
    }
    
    public function getActivityData($data = array()){
        $outArr = array();
        if(is_array($data) && !empty($data) && !empty($data['act_text'])){
            $outArr = unserialize(base64_decode($data['act_text']));
        }
        return $outArr;
    }


    public function add($data = array()){
        $data = base64_encode(serialize($data));
        C::t('#tom_weixin#tom_weixin_activity')->insert(array('openid'=>$this->openid,'act_text'=>$data,'act_cmd'=>$this->keyword,'act_type'=>$this->act_type,'act_time'=>TIMESTAMP));
        return true;
    }
    
    public function update($data = array()){
        $data = base64_encode(serialize($data));
        C::t('#tom_weixin#tom_weixin_activity')->update($this->openid,array('act_text'=>$data));
        return true;
    }
    
    public function delete(){
        C::t('#tom_weixin#tom_weixin_activity')->delete($this->openid);
        return true;
    }
    
    public function delete_by_acttime(){
        C::t('#tom_weixin#tom_weixin_activity')->delete_by_acttime();
        return true;
    }
}

?>
