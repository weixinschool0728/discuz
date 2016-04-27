<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=goods';
$modListUrl = $adminListUrl.'&tmod=goods';
$modFromUrl = $adminFromUrl.'&tmod=goods';

$get_list_url_value = get_list_url("tom_pintuan_admin_goods_list");
if($get_list_url_value){
    $modListUrl = $get_list_url_value;
}

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        $insertData['add_time']      = TIMESTAMP;
        $insertData['edit_time']     = TIMESTAMP;
        C::t('#tom_pintuan#tom_pintuan_goods')->insert($insertData);
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
    $goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData['edit_time']     = TIMESTAMP;
        $updateData = __get_post_data($goodsInfo);
        C::t('#tom_pintuan#tom_pintuan_goods')->update($goodsInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($goodsInfo);
        tomshowsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'show'){
    $updateData = array();
    $updateData['is_show']     = $pintuanConfig['admin_goods_is_show'];
    C::t('#tom_pintuan#tom_pintuan_goods')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'batch_show'){
    if(is_array($_GET['ids']) && !empty($_GET['ids'])){
        foreach ($_GET['ids'] as $key => $value){
            $id = intval($value);
            $updateData = array();
            $updateData['is_show']     = $pintuanConfig['admin_goods_is_show'];
            C::t('#tom_pintuan#tom_pintuan_goods')->update($id,$updateData);
        }
    }
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'hide'){
    $updateData = array();
    $updateData['is_show']     = 2;
    C::t('#tom_pintuan#tom_pintuan_goods')->update($_GET['id'],$updateData);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'batch_hide'){
    if(is_array($_GET['ids']) && !empty($_GET['ids'])){
        foreach ($_GET['ids'] as $key => $value){
            $id = intval($value);
             $updateData = array();
            $updateData['is_show']     = 2;
            C::t('#tom_pintuan#tom_pintuan_goods')->update($id,$updateData);
        }
    }
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_pintuan#tom_pintuan_goods')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    set_list_url("tom_pintuan_admin_goods_list");
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $goods_name = !empty($_GET['goods_name'])? addslashes($_GET['goods_name']):'';
    $is_show = isset($_GET['is_show'])? intval($_GET['is_show']):0;
    $goods_sort = isset($_GET['goods_sort'])? intval($_GET['goods_sort']):0;
    $cate_id = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    $shop_id = isset($_GET['shop_id'])? intval($_GET['shop_id']):0;
    
    $where = "";
    if(!empty($is_show)){
        $where.= " AND is_show={$is_show} ";
    }
    if(!empty($cate_id)){
        $where.= " AND cate_id={$cate_id} ";
    }
    if(!empty($shop_id)){
        $where.= " AND shop_id={$shop_id} ";
    }
    
    $sort = " ORDER BY add_time DESC ";
    if($goods_sort == 1){
        $sort = " ORDER BY add_time DESC ";
    }
    if($goods_sort == 2){
        $sort = " ORDER BY goods_num ASC,add_time DESC ";
    }
    if($goods_sort == 3){
        $sort = " ORDER BY sales_num DESC,add_time DESC ";
    }
    
    $modBasePageUrl = $modBaseUrl."&goods_name={$goods_name}&is_show={$is_show}&goods_sort={$goods_sort}&cate_id={$cate_id}&shop_id={$shop_id}";
    
    $pagesize = $pintuanConfig['admin_goods_pagesize'];
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_count($where,$goods_name);
    $goodsList = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_list($where,$sort,$start,$pagesize,$goods_name);
    showtableheader();
    $Lang['pin_help_1']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['pin_help_1']);
    echo '<tr><th colspan="15" class="partition">' . $Lang['pin_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['pin_help_1'] . '</font></a></li>';
    echo '<li>' . $Lang['pin_help_2_a'] . '<a target="_blank" href="#"><font color="#FF0000">' . $Lang['pin_help_2_b'] . '</font></a></li>';
    echo '</ul></td></tr>';
    showtablefooter();
    
    $fenghao = $Lang['fenghao'];
    showformheader($modFromUrl.'&formhash='.FORMHASH);
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $Lang['goods_search_title'] . '</th></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['goods_name'] . '</b></td><td><input name="goods_name" type="text" value="'.$goods_name.'" size="40" /></td></tr>';
    
    $cateList = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_all_list(""," ORDER BY csort ASC,id DESC ",0,100);
    $cate_list_item = array();
    if(is_array($cateList) && !empty($cateList)){
        foreach ($cateList as $key => $value){
            $cate_list_item[$value['id']] = $value['name'];
        }
    }
    echo '<tr><td width="100" align="right"><b>' . $Lang['goods_cate_id'] . '</b></td><td><select name="cate_id" >';
    echo '<option value="0">'.$Lang['goods_cate_id'].'</option>';
    foreach ($cate_list_item as $key => $value){
        if($key == $cate_id){
            echo '<option value="'.$key.'" selected>'.$value.'</option>';
        }else{
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo '</select></td></tr>';
    
    $shopList = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_list(""," ORDER BY id DESC ",0,500);
    $shop_list_item = array();
    if(is_array($shopList) && !empty($shopList)){
        foreach ($shopList as $key => $value){
            $shop_list_item[$value['id']] = $value['name'];
        }
    }
    echo '<tr><td width="100" align="right"><b>' . $Lang['goods_shop_id'] . '</b></td><td><select name="shop_id" >';
    echo '<option value="0">'.$Lang['goods_shop_id'].'</option>';
    foreach ($shop_list_item as $key => $value){
        if($key == $shop_id){
            echo '<option value="'.$key.'" selected>'.$value.'</option>';
        }else{
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo '</select></td></tr>';
    
    echo '<tr><td width="100" align="right"><b>' . $Lang['goods_is_show'] . '</b></td><td><select name="is_show" >';
    echo '<option value="0">'.$Lang['goods_is_show'].'</option>';
    $is_show1_selected = "";
    $is_show2_selected = "";
    if(1 == $is_show){
        $is_show1_selected = "selected";
    }
    if(2 == $is_show){
        $is_show2_selected = "selected";
    }
    echo '<option value="1" '.$is_show1_selected.'>'.$Lang['goods_is_show1'].'</option>';
    echo '<option value="2" '.$is_show2_selected.'>'.$Lang['goods_is_show2'].'</option>';
    echo '</select></td></tr>';
    echo '<tr><td width="100" align="right"><b>' . $Lang['goods_sort_title'] . '</b></td><td><select name="goods_sort" >';
    echo '<option value="0">'.$Lang['goods_sort_title'].'</option>';
    $sort1_selected = "";
    $sort2_selected = "";
    $sort3_selected = "";
    if(1 == $goods_sort){
        $sort1_selected = "selected";
    }
    if(2 == $goods_sort){
        $sort2_selected = "selected";
    }
    if(3 == $goods_sort){
        $sort3_selected = "selected";
    }
    echo '<option value="1" '.$sort1_selected.'>'.$Lang['goods_sort_1'].'</option>';
    echo '<option value="2" '.$sort2_selected.'>'.$Lang['goods_sort_2'].'</option>';
    echo '<option value="3" '.$sort3_selected.'>'.$Lang['goods_sort_3'].'</option>';
    echo '</select></td></tr>';
    
    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();
    
    __create_nav_html();
    
    $anchor = isset($_GET['anchor']) ? dhtmlspecialchars($_GET['anchor']) : '';
    echo '<form name="cpform2" id="cpform2" method="post" autocomplete="off" action="'.ADMINSCRIPT.'?action='.$modFromUrl.'&formhash='.FORMHASH.'" onsubmit="return goods_form();">'.
		'<input type="hidden" name="formhash" value="'.FORMHASH.'" />'.
		'<input type="hidden" id="formscrolltop" name="scrolltop" value="" />'.
		'<input type="hidden" name="anchor" value="'.$anchor.'" />';
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['goods_id'] . '</th>';
    echo '<th>' . $Lang['goods_goods_pic'] . '</th>';
    echo '<th width="200">' . $Lang['goods_name'] . '</th>';
    echo '<th>' . $Lang['goods_cate_id'] . '</th>';
    echo '<th>' . $Lang['goods_shop_id'] . '</th>';
    echo '<th>' . $Lang['goods_tuan_price'] . '</th>';
    echo '<th>' . $Lang['goods_one_price'] . '</th>';
    echo '<th>' . $Lang['goods_tuan_num'] . '</th>';
    echo '<th>' . $Lang['goods_goods_num'] . '</th>';
    echo '<th>' . $Lang['goods_sales_num'] . '</th>';
    echo '<th>' . $Lang['goods_virtual_sales_num'] . '</th>';
    echo '<th>' . $Lang['goods_is_show'] . '</th>';  
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($goodsList as $key => $value) {
        
        if(!preg_match('/^http/', $value['goods_pic']) ){
            $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['goods_pic'];
        }else{
            $goods_pic = $value['goods_pic'];
        }
        
        $cateInfo = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_by_id($value['cate_id']);
        $shopInfo = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_by_id($value['shop_id']);
        
        echo '<tr>';
        echo '<td><input class="checkbox" type="checkbox" name="ids[]" value="' . $value['id'] . '" >' . $value['id'] . '</td>';
        echo '<td><img src="'.$goods_pic.'" width="40" /></td>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td>' . $cateInfo['name'] . '</td>';
        echo '<td>' . $shopInfo['name'] . '</td>';
        echo '<td>' . $value['tuan_price'] . '</td>';
        echo '<td>' . $value['one_price'] . '</td>';
        echo '<td>' . $value['tuan_num'] . '</td>';
        echo '<td>' . $value['goods_num'] . '</td>';
        echo '<td>' . $value['sales_num'] . '</td>';
        echo '<td>' . $value['virtual_sales_num'] . '</td>';
        if($value['is_show'] == 1){
            echo '<td><font color="#009900">' . $Lang['goods_is_show1']. '</font></td>';
        }else{
            echo '<td><font color="#FF0000">' . $Lang['goods_is_show2']. '</font></td>';
        }
        echo '<td>';
        echo '<a href="'.$adminBaseUrl.'&tmod=order&goods_id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['goods_order_title'] . '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['goods_edit']. '</a>&nbsp;|&nbsp;';
        if($value['is_show'] == 1){
            echo '<a href="'.$modBaseUrl.'&act=hide&id='.$value['id'].'&formhash='.FORMHASH.'"><font color="#FF0000">' . $Lang['goods_is_show2']. '</font></a>&nbsp;|&nbsp;';
        }else{
            echo '<a href="'.$modBaseUrl.'&act=show&id='.$value['id'].'&formhash='.FORMHASH.'"><font color="#009900">' . $Lang['goods_is_show1']. '</font></a>&nbsp;|&nbsp;';
        }
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
       $formstr = <<<EOF
        <tr>
            <td class="td25">
                <input type="checkbox" name="chkall" id="chkallFh9R" class="checkbox" onclick="checkAll('prefix', this.form, 'ids')" />
                <label for="chkallFh9R">{$Lang['checkall']}</label>
            </td>
            <td class="td25">
                <select name="act" >
                    <option value="batch_show">{$Lang['batch_goods_show']}</option>
                    <option value="batch_hide">{$Lang['batch_goods_hide']}</option>
                </select>
            </td>
            <td colspan="15">
                <div class="fixsel"><input type="submit" class="btn" id="submit_announcesubmit" name="announcesubmit" value="{$Lang['batch_btn']}" /></div>
            </td>
        </tr>
        <script type="text/javascript">
        function goods_form(){
          var r = confirm("{$Lang['batch_make_sure']}")
          if (r == true){
            return true;
          }else{
            return false;
          }
        }
        
        </script>
EOF;
    
    echo $formstr;
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
    
    $name           = isset($_GET['name'])? addslashes($_GET['name']):'';
    $cate_id        = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    $shop_id        = isset($_GET['shop_id'])? intval($_GET['shop_id']):0;
    $tuan_num       = isset($_GET['tuan_num'])? intval($_GET['tuan_num']):0;
    $goods_num      = isset($_GET['goods_num'])? intval($_GET['goods_num']):0;
    $xiangou_num    = isset($_GET['xiangou_num'])? intval($_GET['xiangou_num']):0;
    $goods_discount = isset($_GET['goods_discount'])? addslashes($_GET['goods_discount']):'';
    $market_price   = isset($_GET['market_price'])? addslashes($_GET['market_price']):'';
    $tuanz_price    = isset($_GET['tuanz_price'])? addslashes($_GET['tuanz_price']):'';
    $tuan_price     = isset($_GET['tuan_price'])? addslashes($_GET['tuan_price']):'';
    $tuan_num_2     = isset($_GET['tuan_num_2'])? intval($_GET['tuan_num_2']):0;
    $tuan_price_2   = isset($_GET['tuan_price_2'])? addslashes($_GET['tuan_price_2']):'';
    $tuan_num_3     = isset($_GET['tuan_num_3'])? intval($_GET['tuan_num_3']):0;
    $tuan_price_3   = isset($_GET['tuan_price_3'])? addslashes($_GET['tuan_price_3']):'';
    $one_price      = isset($_GET['one_price'])? addslashes($_GET['one_price']):'';
    $open_3_tuan      = isset($_GET['open_3_tuan'])? intval($_GET['open_3_tuan']):0;
    $express_price      = isset($_GET['express_price'])? intval($_GET['express_price']):0;
    $virtual_sales_num      = isset($_GET['virtual_sales_num'])? intval($_GET['virtual_sales_num']):0;
    $tuan_hours      = isset($_GET['tuan_hours'])? intval($_GET['tuan_hours']):24;
    //活动时间
    $start_time     = isset($_GET['start_time'])? addslashes($_GET['start_time']):'';
    $start_time     = strtotime($start_time);
    $end_time       = isset($_GET['end_time'])? addslashes($_GET['end_time']):'';
    $end_time       = strtotime($end_time);
    
    $allow_num      = isset($_GET['allow_num'])? intval($_GET['allow_num']):1;
    $goods_unit     = isset($_GET['goods_unit'])? addslashes($_GET['goods_unit']):"";
    $take_type      = isset($_GET['take_type'])? intval($_GET['take_type']):1;
    $take_pwd       = isset($_GET['take_pwd'])? addslashes($_GET['take_pwd']):'';
    $describe       = isset($_GET['describe'])? addslashes($_GET['describe']):'';
    $share_title    = isset($_GET['share_title'])? addslashes($_GET['share_title']):'';
    $share_desc     = isset($_GET['share_desc'])? addslashes($_GET['share_desc']):'';
    $content        = isset($_GET['content'])? addslashes($_GET['content']):'';
    
    $fieldb1          = isset($_GET['fieldb1'])? addslashes($_GET['fieldb1']):'';
    $fieldb2          = isset($_GET['fieldb2'])? addslashes($_GET['fieldb2']):'';
    $fieldb3          = isset($_GET['fieldb3'])? addslashes($_GET['fieldb3']):'';
    $fieldb3      = strtotime($fieldb3);
    $fieldb4          = isset($_GET['fieldb4'])? addslashes($_GET['fieldb4']):'';
    $fieldb5          = isset($_GET['fieldb5'])? addslashes($_GET['fieldb5']):'';
    $fieldb6          = isset($_GET['fieldb6'])? addslashes($_GET['fieldb6']):'';
    $fieldb7          = isset($_GET['fieldb7'])? addslashes($_GET['fieldb7']):'';
    $fieldb8          = isset($_GET['fieldb8'])? addslashes($_GET['fieldb8']):'';
    $fieldb9          = isset($_GET['fieldb9'])? addslashes($_GET['fieldb9']):'';
    $fieldb10          = isset($_GET['fieldb10'])? addslashes($_GET['fieldb10']):'';
    $fieldb11          = isset($_GET['fieldb11'])? addslashes($_GET['fieldb11']):'';
    $fieldb12          = isset($_GET['fieldb12'])? addslashes($_GET['fieldb12']):'';
    $fieldb13          = isset($_GET['fieldb13'])? addslashes($_GET['fieldb13']):'';
    $fieldb14          = isset($_GET['fieldb14'])? addslashes($_GET['fieldb14']):'';
    $fieldb15          = isset($_GET['fieldb15'])? addslashes($_GET['fieldb15']):'';
    $fieldb16          = isset($_GET['fieldb16'])? addslashes($_GET['fieldb16']):'';
    $fieldb17          = isset($_GET['fieldb17'])? addslashes($_GET['fieldb17']):'';
    $fieldb18          = isset($_GET['fieldb18'])? addslashes($_GET['fieldb18']):'';
    $fieldb19          = isset($_GET['fieldb19'])? addslashes($_GET['fieldb19']):'';
    $fieldb20          = isset($_GET['fieldb20'])? addslashes($_GET['fieldb20']):'';
    $fieldb21          = isset($_GET['fieldb21'])? addslashes($_GET['fieldb21']):'';
    $fieldb22          = isset($_GET['fieldb22'])? addslashes($_GET['fieldb22']):'';
    $fieldb23          = isset($_GET['fieldb23'])? addslashes($_GET['fieldb23']):'';
    $fieldb24          = isset($_GET['fieldb24'])? addslashes($_GET['fieldb24']):'';
    $fieldb25          = isset($_GET['fieldb25'])? addslashes($_GET['fieldb25']):'';
    $fieldb26          = isset($_GET['fieldb26'])? addslashes($_GET['fieldb26']):'';
    $fieldb27          = isset($_GET['fieldb27'])? addslashes($_GET['fieldb27']):'';
    $fieldb28          = isset($_GET['fieldb28'])? addslashes($_GET['fieldb28']):'';
    $fieldb29          = isset($_GET['fieldb29'])? addslashes($_GET['fieldb29']):'';
    $fieldb30          = isset($_GET['fieldb30'])? addslashes($_GET['fieldb30']):'';
    $fieldba          = isset($_GET['fieldba'])? addslashes($_GET['fieldba']):'';
    $fieldbb          = isset($_GET['fieldbb'])? addslashes($_GET['fieldbb']):'';
    $fieldbc          = isset($_GET['fieldbc'])? addslashes($_GET['fieldbc']):'';
    $fieldbd          = isset($_GET['fieldbd'])? addslashes($_GET['fieldbd']):'';
    $fieldbe          = isset($_GET['fieldbe'])? addslashes($_GET['fieldbe']):'';
    $fieldbf          = isset($_GET['fieldbf'])? addslashes($_GET['fieldbf']):'';
    $paixu          = isset($_GET['paixu'])? intval($_GET['paixu']):'';
    
    $goods_pic = "";
    if($_GET['act'] == 'add'){
        $goods_pic        = tomuploadFile("goods_pic");
    }else if($_GET['act'] == 'edit'){
        $goods_pic        = tomuploadFile("goods_pic",$infoArr['goods_pic']);
    }
    
    $list_pic = "";
    if($_GET['act'] == 'add'){
        $list_pic        = tomuploadFile("list_pic");
    }else if($_GET['act'] == 'edit'){
        $list_pic        = tomuploadFile("list_pic",$infoArr['list_pic']);
    }
    
    $pics1 = "";
    if($_GET['act'] == 'add'){
        $pics1        = tomuploadFile("pics1");
    }else if($_GET['act'] == 'edit'){
        $pics1        = tomuploadFile("pics1",$infoArr['pics1']);
    }
    
    $pics2 = "";
    if($_GET['act'] == 'add'){
        $pics2        = tomuploadFile("pics2");
    }else if($_GET['act'] == 'edit'){
        $pics2        = tomuploadFile("pics2",$infoArr['pics2']);
    }
    
    $pics3 = "";
    if($_GET['act'] == 'add'){
        $pics3        = tomuploadFile("pics3");
    }else if($_GET['act'] == 'edit'){
        $pics3        = tomuploadFile("pics3",$infoArr['pics3']);
    }

    $data['name']           = $name;
    $data['cate_id']        = $cate_id;
    $data['shop_id']        = $shop_id;
    $data['goods_pic']      = $goods_pic;
    $data['list_pic']       = $list_pic;
    $data['tuan_num']       = $tuan_num;
    $data['goods_num']      = $goods_num;
    $data['xiangou_num']      = $xiangou_num;
    $data['goods_discount'] = $goods_discount;
    $data['market_price']   = $market_price;
    $data['tuanz_price']     = $tuanz_price;
    $data['tuan_price']     = $tuan_price;
    $data['one_price']      = $one_price;
    $data['tuan_num_2']     = $tuan_num_2;
    $data['tuan_price_2']   = $tuan_price_2;
    $data['tuan_num_3']     = $tuan_num_3;
    $data['tuan_price_3']   = $tuan_price_3;
    $data['open_3_tuan']   = $open_3_tuan;
    $data['express_price']   = $express_price;
    $data['pics1']          = $pics1;
    $data['pics2']          = $pics2;
    $data['pics3']          = $pics3;
    $data['virtual_sales_num']      = $virtual_sales_num;
    $data['tuan_hours']     = $tuan_hours;
    $data['allow_num']      = $allow_num;
    $data['goods_unit']     = $goods_unit;
    $data['take_type']     = $take_type;
    $data['take_pwd']     = $take_pwd;
    $data['share_title']    = $share_title;
    $data['share_desc']     = $share_desc;
    $data['describe']       = $describe;
    $data['content']        = $content;
    $data['start_time']   = $start_time;
    $data['end_time']     = $end_time;
    $data['fieldb1']        = $fieldb1;
    $data['fieldb2']        = $fieldb2;
    $data['fieldb3']        = $fieldb3;
    $data['fieldb4']        = $fieldb4;
    $data['fieldb5']        = $fieldb5;
    $data['fieldb6']        = $fieldb6;
    $data['fieldb7']        = $fieldb7;
    $data['fieldb8']        = $fieldb8;
    $data['fieldb9']        = $fieldb9;
    $data['fieldb12']        = $fieldb12;
    $data['fieldb11']        = $fieldb11;
    $data['fieldb12']        = $fieldb12;
    $data['fieldb13']        = $fieldb13;
    $data['fieldb14']        = $fieldb14;
    $data['fieldb15']        = $fieldb15;
    $data['fieldb16']        = $fieldb16;
    $data['fieldb17']        = $fieldb17;
    $data['fieldb18']        = $fieldb18;
    $data['fieldb19']        = $fieldb19;
    $data['fieldb20']        = $fieldb20;
    $data['fieldb21']        = $fieldb21;
    $data['fieldb22']        = $fieldb22;
    $data['fieldb23']        = $fieldb23;
    $data['fieldb24']        = $fieldb24;
    $data['fieldb25']        = $fieldb25;
    $data['fieldb26']        = $fieldb26;
    $data['fieldb27']        = $fieldb27;
    $data['fieldb28']        = $fieldb28;
    $data['fieldb29']        = $fieldb29;
    $data['fieldb30']        = $fieldb30;
    $data['fieldba']        = $fieldba;
    $data['fieldbb']        = $fieldbb;
    $data['fieldbc']        = $fieldbc;
    $data['fieldbd']        = $fieldbd;
    $data['fieldbe']        = $fieldbe;
    $data['fieldbf']        = $fieldbf;
    $data['paixu']        = $paixu;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang,$pintuanConfig;
    $options = array(
        'name'              => '',
        'cate_id'           => 0,
        'shop_id'           => 0,
        'goods_pic'         => '',
        'list_pic'          => '',
        'tuan_num'          => 0,
        'goods_num'         => 0,
        'xiangou_num'         => 0,
        'goods_discount'    => "",
        'market_price'      => '0.00',
        'tuanz_price'        => '0.00',
        'tuan_price'        => '0.00',
        'one_price'         => '0.00',
        'tuan_num_2'        => 0,
        'tuan_price_2'      => '0.00',
        'tuan_num_3'        => 0,
        'tuan_price_3'      => '0.00',
        'open_3_tuan'        => 0,
        'express_price'        => 0,
        'pics1'             => "",
        'pics2'             => "",
        'pics3'             => '',
        'virtual_sales_num' => "0",
        'tuan_hours'         => "24",
        'allow_num'         => "1",
        'take_type'         => "3",
        'take_pwd'          => mt_rand(111111, 999999),
        'goods_unit'        => $Lang['goods_unit_value'],
        'share_title'       => $Lang['goods_share_title_value'],
        'share_desc'        => $Lang['goods_share_desc_value'],
        'describe'          => "",
        'content'           => "",
        'start_time'    => 0,
        'end_time'      => 0,
        'fieldb1'         => "",
        'fieldb2'         => "",
        'fieldb3'         => 0,
        'fieldb4'         => 0,
        'fieldb5'         => 0,
        'fieldb6'         => "",
        'fieldb7'         => "",
        'fieldb8'         => "",
        'fieldb9'         => "",
        'fieldb10'         => "",
        'fieldb11'         => "",
        'fieldb12'         => "",
        'fieldb13'         => "",
        'fieldb14'         => "",
        'fieldb15'         => "",
        'fieldb16'         => "",
        'fieldb17'         => "",
        'fieldb18'         => "",
        'fieldb19'         => "",
        'fieldb20'         => "",
        'fieldb21'         => "",
        'fieldb22'         => "",
        'fieldb23'         => "",
        'fieldb24'         => "",
        'fieldb25'         => "",
        'fieldb26'         => "",
        'fieldb27'         => "",
        'fieldb28'         => "",
        'fieldb29'         => "",
        'fieldb30'         => "",
        'paixu'         => 100,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['goods_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['goods_name_msg']),"input");
    
    $cateList = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_all_list(""," ORDER BY csort ASC,id DESC ",0,100);
    $cate_list_item = array();
    if(is_array($cateList) && !empty($cateList)){
        foreach ($cateList as $key => $value){
            $cate_list_item[$value['id']] = $value['name'];
        }
    }
    tomshowsetting(true,array('title'=>$Lang['goods_cate_id'],'name'=>'cate_id','value'=>$options['cate_id'],'msg'=>$Lang['goods_cate_id_msg'],'item'=>$cate_list_item),"select");
    
    $shopList = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_all_list(""," ORDER BY id DESC ",0,500);
    $shop_list_item = array();
    if(is_array($shopList) && !empty($shopList)){
        foreach ($shopList as $key => $value){
            $shop_list_item[$value['id']] = $value['name'];
        }
    }
    tomshowsetting(true,array('title'=>$Lang['goods_shop_id'],'name'=>'shop_id','value'=>$options['shop_id'],'msg'=>$Lang['goods_shop_id_msg'],'item'=>$shop_list_item),"select");
    
    tomshowsetting(true,array('title'=>$Lang['goods_goods_pic'],'name'=>'goods_pic','value'=>$options['goods_pic'],'msg'=>$Lang['goods_goods_pic_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_list_pic'],'name'=>'list_pic','value'=>$options['list_pic'],'msg'=>$Lang['goods_list_pic_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_goods_num'],'name'=>'goods_num','value'=>$options['goods_num'],'msg'=>$Lang['goods_goods_num_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_xiangou_num'],'name'=>'xiangou_num','value'=>$options['xiangou_num'],'msg'=>$Lang['goods_xiangou_num_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_goods_discount'],'name'=>'goods_discount','value'=>$options['goods_discount'],'msg'=>$Lang['goods_goods_discount_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_market_price'],'name'=>'market_price','value'=>$options['market_price'],'msg'=>$Lang['goods_market_price_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_tuanz_price'],'name'=>'tuanz_price','value'=>$options['tuanz_price'],'msg'=>$Lang['goods_tuanz_price_msg']),"input");
    $open_3_tuan_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['goods_open_3_tuan'],'name'=>'open_3_tuan','value'=>$options['open_3_tuan'],'msg'=>$Lang['goods_open_3_tuan_msg'],'item'=>$open_3_tuan_item),"radio");
    
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_num'],'name'=>'tuan_num','value'=>$options['tuan_num'],'msg'=>$Lang['goods_tuan_num_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_price'],'name'=>'tuan_price','value'=>$options['tuan_price'],'msg'=>$Lang['goods_tuan_price_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_num_2'],'name'=>'tuan_num_2','value'=>$options['tuan_num_2'],'msg'=>$Lang['goods_tuan_num_2_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_price_2'],'name'=>'tuan_price_2','value'=>$options['tuan_price_2'],'msg'=>$Lang['goods_tuan_price_2_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_num_3'],'name'=>'tuan_num_3','value'=>$options['tuan_num_3'],'msg'=>$Lang['goods_tuan_num_3_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_price_3'],'name'=>'tuan_price_3','value'=>$options['tuan_price_3'],'msg'=>$Lang['goods_tuan_price_3_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_one_price'],'name'=>'one_price','value'=>$options['one_price'],'msg'=>$Lang['goods_one_price_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_express_price'],'name'=>'express_price','value'=>$options['express_price'],'msg'=>$Lang['goods_express_price_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_pics1'],'name'=>'pics1','value'=>$options['pics1'],'msg'=>$Lang['goods_pics1_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_pics2'],'name'=>'pics2','value'=>$options['pics2'],'msg'=>$Lang['goods_pics2_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_pics3'],'name'=>'pics3','value'=>$options['pics3'],'msg'=>$Lang['goods_pics3_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_virtual_sales_num'],'name'=>'virtual_sales_num','value'=>$options['virtual_sales_num'],'msg'=>$Lang['goods_virtual_sales_num_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_hours'],'name'=>'tuan_hours','value'=>$options['tuan_hours'],'msg'=>$Lang['goods_tuan_hours_msg']),"input");
    $allow_num_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['goods_allow_num'],'name'=>'allow_num','value'=>$options['allow_num'],'msg'=>$Lang['goods_allow_num_msg'],'item'=>$allow_num_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['goods_unit'],'name'=>'goods_unit','value'=>$options['goods_unit'],'msg'=>$Lang['goods_unit_msg']),"input");
    
    $take_type_item = array(1=>$Lang['goods_take_type_1'],2=>$Lang['goods_take_type_2'],3=>$Lang['goods_take_type_3'],4=>$Lang['goods_take_type_4']);
    tomshowsetting(true,array('title'=>$Lang['goods_take_type'],'name'=>'take_type','value'=>$options['take_type'],'msg'=>$Lang['goods_take_type_msg'],'item'=>$take_type_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['goods_take_pwd'],'name'=>'take_pwd','value'=>$options['take_pwd'],'msg'=>$Lang['goods_take_pwd_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_describe'],'name'=>'describe','value'=>$options['describe'],'msg'=>$Lang['goods_describe_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['goods_share_title'],'name'=>'share_title','value'=>$options['share_title'],'msg'=>$Lang['goods_share_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_share_desc'],'name'=>'share_desc','value'=>$options['share_desc'],'msg'=>$Lang['goods_share_desc_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_content'],'name'=>'content','value'=>$options['content'],'msg'=>$Lang['goods_content_msg']),"text");
    
    //拼团结束日期
    tomshowsetting(true,array('title'=>$Lang['fieldb3'],'name'=>'fieldb3','value'=>$options['fieldb3'],'msg'=>$Lang['fieldb3_msg']),"calendar");
    //团购活动时间
    tomshowsetting(true,array('title'=>$Lang['start_time'],'name'=>'start_time','value'=>$options['start_time'],'msg'=>$Lang['start_time_msg']),"calendar");
    tomshowsetting(true,array('title'=>$Lang['end_time'],'name'=>'end_time','value'=>$options['end_time'],'msg'=>$Lang['end_time_msg']),"calendar");
	//商品置顶
    $fieldb1_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['fieldb1'],'name'=>'fieldb1','value'=>$options['fieldb1'],'msg'=>$Lang['fieldb1_msg'],'item'=>$fieldb1_item),"radio");
    //分享商品图
    $fieldb2_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['fieldb2'],'name'=>'fieldb2','value'=>$options['fieldb2'],'msg'=>$Lang['fieldb2_msg'],'item'=>$fieldb2_item),"radio");
	//分裂购买模式
    $fieldb6_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['fieldb6'],'name'=>'fieldb6','value'=>$options['fieldb6'],'msg'=>$Lang['fieldb6_msg'],'item'=>$fieldb6_item),"radio");
    //下单次数
    tomshowsetting(true,array('title'=>$Lang['fieldb4'],'name'=>'fieldb4','value'=>$options['fieldb4'],'msg'=>$Lang['fieldb4_msg']),"input");
    //tomshowsetting(array('title'=>$Lang['fieldb5'],'name'=>'fieldb5','value'=>$options['fieldb5'],'msg'=>$Lang['fieldb5_msg']),"input");
    //tomshowsetting(array('title'=>$Lang['fieldb7'],'name'=>'fieldb7','value'=>$options['fieldb7'],'msg'=>$Lang['fieldb7_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb8'],'name'=>'fieldb8','value'=>$options['fieldb8'],'msg'=>$Lang['fieldb8_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb9'],'name'=>'fieldb9','value'=>$options['fieldb9'],'msg'=>$Lang['fieldb9_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb10'],'name'=>'fieldb10','value'=>$options['fieldb10'],'msg'=>$Lang['fieldb10_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb11'],'name'=>'fieldb11','value'=>$options['fieldb11'],'msg'=>$Lang['fieldb11_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb12'],'name'=>'fieldb12','value'=>$options['fieldb12'],'msg'=>$Lang['fieldb12_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb13'],'name'=>'fieldb13','value'=>$options['fieldb13'],'msg'=>$Lang['fieldb13_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb14'],'name'=>'fieldb14','value'=>$options['fieldb14'],'msg'=>$Lang['fieldb14_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb15'],'name'=>'fieldb15','value'=>$options['fieldb15'],'msg'=>$Lang['fieldb15_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb16'],'name'=>'fieldb16','value'=>$options['fieldb16'],'msg'=>$Lang['fieldb16_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb17'],'name'=>'fieldb17','value'=>$options['fieldb17'],'msg'=>$Lang['fieldb17_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb18'],'name'=>'fieldb18','value'=>$options['fieldb18'],'msg'=>$Lang['fieldb18_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb19'],'name'=>'fieldb19','value'=>$options['fieldb19'],'msg'=>$Lang['fieldb19_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb20'],'name'=>'fieldb20','value'=>$options['fieldb20'],'msg'=>$Lang['fieldb20_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb21'],'name'=>'fieldb21','value'=>$options['fieldb21'],'msg'=>$Lang['fieldb21_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb22'],'name'=>'fieldb22','value'=>$options['fieldb22'],'msg'=>$Lang['fieldb22_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb23'],'name'=>'fieldb23','value'=>$options['fieldb23'],'msg'=>$Lang['fieldb23_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb24'],'name'=>'fieldb24','value'=>$options['fieldb24'],'msg'=>$Lang['fieldb24_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb25'],'name'=>'fieldb25','value'=>$options['fieldb25'],'msg'=>$Lang['fieldb25_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb26'],'name'=>'fieldb26','value'=>$options['fieldb26'],'msg'=>$Lang['fieldb26_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb27'],'name'=>'fieldb27','value'=>$options['fieldb27'],'msg'=>$Lang['fieldb27_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb28'],'name'=>'fieldb28','value'=>$options['fieldb28'],'msg'=>$Lang['fieldb28_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb29'],'name'=>'fieldb29','value'=>$options['fieldb29'],'msg'=>$Lang['fieldb29_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldb30'],'name'=>'fieldb30','value'=>$options['fieldb30'],'msg'=>$Lang['fieldb30_msg']),"input");
    
    //tomshowsetting(array('title'=>$Lang['fieldba'],'name'=>'fieldba','value'=>$options['fieldba'],'msg'=>$Lang['fieldba_msg']),"input");
    //tomshowsetting(array('title'=>$Lang['fieldbb'],'name'=>'fieldbb','value'=>$options['fieldbb'],'msg'=>$Lang['fieldbb_msg']),"textarea");
    //tomshowsetting(array('title'=>$Lang['fieldbc'],'name'=>'fieldbc','value'=>$options['fieldbc'],'msg'=>$Lang['fieldbc_msg']),"input");
    //tomshowsetting(array('title'=>$Lang['fieldbd'],'name'=>'fieldbd','value'=>$options['fieldbd'],'msg'=>$Lang['fieldbd_msg']),"textarea");
    //tomshowsetting(array('title'=>$Lang['fieldbe'],'name'=>'fieldbe','value'=>$options['fieldbe'],'msg'=>$Lang['fieldbe_msg']),"input");
    //tomshowsetting(array('title'=>$Lang['fieldbf'],'name'=>'fieldbf','value'=>$options['fieldbf'],'msg'=>$Lang['fieldbf_msg']),"textarea");
    
    tomshowsetting(true,array('title'=>$Lang['goods_paixu'],'name'=>'paixu','value'=>$options['paixu'],'msg'=>$Lang['goods_paixu_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['goods_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['goods_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['goods_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['goods_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['goods_edit'],"",true);
    }else{
        tomshownavli($Lang['goods_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['goods_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}

?>
