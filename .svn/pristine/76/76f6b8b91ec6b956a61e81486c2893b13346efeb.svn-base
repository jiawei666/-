<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/22
 * Time: 21:15
 */
namespace Onemla;

class OnemlaRequest{

    /**
     * Gets the request method.
     *
     * @return  string
     */
    public static function getMethod()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        return $method;
    }

    public static function getVar($name, $default = '', $method = 'REQUEST',  $filter=null,$datas=null){
        // Ensure hash and type are uppercase
        $method = strtoupper($method);

        if ($method === 'METHOD') {
            $method = self::getMethod();
        }

        if($method == 'DELETE')
            $method = 'GET';

        return I($method.'.'.$name, $default, $filter, $datas || $method == 'PATCH');
    }

    public static function getInt($name, $default = 0, $method = 'METHOD'){
        return (int)self::getVar($name, $default, $method, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function getFloat($name, $default = 0.0, $method = 'METHOD'){
        return self::getVar($name, $default, $method, "floatval");
    }

    public static function getBool($name, $default = false, $method = 'METHOD'){
        return self::getVar($name, $default, $method, FILTER_VALIDATE_BOOLEAN);
    }

    public static function getAlnum($name, $default = '', $method = 'METHOD'){
        return self::getVar($name, $default, $method, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public static function getEmail($name, $default = '', $method = 'METHOD'){
        return self::getVar($name, $default, $method, FILTER_SANITIZE_EMAIL);
    }

    public static function getUrl($name, $default = '', $method = 'METHOD'){
        return self::getVar($name, $default, $method, FILTER_SANITIZE_URL);
    }

    public static function getString($name, $default = '', $method = 'METHOD'){
        return self::getVar($name, $default, $method, FILTER_SANITIZE_STRING);
    }

    public static function getHtmlString($name, $default = '', $method = 'METHOD'){
//        return self::getVar($name, $default, $method, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $html = self::getVar($name, $default, $method);

//        return htmlspecialchars($html);
        return $html;
    }

    public static function getEncoded($name, $default = '', $method = 'METHOD'){
        return self::getVar($name, $default, $method, FILTER_SANITIZE_ENCODED);
    }

    public static function getRaw($name, $default = '', $method = 'METHOD'){
        return self::getVar($name, $default, $method, FILTER_UNSAFE_RAW);
    }

    public static function getCallback($name, $default = '', $method = 'METHOD', $options=array()){
        return self::getVar($name, $default, $method,  FILTER_CALLBACK, $options);
    }

    /**
     * 获取系统当前时间
     *
     * @return int
     */
    public static function requestTime(){
        static $iTime;
        if (!$iTime) {
            $iTime = static::getInt('REQUEST_TIME', 0);
            $iTime = $iTime ? $iTime : time();
        }
        return $iTime ? $iTime : time();
    }

    public static function getEndTime($pTime)
    {
        $pFormat = $pTime." 23:59:59";
        return strtotime($pFormat);
    }

    public static function getStartTime($pDate)
    {
        return strtotime($pDate);
    }

    /**
     * 获取当天日期 Ymd
     */
    public static function getDayDate()
    {
        static $iDayDate;
        if ( ! $iDayDate ) {
            $iDayDate = date("Ymd", self::requestTime());
        }
        return $iDayDate;
    }

    public static function validate_email($name, $default = false, $method = 'METHOD'){
        return self::getVar($name, $default, $method,  FILTER_VALIDATE_EMAIL);
    }

    public static function validate_ip($name, $default = false, $method = 'METHOD'){
        return self::getVar($name, $default, $method,  FILTER_VALIDATE_IP );
    }

    public static function validate_url($name, $default = false, $method = 'METHOD'){
        return self::getVar($name, $default, $method,  FILTER_VALIDATE_URL );
    }

    public static function validate_regexp($name, $default = false, $method = 'METHOD', $options){
        return self::getVar($name, $default, $method,  FILTER_VALIDATE_REGEXP, $options);
    }
}