<?php
include_once 'sendSMS.php';

/**
 * 添加富文本框控件js文件
 */
function addUeditorScript() {
    \Onemla\OnemlaHelper::addScript('upload/ajaxfileupload.js');
    \Onemla\OnemlaHelper::addScript('ueditor/ueditor.config.js');
    \Onemla\OnemlaHelper::addScript('ueditor/ueditor.all.js');
}

/**
 * 生成富文本框函数
 * $name : textarea name值
 * $config : 配置 格式：array('height'=>'400','width'=>'620','resize_type'=>1)
 */
function textArea($name, $config) {
    $resizeType = $config['resize_type'] ? 'true' : 'false';
    echo '<script type="text/javascript">
	$(\'textarea[name="' . $name . '"]\').attr("id", "editor_id_' . $name . '");
	window.UEDITOR_HOME_URL = "' . __ROOT__ . '/Res/js/ueditor/";
	window.UEDITOR_CONFIG.initialFrameHeight = parseInt("' . $config['height'] . '");
	window.UEDITOR_CONFIG.initialFrameWidth = parseInt("' . $config['width'] . '");
	window.UEDITOR_CONFIG.scaleEnabled = ' . $resizeType . ';
	window.UEDITOR_CONFIG.imageUrl = "' . U("File/File/ue_upimg", '', false, true) . '";
	window.UEDITOR_CONFIG.imagePath = "";
	window.UEDITOR_CONFIG.imageFieldName = "imgFile";

	//在这里扫描图片
	window.UEDITOR_CONFIG.imageManagerUrl="' . U("File/File/ue_mgimg", '', false, true) . '"; //图片在线管理的处理地址
	window.UEDITOR_CONFIG.imageManagerPath="";

	var imageEditor = UE.getEditor("editor_id_' . $name . '");</script>';
}

function upload($data = array()) {
    $data = array_merge(
            array(
        'file' => null,
        'maxSize' => C('upload.maxSize', null, 1),
        'exts' => C('upload.exts', null, array()),
        'rootPath' => C('upload.rootPath'),
        'savePath' => C('upload.savePath', null, '/temp/'),
        'saveName' => array('uniqid', array('', true)),
        'saveExt' => '',
        'replace' => false,
        'mimes' => array(),
        'autoSub' => true,
        'subName' => array('date', 'Y-m-d'),
        'subName' => '',
        'hash' => true,
        'callback' => false,
        'driver' => '',
        'driverConfig' => array(),
            ), $data
    );

    $upload = new \Think\Upload($data);
    if (empty($data['file'])) {
        $info = $upload->upload();
    } else {
        $info = $upload->upload($data['file']);
    }
    if ($info) {
        foreach ($info as $k => $v) {
            $savePath = strpos($v['savepath'], '/') === 0 ? $v['savepath'] : '/' . $v['savepath'];
            $info[$k]['url'] = C("TMPL_PARSE_STRING.__UPLOAD__") . $savePath . $v['savename'];
            $info[$k]['file'] = $v['savepath'] . $v['savename'];
        }
    }
    return array('info' => $info, 'error' => $upload->getError());
}

function upload_img($data = array(), $rootPath) {
    $data = array_merge(
            array(
        'file' => null,
        'maxSize' => C('upload.maxSize', null, 1),
        'exts' => C('upload.exts', null, array()),
        'rootPath' => $rootPath,
        'savePath' => C('upload.savePath', null, '/temp/'),
        'saveName' => array('uniqid', array('', true)),
        'saveExt' => '',
        'replace' => false,
        'mimes' => array(),
        'autoSub' => true,
        'subName' => array('date', 'Y-m-d'),
        'subName' => '',
        'hash' => true,
        'callback' => false,
        'driver' => '',
        'driverConfig' => array(),
            ), $data
    );

    $upload = new \Think\Upload($data);
    if (empty($data['file'])) {
        $info = $upload->upload();
    } else {
        $info = $upload->upload($data['file']);
    }
    if ($info) {
        foreach ($info as $k => $v) {
            $savePath = strpos($v['savepath'], '/') === 0 ? $v['savepath'] : '/' . $v['savepath'];
            $info[$k]['url'] = C("TMPL_PARSE_STRING.__UPLOAD__") . $savePath . $v['savename'];
            $info[$k]['file'] = $v['savepath'] . $v['savename'];
        }
    }
    return array('info' => $info, 'error' => $upload->getError());
}

/*
 * 单文件上传
 * **/
function uploadOne($file,$path){
        $data = array(
            'autoSub' => false,
        );
        $upload = new \Think\Upload($data);// 实例化上传类
        $upload->maxSize   =     2097152 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      $path; // 设置附件上传根目录
        // 上传单个文件
        $info   =   $upload->uploadOne($file);
        if(!$info) {// 上传错误提示错误信息
            return array('flag'=>'error','msg'=> $upload->getError());
        }else{// 上传成功 获取上传文件信息
            $info['url'] = C("TMPL_PARSE_STRING.__UPLOAD__") . $path . $info['savename'];
            $info['file'] = $info['savepath'] . $info['savename'];
            return array('flag'=>'success','msg'=>$info);
        }
}

/**
 * URL组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @return string
 */
function Url($url = '', $vars = '', $suffix = true, $domain = false) {
    if ($url) {
        $array = explode('/', $url);
        $count = count($array);
        if ($count == 1) {
            $url = COMPONENTS_INPUT . '/' . CONTROLLER_NAME . '/' . $url;
        } elseif ($count == 2) {
            $url = COMPONENTS_INPUT . '/' . join('/', $array);
        }
    }
    return U($url, $vars, $suffix, $domain);
}

/**
 * 组合 javascript Url
 */
function JsUrl($var, $url, $param, $suffix = true, $domain = false) {
    $iCount = 0;
    $pReplace = array();
    foreach ($param as $key => $value) {
        $rep = "S" . $iCount;
        $param[$key] = $rep;
        $pReplace[$rep] = $value;
        $iCount ++;
    }
    $url = Url($url, $param, $suffix, $domain);
    $pStr = "var $var='$url';\n";
    foreach ($pReplace as $key => $value) {
        $pStr .= "$var = $var.replace('$key', $value);\n";
    }
    return $pStr;
}

//获取model验证错误信息
function getModelError($model) {
    $arr = $model->getError();
    $str = '';
    foreach ($arr as $v) {
        $str .= $v . PHP_EOL;
    }
    return $str;
}

/* * 判断是否闰年* */

function isLeap($year) {
    $leap = false;
    if ($year % 4 == 0) {
        if ($year % 100 == 0) {
            if ($year % 400 == 0) { //能被400整除的,是闰年
                $leap = true;
            } else { //能被100整除,但不能被400整除的,不是闰年
                $leap = false;
            }
        } else { //能被4整除,但不能被100整除的,不是闰年
            $leap = true;
        }
    } else { //不能被4整除的,不是闰年
        $leap = false;
    }

    return $leap;
}

function get_order_sn() {
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function getDayConfig() {
    return $config = array(
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    );
}

/* * 获取天数  字符串 2015-08-01* */

function getDays($minTime, $maxTime) {
    $config = getDayConfig();

    $min = explode('-', $minTime);
    $max = explode('-', $maxTime);

    $minYear = (int) $min[0];
    $maxYear = (int) $max[0];
    $minMonth = (int) $min[1];
    $maxMonth = (int) $max[1];
    $minDay = (int) $min[2];
    $maxDay = (int) $max[2];

    $day_count = 0;

    for ($i = $minYear; $i <= $maxYear; $i++) {
        $config[2] = isLeap($i) ? 29 : 28;
        for ($m = 1; $m <= 12; $m++) {
            for ($d = 1; $d <= 31; $d++) {
                if ($d > $config[$m])
                    continue;

                if ($minYear == $maxYear) {
                    if ($m >= $minMonth && $m <= $maxMonth) {
                        if ($minMonth == $maxMonth) {

                            if ($d >= $minDay && $d < $maxDay)
                                $day_count += 1;
                        }else if ($m == $minMonth && $d >= $minDay || $m > $minMonth && $m < $maxMonth || $m == $maxMonth && $d < $maxDay) {
                            $day_count += 1;
                        }
                    }
                } else {
                    if ($i == $minYear) {
                        if ($m >= $minMonth) {
                            if ($m == $minMonth && $d >= $minDay || $m > $minMonth) {
                                $day_count += 1;
                            }
                        }
                    } else if ($i == $maxYear) {
                        if ($m <= $maxMonth) {
                            if ($m == $maxMonth && $d < $maxDay || $m < $maxMonth) {
                                $day_count += 1;
                            }
                        }
                    } else {
                        $day_count += 1;
                    }
                }
            }
        }
    }

    return $day_count;
}

//$cut_str 要裁剪的字符
function parseStr1($str, $cut_str) {
    return str_repeat($cut_str, '', $str);
}

//过滤[ ]字符
function parseStr2($str) {
    $str = str_replace('[', '', $str);
    return str_replace(']', '', $str);
}

//将数组转换成[ ]，字符的格式
function conversionStr2($list) {
    $str = '';
    foreach ($list as $item) {
        $str = $str . '[' . $item . '],';
    }

    if ($str != '')
        $str = substr($str, 0, strlen($str) - 1);

    return $str;
}

//生成 短信4位数 验证码
function generate_code($length = 4) {
    return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

/**
 * 获取国家配置
 */
function getCfgCountry() {
    static $pCountryCfg = NULL;
    if (!empty($pCountryCfg)) {
        return $pCountryCfg;
    }

    $pCfgKey = "region:getCfgCountry";
    $pCfgValue = \Onemla\OnemlaHelper::cache($pCfgKey);

    if (empty($pCfgValue)) {
        $pRegionModel = M("regions");
        $pSelectData = $pRegionModel->where(array(
                    "type" => 0,
                ))->field(array(
                    "id", "pid", "name"
                ))->select();

        foreach ($pSelectData as $item) {
            $id = $item['id'];
            $pCountryCfg[$id] = $item;
        }

        //$pCountryCfg = $pSelectData;

        \Onemla\OnemlaHelper::cache($pCfgKey, json_encode($pCountryCfg));
    } else {
        if (is_array($pCfgValue)) {
            return $pCfgValue;
        }
        $pCountryCfg = json_decode($pCfgValue, true);
    }
    return $pCountryCfg;
}

function getCfgCountryMap() {
    $pCfgCountry = getCfgCountry();
    foreach ($pCfgCountry as $value) {
        $pCountryMap[$value['id']] = $value['name'];
    }
    return $pCountryMap;
}

/**
 * 获取省配置
 */
function getCfgProvince() {
    static $pProvinceCfg = NULL;
    if (!empty($pProvinceCfg)) {
        return $pProvinceCfg;
    }

    $pCfgKey = "region:getCfgProvince";
    $pCfgValue = \Onemla\OnemlaHelper::cache($pCfgKey);

    if (empty($pCfgValue)) {
        $pRegionModel = M("regions");
        $pSelectData = $pRegionModel->where(array(
                    "type" => 1,
                ))->field(array(
                    "id", "pid", "name"
                ))->select();

        foreach ($pSelectData as $item) {
            $id = $item['id'];
            $pProvinceCfg[$id] = $item;
        }

        //$pProvinceCfg = $pSelectData;
        \Onemla\OnemlaHelper::cache($pCfgKey, json_encode($pProvinceCfg));
    } else {
        if (is_array($pCfgValue)) {
            return $pCfgValue;
        }
        $pProvinceCfg = json_decode($pCfgValue, true);
    }
    return $pProvinceCfg;
}

function getCfgProvinceMap() {
    $pProvinceCfg = getCfgProvince();
    foreach ($pProvinceCfg as $value) {
        $pProvinceMap[$value['pid']][$value['id']] = $value['name'];
    }
    return $pProvinceMap;
}

function getCfgProvinceByCountry($CountryId = 1) {
    $pProvinceCfg = getCfgProvince();
    $pProvinceMap = array();
    foreach ($pProvinceCfg as $value) {
        if ($CountryId != $value['pid'])
            continue;
        $pProvinceMap[$value['id']] = $value;
    }

    return $pProvinceMap;
}

/**
 * 获取城市配置
 */
function getCfgCity() {
    static $pCityCfg = NULL;
    if (!empty($pCityCfg)) {
        return $pCityCfg;
    }

    $pCfgKey = "region:getCfgCity";
    $pCfgValue = \Onemla\OnemlaHelper::cache($pCfgKey);

    if (empty($pCfgValue)) {
        $pRegionModel = M("regions");
        $pSelectData = $pRegionModel->where(array(
                    "type" => 2,
                ))->field(array(
                    "id", "pid", "name"
                ))->select();

        foreach ($pSelectData as $item) {
            $id = $item['id'];
            $pCityCfg[$id] = $item;
        }

        //$pCityCfg = $pSelectData;
        \Onemla\OnemlaHelper::cache($pCfgKey, json_encode($pCityCfg));
    } else {
        if (is_array($pCfgValue)) {
            return $pCfgValue;
        }
        $pCityCfg = json_decode($pCfgValue, true);
    }
    return $pCityCfg;
}

function getCfgCityMap() {
    $pCityCfg = getCfgCity();
    foreach ($pCityCfg as $value) {
        $pCityMap[$value['pid']][$value['id']] = $value['name'];
    }
    return $pCityMap;
}

/*
 * 获取国家名字
 * */

function getCountryName($cid) {
    $data = getCfgCountry();
    return $data[$cid]['name'];
}

/*
 * 获取省名字
 * */

function getProvinceName($pid) {
    $data = getCfgProvince();
    return $data[$pid]['name'];
}

/*
 * 获取市区名字
 * */

function getCityName($cid) {
    $data = getCfgCity();
    return $data[$cid]['name'];
}

function setSelectJS($id, $selectId) {
    $pCode = JS_BUILDER_DIVISION . "$(document).ready(function() {" . JS_BUILDER_DIVISION;
    $pCode .= "$('#" . $id . "').find(\"option[value='" . $selectId . "']\").attr(\"selected\",true); " . JS_BUILDER_DIVISION;
    $pCode .= "$('#" . $id . "').change(); " . JS_BUILDER_DIVISION;
    $pCode .= "});" . JS_BUILDER_DIVISION;
    return $pCode;
}

/**
 * 生成国 省 市 联动js
 * 分别传入对应ID
 * */
function getBuildSelectJS($country_id, $province_id, $city_id, $coid = 0, $pid = 0, $cid = 0) {
    $pProvinceCfg = getCfgProvince();
    $pCityCfg = getCfgCity();

    $pProvinceMap = array();
    $pCityMap = array();


    foreach ($pProvinceCfg as $value) {
        $pProvinceMap[$value['pid']][$value['id']] = $value['name'];
    }
    foreach ($pCityCfg as $value) {
        $pCityMap[$value['pid']][$value['id']] = $value['name'];
    }

    $pSelectCode = buildSelectToOther($country_id, $province_id, $pProvinceMap, "");
    $pSelectCode .= JS_BUILDER_DIVISION;
    $pSelectCode .= buildSelectToOther($province_id, $city_id, $pCityMap, "");

    if ($coid > 0)
        $pSelectCode .= setSelectJS($country_id, $coid);
    if ($pid > 0)
        $pSelectCode .= setSelectJS($province_id, $pid);
    if ($cid > 0)
        $pSelectCode .= setSelectJS($city_id, $cid);

    return $pSelectCode;
}

function convert_date($date) {
    return date('Y-m-d H:i:s', $date);
}

function getPCitySelectJs($province_id, $city_id, $pid = 0, $cid = 0) {

    $pCityCfg = getCfgCity();
    $pCityMap = array();
    foreach ($pCityCfg as $value) {
        $pCityMap[$value['pid']][$value['id']] = $value['name'];
    }

    $pSelectCode = buildSelectToOther($province_id, $city_id, $pCityMap, "");

    if ($pid > 0)
        $pSelectCode .= setSelectJS($province_id, $pid);
    if ($cid > 0)
        $pSelectCode .= setSelectJS($city_id, $cid);

    return $pSelectCode;
}

function get_company_nature($key) {
    $company_nature = C('company_nature');

    $name = $company_nature[$key];

    if (empty($name))
        return '';
    else
        return $name;
}


/**
 * 
 * @param type $file_name 文件名称
 * @param type $url  文件存放路径
 * @return string
 */
function delAdminFile($file_name,$pathUrl) {
    $php_self = substr($_SERVER['PHP_SELF'], 0, -9);
    $file = $_SERVER['DOCUMENT_ROOT'] . $php_self .$pathUrl . $file_name;
    return $file;
}

//  提示信息，关闭窗口，刷新父页面
function winclose($val, $f = 'refrash') {
    echo "<meta content='text html; charset=utf-8' http-equiv='Content-Type'><script>alert('" . $val . "');parent.global.ExitWin('" . $f . "');</script>";
}

//  提示信息，关闭窗口
function winexit($val) {
    echo "<meta content='text html; charset=utf-8' http-equiv='Content-Type'><script>alert('" . $val . "');parent.window.close();</script>";
}

//  提示信息，返回上一页
function winback($val) {
    echo "<meta content='text html; charset=utf-8' http-equiv='Content-Type'><script>alert('" . $val . "');location='" . $_SERVER['HTTP_REFERER'] . "';</script>";
    exit;
}

//  提示信息，转到
function wingo($val, $url) {
    echo "<meta content='text html; charset=utf-8' http-equiv='Content-Type'><script>alert('" . $val . "');location='" . $url . "';</script>";
    exit;
}
// 提示信息，保留数据返回上一页
function win_savedata_back($val) {
    echo "<meta content='text html; charset=utf-8' http-equiv='Content-Type'><script>alert('" . $val . "');history.back(-1);</script>";
    exit;
}

function getLive_bsn_id() {
    $user_id = \Onemla\OnemlaHelper::getUserId();
    $model = M('live_bsn');
    $bsn_id = $model->where(array('user_id'=>$user_id))->getField('id');
    return $bsn_id;
}

function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');

    }
    elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function IpLocation() {
//    $Ip = new \Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
//    $area = $Ip->getlocation(''); // 获取某个IP地址所在的位置
//    $position = strpos($area['country'],'省');
//    if(!$position){
//        return '定位失败';
//    }
//    $position = $position+3;
//
//    $present_city = substr($area['country'],$position);
//    $present_city = substr($present_city,0,-3);
//    return $present_city;

    $ip = get_client_ip(0,true);
    $key = 'a1104a8429c1844023c23c10296622da';
    $url = 'http://restapi.amap.com/v3/ip?key='.$key.'&ip='.$ip;
    $info = file_get_contents($url);
    $info = json_decode($info);
    if($info->city!=''){
        return $info->city;
    }else{
        return false;
    }

}

function getBsnIdByUserId($id){
    $model = M('live_bsn');
    $bsn_id = $model->where(array('user_id'=>$id))->getField('id');
    return $bsn_id;
}

function curlLiveInfo($data=''){
    if(empty($data)){
        return false;
    }
    $post_data = array('liveId'=>$data);
    $postUrl = 'http://www.no88.com/lyNotv/getStauts';
    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);
    $curlPost = $post_data;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    $res = json_decode($data);
    if($res->status==200){
        return $res->result;
    }else{
        return false;
    }
}

function getLiveInfo($data=''){
    $info = curlLiveInfo($data);
    if($info){
        return array('status'=>$info->playStatus,'view_count'=>$info->online);
    }else{
        return array('status'=>3,'view_count'=>0);
    }
}

function sortRoomByCount($arr=array(),$page,$multiple=3){
    function my_sort($param1, $param2){
        if($param1['view_count'] == $param2['view_count']) return 0;
        else return $param1['view_count'] > $param2['view_count'] ? -1 : 1;
    }
    uasort($arr, 'my_sort');

    $count=count($arr);

    $Page = new \Org\Page\Page($count,$multiple);
    $arr=array_slice($arr,$page*$multiple,$Page->listRows);
    return $arr;
}

/**
 * 删除目录及目录下所有文件或删除指定文件
 * @param str $path   待删除目录路径
 * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
 * @return bool 返回删除状态
 */
function delDirAndFile($path, $delDir = FALSE) {
    $handle = opendir($path);
    if ($handle) {
        while (false !== ( $item = readdir($handle) )) {
            if ($item != "." && $item != "..")
                is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
        }
        closedir($handle);
        if ($delDir)
            return rmdir($path);
    }else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return FALSE;
        }
    }
}

