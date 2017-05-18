<?php
/**
 * Created by PhpStorm.
 * User: liyan
 * Date: 2015/4/16
 * Time: 18:09
 */
namespace Onemla;


class OnemlaLog {

    const LOG_INFO       = "info";      //信息
    const LOG_CRON       = "cron";      //定时运行日志
    const LOG_PAY        = "pay";       //充值日志
    const LOG_PAY_NOTIFY = "payNotify"; //充值通知日志

    public static function getInstance()
    {
        static $_instance = null;
        if ( ! $_instance ) {
            $_instance = new OnemlaLog();
        }
        return $_instance;
    }

    public static function log($message, $level = OnemlaLog::LOG_INFO)
    {
        $pInstance = self::getInstance();
        $pInstance -> logFile($message, $level);
    }

    //日志文件路径
    private function logPath($level)
    {
        return ROOT_PATH."/Runtime/CustomerLogs/".$level;
    }

    //写日志
    private function logFile($message, $level='info')
    {
        $pDirectory = $this -> logPath($level);

        if ( ! is_dir($pDirectory) ) {
            mkdir($pDirectory, 0777, true);
        }

        $filePath = $pDirectory . '/' . $level . '_'.date('Y-m-d').'.log';

        $title = date('Y-m-d H:i:s').': ';
        if($fp = fopen($filePath, "ab"))
        {
            fwrite($fp, $title.$message."\r\n");
            fclose($fp);
        }
        return true;
    }
}