<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/31 0031
 * Time: 上午 10:04
 */

namespace Components\Solution\Controller;

use Components\Solution\Model\SolutionModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Activity\Model\ActivityModel;

class IndexController extends ViewController
{
    /**
     * FAQ列表
     */
    public function index()
    {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_CAANDSO);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_CAANDSO_TWO);

        $model = new SolutionModel();
        $info = $model->getList();

//        $res = password_verify('123456','abc');
//        dump($res);
//        exit;
        $this->assign('list', $info['list']);
        $this->display('help');
    }

}

