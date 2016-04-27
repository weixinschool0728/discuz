<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_weixin_module extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_weixin_module';
		$this->_pk    = 'id';
	}

    public function fetch_one_by_modulecmd($modulecmd,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE module_cmd=%s", array($this->_table, $modulecmd));
	}
	
	public function fetch_one_by_moduleid($moduleid,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE module_id=%s", array($this->_table, $moduleid));
	}
	
	public function fetch_all_list($field='*',$where='',$order='') {
        $whereStr = '';
        if(!empty($where)){
            $whereStr = ' WHERE '.$where;
        }
        $orderStr = '';
        if(!empty($order)){
            $orderStr = ' ORDER BY '.$order;
        }
		return DB::fetch_all("SELECT $field FROM %t $whereStr $orderStr ", array($this->_table));
	}
	
	public function delete_by_moduleid($moduleid) {
		return DB::query("DELETE FROM %t WHERE module_id=%s", array($this->_table, $moduleid));
	}
    
    public function disable_by_moduleid($moduleid) {
		return DB::query("UPDATE %t SET status=2 WHERE module_id=%s", array($this->_table, $moduleid));
	}
    
    public function enable_by_moduleid($moduleid) {
		return DB::query("UPDATE %t SET status=1 WHERE module_id=%s", array($this->_table, $moduleid));
	}
    
    public function update_module_ver($moduleid,$ver) {
		return DB::query("UPDATE %t SET part1=%s WHERE module_id=%s", array($this->_table, $ver,$moduleid));
	}

}


?>
