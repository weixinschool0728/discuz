<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_tom_weixin_user extends discuz_table
{
	public function __construct() {
		$this->_table = 'tom_weixin_user';
		$this->_pk    = 'openid';
		parent::__construct();
	}
	
	public function fetch_one_by_openid($openid) {
		return DB::fetch_first("SELECT * FROM %t WHERE openid=%s ", array($this->_table, $openid));
	}
	
	public function fetch_one_by_uid($uid) {
		return DB::fetch_first("SELECT * FROM %t WHERE uid=%d ", array($this->_table, $uid));
	}
	
	public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
	
    public function delete_by_openid($openid) {
		return DB::query("DELETE FROM %t WHERE openid=%s", array($this->_table, $openid));
	}
    
	public function delete_by_uid($uid) {
		return DB::query("DELETE FROM %t WHERE uid=%d", array($this->_table, $uid));
	}

}

?>