<?php

namespace Components\Home\Controller;

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
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_ADMIN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_ADMIN_ONE);

        $model = new UserModel();
        $info = $model->getList();
        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('user_name', $info['user_name']);
        $this->assign('phone', $info['phone']);
        $this->assign('date_start', $info['date_start']);
        $this->assign('date_end', $info['date_end']);
        $this->display();
    }




}
