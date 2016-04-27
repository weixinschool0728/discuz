<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_pintuan_tuan_team extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_pintuan_tuan_team';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function fetch_by_tuan_user_id($tuan_id,$user_id) {
		return DB::fetch_first("SELECT * FROM %t WHERE tuan_id=%d AND user_id=%d ", array($this->_table, $tuan_id,$user_id));
	}
    
    public function fetch_top_by_tuan_id($tuan_id) {
		return DB::fetch_first("SELECT * FROM %t WHERE tuan_id=%d AND type_id=1 ", array($this->_table, $tuan_id));
	}
	
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function insert_id() {
		return DB::insert_id();
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
	
	public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function delete_by_tuan_id($tuan_id) {
		return DB::query("DELETE FROM %t WHERE tuan_id=%d", array($this->_table, $tuan_id));
	}

}


?>
