<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=express'; 
$modListUrl = $adminListUrl.'&tmod=express';
$modFromUrl = $adminFromUrl.'&tmod=express';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_pintuan#tom_pintuan_express')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enctype');
        showtableheader();
        __create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $expressInfo = C::t('#tom_pintuan#tom_pintuan_express')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($expressInfo);
        C::t('#tom_pintuan#tom_pintuan_express')->update($expressInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($expressInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_pintuan#tom_pintuan_express')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'express_item_add'){
    
    $express_id = isset($_GET['express_id'])? intval($_GET['express_id']):0;
    if(submitcheck('submit')){
        
        $express_price = isset($_GET['express_price'])? intval($_GET['express_price']):0;
        $province_id   = isset($_GET['province_id'])? intval($_GET['province_id']):0;
        $city_id       = isset($_GET['city_id'])? intval($_GET['city_id']):0;
        
        $provinceStr = "";
        $cityStr = "";
        $provinceInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($province_id);
        $cityInfo = C::t('#tom_pintuan#tom_pintuan_district')->fetch_by_id($city_id);
        if($provinceInfo){
            $provinceStr = $provinceInfo['name'];
        }
        if($cityInfo){
            $cityStr = $cityInfo['name'];
        }
        
        $insertData = array();
        $insertData['express_id'] = $express_id;
        $insertData['express_price'] = $express_price;
        $insertData['province_id'] = $province_id;
        $insertData['province_name'] = $provinceStr;
        $insertData['city_id'] = $city_id;
        $insertData['city_name'] = $cityStr;
        C::t('#tom_pintuan#tom_pintuan_express_item')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl.'&act=express_item_list&express_id='.$express_id, 'succeed');
    }else{
        
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=express_item_add&express_id='.$express_id,'enctype');
        showtableheader();
        
        $provinceList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_level(1);
        $provinceStr = '<tr class="header"><th>'.$Lang['express_item_province'].'</th><th></th></tr>';
        $provinceStr.= '<tr><td width="300"><select name="province_id" id="province_id" onchange="getCity();">';
        foreach ($provinceList as $key => $value){
            $provinceStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        $provinceStr.= '</select></td><td></td></tr>';
        echo $provinceStr;
        
        $cityList = C::t('#tom_pintuan#tom_pintuan_district')->fetch_all_by_upid(1);
        $cityStr = '<tr class="header"><th>'.$Lang['express_item_city'].'</th><th></th></tr>';
        $cityStr.= '<tr><td width="300"><select name="city_id" id="city_id">';
        $cityStr.=  '<option value="0">'.$Lang['express_item_city_no'].'</option>';
        foreach ($cityList as $key => $value){
            $cityStr.=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        $cityStr.= '</select></td><td></td></tr>';
        echo $cityStr;
        
        tomshowsetting(true,array('title'=>$Lang['express_item_price'],'name'=>'express_price','value'=>1000,'msg'=>$Lang['express_item_price_msg']),"input");
        
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
        
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
            var cityHtml = '<option value="0">{$Lang['express_item_city_no']}</option>';
            jq.each(json,function(k,v){
                cityHtml+= '<option value="'+v.id+'">'+v.name+'</option>';
            })
            jq("#city_id").html(cityHtml);
            jq("#city_id").show();
        }
    });
}   
</script>
EOF;
    echo $jsstr;
    }
    
}else if($_GET['act'] == 'express_item_list'){
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $express_id = isset($_GET['express_id'])? intval($_GET['express_id']):0;
    
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $express_itemList = C::t('#tom_pintuan#tom_pintuan_express_item')->fetch_all_list(" AND express_id={$express_id} "," ORDER BY id DESC ",$start,$pagesize);
    $count = C::t('#tom_pintuan#tom_pintuan_express_item')->fetch_all_count(" AND express_id={$express_id} ");
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['express_item_province'] . '</th>';
    echo '<th>' . $Lang['express_item_city'] . '</th>';
    echo '<th>' . $Lang['express_item_price'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($express_itemList as $key => $value) {
        
        echo '<tr>';
        echo '<td>' . $value['province_name'] . '</td>';
        if(!empty($value['city_name'])){
            echo '<td>' . $value['city_name'] . '</td>';
        }else{
            echo '<td>-</td>';
        }
        echo '<td>' . $value['express_price'] . '</td>';
        echo '<td>';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=express_item_del&id='.$value['id'].'&express_id='.$express_id.'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBaseUrl.'&act=express_item_list&express_id='.$express_id);	
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
    
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'express_item_del'){
    
    $express_id = isset($_GET['express_id'])? intval($_GET['express_id']):0;
    
    C::t('#tom_pintuan#tom_pintuan_express_item')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl.'&act=express_item_list&express_id='.$express_id, 'succeed');
    
}else{
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $expressList = C::t('#tom_pintuan#tom_pintuan_express')->fetch_all_list(""," ORDER BY id DESC ",$start,$pagesize);
    $count = C::t('#tom_pintuan#tom_pintuan_express')->fetch_all_count("");
    
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['express_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['express_help_1'] . '</font></a></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['express_title'] . '</th>';
    echo '<th>' . $Lang['express_default_price'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($expressList as $key => $value) {
        
        echo '<tr>';
        echo '<td>' . $value['title'] . '</td>';
        echo '<td>' . $value['default_price'] . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=express_item_add&express_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['express_item_add']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=express_item_list&express_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['express_item_list_title']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['express_edit']. '</a>&nbsp;|&nbsp;';
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
    
    $title        = isset($_GET['title'])? addslashes($_GET['title']):'';
    $default_price      = isset($_GET['default_price'])? intval($_GET['default_price']):0;

    $data['title']       = $title;
    $data['default_price']       = $default_price;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'title'              => '',
        'default_price'      => 1000,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['express_title'],'name'=>'title','value'=>$options['title'],'msg'=>$Lang['express_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['express_default_price'],'name'=>'default_price','value'=>$options['default_price'],'msg'=>$Lang['express_default_price_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['express_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['express_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['express_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['express_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['express_edit'],"",true);
    }else if($_GET['act'] == 'express_item_list'){
        $expressInfo = C::t('#tom_pintuan#tom_pintuan_express')->fetch_by_id($_GET['express_id']);
        tomshownavli($Lang['express_list_title'].' <font color="#FF0000">>></font>',$modBaseUrl,false);
        tomshownavli($expressInfo['title'].' <font color="#FF0000">>></font>',"",false);
        tomshownavli($Lang['express_item_list_title'],"",true);
        tomshownavli($Lang['express_item_add'],$modBaseUrl."&act=express_item_add&express_id=".$_GET['express_id'].'&formhash='.FORMHASH,false);
    }else if($_GET['act'] == 'express_item_add'){
        $expressInfo = C::t('#tom_pintuan#tom_pintuan_express')->fetch_by_id($_GET['express_id']);
        tomshownavli($Lang['express_list_title'].' <font color="#FF0000">>></font>',$modBaseUrl,false);
        tomshownavli($expressInfo['title'].' <font color="#FF0000">>></font>',"",false);
        tomshownavli($Lang['express_item_list_title'],$modBaseUrl."&act=express_item_list&express_id=".$_GET['express_id'].'&formhash='.FORMHASH,false);
        tomshownavli($Lang['express_item_add'],"",true);
    }else{
        tomshownavli($Lang['express_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['express_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}

?>
