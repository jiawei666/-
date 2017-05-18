<?php
namespace Hex32;

class Hex32 {
    const KeyCode = '0KAGOZ543H9YDWX6LVUCERQTP1NS7JMI';
    const PaddingCode = 'FB2FB8';

    public function __construct() {

    }
    /**
     * 将64进制的数字字符串转为10进制的数字字符串
     * @param $m string 64进制的数字字符串
     * @param $len integer 返回字符串长度，如果长度不够用0填充，0为不填充
     * @return string
     * @author 野马
     */
    public function hex32to10($m, $len = 0) {
        $m = (string)$m;
        $hex2 = '';
        $Code = self::KeyCode;
        for($i = 0, $l = strlen($Code); $i < $l; $i++) {
            $KeyCode[] = $Code[$i];
        }
        $KeyCode = array_flip($KeyCode);

        for($i = 0, $l = strlen($m); $i < $l; $i++) {
            $one = $m[$i];
            $hex2 .= str_pad(decbin($KeyCode[$one]), 5, '0', STR_PAD_LEFT);
        }
        $return = bindec($hex2);

        if($len) {
            $clen = strlen($return);
            if($clen >= $len) {
                return $return;
            }
            else {
                return str_pad($return, $len, '0', STR_PAD_LEFT);
            }
        }
        return $return;
    }

    /**
     * 将10进制的数字字符串转为64进制的数字字符串
     * @param $m string 10进制的数字字符串
     * @param $len integer 返回字符串长度，如果长度不够用0填充，0为不填充
     * @return string
     * @author 野马
     */
    public function hex10to32($m, $len = 0) {
        $KeyCode = self::KeyCode;
        $hex2 = decbin($m);
        $hex2 = $this->str_rsplit($hex2, 5);

        $hex64 = array();
        foreach($hex2 as $one) {
            $t = bindec($one);
            $hex64[] = $KeyCode[$t];
        }
        $return = preg_replace('/^0*/', '', implode('', $hex64));
        if($len) {
            $clen = strlen($return);
            $iDiff = $len - $clen;
            if($iDiff > 0) {
                $iPaddingCode = self::PaddingCode;
                for ($k=0;$k < $iDiff;$k++){
                    $return = $iPaddingCode[$k].$return;
                }
                return $return;
            }else {
                return $return;
            }
        }
        return $return;
    }

    /**
     * 功能和PHP原生函数str_split接近，只是从尾部开始计数切割
     * @param $str string 需要切割的字符串
     * @param $len integer 每段字符串的长度
     * @return array
     * @author 野马
     */
    protected function str_rsplit($str, $len = 1) {
        if($str == null || $str == false || $str == '') return false;
        $strlen = strlen($str);
        if($strlen <= $len) return array($str);
        $headlen = $strlen % $len;
        if($headlen == 0) {
            return str_split($str, $len);
        }
        $return = array(substr($str, 0, $headlen));
        return array_merge($return, str_split(substr($str, $headlen), $len));
    }
}