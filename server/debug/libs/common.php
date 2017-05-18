<?php

define("BASE_URL", "http://localhost/svn_pro/probject_code/e-shop/server/Onemla");

function debug($msg)
{
    if ( is_array($msg) ) {
        echo "<pre>";
        print_r($msg);
        echo "</pre>";
    } else {
        echo $msg."\n";
    }
}

function componentsRequest($action, $post = array())
{
    $pExpArr = explode(".", $action);
    if ( count($pExpArr) != 3 ) {
        die("action 格式不正确！");
    }
    $url = BASE_URL."/"."index.php?m={$pExpArr[0]}&c={$pExpArr[1]}&a={$pExpArr[2]}";
    requestPost($url, $post);
}

function administratorRequest($action, $post = array())
{
    $pExpArr = explode(".", $action);
    if ( count($pExpArr) != 3 ) {
        die("action 格式不正确！");
    }
    $url = BASE_URL."/Administrator/"."index.php?m={$pExpArr[0]}&c={$pExpArr[1]}&a={$pExpArr[2]}";
    requestPost($url, $post);
}

function administratorMerRequest($action, $post = array())
{
    $pExpArr = explode(".", $action);
    if ( count($pExpArr) != 3 ) {
        die("action 格式不正确！");
    }
    $url = BASE_URL."/AdministratorMer/"."index.php?m={$pExpArr[0]}&c={$pExpArr[1]}&a={$pExpArr[2]}";
    requestPost($url, $post);
}

function requestPost($url, $post = array(), $options = array(), $iTimeout = 30)
{
    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => $iTimeout,
        CURLOPT_POSTFIELDS => http_build_query($post)
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch)){
        $result = curl_error($ch);
    }
    curl_close($ch);

    echo "url：".$url."\n";
    echo "post：\n";
    if ( ! empty($post) ) {
        print_r($post);
    }
    echo "result：".$result."\n";
    print_r(json_decode($result, true));
}

function requestGet($url, $get = array(), $options = array())
{
    $defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 30
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch)) {
        $result = curl_error($ch);
    }
    curl_close($ch);
    return $result;
}