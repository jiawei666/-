<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/5/29
 * Time: 14:14
 */
namespace Components\Cli\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\ViewController;
use Services\System\Cron\CronService;

class IndexController extends ViewController{

    public function Index()
    {
        $pCronService = new CronService();
        $pCronService -> execute(CronService::TYPE_WECHAT);
    }

    public function loadConfig()
    {
        $pCronConfig = C("cron");
        $pCronExecuteModel = new OnemlaModel("cron_execute");
        $pCronExecuteModel -> duplicateInsert($pCronConfig);
    }
    public function textPush(){
        OnemlaHelper::getModel('User') -> update_m();
//        echo '测试调用推送';
//        OnemlaHelper::getModel('Message') -> checkSendMsg();
    }

}