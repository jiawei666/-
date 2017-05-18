<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/20 0020
 * Time: 下午 1:02
 */

namespace Components\Solution\Model;

use Onemla\OnemlaModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Org\Page\Page;
use Components\User\Model\UserModel;

class SolutionModel extends OnemlaModel {

    /**
     * 解决方案列表
     */
    public function getList() {//获取show字段为1(显示)的列表
        $where['show'] = 1;
        $list = $this
            ->where($where)
            ->order('create_time desc')
            ->select();
        $msg = array(
            'list' => $list,
        );
        return $msg;
    }

}