/*
 * 发送邮件/QQ/163
 * */
function sendEmail($to, $title = '', $content = '') {
    Vendor('phpmailer.PHPMailerAutoload');
    $mail = new \PHPMailer(); //实例化
    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = false;
    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth=true;
    //链接qq域名邮箱的服务器地址
    $mail->Host = C('MAIL_HOST');
    //设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = C('MAIL_SMTPSECURE');
    //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
    $mail->Port = C('SMTP_PORT');
    //设置smtp的helo消息头 这个可有可无 内容任意
    // $mail->Helo = 'Hello smtp.qq.com Server';
    //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->CharSet = C('MAIL_CHARSET');
    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = C('MAIL_FROMNAME');
    //smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username =C('MAIL_USERNAME');
    //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
    $mail->Password = C('MAIL_PASSWORD');
    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From = C('MAIL_FROM');
    //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true);
    //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    $mail->addAddress($to);
    //添加多个收件人 则多次调用方法即可
    // $mail->addAddress('xxx@163.com','lsgo在线通知');
    //添加该邮件的主题
    $mail->Subject = $title;
    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = $content;
    //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
    // $mail->addAttachment('./d.jpg','mm.jpg');
    //同样该方法可以多次调用 上传多个附件
    // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');
    return  $mail->Send() ? '发送成功' : $mail->ErrorInfo;
//    return $mail->Send() ?  '发送成功' : '发送失败';
}

function judgeUtf8($str){
    if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $str)>0){
       return 1;
    }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $str)>0){
        return 2;
    }else{
        return 3;
    }
}

//function getLiveNameByUserId($id){
//    $model = M('user_info');
//    $live_name = $model->where(array('user_id'=>$id))->getField('live_name');
//    return $live_name;
//}
//
//function getPublicLogoByUserId($id){
//    $model = M('user_info');
//    $public_logo = $model->where(array('user_id'=>$id))->getField('public_logo');
//    return $public_logo;
//}
//
//function getQrCodeByUserId($id){
//    $model = M('user_info');
//    $qr_code = $model->where(array('user_id'=>$id))->getField('qr_code');
//    return $qr_code;
//}
//
//function getAuditTypeByUserId($id){
//    $model = M('user_info');
//    $type = $model->where(array('user_id'=>$id))->getField('type');
//    return $type;
//}



