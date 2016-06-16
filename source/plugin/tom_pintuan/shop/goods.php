<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

  /* 商品分类查询 */
    $cateList = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_all_list(""," ORDER BY csort ASC,id DESC ",0,100);
    $cate_list_item = array();
    if(is_array($cateList) && !empty($cateList)){
        foreach ($cateList as $key => $value){
            $cate_list_item[$value['id']] = $value['name'];
        }
    }
    
    /* 快递模板 */
    $expressList = C::t('#tom_pintuan#tom_pintuan_express')->fetch_all_list(""," ORDER BY id DESC ",0,100);
    $express_list_item = array();
    if(is_array($expressList) && !empty($expressList)){
        foreach ($expressList as $key => $val){
            $express_list_item[$val['id']] = $val['title'];
        }
    }
     
    /* 加载语言包 */
    $pc_goods_showarr = lang('tom_pintuan/shop','shopgoods');
    $pc_set_shuomingarr = lang('tom_pintuan/shop','setshuoming');
    $pc_goods_infoarr = lang('tom_pintuan/shop','goodsinfo');
    
    $formhash = FORMHASH;
    $urlgoods="/plugin.php?id=tom_pintuan:shop&mod=goods";

if ($_GET['act'] == 'hide'){//商品下架处理
    $updateData = array();
    $updateData['is_show']     = 2;
    C::t('#tom_pintuan#tom_pintuan_goods')->update($_GET['good_id'],$updateData);    
    dheader('location:'.$urlgoods);
    exit;
    
}else if ($_GET['act'] == 'show'){//商品上架处理
    $updateData = array();
    $updateData['is_show']     = 1;
    C::t('#tom_pintuan#tom_pintuan_goods')->update($_GET['good_id'],$updateData);
    dheader('location:'.$urlgoods);
    exit;
}else if ($_GET['act'] == 'add'){//显示商品添加模板
    $shangjia_time = date('Y-m-d H:i:s');
    $xiajia_time = date('Y-m-d H:i:s');
    $take_pwd = mt_rand(111111, 999999);
    $share_title = $Lang['goods_share_title_value'];
    $share_desc = $Lang['goods_share_desc_value'];
    include template("tom_pintuan:shop/goodsadd");
    
}else if ($_GET['act'] == 'doadd'){//实现商品添加功能
   
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

    $goods_pic="";
    if (isset($_GET['goods_pic'])){
        $goods_pic = $_GET['goods_pic'];
    }else{
        $goods_pic = tomuploadFile('goods_pic');
    }
    
    $list_pic="";
    if (isset($_GET['list_pic'])){
        $list_pic = $_GET['list_pic'];
    }else{
        $list_pic = tomuploadFile('list_pic');
    }
    
    $pics1="";
    if (isset($_GET['pics1'])){
        $pics1 = $_GET['pics1'];
    }else{
        $pics1 = tomuploadFile('pics1');
    }
    
    $pics2="";
    if (isset($_GET['pics2'])){
        $pics2 = $_GET['pics2'];
    }else{
        $pics2 = tomuploadFile('pics2');
    }
    
    $pics3="";
    if (isset($_GET['pics3'])){
        $pics3 = $_GET['pics3'];
    }else{
        $pics3 = tomuploadFile('pics3');
    }
    
    $data = array();
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
    $data['paixu']        = 1000;
    
 /*    echo '<pre>';
    print_r($data);   
    echo '</pre>';
   */
    
    $insertData = $data;
    $insertData['add_time']      = TIMESTAMP;
    $insertData['edit_time']     = TIMESTAMP;
 
   $result =  C::t('#tom_pintuan#tom_pintuan_goods')->insert($insertData);

    if ($result){
        dheader('location:'.$urlgoods);
        exit;
    }else{
        echo '添加商品失败';
        $urlgoodsadd = $urlgoods.'&act=add';
        dheader('location:'.$urlgoodsadd);
        exit;
    }
   
}elseif ($_GET['act'] == 'edit'){//显示商品修改模板
   
    $goodsInfoArr = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($_GET['good_id']);
    
    if(!preg_match('/^http/', $goodsInfoArr['goods_pic']) ){
        $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfoArr['goods_pic'];
    }else{
        $goods_pic = $goodsInfoArr['goods_pic'];
    }
    
    if(!preg_match('/^http/', $goodsInfoArr['list_pic']) ){
        $list_pic= (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfoArr['list_pic'];
    }else{
        $list_pic = $goodsInfoArr['list_pic'];
    }
    
    if(!preg_match('/^http/', $goodsInfoArr['pics1']) ){
        $pics1 = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfoArr['pics1'];
    }else{
        $pics1 = $goodsInfoArr['pics1'];
    }
    
    if(!preg_match('/^http/', $goodsInfoArr['pics2']) ){
        $pics2 = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfoArr['pics2'];
    }else{
        $pics2 = $goodsInfoArr['pics2'];
    }
    
    if(!preg_match('/^http/', $goodsInfoArr['pics3']) ){
        $pics3 = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfoArr['pics3'];
    }else{
        $pics3 = $goodsInfoArr['pics3'];
    }

    $goodsInfoArr['content'] = stripslashes($goodsInfoArr['content']);
    include template("tom_pintuan:shop/goodsedit");
    
}elseif ($_GET['act'] == 'doedit'){//实现商品修改功能
  
    $name           = isset($_GET['name'])? addslashes($_GET['name']):'';
    $cate_id        = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
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
    
    
    $goods_pic="";
    if (isset($_GET['goods_pic'])){
        $goods_pic = $_GET['goods_pic'];
    }else{
        $goods_pic = tomuploadFile('goods_pic',$_GET['TMPgoods_pic']);
    }
    
    $list_pic="";
    if (isset($_GET['list_pic'])){
        $list_pic = $_GET['list_pic'];
    }else{
        $list_pic = tomuploadFile('list_pic',$_GET['TMPlist_pic']);
    }
    
    $pics1="";
    if (isset($_GET['pics1'])){
        $pics1 = $_GET['pics1'];
    }else{
        $pics1 = tomuploadFile('pics1',$_GET['TMPpics1']);
    }
    
    $pics2="";
    if (isset($_GET['pics2'])){
        $pics2 = $_GET['pics2'];
    }else{
        $pics2 = tomuploadFile('pics2',$_GET['TMPpics2']);
    }
    
    $pics3="";
    if (isset($_GET['pics3'])){
        $pics3 = $_GET['pics3'];
    }else{
        $pics3 = tomuploadFile('pics3',$_GET['TMPpics3']);
    }

    $data = array();
    $data['name']           = $name;
    $data['cate_id']        = $cate_id;
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
    
    //查询数据库
    $updateData = $data;
    $updateData['edit_time']     = TIMESTAMP;
    $result = C::t('#tom_pintuan#tom_pintuan_goods')->update($_GET['good_id'],$updateData);
    
    if ($result){
        dheader('location:'.$urlgoods);
        exit;
    }else{
        echo '修改商品失败';
         dheader('location:'.$urlgoods);
        exit;
    }
    
    
}elseif ($_GET['act'] == 'del'){
   // C::t('#tom_pintuan#tom_pintuan_goods')->delete_by_id($_GET['good_id']);
    dheader('location:'.$urlgoods);
    exit;
}else {
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $goods_name = !empty($_GET['goods_name'])? addslashes($_GET['goods_name']):'';
    $is_show = isset($_GET['is_show'])? intval($_GET['is_show']):0;
    $goods_sort = isset($_GET['goods_sort'])? intval($_GET['goods_sort']):0;
    $cate_id = isset($_GET['cate_id'])? intval($_GET['cate_id']):0;
    
    /* 按条件搜索商品   */
    $where = " AND shop_id={$shopInfo['id']} ";
    
    if(!empty($is_show)){
        $where.= " AND is_show={$is_show} ";
    }
    if(!empty($cate_id)){
        $where.= " AND cate_id={$cate_id} ";
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
    
    $searchUrl = "&goods_name={$goods_name}&is_show={$is_show}&goods_sort={$goods_sort}&cate_id={$cate_id}&shop_id={$shopInfo['id']}";
    
    $pagesize = 10;
    $start = ($page-1)*$pagesize;	
    $count = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_count($where,$goods_name);
    $goodsList = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_like_list($where,$sort,$start,$pagesize,$goods_name);
    $goods_pic_item = array();
    foreach ($goodsList as $key => $value) {
        if(!preg_match('/^http/', $value['goods_pic']) ){
            $goods_pic_item[$value['id']] = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['goods_pic'];
        }else{
            $goods_pic_item[$value['id']] = $value['goods_pic'];
        }
    }
 
   
    $pages = helper_page::multi($count, $pagesize, $page, "plugin.php?id=tom_pintuan:shop&mod=goods".$searchUrl, 0, 11, false, false);
   
    include template("tom_pintuan:shop/goods");
}



?>