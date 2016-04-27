<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_weixin_log extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_weixin_log';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_all("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
	public function fetch_all_list($field='*',$where='') {
        $whereStr = '';
        if(!empty($where)){
            $whereStr = ' WHERE '.$where;
        }
		return DB::fetch_all("SELECT $field FROM %t $whereStr ORDER BY id DESC", array($this->_table));
	}
    
    public function fetch_all_count($where) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $where ");
		return $return['num'];
	}
	
	public function delete_by_openid($openid) {
		return DB::query("DELETE FROM %t WHERE open_id=%s", array($this->_table, $openid));
	}
    
    public function delete_by_logtime() {
		$logtime = TIMESTAMP - 86400*2;
		return DB::query("DELETE FROM %t WHERE log_time<%d", array($this->_table, $logtime));
	}

}


?>
