<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_feed.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global  $lang;
$lang=array
(
    'shopgoods'=>array(
        'pc_shop_order_list' => '订单列表',
    	'pc_shop_goods_list' => '商品管理',
    	'pc_shop_goods_add'  => '添加商品',
    	'pc_shop_goods_edit' => '修改商品',
    	'pc_shop_goods_order' => '商品订单',
    	'pc_shop_goods_del' => '彻底删除',
        'pc_goods_cate'      => '商品分类',
        'pc_shop_shops'      => '商家店铺',
        'pc_goods_name'      => '商品名称',
        'pc_goods_is_show'   => '上架状态',
        'pc_goods_is_show1'  => '上架',
        'pc_goods_is_show2'  => '下架',
    ),
    'setshuoming' =>array(
        'set_goods_name'  => '填写商品名称',
        'set_goods_cate'  => '填写商品分类',
        'set_goods_shop_name'  => '我的店铺',
        'set_goods_pic'  => '上传商品图片，图片尺寸 120*120',
        'set_goods_list_pic'  => '上传了列表图片，图片尺寸 720*400',
        'set_goods_store'  => '	设置商品库存，用户下单时，库存会减少',
        'set_goods_one_num'  => '	设置每个微信号最多购买数量，如果不限制设置0',
        'set_goods_off'  => '	设置商品给用户的折扣，就是你拼团价格大概是市场价的几折',
        'set_goods_market_price'  => '	填写商品市场价格，显示使用',
        'set_goods_header_price'  => '	置团长开团价格，如果不单独设置团长价格，设置0.00 。如果设置团长价格，必须大于0',
        'set_header_allow_num'  => '团长优惠限购数量。不限制，填写0	',
        'set_open_3_tuan'  => '开启后允许用户选择不同拼团等级，不同等级成团人数和价格不一样	',
        'set_only_one_buy'  => '开启后，只显示单独购买按钮，关闭拼团购买入口',
        'set_tuan_num'  => '设置满多少人成团	',
        'set_tuan_price'  => '拼团支付价格',
        'set_single_price'  => '用户单独购买是支付价格',
        'set_express_price'  => '设置你收取用户的的快递费金额，单位（分）',
        'set_goods_ppt_pic'  => '图片尺寸：720*400',
        'set_goods_sale_num'  => '商品页显示的销量 = 虚拟销量 + 真实销量',
        'set_goods_timer'  => '设置开团后，倒计时时间',
        'set_goods_unit'  => '填写页面上显示的商品单位，如： 件、份、个等等',
        'set_goods_shipping'  => '选择到店取货方式时，必须在商品介绍里面说明取货地址等信息，或者预约联系方式',
        'set_shangjia_time'  => '上架时间的格式2016-05-16 11:05:12',
        'set_xiajia_time'  => '下架时间的格式2016-05-16 11:05:12',
        'set_verify_shuoming'  => '到店取货时，商家微信扫描订单二维码，进入订单页面输入密码确认订单',
        'set_share_title'  => '相关变量：拼团还差人数： NUM 如：我正在参加XXX拼团，还差NUM人',
        'set_share_desc'  => '相关变量：拼团还差人数： NUM',
        'set_goods_paixu'  => '设置商品排序',

    ),
    'goodsinfo' =>array(
        'goods_name' => '商品名称',
        'goods_cate' => '商品分类',
        'shop_name' => '商家店铺',
        'goods_pic' => '商品图片',
        'goods_list_name' => '商品列表图片',
        'goods_store' => '商品库存',
        'goods_one_name' => '设置每个微信号最多购买数量',
        'goods_off' => '商品折扣',
        'goods_market_price' => '商品市场价格',
        'tuan_header_price' => '团长开团价格',
        'tuan_header_num' => '团长开团价格限购',
        'open_3_tuan' => '开启3级拼团价格',
        'only_one_buy' => '只允许单独购买',
        'tuan_num' => '拼团人数',
        'tuan_price' => '拼团价格',
        'tuan_num_2' => '【二级】拼团人数',
        'tuan_price_2' => '【二级】拼团价格',
        'tuan_num_3' => '【三级】拼团人数',
        'tuan_price_3' => '【三级】拼团价格',
        'single_price' => '单独购买价格',
        'express_price' => '快递费（单位 分）',
        'express_name' => '选择快递费模板',
        'goods_ppt_1' => '商品幻灯片【一】',
        'goods_ppt_2' => '商品幻灯片【二】',
        'goods_ppt_3' => '商品幻灯片【三】',
        'goods_vr_num' => '虚拟销量',
        'tuan_limit' => '拼团限时',
        'open_choice_num' => '开启选择商品数量',
        'goods_init' => '商品单位',
        'goods_shipping' => '配送方式',
        'shangjia_time' => '上架时间',
        'xiajia_time' => '下架时间',
        'shop_verify' => '商家验证密码',
        'goods_depict' => '商品描述',
        'share_title' => '拼团分享标题',
        'share_desc' => '拼团分享描述',
        'goods_content' => '商品详情',
        'goods_paixu' => '排序',
        'goods_express' => '快递发货',
        'goods_daodian' => '到店取货',
        'pattern_mix' => '到店+快递',
        'goods_open'  => '开启',
        'goods_close'  => '关闭',
        'file_upload'  => '上传文件',
        'import_url'  => '输入URL',
        'share_title_val'  => "我正在参加小潜潜拼团，还差{NUM}人",
        'share_desc_val'  => "我正在参加小潜潜拼团，还差{NUM}人",
    ),
	
);

?>