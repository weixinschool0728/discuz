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
    
    showtableheader();
    $Lang['shop_help_1']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['shop_help_1']);
    echo '<tr><th colspan="15" class="partition">' . $Lang['shop_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['shop_help_1'] . '</font></a></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 20;
    $start = ($page-1)*$pagesize;	
    $shopList = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_list(""," ORDER BY id DESC ",$start,$pagesize);
    $count = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_count("");
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
    $multi = multi($count, $pagesize, $page, $modBaseUrl);	
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
    
    $bbs_uid       = isset($_GET['bbs_uid'])? intval($_GET['bbs_uid']):0;
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    $tel        = isset($_GET['tel'])? addslashes($_GET['tel']):'';
    $address        = isset($_GET['address'])? addslashes($_GET['address']):'';
    $order_pwd        = isset($_GET['order_pwd'])? addslashes($_GET['order_pwd']):'';
    $manage_openid        = isset($_GET['manage_openid'])? addslashes($_GET['manage_openid']):'';
    
    $province_id   = isset($_GET['province_id'])? intval($_GET['province_id']):0;
    $city_id       = isset($_GET['city_id'])? intval($_GET['city_id']):0;
    $area_id   = isset($_GET['area_id'])? intval($_GET['area_id']):0;
    
    
    
    $logo = "";
    if($_GET['act'] == 'add'){
        $logo        = tomuploadFile("logo");
    }else if($_GET['act'] == 'edit'){
        $logo        = tomuploadFile("logo",$infoArr['logo']);
    }

    $data['bbs_uid']        = $bbs_uid;
    $data['name']           = $name;
    $data['tel']            = $tel;
    $data['address']        = $address;
    $data['order_pwd']      = $order_pwd;
    $data['manage_openid']  = $manage_openid;
    $data['logo']           = $logo;
    $data['province_id']           = $province_id;
    $data['city_id']           = $city_id;
    $data['area_id']           = $area_id;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'bbs_uid'           => 0,
        'name'              => '',
        'tel'               => '',
        'logo'              => '',
        'address'           => '',
        'order_pwd'         => '',
        'manage_openid'     => '',
        'province_id'       => 0,
        'city_id'           => 0,
        'area_id'           => 0,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['shop_bbs_uid'],'name'=>'bbs_uid','value'=>$options['bbs_uid'],'msg'=>$Lang['shop_bbs_uid_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['shop_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['shop_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['shop_logo'],'name'=>'logo','value'=>$options['logo'],'msg'=>$Lang['shop_logo_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['shop_tel'],'name'=>'tel','value'=>$options['tel'],'msg'=>$Lang['shop_tel_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['shop_manage_openid'],'name'=>'manage_openid','value'=>$options['manage_openid'],'msg'=>$Lang['shop_manage_openid_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['shop_address'],'name'=>'address','value'=>$options['address'],'msg'=>$Lang['shop_address_msg']),"textarea");
    //tomshowsetting(true,array('title'=>$Lang['shop_order_pwd'],'name'=>'order_pwd','value'=>$options['order_pwd'],'msg'=>$Lang['shop_order_pwd_msg']),"input");
    
    $provinceList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_level(1);
    $provinceStr = '<tr class="header"><th>'.$Lang['shop_province_id'].'</th><th></th></tr>';
    $provinceStr.= '<tr><td width="300"><select name="province_id" id="province_id" onchange="getCity();">';
    $provinceStr.=  '<option value="0">'.$Lang['shop_province_id'].'</option>';
    foreach ($provinceList as $key => $value){
        if($value['id'] == $options['province_id']){
            $provinceStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
        }else{
            $provinceStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        
    }
    $provinceStr.= '</select></td><td>'.$Lang['shop_province_id_msg'].'</td></tr>';
    echo $provinceStr;

    $cityList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($options['province_id']);
    $cityStr = '<tr class="header"><th>'.$Lang['shop_city_id'].'</th><th></th></tr>';
    $cityStr.= '<tr><td width="300"><select name="city_id" id="city_id" onchange="getArea();">';
    $cityStr.=  '<option value="0">'.$Lang['shop_city_id'].'</option>';
    if($options['province_id'] > 0){
        foreach ($cityList as $key => $value){
            if($value['id'] == $options['city_id']){
                $cityStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
            }else{
                $cityStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
            
        }
    }
    $cityStr.= '</select></td><td>'.$Lang['shop_city_id_msg'].'</td></tr>';
    echo $cityStr;

    $areaList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid($options['city_id']);
    $areaStr = '<tr class="header"><th>'.$Lang['shop_area_id'].'</th><th></th></tr>';
    $areaStr.= '<tr><td width="300"><select name="area_id" id="area_id">';
    $areaStr.=  '<option value="0">'.$Lang['shop_area_id'].'</option>';
    if($options['city_id'] > 0){
        foreach ($areaList as $key => $value){
            if($value['id'] == $options['area_id']){
                $areaStr.=  '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
            }else{
                $areaStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
            
        }
    }
    $areaStr.= '</select></td><td>'.$Lang['shop_area_id_msg'].'</td></tr>';
    echo $areaStr;

    $jsstr = <<<EOF
<script type="text/javascript">
function getCity(){
  var province = jq("#province_id").val();
  jq.ajax({
        type: "GET",
        url: "plugin.php?id=tom_pintuan:api",
        data: "act=city&pid="+province,
        dataType : "jsonp",
        jsonpCallback:"jsonpCallback",
        cache : false,
        success: function(json){
            var cityHtml = '<option value="0">{$Lang['shop_city_id']}</option>';
            jq.each(json,function(k,v){
                cityHtml+= '<option value="'+v.id+'">'+v.name+'</option>';
            })
            jq("#city_id").html(cityHtml);
            jq("#city_id").show();
        }
    });
}
function getArea(){
  var city = jq("#city_id").val();
  jq.ajax({
        type: "GET",
        url: "plugin.php?id=tom_pintuan:api",
        data: "act=area&pid="+city,
        dataType : "jsonp",
        jsonpCallback:"jsonpCallback",
        cache : false,
        success: function(json){
            var areaHtml = '<option value="0">{$Lang['shop_area_id']}</option>';
            jq.each(json,function(k,v){
                areaHtml+= '<option value="'+v.id+'">'+v.name+'</option>';
            })
            jq("#area_id").html(areaHtml);
            jq("#area_id").show();
        }
    });
}
</script>
EOF;
    echo $jsstr;
    
    
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
