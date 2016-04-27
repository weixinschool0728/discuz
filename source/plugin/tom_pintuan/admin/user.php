<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&tmod=user'; 
$modListUrl = $adminListUrl.'&tmod=user';
$modFromUrl = $adminFromUrl.'&tmod=user';

$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';

$get_list_url_value = get_list_url("tom_pintuan_admin_user_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($formhash == FORMHASH && $act == 'info'){
    
}else if($formhash == FORMHASH && $act == 'address'){
    $user_id = isset($_GET['user_id'])? intval($_GET['user_id']):0;
    $addressList = C::t('#tom_pintuan#tom_pintuan_address')->fetch_all_list(" AND user_id={$user_id} ","ORDER BY id DESC",0,100);
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['address_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['address_xm'] . '</th>';
	echo '<th>' . $Lang['address_tel'] . '</th>';
	if(!empty($pintuanConfig['yihe_fielda'])){
        echo '<th>' . $pintuanConfig['yihe_fielda'] . '</th>';
    }
    echo '<th>' . $Lang['address_str'] . '</th>';
    echo '</tr>';
    foreach ($addressList as $key => $value){
        
        echo '<tr>';
        echo '<td>'.$value['xm'].'</td>';
        echo '<td>'.$value['tel'].'</td>';
		if(!empty($pintuanConfig['yihe_fielda'])){
        echo '<td>' .$value['fielda']. '<td>';
    	}
        echo '<td>'.$value['area_str'].' '.$value['info'].'</td>';
        echo '</tr>';
    }
    showtablefooter();
    
}else{
    
    set_list_url("tom_pintuan_admin_user_list");
    
    $user_id = !empty($_GET['user_id'])? addslashes($_GET['user_id']):0;
    $nickname = !empty($_GET['nickname'])? addslashes($_GET['nickname']):'';
    
    $where = "";
    if(!empty($user_id)){
        $where = " AND id=$user_id ";
    }
    
    $pagesize = 10;
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_pintuan#tom_pintuan_user')->fetch_all_like_count($where,$nickname);
    $userList = C::t('#tom_pintuan#tom_pintuan_user')->fetch_all_like_list($where,"ORDER BY add_time DESC",$start,$pagesize,$nickname);
    
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['user_search_list'] . '</th></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['user_id'] . '</b></td><td><input name="user_id" type="text" value="'.$user_id.'" size="40" /></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['user_nickname'] . '</b></td><td><input name="nickname" type="text" value="'.$nickname.'" size="40" /></td></tr>';
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['user_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $Lang['user_id'] . '</th>';
    echo '<th>' . $Lang['user_picurl'] . '</th>';
    echo '<th>' . $Lang['user_nickname'] . '</th>';
    echo '<th>' . $Lang['user_openid'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    foreach ($userList as $key => $value){
        
        echo '<tr>';
        echo '<td>'.$value['id'].'</td>';
        echo '<td><img src="'.$value['picurl'].'" width="40" /></td>';
        echo '<td>'.$value['nickname'].'</td>';
        echo '<td>'.$value['openid'].'</td>';
        echo '<td>';
        echo '<a href="'.$adminBaseUrl.'&tmod=order&user_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['user_orders'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=address&user_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['user_address'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
    showsubmit('', '', '', '', $multi, false);
}
?>
