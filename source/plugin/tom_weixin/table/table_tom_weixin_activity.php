<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_tom_weixin_activity extends discuz_table
{
	public function __construct() {
		$this->_table = 'tom_weixin_activity';
		$this->_pk    = 'openid';
		parent::__construct();
	}
	
	public function fetch_one_by_openid($openid,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE openid=%s", array($this->_table, $openid));
	}
    
    public function update_acttext_by_openid($openid,$value) {
		return DB::query("UPDATE %t SET act_text=%s WHERE openid=%s", array($this->_table, $value, $openid));
	}
    
    public function delete_by_acttime() {
        
        $rand_num = rand(1, 10);
        if($rand_num == 5){
            $acttime = TIMESTAMP - 10800;
            return DB::query("DELETE FROM %t WHERE act_time<%d", array($this->_table, $acttime));
        }
        
        return false;
	}

}

?>