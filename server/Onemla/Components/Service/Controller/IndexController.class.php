<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6 0006
 * Time: 上午 11:15
 */

namespace Components\Service\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;

class IndexController extends ViewController {


    /**
     * 用户列表
     */
    public function index() {
        $this->display('service');
    }




}