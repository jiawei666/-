<?php

/**
 * 完成地址
 */
function FullUrl($pUrl)
{
    $pBaseUrl = '';
    if(strpos($pUrl, 'http://') === false && strpos($pUrl, 'https://') === false){
        $pBaseUrl = C("TMPL_PARSE_STRING.__UPLOAD__");
    }

    return $pBaseUrl.$pUrl;
}

function GetlocationByIp($ip = ''){
    $get = array(
        'ak' => C('BAIDU_MAP.ak'),
        'coor' => 'bd09ll'
    );
    $res = \Onemla\OnemlaHelper::requestGet('http://api.map.baidu.com/location/ip', $get);
    if(empty($res)){ return false; }
    return  $json = json_decode($res, true);
}

function QQMap2BaiDuMap_Coor($location){
    $get = array(
        'ak' => C('BAIDU_MAP.ak'),
        'coords' => $location['x'].','.$location['y'],
        'from'=>1,
        'to' => 5
    );
    $res = \Onemla\OnemlaHelper::requestGet('http://api.map.baidu.com/geoconv/v1/', $get);
    if(empty($res)){ return false; }
    $json = json_decode($res, true);

    return $json['result'][0];
}

function Baidu_Geocoder($location = array()){
    if(empty($location)){
        $location = GetlocationByIp();
        $location = $location['content']['point'];
    }
    $get = array(
        'ak' => C('BAIDU_MAP.ak'),
        'location' => $location['y'].','.$location['x'],
        'output' => 'json',
        'pois' => 1,
    );
    $res = \Onemla\OnemlaHelper::requestGet('http://api.map.baidu.com/geocoder/v2/', $get);
    if(empty($res)){ return false; }
    $json = json_decode($res, true);
    $output = $json['result']['addressComponent'];
    $output['address'] = $json['result']['formatted_address'];

    return $output;
}

function GetIpLookup($ip = ''){
    if(empty($ip)){
//        $ip = get_client_ip();
        $ip = '223.73.59.189';
    }
    $res = \Onemla\OnemlaHelper::requestGet('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    if(empty($res)){ return false; }
    $jsonMatches = array();
    preg_match('#\{.+?\}#', $res, $jsonMatches);
    if(!isset($jsonMatches[0])){ return false; }
    $json = json_decode($jsonMatches[0], true);
    if(isset($json['ret']) && $json['ret'] == 1){
        $json['ip'] = $ip;
        unset($json['ret']);
    }else{
        return false;
    }
    return $json;
}

/**
 * 提取数组字段值
 */
function extractFieldValue($pArr, $pField)
{
    if ( empty($pArr) ) {
        return array();
    }
    $pValues = array();
    foreach ($pArr as $value) {
        $pValues[$value[$pField]] = 1;
    }
    return array_keys($pValues);
}

/**
 * 组合地址
 */
function combinAddress($iProvinceId, $iCityId, $pAddress)
{
    $pProvinceName = getProvinceName($iProvinceId)."省";
    $pCityName = getCityName($iCityId)."市";
    return $pProvinceName.$pCityName.$pAddress;
}

/**
 * 获取下一个年月
 * @param $yearMonth Ym
 * @return ym
 */
function getNextYearMonth($yearMonth)
{
    return date("Ym", strtotime("+1 months", strtotime($yearMonth."01")));
}