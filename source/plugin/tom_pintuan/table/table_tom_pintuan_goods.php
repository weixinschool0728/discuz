<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_pintuan_goods extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_pintuan_goods';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function fetch_all_like_list($condition,$orders = '',$start = 0,$limit = 10,$goods_name='') {
        if(!empty($goods_name)){
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i AND name LIKE %s $orders LIMIT $start,$limit",array($this->_table,$condition,'%'.$goods_name.'%'));
        }else{
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
        }
		
		return $data;
	}
    
    public function fetch_all_like_count($condition,$goods_name='') {
        if(!empty($goods_name)){
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i AND name LIKE %s ",array($this->_table,$condition,'%'.$goods_name.'%'));
        }else{
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i ",array($this->_table,$condition));
        }
		return $return['num'];
	}
	
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_list_id($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT id FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_sun_sales_num($condition) {
        $return1 = DB::fetch_first("SELECT SUM(sales_num) AS sun_sales_num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
        $return2 = DB::fetch_first("SELECT SUM(virtual_sales_num) AS sun_virtual_sales_num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return1['sun_sales_num'] + $return2['sun_virtual_sales_num'];
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

}


?>
