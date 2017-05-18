<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6 0006
 * Time: 上午 10:55
 */

namespace Components\Scene\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;

class IndexController extends ViewController {

    public function running(){
        $this->display('scene-running');
    }
    public function school(){
        $this->display('scene-school');
    }

    public function study(){
        $this->display('scene-study');
    }

    public function video(){
        $this->display('scene-video');
    }

}