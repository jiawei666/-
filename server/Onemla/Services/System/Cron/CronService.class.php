<?php

namespace Services\System\Cron;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\OnemlaService;

class CronService extends OnemlaService
{
    const TYPE_WECHAT = 1;         //前台
    const TYPE_ADMINISTRATOR = 2;  //管理员后台

    const CRON_TYPE_SPACE = 0;  //间隔运行
    const CRON_TYPE_CLOCK_SPACE = 1;  //定时间隔运行
    const CRON_TYPE_WEEK_SPACE = 2;  //周运行
    const CRON_TYPE_MONTH_SPACE = 3;  //月间隔运行

    const DAY_RESET_TIME = "23:50:00"; //重置时间

    public function execute($iType)
    {
        $pCronExecuteModel = M("cron_execute");

        $pTimeExecute = $pCronExecuteModel -> where(array(
            'cron_state' => 1,
            'type'       => $iType,
        )) -> order("priority asc") -> select();

        if ( empty($pTimeExecute) ) {
            return;
        }

        $pCanExecute   = array ();
        $pUpdateExcute = array (); // 初始化时间
        $iNowTime      = time ();

        foreach ( $pTimeExecute as $value ) {

            $iCronType = $value ['cron_type'];
            $iExecuteTime = $value ['execute_time'];
            $pCronTime = $value ['cron_time'];
            $pSpaceTime = $value ['space_time'];
            $pFuncName = $value ['func_name'];

            if ($iExecuteTime == 0) {
                $pUpdateExcute [] = $pFuncName;
                continue;
            }
            if ($iCronType == self::CRON_TYPE_SPACE) {
                $iInterval = $this->getSpaceInterVal($pSpaceTime);
                if ($iNowTime >= $iExecuteTime + $iInterval) {
                    $pCanExecute [] = $pFuncName;
                    $pUpdateExcute [] = $pFuncName;
                }
            } else if ($iCronType == self::CRON_TYPE_CLOCK_SPACE) {

                $iInterval = $this -> getSpaceInterVal($pSpaceTime);
                $pPreviousExecute = date("Y-m-d", $iExecuteTime);
                $pPreviousExecute = $pPreviousExecute . " " . $pCronTime;
                $iDefineTime = strtotime($pPreviousExecute);
                $iDefineTime += $iInterval;

                // 是否到达执行时间
                if ($iNowTime >= $iDefineTime) {
                    $pCanExecute [] = $pFuncName;
                    $pUpdateExcute [] = $pFuncName;
                }
            } else {
                if ($iCronType == self::CRON_TYPE_WEEK_SPACE) { // 周执行
                    $iNowWeek = date("w");
                    if ($iNowWeek != $pSpaceTime) {
                        continue;
                    }
                } else if ($iCronType == self::CRON_TYPE_MONTH_SPACE) {
                    $iTodayDay = date("d");
                    if ($iTodayDay != $pSpaceTime) {
                        continue;
                    }
                }
                $pPreExecuteDate = date("Y-m-d", $iExecuteTime);
                $pTodayDate = date("Y-m-d");
                if ($pPreExecuteDate == $pTodayDate) { // 今天已经执行过了
                    continue;
                }
                $pDefineTime = $pTodayDate . " " . $pCronTime;
                $iDefineTime = strtotime($pDefineTime);
                if ($iNowTime >= $iDefineTime) {
                    $pCanExecute [] = $pFuncName;
                    $pUpdateExcute [] = $pFuncName;
                }
            }
        }

        /**
         * 执行代码
         */
        foreach ( $pCanExecute as $pFuncName ) {
            $arr_exp = explode ( '.', $pFuncName );

            if ( count ( $arr_exp ) != 2 ) {
                continue;
            }

            $class  = $arr_exp [0];
            $method = $arr_exp [1];

            $pModel = OnemlaHelper::getModel($class);
            $pModel -> $method();
        }

        //添加日志
        $this -> addCronLogs($pCanExecute);

        if ( ! empty ( $pUpdateExcute )) {
            $this -> updateExecuteTime($pUpdateExcute);
        }
    }

    private function updateExecuteTime($pUpdateExecute)
    {
        $pCronExecuteModel = M("cron_execute");
        return $pCronExecuteModel -> where(array(
            'func_name' => array("IN", $pUpdateExecute)
        )) -> save(array(
            'execute_time' => OnemlaRequest::requestTime(),
        ));
    }

    /**
     * 添加执行日志
     */
    private function addCronLogs($pFuncNames)
    {
        if ( empty($pFuncNames) ) {
            return 0;
        }
        $pInserts = array();
        foreach ($pFuncNames as $value) {
            $pInserts[] = array(
                'func_name'    => $value,
                'execute_time' => OnemlaRequest::requestTime(),
            );
        }
        $pCronLogModel = M("cron_log");

        return $pCronLogModel -> addAll($pInserts);
    }

    private function getSpaceInterVal($pSpaceTime) {
        $pSpaceDate = date_parse ( $pSpaceTime );
        $iInterval = $pSpaceDate ['day'] * 3600 * 24 + $pSpaceDate ['hour'] * 3600 + $pSpaceDate ['minute'] * 60;
        return $iInterval;
    }
}