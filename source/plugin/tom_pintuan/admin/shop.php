<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=shop'; 
$modListUrl = $adminListUrl.'&tmod=shop';
$modFromUrl = $adminFromUrl.'&tmod=shop';

$get_list_url_value = get_list_url("tom_pintuan_admin_shop_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_pintuan#tom_pintuan_shop')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enctype');
        showtableheader();
        __create_info_html();
        tomshowsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $shopInfo = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($shopInfo);
        C::t('#tom_pintuan#tom_pintuan_shop')->update($shopInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($shopInfo);
        tomshowsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_pintuan#tom_pintuan_shop')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    set_list_url("tom_pintuan_admin_shop_list");
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 20;
    $start = ($page-1)*$pagesize;	
    $shopList = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_list(""," ORDER BY id DESC ",$start,$pagesize);
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['shop_logo'] . '</th>';
    echo '<th>' . $Lang['shop_name'] . '</th>';
    echo '<th>' . $Lang['shop_tel'] . '</th>';
    echo '<th>' . $Lang['shop_address'] . '</th>';
    echo '<th>' . $Lang['shop_yizhifu'] . '</th>';
    echo '<th>' . $Lang['shop_tuikuanzhong'] . '</th>';
    echo '<th>' . $Lang['shop_tuikuanchenggong'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($shopList as $key => $value) {
        
        if(!preg_match('/^http/', $value['logo']) ){
            $logo = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['logo'];
        }else{
            $logo = $value['logo'];
        }
        
        $goodsIds = "";
        $goodsListTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_list_id(" AND shop_id={$value['id']} "," ORDER BY add_time DESC ",0,10000);
        if(is_array($goodsListTmp) && !empty($goodsListTmp)){
            $goodsIdArr = array();
            foreach ($goodsListTmp as $k => $v){
                $goodsIdArr[] = $v['id'];
            }
            if(!empty($goodsIdArr)){
                $goodsIds = " AND goods_id IN(".  implode(",", $goodsIdArr).") ";
            }
        }
        
        if(!empty($goodsIds) && $pintuanConfig['admin_shop_count'] == 1){
            $yizhifu = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_sun_pay_price(" {$goodsIds} AND order_status IN(2,3,4,5) ");
            $tuikuanzhong = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_sun_pay_price(" {$goodsIds} AND order_status IN(7) ");
            $tuikuanchenggong = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_sun_pay_price(" {$goodsIds} AND order_status IN(8) ");
        }else{
            $yizhifu = $tuikuanzhong = $tuikuanchenggong = '0.00';
        }
        echo '<tr>';
        echo '<td><img src="'.$logo.'" width="40" /></td>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>' . $value['tel'] . '</td>';
        echo '<td>' . $value['address'] . '</td>';
        echo '<td><font color="#1e9203">' . $yizhifu . '</font></td>';
        echo '<td><font color="#fc2009">' . $tuikuanzhong . '</font></td>';
        echo '<td><font color="#fc2009">' . $tuikuanchenggong . '</font></td>';
        echo '<td>';
        echo '<a href="'.$adminBaseUrl.'&tmod=order&shop_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['shop_order_title'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['shop_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
    
    $jsstr = <<<EOF
<script type="text/javascript">
function del_confirm(url){
  var r = confirm("{$Lang['makesure_del_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
</script>
EOF;
    echo $jsstr;
    
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    $tel        = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $address        = isset($_GET['address'])? addslashes($_GET['address']):'';
    $order_pwd        = isset($_GET['order_pwd'])? addslashes($_GET['order_pwd']):'';
    $manage_openid        = isset($_GET['manage_openid'])? addslashes($_GET['manage_openid']):'';
    
    
    $logo = "";
    if($_GET['act'] == 'add'){
        $logo        = tomuploadFile("logo");
    }else if($_GET['act'] == 'edit'){
        $logo        = tomuploadFile("logo",$infoArr['logo']);
    }

    $data['name']           = $name;
    $data['tel']            = $tel;
    $data['address']        = $address;
    $data['order_pwd']      = $order_pwd;
    $data['manage_openid']  = $manage_openid;
    $data['logo']           = $logo;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'              => '',
        'tel'               => '',
        'logo'              => '',
        'address'           => '',
        'order_pwd'         => '',
        'manage_openid'     => '',
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['shop_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['shop_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['shop_logo'],'name'=>'logo','value'=>$options['logo'],'msg'=>$Lang['shop_logo_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['shop_tel'],'name'=>'tel','value'=>$options['tel'],'msg'=>$Lang['shop_tel_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['shop_manage_openid'],'name'=>'manage_openid','value'=>$options['manage_openid'],'msg'=>$Lang['shop_manage_openid_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['shop_address'],'name'=>'address','value'=>$options['address'],'msg'=>$Lang['shop_address_msg']),"textarea");
    //tomshowsetting(true,array('title'=>$Lang['shop_order_pwd'],'name'=>'order_pwd','value'=>$options['order_pwd'],'msg'=>$Lang['shop_order_pwd_msg']),"input");
    
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['shop_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['shop_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['shop_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['shop_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['shop_edit'],"",true);
    }else{
        tomshownavli($Lang['shop_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['shop_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}

?>
