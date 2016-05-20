<?php
//var_dump($_SERVER);die;
if ($_SERVER['REMOTE_ADDR'] != "112.74.83.214" && $_SERVER['REMOTE_ADDR'] != "10.24.220.220" && $_SERVER['REMOTE_ADDR'] != "127.0.0.1") {
    echo "access deny;";
    exit;
}

define('IN_DISCUZ', true);
define("DS", DIRECTORY_SEPARATOR);
define('DISCUZ_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('DISCUZ_CORE_DEBUG', false);
define('DISCUZ_TABLE_EXTENDABLE', false);
include DISCUZ_ROOT . "config" . DS . "config_global.php";

class MysqlH {

    private $con;

    function __construct($_config) {
        $this->con = mysql_connect($_config["db"][1]["dbhost"], $_config["db"][1]["dbuser"], $_config["db"][1]["dbpw"]);
        if (!$this->con) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($_config["db"][1]["dbname"], $this->con);
    }

    function query($sql, $fetch = true) {
        $result = mysql_query($sql);
        if ($fetch) {

            $res = array();

            while ($row = mysql_fetch_assoc($result)) {
                $res[] = $row;
            }
            return $res;
        } else {
            if (mysql_num_rows($result)) {
                return mysql_fetch_assoc($result);
            }
        }
    }

    function Update($sql) {
        return mysql_query($sql);
    }

    function __destruct() {
        mysql_close($this->con);
    }

}

if (isset($_GET['acti']) && $_GET['acti'] == "updaterefund") {
    set_time_limit(300);
    error_reporting(0);
    $wxpay_appid = trim("wxaccf5321ad827caa");
    $wxpay_mchid = trim("1333436401");
    $wxpay_key = trim("82d3111d831765277acce5ed9a84b0ee");
    $wxpay_appsecret = trim("a54de6cc09ad05f1d165e54803a1ea81");

    define("TOM_WXPAY_APPID", $wxpay_appid);
    define("TOM_WXPAY_MCHID", $wxpay_mchid);
    define("TOM_WXPAY_KEY", $wxpay_key);
    define("TOM_WXPAY_APPSECRET", $wxpay_appsecret);

    include DISCUZ_ROOT . './source/plugin/tom_pintuan/class/wxpay/lib/WxPay.Api.php';
    $mysqlH = new MysqlH($_config);
    $start = 0;
    $pageper = 10;
    do {
        $res = array();
        $sql = "select * from pre_tom_pintuan_order where order_status=7 limit {$start},{$pageper} ";
        $res = $mysqlH->query($sql);
        $start+=$pageper;

        foreach ($res as $key => $orderInfo) {
            if ($orderInfo && !empty($orderInfo['order_no']) && !empty($orderInfo['pay_price']) && $orderInfo['order_status'] == 7) {
                $input = new WxPayRefundQuery();
                $input->SetOut_trade_no($orderInfo['order_no']);
                $return = WxPayApi::refundQuery($input);
                if ((is_array($return) && isset($return['refund_status_0']) && $return['refund_status_0'] == 'SUCCESS') || (is_array($return) && $return['result_code'] == 'SUCCESS')) {
                    $updateData = array();
                    $updateData['order_status'] = 8;
                    $sql = "update pre_tom_pintuan_order set order_status=8 where id={$orderInfo['id']}";
                    echo $orderInfo['id'];
                   var_dump($mysqlH->Update($sql));
                }
            }
        }
        sleep(1);
    } while (count($res) == 10);
    echo "update success!";
} else {
    echo "参数错误";
}


