<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$orderStatusArray = array(
    1 => '待支付',
    2 => '已支付',
    3 => '已确认，待发货',
    4 => '配送中',
    5 => '已签收',
    6 => '交易已取消',
    7 => '退款处理中',
    8 => '退款成功',
);

$orderStatusColorArray = array(
    1 => '#fd0303',
    2 => '#1e9203',
    3 => '#1e9203',
    4 => '#0585d6',
    5 => '#ff6203',
    6 => '#fd0303',
    7 => '#777a77',
    8 => '#05a6ce',
);


$tstatusArray = array(
    1 => '开团订单',
    2 => '参团订单',
    3 => '普通订单',
);

$tstatusColorArray = array(
    1 => '#1ea004',
    2 => '#0489d5',
    3 => '#2e2f2e',
);

$tuanStatusArray = array(
    1 => '未支付',
    2 => '已支付，拼团中',
    3 => '拼团成功',
    4 => '拼团失败',
);

$tuanStatusColorArray = array(
    1 => '#fc2009',
    2 => '#0389c1',
    3 => '#359606',
    4 => '#fc2009',
);

$kuaidi100Array = array(
    'debangwuliu'   => '德邦物流',
    'ems'           => 'ems快递',
    'menduimen'     => '门对门',
    'rufengda'      => '如风达',
    'shentong'      => '申通',
    'shunfeng'      => '顺丰',
    'tiantian'      => '天天快递',
    'yuantong'      => '圆通速递',
    'yunda'         => '韵达快运',
    'zhaijisong'    => '宅急送',
    'zhongtiekuaiyun' => '中铁快运',
    'zhongtong'     => '中通速递',
    'huitongkuaidi' => '汇通快运',
);

?>
