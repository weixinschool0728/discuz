<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.net
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
    $Lang['pin_help_3']  = str_replace("{SITEURL}", $_G['siteurl'], $Lang['pin_help_3']);
    echo '<tr><th colspan="15" class="partition">' . $Lang['pin_help_title'] . '</th></tr>';
    echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $Lang['pin_help_1'] . '</font></a></li>';
    echo '<li>' . $Lang['pin_help_2_a'] . '<a target="_blank" href="http://www.tomwx.net/index.php?m=help&t=plugin&pluginid=tom_pintuan"><font color="#FF0000">' . $Lang['pin_help_2_b'] . '</font></a></li>';
    echo '<li>' . $Lang['pin_help_3'] . '</font></a></li>';
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
    echo '<th>' . $Lang['goods_clicks'] . '</th>';
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
        echo '<td>' . $value['clicks'] . '</td>';
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
    $tuanz_price_num    = isset($_GET['tuanz_price_num'])? intval($_GET['tuanz_price_num']):0;
    $tuan_price     = isset($_GET['tuan_price'])? addslashes($_GET['tuan_price']):'';
    $tuan_num_2     = isset($_GET['tuan_num_2'])? intval($_GET['tuan_num_2']):0;
    $tuan_price_2   = isset($_GET['tuan_price_2'])? addslashes($_GET['tuan_price_2']):'';
    $tuan_num_3     = isset($_GET['tuan_num_3'])? intval($_GET['tuan_num_3']):0;
    $tuan_price_3   = isset($_GET['tuan_price_3'])? addslashes($_GET['tuan_price_3']):'';
    $one_price      = isset($_GET['one_price'])? addslashes($_GET['one_price']):'';
    $open_3_tuan      = isset($_GET['open_3_tuan'])? intval($_GET['open_3_tuan']):0;
    $only_one_buy      = isset($_GET['only_one_buy'])? intval($_GET['only_one_buy']):0;
    $express_price      = isset($_GET['express_price'])? intval($_GET['express_price']):0;
    $express_id      = isset($_GET['express_id'])? intval($_GET['express_id']):0;
    $virtual_sales_num      = isset($_GET['virtual_sales_num'])? intval($_GET['virtual_sales_num']):0;
    $tuan_hours      = isset($_GET['tuan_hours'])? intval($_GET['tuan_hours']):24;
    $allow_num      = isset($_GET['allow_num'])? intval($_GET['allow_num']):1;
    $goods_unit     = isset($_GET['goods_unit'])? addslashes($_GET['goods_unit']):"";
    $take_type      = isset($_GET['take_type'])? intval($_GET['take_type']):1;
    $shangjia_time      = isset($_GET['shangjia_time'])? $_GET['shangjia_time']:date("Y-m-d H:m:i");
    $xiajia_time      = isset($_GET['xiajia_time'])? $_GET['xiajia_time']:date("Y-m-d H:m:i");
    $take_pwd       = isset($_GET['take_pwd'])? addslashes($_GET['take_pwd']):'';
    $describe       = isset($_GET['describe'])? addslashes($_GET['describe']):'';
    $share_title    = isset($_GET['share_title'])? addslashes($_GET['share_title']):'';
    $share_desc     = isset($_GET['share_desc'])? addslashes($_GET['share_desc']):'';
    $content        = isset($_GET['content'])? addslashes($_GET['content']):'';
    $paixu          = isset($_GET['paixu'])? intval($_GET['paixu']):10000;
    
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
    $data['tuanz_price_num']     = $tuanz_price_num;
    $data['tuan_price']     = $tuan_price;
    $data['one_price']      = $one_price;
    $data['tuan_num_2']     = $tuan_num_2;
    $data['tuan_price_2']   = $tuan_price_2;
    $data['tuan_num_3']     = $tuan_num_3;
    $data['tuan_price_3']   = $tuan_price_3;
    $data['open_3_tuan']   = $open_3_tuan;
    $data['only_one_buy']   = $only_one_buy;
    $data['express_price']   = $express_price;
    $data['express_id']   = $express_id;
    $data['pics1']          = $pics1;
    $data['pics2']          = $pics2;
    $data['pics3']          = $pics3;
    $data['virtual_sales_num']      = $virtual_sales_num;
    $data['tuan_hours']     = $tuan_hours;
    $data['allow_num']      = $allow_num;
    $data['goods_unit']     = $goods_unit;
    $data['take_type']     = $take_type;
    $data['shangjia_time']     = $shangjia_time;
    $data['xiajia_time']     = $xiajia_time;
    $data['take_pwd']     = $take_pwd;
    $data['share_title']    = $share_title;
    $data['share_desc']     = $share_desc;
    $data['describe']       = $describe;
    $data['content']        = $content;
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
        'tuanz_price_num'        => 0,
        'tuan_price'        => '0.00',
        'one_price'         => '0.00',
        'tuan_num_2'        => 0,
        'tuan_price_2'      => '0.00',
        'tuan_num_3'        => 0,
        'tuan_price_3'      => '0.00',
        'open_3_tuan'        => 0,
        'only_one_buy'        => 0,
        'express_price'        => 0,
        'express_id'        => 0,
        'pics1'             => "",
        'pics2'             => "",
        'pics3'             => '',
        'virtual_sales_num' => "0",
        'tuan_hours'         => "24",
        'allow_num'         => "1",
        'take_type'         => "3",
        'shangjia_time'         => date('Y-m-d H:m:i'),
        'xiajia_time'         => date('Y-m-d H:m:i'),
        'take_pwd'          => mt_rand(111111, 999999),
        'goods_unit'        => $Lang['goods_unit_value'],
        'share_title'       => $Lang['goods_share_title_value'],
        'share_desc'        => $Lang['goods_share_desc_value'],
        'describe'          => "",
        'content'           => "",
        'paixu'           => "10000",
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
    tomshowsetting(true,array('title'=>$Lang['goods_tuanz_price_num'],'name'=>'tuanz_price_num','value'=>$options['tuanz_price_num'],'msg'=>$Lang['goods_tuanz_price_num_msg']),"input");
    $open_3_tuan_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['goods_open_3_tuan'],'name'=>'open_3_tuan','value'=>$options['open_3_tuan'],'msg'=>$Lang['goods_open_3_tuan_msg'],'item'=>$open_3_tuan_item),"radio");
    
    $only_one_buy_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['goods_only_one_buy'],'name'=>'only_one_buy','value'=>$options['only_one_buy'],'msg'=>$Lang['goods_only_one_buy_msg'],'item'=>$only_one_buy_item),"radio");
    
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_num'],'name'=>'tuan_num','value'=>$options['tuan_num'],'msg'=>$Lang['goods_tuan_num_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_price'],'name'=>'tuan_price','value'=>$options['tuan_price'],'msg'=>$Lang['goods_tuan_price_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_num_2'],'name'=>'tuan_num_2','value'=>$options['tuan_num_2'],'msg'=>$Lang['goods_tuan_num_2_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_price_2'],'name'=>'tuan_price_2','value'=>$options['tuan_price_2'],'msg'=>$Lang['goods_tuan_price_2_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_num_3'],'name'=>'tuan_num_3','value'=>$options['tuan_num_3'],'msg'=>$Lang['goods_tuan_num_3_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_price_3'],'name'=>'tuan_price_3','value'=>$options['tuan_price_3'],'msg'=>$Lang['goods_tuan_price_3_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_one_price'],'name'=>'one_price','value'=>$options['one_price'],'msg'=>$Lang['goods_one_price_msg']),"input");
    
    tomshowsetting(true,array('title'=>$Lang['goods_express_price'],'name'=>'express_price','value'=>$options['express_price'],'msg'=>$Lang['goods_express_price_msg']),"input");
    
    $expressList = C::t('#tom_pintuan#tom_pintuan_express')->fetch_all_list(""," ORDER BY id DESC ",0,500);
    $express_list_item = array();
    $express_list_item['0'] = $Lang['goods_express_id_no'];
    if(is_array($expressList) && !empty($expressList)){
        foreach ($expressList as $key => $value){
            $express_list_item[$value['id']] = $value['title'];
        }
    }
    tomshowsetting(true,array('title'=>$Lang['goods_express_id'],'name'=>'express_id','value'=>$options['express_id'],'msg'=>$Lang['goods_express_id_msg'],'item'=>$express_list_item),"select");
    
    tomshowsetting(true,array('title'=>$Lang['goods_pics1'],'name'=>'pics1','value'=>$options['pics1'],'msg'=>$Lang['goods_pics1_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_pics2'],'name'=>'pics2','value'=>$options['pics2'],'msg'=>$Lang['goods_pics2_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_pics3'],'name'=>'pics3','value'=>$options['pics3'],'msg'=>$Lang['goods_pics3_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['goods_virtual_sales_num'],'name'=>'virtual_sales_num','value'=>$options['virtual_sales_num'],'msg'=>$Lang['goods_virtual_sales_num_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_tuan_hours'],'name'=>'tuan_hours','value'=>$options['tuan_hours'],'msg'=>$Lang['goods_tuan_hours_msg']),"input");
    $allow_num_item = array(0=>$Lang['close'],1=>$Lang['open']);
    tomshowsetting(true,array('title'=>$Lang['goods_allow_num'],'name'=>'allow_num','value'=>$options['allow_num'],'msg'=>$Lang['goods_allow_num_msg'],'item'=>$allow_num_item),"radio");
    tomshowsetting(true,array('title'=>$Lang['goods_unit'],'name'=>'goods_unit','value'=>$options['goods_unit'],'msg'=>$Lang['goods_unit_msg']),"input");
    
    $take_type_item = array(1=>$Lang['goods_take_type_1'],2=>$Lang['goods_take_type_2'],3=>$Lang['goods_take_type_3']);
    tomshowsetting(true,array('title'=>$Lang['goods_take_type'],'name'=>'take_type','value'=>$options['take_type'],'msg'=>$Lang['goods_take_type_msg'],'item'=>$take_type_item),"radio");
    tomshowsetting(true,array('title'=>"上架时间",'name'=>'shangjia_time','value'=>$options['shangjia_time'],'msg'=>$Lang['goods_take_type_msg'],'item'=>$take_type_item),"input");
    tomshowsetting(true,array('title'=>"下架时间",'name'=>'xiajia_time','value'=>$options['xiajia_time'],'msg'=>$Lang['goods_take_type_msg'],'item'=>$take_type_item),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_take_pwd'],'name'=>'take_pwd','value'=>$options['take_pwd'],'msg'=>$Lang['goods_take_pwd_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_describe'],'name'=>'describe','value'=>$options['describe'],'msg'=>$Lang['goods_describe_msg']),"textarea");
    tomshowsetting(true,array('title'=>$Lang['goods_share_title'],'name'=>'share_title','value'=>$options['share_title'],'msg'=>$Lang['goods_share_title_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_share_desc'],'name'=>'share_desc','value'=>$options['share_desc'],'msg'=>$Lang['goods_share_desc_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['goods_content'],'name'=>'content','value'=>$options['content'],'msg'=>$Lang['goods_content_msg']),"text");
    
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
        tomshownavli($Lang['express_list_title'],$adminBaseUrl.'&tmod=express',false);
    }
    tomshownavfooter();
}

?>
